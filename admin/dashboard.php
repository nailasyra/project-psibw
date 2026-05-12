<?php
session_start();
include "../config/koneksi.php";
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
}

if($_SESSION['role'] != 1){
    header("Location: ../login.php");
}

// TOTAL DATA
$q = mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa");
$data = mysqli_fetch_assoc($q);
$total_mahasiswa = $data['total'];

$q = mysqli_query($conn, "SELECT COUNT(*) as total FROM dosen");
$data = mysqli_fetch_assoc($q);
$total_dosen = $data['total'];

$q = mysqli_query($conn, "SELECT COUNT(*) as total FROM matakuliah");
$data = mysqli_fetch_assoc($q);
$total_matakuliah = $data['total'];

// MAHASISWA TERBARU
$mahasiswa_terbaru = [];
$q = mysqli_query($conn, "SELECT nim, nama FROM mahasiswa ORDER BY nim DESC LIMIT 4");
while($row = mysqli_fetch_assoc($q)){
    $mahasiswa_terbaru[] = $row;
}

// DOSEN TERBARU
$dosen_terbaru = [];
$q = mysqli_query($conn, "SELECT nidn, nama FROM dosen ORDER BY nidn DESC LIMIT 4");
while($row = mysqli_fetch_assoc($q)){
    $dosen_terbaru[] = $row;
}

// MATKUL TERBARU
$matakuliah_terbaru = [];
$q = mysqli_query($conn, "SELECT kode_mk, nama_mk FROM matakuliah ORDER BY kode_mk DESC LIMIT 4");
while($row = mysqli_fetch_assoc($q)){
    $matakuliah_terbaru[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIAKAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #1a3a5c;
            --sidebar-hover: #22497a;
            --sidebar-active: #2980b9;
            --primary: #2980b9;
            --primary-dark: #1f6391;
            --green: #27ae60;
            --orange: #e67e22;
            --red: #e74c3c;
            --yellow: #f39c12;
            --bg: #f0f4f8;
            --white: #ffffff;
            --text: #2d3748;
            --text-muted: #718096;
            --border: #e2e8f0;
            --shadow: 0 2px 12px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.12);
            --radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: width 0.3s;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand-text span {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.35);
            padding: 8px 20px 4px;
            text-transform: uppercase;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .sidebar-nav ul li a i {
            width: 18px;
            font-size: 14px;
            text-align: center;
        }

        .sidebar-nav ul li a:hover {
            background: rgba(255,255,255,0.06);
            color: white;
            border-left-color: rgba(255,255,255,0.3);
        }

        .sidebar-nav ul li a.active {
            background: var(--primary);
            color: white;
            border-left-color: #74c0fc;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .sidebar-footer a:hover { color: var(--red); }

        /* ===== MAIN ===== */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--white);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-left p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-generate {
            background: var(--green);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-generate:hover { background: #219a52; transform: translateY(-1px); }

        .btn-logout {
            background: var(--red);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-logout:hover { background: #c0392b; }

        /* ===== CONTENT ===== */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            border-top: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card.blue  { border-top-color: var(--primary); }
        .stat-card.green { border-top-color: var(--green); }
        .stat-card.orange{ border-top-color: var(--orange); }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-card.blue  .stat-icon { background: #ebf5fb; color: var(--primary); }
        .stat-card.green .stat-icon { background: #eafaf1; color: var(--green); }
        .stat-card.orange .stat-icon { background: #fef9e7; color: var(--orange); }

        .stat-info h4 {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .stat-info .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-info p {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ===== TABLES SECTION ===== */
        .tables-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .table-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-header {
            padding: 14px 18px;
            color: white;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-header.blue   { background: var(--primary); }
        .table-header.green  { background: var(--green); }
        .table-header.yellow { background: var(--yellow); }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .table-card table thead th {
            background: #f8fafc;
            padding: 9px 14px;
            text-align: left;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }

        .table-card table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        .table-card table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-card table tbody tr:hover td {
            background: #f7fafc;
        }

        .table-footer {
            padding: 12px 16px;
            text-align: center;
            border-top: 1px solid var(--border);
        }

        .btn-lihat {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            color: white;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-lihat:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn-lihat.blue   { background: var(--primary); }
        .btn-lihat.green  { background: var(--green); }
        .btn-lihat.yellow { background: var(--yellow); }

        .badge-nim {
            background: #ebf5fb;
            color: var(--primary-dark);
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
        }

        /* ===== FOOTER ===== */
        .page-footer {
            text-align: center;
            padding: 16px 28px;
            font-size: 11.5px;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            background: white;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="brand-text">
                <h2>PORTAL</h2>
                <span>PSIBW Akademik</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <ul>
            <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="tabelmahasiswa/mahasiswa.php"><i class="fas fa-user-graduate"></i> Data Mahasiswa</a></li>
            <li><a href="tabeldosen/dosen.php"><i class="fas fa-chalkboard-teacher"></i> Data Dosen</a></li>
            <li><a href="tabelmatkul/matakuliah"><i class="fas fa-book"></i> Data Mata Kuliah</a></li>
        </ul>
        <div class="nav-label" style="margin-top:10px;">Pengaturan</div>
        <ul>
            <li><a href="#"><i class="fas fa-cog"></i> Pengaturan Akun</a></li>
            <li><a href="#"><i class="fas fa-key"></i> Ganti Password</a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <h3>Dashboard Admin</h3>
            <p>Sistem Informasi Akademik &mdash; Universitas Riau</p>
        </div>
        <div class="topbar-right">
            <button class="btn-generate" onclick="window.print()">
                <i class="fas fa-file-export"></i> Generate Chart
            </button>
            <a href="../logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <h4>Total Mahasiswa</h4>
                    <div class="stat-number"><?= $total_mahasiswa ?></div>
                    <p>data terdaftar pada sistem</p>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-info">
                    <h4>Total Dosen</h4>
                    <div class="stat-number"><?= $total_dosen ?></div>
                    <p>data terdaftar pada sistem</p>
                </div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-info">
                    <h4>Total Mata Kuliah</h4>
                    <div class="stat-number"><?= $total_matakuliah ?></div>
                    <p>data terdaftar pada sistem</p>
                </div>
            </div>
        </div>

        <!-- TABLES -->
        <div class="tables-grid">

            <!-- Mahasiswa Terbaru -->
            <div class="table-card">
                <div class="table-header blue">
                    <i class="fas fa-user-graduate"></i> Mahasiswa Terbaru
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mahasiswa_terbaru as $m): ?>
                        <tr>
                            <td><span class="badge-nim"><?= $m['nim'] ?></span></td>
                            <td><?= $m['nama'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-footer">
                    <a href="tabelmahasiswa/mahasiswa.php" class="btn-lihat blue">Lihat Semua</a>
                </div>
            </div>

            <!-- Dosen Terbaru -->
            <div class="table-card">
                <div class="table-header green">
                    <i class="fas fa-chalkboard-teacher"></i> Dosen Terbaru
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nidn</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dosen_terbaru as $d): ?>
                        <tr>
                            <td><span class="badge-nim"><?= $d['nidn'] ?></span></td>
                            <td><?= $d['nama'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-footer">
                    <a href="tabeldosen/dosen.php" class="btn-lihat green">Lihat Semua</a>
                </div>
            </div>

            <!-- Mata Kuliah Terbaru -->
            <div class="table-card">
                <div class="table-header yellow">
                    <i class="fas fa-book"></i> Mata Kuliah Terbaru
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Kode_Matkul</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($matakuliah_terbaru as $mk): ?>
                        <tr>
                            <td><span class="badge-nim"><?= $mk['kode_mk'] ?></span></td>
                            <td><?= $mk['nama_mk'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-footer">
                    <a href="matakuliah.php" class="btn-lihat yellow">Lihat Semua</a>
                </div>
            </div>

        </div>
    </div><!-- /content -->

    <div class="page-footer">
        &copy; 2025 Sistem Informasi Akademik — Universitas Riau
    </div>

</div><!-- /main -->

</body>
</html>