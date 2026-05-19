<?php
session_start();
include '../config/koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['username']) || $_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data mahasiswa
$stmt = mysqli_prepare($conn, "SELECT * FROM mahasiswa WHERE nim = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$mhs = mysqli_fetch_assoc($result);

// Ambil matakuliah berdasarkan prodi mahasiswa
$stmt2 = mysqli_prepare($conn, "
    SELECT kode_mk, nama_mk, sks, semester
    FROM matakuliah
    WHERE prodi = ?
    ORDER BY semester ASC
");
mysqli_stmt_bind_param($stmt2, "s", $mhs['prodi']);
mysqli_stmt_execute($stmt2);
$result_mk = mysqli_stmt_get_result($stmt2);

$matakuliah = [];
$total_sks = 0;

while ($row = mysqli_fetch_assoc($result_mk)) {
    $matakuliah[] = $row;
    $total_sks += $row['sks'];
}

$jumlah_mk = count($matakuliah);

// Handle tukar password
$pesan_pw = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ganti_password') {
    $pw_lama = $_POST['password_lama'] ?? '';
    $pw_baru = $_POST['password_baru'] ?? '';
    $pw_konfirm = $_POST['password_konfirm'] ?? '';

    $stmt_cek = mysqli_prepare($conn, "SELECT password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt_cek, "s", $username);
    mysqli_stmt_execute($stmt_cek);
    $res_cek = mysqli_stmt_get_result($stmt_cek);
    $user_data = mysqli_fetch_assoc($res_cek);

    if (!$user_data || !password_verify($pw_lama, $user_data['password'])) {
        $pesan_pw = ['type' => 'error', 'text' => 'Password lama tidak sesuai.'];
    } elseif (strlen($pw_baru) < 6) {
        $pesan_pw = ['type' => 'error', 'text' => 'Password baru minimal 6 karakter.'];
    } elseif ($pw_baru !== $pw_konfirm) {
        $pesan_pw = ['type' => 'error', 'text' => 'Konfirmasi password tidak cocok.'];
    } else {
        $pw_hash = password_hash($pw_baru, PASSWORD_DEFAULT);
        $stmt_upd = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE username = ?");
        mysqli_stmt_bind_param($stmt_upd, "ss", $pw_hash, $username);
        mysqli_stmt_execute($stmt_upd);
        $pesan_pw = ['type' => 'success', 'text' => 'Password berhasil diubah!'];
    }
}

// Halaman aktif
$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1341a8;
            --primary-light: #e8efff;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --text: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f3f4f6;
            --sidebar-w: 250px;
            --card-radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #1a3a5c;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand .logo-text {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .sidebar-brand .logo-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            margin-top: 2px;
            font-weight: 500;
        }

        .sidebar-user {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: 14px;
            flex-shrink: 0;
        }
        .sidebar-user-info .name {
            font-size: 13px; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 150px;
        }
        .sidebar-user-info .nim {
            font-size: 11px; color: rgba(255,255,255,0.4);
            font-family: 'DM Mono', monospace;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-label {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.25);
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 0 8px; margin-bottom: 6px; margin-top: 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all 0.18s;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
        }
        .nav-item.active {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
        }
        .nav-item svg { flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .btn-logout-side {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            color: #fca5a5;
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all 0.18s;
            width: 100%;
            background: none; border: none; cursor: pointer;
        }
        .btn-logout-side:hover { background: rgba(239,68,68,0.15); color: #ef4444; }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 32px 36px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 24px; font-weight: 800; color: var(--text);
            letter-spacing: -0.5px;
        }
        .page-header p {
            font-size: 14px; color: var(--text-muted); margin-top: 4px;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 22px 24px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.blue::before { background: var(--primary); }
        .stat-card.green::before { background: var(--success); }
        .stat-card.amber::before { background: var(--accent); }

        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .stat-icon.blue { background: #eff6ff; color: var(--primary); }
        .stat-icon.green { background: #ecfdf5; color: var(--success); }
        .stat-icon.amber { background: #fffbeb; color: var(--accent); }

        .stat-value {
            font-size: 28px; font-weight: 800; color: var(--text);
            letter-spacing: -1px; line-height: 1;
        }
        .stat-label {
            font-size: 12.5px; color: var(--text-muted);
            margin-top: 6px; font-weight: 500;
        }

        /* ===== SECTION CARD ===== */
        .section-card {
            background: #fff;
            border-radius: var(--card-radius);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 22px;
        }
        .section-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .section-header h3 {
            font-size: 15px; font-weight: 700; color: var(--text);
        }
        .section-body { padding: 24px; }

        /* ===== BIODATA TABLE ===== */
        .bio-table { width: 100%; }
        .bio-table tr td {
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: top;
        }
        .bio-table tr:last-child td { border-bottom: none; }
        .bio-table td:first-child {
            font-weight: 600; color: var(--text-muted);
            width: 180px; font-size: 13px;
        }
        .bio-table td:last-child { color: var(--text); }

        /* ===== MK TABLE ===== */
        .mk-table { width: 100%; border-collapse: collapse; }
        .mk-table th {
            background: #f8fafc;
            padding: 11px 14px;
            font-size: 12px; font-weight: 700;
            color: var(--text-muted); text-align: left;
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }
        .mk-table td {
            padding: 12px 14px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        .mk-table tr:last-child td { border-bottom: none; }
        .mk-table tr:hover td { background: #fafafa; }

        /* ===== FORM PASSWORD ===== */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--text); margin-bottom: 7px;
        }
        .form-group input {
            width: 100%; max-width: 420px;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: 14px; font-family: inherit;
            color: var(--text);
            transition: border-color 0.18s;
            background: #fafafa;
        }
        .form-group input:focus {
            outline: none; border-color: var(--primary);
            background: #fff;
        }
        .btn-primary {
            padding: 11px 28px;
            background: var(--primary);
            color: #fff; border: none;
            border-radius: 9px; font-size: 14px; font-weight: 600;
            font-family: inherit; cursor: pointer;
            transition: background 0.18s;
        }
        .btn-primary:hover { background: var(--primary-dark); }

        .alert {
            padding: 12px 16px; border-radius: 9px;
            font-size: 13.5px; font-weight: 500;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ===== SEMESTER BADGE ===== */
        .smt-badge {
            display: inline-block; padding: 2px 9px;
            border-radius: 20px; font-size: 11.5px; font-weight: 600;
            background: var(--primary-light); color: var(--primary);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-text">Portal Akademik</div>
        <div class="logo-sub">Sistem Informasi Mahasiswa</div>
    </div>

    <div class="sidebar-user">
        <div class="avatar"><?= strtoupper(substr($mhs['nama'], 0, 1)) ?></div>
        <div class="sidebar-user-info">
            <div class="name"><?= htmlspecialchars($mhs['nama']) ?></div>
            <div class="nim"><?= htmlspecialchars($mhs['nim']) ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu</div>
        <a href="?page=dashboard" class="nav-item <?= ($page == 'dashboard') ? 'active' : '' ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>
        <a href="?page=biodata" class="nav-item <?= ($page == 'biodata') ? 'active' : '' ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Biodata
        </a>
        <a href="?page=matakuliah" class="nav-item <?= ($page == 'matakuliah') ? 'active' : '' ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            Matakuliah
        </a>

        <div class="nav-label" style="margin-top:16px;">Akun</div>
        <a href="?page=ganti_password" class="nav-item <?= ($page == 'ganti_password') ? 'active' : '' ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Tukar Password
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="../logout.php" class="btn-logout-side">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Logout
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

    <!-- ======= DASHBOARD ======= -->
    <?php if ($page == 'dashboard'): ?>
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, <strong><?= htmlspecialchars($mhs['nama']) ?></strong> 👋</p>
    </div>

    <!-- Stat Cards -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <div class="stat-value"><?= $jumlah_mk ?></div>
            <div class="stat-label">Total Matakuliah</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
            </div>
            <div class="stat-value"><?= $total_sks ?></div>
            <div class="stat-label">Total SKS</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon amber">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="stat-value"><?= $mhs['prodi'] ?></div>
            <div class="stat-label">Kode Prodi</div>
        </div>
    </div>

    <!-- Ringkasan Biodata -->
    <div class="section-card">
        <div class="section-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <h3>Informasi Mahasiswa</h3>
        </div>
        <div class="section-body">
            <table class="bio-table">
                <tr><td>NIM</td><td><?= htmlspecialchars($mhs['nim']) ?></td></tr>
                <tr><td>Program Studi</td><td><?= htmlspecialchars($mhs['prodi']) ?></td></tr>
                <tr><td>Jurusan</td><td><?= htmlspecialchars($mhs['jur']) ?></td></tr>
                <tr><td>Email</td><td><?= htmlspecialchars($mhs['email']) ?></td></tr>
            </table>
        </div>
    </div>

    <!-- MK Preview -->
    <?php if (!empty($matakuliah)): ?>
    <div class="section-card">
        <div class="section-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            <h3>Matakuliah (5 Terbaru)</h3>
        </div>
        <div style="padding:0;">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Matakuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($matakuliah, 0, 5) as $mk): ?>
                    <tr>
                        <td><span style="font-family:'DM Mono',monospace;font-size:12.5px;"><?= htmlspecialchars($mk['kode_mk']) ?></span></td>
                        <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                        <td><?= $mk['sks'] ?></td>
                        <td><span class="smt-badge">Smt <?= $mk['semester'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- ======= BIODATA ======= -->
    <?php elseif ($page == 'biodata'): ?>
    <div class="page-header">
        <h1>Biodata</h1>
        <p>Informasi lengkap data diri mahasiswa.</p>
    </div>

    <div class="section-card">
        <div class="section-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <h3>Data Diri</h3>
        </div>
        <div class="section-body">
            <table class="bio-table">
                <tr><td>NIM</td><td><?= htmlspecialchars($mhs['nim']) ?></td></tr>
                <tr><td>Nama Lengkap</td><td><?= htmlspecialchars($mhs['nama']) ?></td></tr>
                <tr><td>Email</td><td><?= htmlspecialchars($mhs['email']) ?></td></tr>
                <tr><td>Program Studi</td><td><?= htmlspecialchars($mhs['prodi']) ?></td></tr>
                <tr><td>Jurusan</td><td><?= htmlspecialchars($mhs['jur']) ?></td></tr>
                <tr><td>Alamat</td><td><?= htmlspecialchars($mhs['alamat']) ?></td></tr>
                <tr><td>Tempat Lahir</td><td><?= htmlspecialchars($mhs['tmp_lahir']) ?></td></tr>
                <tr><td>Tanggal Lahir</td><td><?= htmlspecialchars($mhs['tgl_lahir']) ?></td></tr>
            </table>
        </div>
    </div>

    <!-- ======= MATAKULIAH ======= -->
    <?php elseif ($page == 'matakuliah'): ?>
    <div class="page-header">
        <h1>Matakuliah</h1>
        <p>Daftar matakuliah sesuai program studi.</p>
    </div>

    <!-- Ringkasan SKS -->
    <div class="stats-grid" style="margin-bottom:24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <div class="stat-value"><?= $jumlah_mk ?></div>
            <div class="stat-label">Total Matakuliah</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat-value"><?= $total_sks ?></div>
            <div class="stat-label">Total SKS</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon amber">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="stat-value"><?= $mhs['prodi'] ?></div>
            <div class="stat-label">Kode Prodi</div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            <h3>Daftar Matakuliah</h3>
        </div>
        <div style="padding:0;">
            <?php if (!empty($matakuliah)): ?>
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode MK</th>
                        <th>Nama Matakuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matakuliah as $i => $mk): ?>
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;"><?= $i+1 ?></td>
                        <td><span style="font-family:'DM Mono',monospace;font-size:12.5px;"><?= htmlspecialchars($mk['kode_mk']) ?></span></td>
                        <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                        <td><?= $mk['sks'] ?></td>
                        <td><span class="smt-badge">Smt <?= $mk['semester'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding:40px;text-align:center;color:var(--text-muted);font-size:14px;">
                Belum ada matakuliah untuk prodi ini.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ======= GANTI PASSWORD ======= -->
    <?php elseif ($page == 'ganti_password'): ?>
    <div class="page-header">
        <h1>Tukar Password</h1>
        <p>Ubah password akun kamu di sini.</p>
    </div>

    <div class="section-card" style="max-width:520px;">
        <div class="section-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <h3>Ubah Password</h3>
        </div>
        <div class="section-body">
            <?php if (!empty($pesan_pw)): ?>
            <div class="alert alert-<?= $pesan_pw['type'] ?>">
                <?= $pesan_pw['type'] === 'success' ? '✓' : '⚠' ?>
                <?= htmlspecialchars($pesan_pw['text']) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="action" value="ganti_password">
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" placeholder="Masukkan password lama" required>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_konfirm" placeholder="Ulangi password baru" required>
                </div>
                <button type="submit" class="btn-primary">Simpan Password</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

</main>

</body>
</html>