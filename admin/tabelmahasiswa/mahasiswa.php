<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SESSION['role'] != 1) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa — SIAKAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

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
            --purple: #8e44ad;
            --bg: #f0f4f8;
            --white: #ffffff;
            --text: #2d3748;
            --text-muted: #718096;
            --border: #e2e8f0;
            --shadow: 0 2px 12px rgba(0,0,0,0.08);
            --radius: 10px;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

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
            left: 0; top: 0; bottom: 0;
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
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .brand-text h2 { font-size:16px; font-weight:700; letter-spacing:1px; }
        .brand-text span { display:block; font-size:10px; color:rgba(255,255,255,0.5); letter-spacing:0.5px; }

        .sidebar-nav { flex:1; padding:16px 0; }

        .nav-label {
            font-size:10px; font-weight:600; letter-spacing:1.2px;
            color:rgba(255,255,255,0.35); padding:8px 20px 4px;
            text-transform:uppercase;
        }

        .sidebar-nav ul { list-style:none; }

        .sidebar-nav ul li a {
            display:flex; align-items:center; gap:12px;
            padding:11px 20px;
            color:rgba(255,255,255,0.75);
            text-decoration:none; font-size:13.5px; font-weight:500;
            box-shadow:inset 3px 0 0 transparent;
            transition:all 0.2s;
        }

        .sidebar-nav ul li a i { width:18px; font-size:14px; text-align:center; }

        .sidebar-nav ul li a:hover {
            background:rgba(255,255,255,0.06); color:white;
            box-shadow:inset 3px 0 0 rgba(255,255,255,0.3);
        }

        .sidebar-nav ul li a.active {
            background:var(--primary); color:white;
            box-shadow:inset 3px 0 0 #74c0fc;
        }

        .sidebar-footer { padding:16px 20px; border-top:1px solid rgba(255,255,255,0.08); }

        .sidebar-footer a {
            display:flex; align-items:center; gap:10px;
            color:rgba(255,255,255,0.6); text-decoration:none;
            font-size:13px; transition:color 0.2s;
        }

        .sidebar-footer a:hover { color:var(--red); }

        /* ===== MAIN ===== */
        .main { margin-left:240px; flex:1; display:flex; flex-direction:column; min-height:100vh; }

        /* ===== TOPBAR ===== */
        .topbar {
            background:var(--white); padding:0 28px; height:64px;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:var(--shadow); position:sticky; top:0; z-index:50;
        }

        .topbar-left h3 { font-size:18px; font-weight:700; }
        .topbar-left p { font-size:12px; color:var(--text-muted); margin-top:1px; }
        .topbar-right { display:flex; align-items:center; gap:10px; }

        .btn-tambah {
            background:var(--primary); color:white; border:none;
            padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600;
            font-family:inherit; cursor:pointer;
            display:flex; align-items:center; gap:7px;
            text-decoration:none; transition:background 0.2s, transform 0.1s;
        }
        .btn-tambah:hover { background:var(--primary-dark); color:white; transform:translateY(-1px); }

        .btn-print {
            background:white; border:1.5px solid var(--border); color:var(--text);
            padding:9px 16px; border-radius:8px; font-size:13px; font-weight:600;
            font-family:inherit; cursor:pointer;
            display:flex; align-items:center; gap:7px; transition:all 0.2s;
        }
        .btn-print:hover { border-color:var(--primary); color:var(--primary); }

        /* ===== CONTENT ===== */
        .content { padding:24px 28px; flex:1; }

        /* ===== TOOLBAR ===== */
        .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:18px; flex-wrap:wrap; }

        .search-box { position:relative; flex:1; min-width:200px; }
        .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px; }
        .search-box input {
            width:100%; padding:9px 12px 9px 36px;
            border:1.5px solid var(--border); border-radius:8px;
            font-family:inherit; font-size:13px; background:white;
            outline:none; transition:border 0.2s;
        }
        .search-box input:focus { border-color:var(--primary); }

        .filter-select {
            padding:9px 14px; border:1.5px solid var(--border); border-radius:8px;
            font-family:inherit; font-size:13px; background:white;
            outline:none; cursor:pointer; transition:border 0.2s;
        }
        .filter-select:focus { border-color:var(--primary); }

        /* ===== TABLE CARD ===== */
        .table-card { background:white; border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }

        .table-card-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:16px 20px; border-bottom:1px solid var(--border);
        }

        .table-card-header h5 { font-size:14px; font-weight:700; display:flex; align-items:center; gap:8px; }
        .table-card-header h5 i { color:var(--primary); }
        .table-card-header span { font-size:11.5px; color:var(--text-muted); background:#f0f4f8; padding:3px 10px; border-radius:20px; }

        .tbl-wrap { overflow-x:auto; }

        table { width:100%; border-collapse:collapse; font-size:12.5px; }

        table thead th {
            background:#f8fafc; padding:10px 14px; text-align:left;
            font-weight:600; font-size:11px; color:var(--text-muted);
            text-transform:uppercase; letter-spacing:0.5px;
            border-bottom:1px solid var(--border); white-space:nowrap;
        }

        table tbody td {
            padding:11px 14px; border-bottom:1px solid var(--border);
            color:var(--text); vertical-align:middle;
        }

        table tbody tr:last-child td { border-bottom:none; }
        table tbody tr:hover td { background:#f7fafc; }

        /* ===== LOADING / EMPTY ===== */
        .tbl-loading {
            text-align:center; padding:40px 20px;
            color:var(--text-muted); font-size:13px;
        }
        .tbl-loading i { font-size:24px; display:block; margin-bottom:10px; }

        /* ===== BADGES ===== */
        .badge-nim {
            background:#ebf5fb; color:var(--primary-dark);
            padding:3px 8px; border-radius:5px; font-size:11px; font-weight:600;
        }

        .badge-status {
            display:inline-block; padding:3px 9px;
            border-radius:20px; font-size:11px; font-weight:600;
        }

        .badge-green  { background:#eafaf1; color:#1e8449; }
        .badge-orange { background:#fef5e7; color:#b7770d; }
        .badge-red    { background:#fdedec; color:#c0392b; }
        .badge-purple { background:#f5eef8; color:#7d3c98; }
        .badge-yellow { background:#fdfbe7; color:#9a7d0a; }
        .badge-blue   { background:#ebf5fb; color:#1a5276; }
        .badge-gray   { background:#f2f3f4; color:#616a6b; }

        /* ===== ACTION BUTTONS ===== */
        .action-group { display:flex; gap:5px; align-items:center; }

        .btn-detail {
            background:#ebf5fb; color:var(--primary-dark);
            border:none; padding:5px 11px; border-radius:6px;
            font-size:11.5px; font-weight:600; font-family:inherit;
            cursor:pointer; transition:all 0.2s;
        }
        .btn-detail:hover { background:var(--primary); color:white; }

        .btn-edit {
            background:#eafaf1; color:#1e8449;
            border:none; padding:5px 11px; border-radius:6px;
            font-size:11.5px; font-weight:600; font-family:inherit;
            cursor:pointer; text-decoration:none; transition:all 0.2s;
            display:inline-flex; align-items:center; gap:4px;
        }
        .btn-edit:hover { background:var(--green); color:white; }

        .btn-delete {
            background:#fdedec; color:#c0392b;
            border:none; padding:5px 11px; border-radius:6px;
            font-size:11.5px; font-weight:600; font-family:inherit;
            cursor:pointer; text-decoration:none; transition:all 0.2s;
            display:inline-flex; align-items:center; gap:4px;
        }
        .btn-delete:hover { background:var(--red); color:white; }

        /* ===== PAGINATION ===== */
        .pagination-wrap {
            display:flex; align-items:center; justify-content:space-between;
            padding:14px 20px; border-top:1px solid var(--border);
            flex-wrap:wrap; gap:10px;
        }

        .pagination-info { font-size:12px; color:var(--text-muted); }

        .pagination-btns { display:flex; gap:5px; align-items:center; }

        .pg-btn {
            min-width:34px; height:34px; padding:0 10px;
            border:1.5px solid var(--border); background:white; color:var(--text);
            border-radius:7px; font-size:13px; font-weight:600;
            font-family:inherit; cursor:pointer;
            display:inline-flex; align-items:center; justify-content:center; gap:5px;
            transition:all 0.2s;
        }
        .pg-btn:hover:not(:disabled) { border-color:var(--primary); color:var(--primary); background:#ebf5fb; }
        .pg-btn.active { background:var(--primary); border-color:var(--primary); color:white; }
        .pg-btn:disabled { opacity:0.4; cursor:not-allowed; }

        /* ===== TOAST ===== */
        .toast-wrap {
            position:fixed; bottom:24px; right:24px; z-index:9999;
            display:flex; flex-direction:column; gap:8px;
        }
        .toast-msg {
            padding:12px 18px; border-radius:8px;
            font-size:13px; font-weight:600;
            box-shadow:0 4px 16px rgba(0,0,0,0.15);
            display:flex; align-items:center; gap:10px;
            animation:slideIn 0.25s ease;
        }
        .toast-success { background:#eafaf1; color:#1e8449; border:1.5px solid #a9dfbf; }
        .toast-error   { background:#fdedec; color:#c0392b; border:1.5px solid #f1948a; }
        @keyframes slideIn { from { transform:translateX(60px); opacity:0; } to { transform:translateX(0); opacity:1; } }

        /* ===== FOOTER ===== */
        .page-footer {
            text-align:center; padding:16px 28px;
            font-size:11.5px; color:var(--text-muted);
            border-top:1px solid var(--border); background:white;
        }

        /* ===== MODAL ===== */
        .modal-header { background:var(--primary); color:white; }
        .modal-title { font-weight:700; }
        .btn-close { filter:invert(1); }

        .detail-foto {
            width:100px; height:100px; border-radius:50%;
            object-fit:cover; border:3px solid var(--primary); margin-bottom:12px;
        }
        .detail-nama { font-size:17px; font-weight:700; color:var(--text); margin-bottom:4px; }
        .detail-nim-badge {
            background:#ebf5fb; color:var(--primary-dark);
            padding:3px 12px; border-radius:20px;
            font-size:12px; font-weight:600; display:inline-block; margin-bottom:16px;
        }
        .detail-row { display:flex; gap:10px; margin-bottom:8px; font-size:13px; }
        .detail-label { color:var(--text-muted); font-weight:600; min-width:120px; }
        .detail-val { color:var(--text); }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .topbar, .toolbar, .action-group, .page-footer { display:none !important; }
            .main { margin-left:0 !important; }
            .content { padding:0 !important; }
            table { font-size:10px; }
            table tbody tr { display:table-row !important; }
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
            <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="../tabelmahasiswa/mahasiswa.php" class="active"><i class="fas fa-user-graduate"></i> Data Mahasiswa</a></li>
            <li><a href="../tabeldosen/dosen.php"><i class="fas fa-chalkboard-teacher"></i> Data Dosen</a></li>
            <li><a href="../tabelmatkul/matakuliah"><i class="fas fa-book"></i> Data Mata Kuliah</a></li>
        </ul>
        <div class="nav-label" style="margin-top:10px;">Pengaturan</div>
        <ul>
            <li><a href="#"><i class="fas fa-cog"></i> Pengaturan Akun</a></li>
            <li><a href="#"><i class="fas fa-key"></i> Ganti Password</a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-wrap" id="toastWrap"></div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <h3><i class="fas fa-user-graduate" style="color:var(--primary);margin-right:8px;"></i>Data Mahasiswa</h3>
            <p>Sistem Informasi Akademik &mdash; FMIPA Universitas Riau</p>
        </div>
        <div class="topbar-right">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="form.html" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Mahasiswa
            </a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- TOOLBAR -->
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama, NIM, email...">
            </div>
            <select class="filter-select" id="filterJur">
                <option value="">Semua Jurusan</option>
                <option value="Matematika">Matematika</option>
                <option value="Fisika">Fisika</option>
                <option value="Kimia">Kimia</option>
                <option value="Biologi">Biologi</option>
                <option value="Ilmu Komputer">Ilmu Komputer</option>
            </select>
            <select class="filter-select" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Masa Langkau">Masa Langkau</option>
                <option value="Alpa Studi">Alpa Studi</option>
                <option value="Semhas">Semhas</option>
                <option value="Kompre">Kompre</option>
                <option value="Alumni">Alumni</option>
            </select>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="fas fa-table"></i> Daftar Mahasiswa FMIPA</h5>
                <span id="rowCount">Memuat...</span>
            </div>

            <div class="tbl-wrap">
                <table id="tblMahasiswa">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="chkAll" onchange="toggleAll(this)"></th>
                            <th style="width:36px;">No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Jenis Kelamin</th>
                            <th>Tgl. Lahir</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tblBody">
                        <tr>
                            <td colspan="11" class="tbl-loading">
                                <i class="fas fa-spinner fa-spin"></i>
                                Memuat data mahasiswa...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                <div class="pagination-info" id="pgInfo">Menampilkan 0–0 dari 0 data</div>
                <div class="pagination-btns" id="pgBtns"></div>
            </div>
        </div>

    </div><!-- /content -->

    <div class="page-footer">
        &copy; 2025 Sistem Informasi Akademik &mdash; FMIPA Universitas Riau
    </div>
</div>

<!-- MODAL DETAIL (single, diisi dinamis) -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Detail Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <!-- diisi via JS -->
            </div>
            <div class="modal-footer" style="justify-content:flex-end; gap:8px;">
                <a href="#" id="modalEditBtn" class="btn-edit" style="padding:7px 16px;">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <button type="button" class="btn-detail" data-bs-dismiss="modal" style="padding:7px 16px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// =============================================
//  HELPER: Mapping kode -> label
// =============================================
const MAP_JUR = {
    160301:'Matematika', 160302:'Fisika', 160303:'Kimia',
    160304:'Biologi', 160305:'Ilmu Komputer'
};
const MAP_PRODI = {
    '160311':'Matematika','160312':'Statistik','160313':'Fisika',
    '160314':'Kimia','160315':'Biologi','160316':'Sistem Informasi',
    '160317':'Manajemen Informatika'
};
const MAP_AGAMA = {1:'Islam',2:'Kristen',3:'Katholik',4:'Hindu',5:'Buddha',6:'Konghuchu'};
const MAP_STATUS = {1:'Aktif',2:'Masa Langkau',3:'Alpa Studi',4:'Semhas',5:'Kompre',6:'Alumni'};
const MAP_STATUS_COLOR = {1:'green',2:'orange',3:'red',4:'purple',5:'yellow',6:'blue'};

function namaJurusan(k)  { return MAP_JUR[k]    || '-'; }
function namaProdi(k)    { return MAP_PRODI[k]  || '-'; }
function namaAgama(k)    { return MAP_AGAMA[k]  || '-'; }
function namaStatus(k)   { return MAP_STATUS[k] || '-'; }
function badgeStatus(k)  { return MAP_STATUS_COLOR[k] || 'gray'; }
function namaJK(k)       { return k == 1 ? 'Laki-Laki' : 'Perempuan'; }
function formatTgl(str)  {
    if (!str) return '-';
    const [y,m,d] = str.split('-');
    return `${d}-${m}-${y}`;
}
function escHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str || ''));
    return div.innerHTML;
}

// =============================================
//  STATE
// =============================================
let allData     = [];   // semua data dari API
let filteredData = [];  // setelah filter/search
const PER_PAGE  = 5;
let currentPage = 1;

// =============================================
//  FETCH DATA dari API
// =============================================
async function loadData() {
    const tbody = document.getElementById('tblBody');
    tbody.innerHTML = `<tr><td colspan="11" class="tbl-loading">
        <i class="fas fa-spinner fa-spin"></i> Memuat data mahasiswa...</td></tr>`;

    try {
        const res  = await fetch('http://localhost/project_uas/api/mahasiswa/get.php');
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        allData = await res.json();
        applyFilter();
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="11" class="tbl-loading" style="color:var(--red);">
            <i class="fas fa-exclamation-triangle"></i>
            Gagal memuat data: ${escHtml(err.message)}</td></tr>`;
        document.getElementById('rowCount').textContent = 'Error';
    }
}

// =============================================
//  RENDER TABEL
// =============================================
function renderTable() {
    const total      = filteredData.length;
    const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
    currentPage      = Math.min(currentPage, totalPages);

    const start = (currentPage - 1) * PER_PAGE;
    const end   = Math.min(start + PER_PAGE, total);
    const slice = filteredData.slice(start, end);

    const tbody = document.getElementById('tblBody');

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="tbl-loading">
            <i class="fas fa-search"></i> Data tidak ditemukan</td></tr>`;
    } else {
        tbody.innerHTML = slice.map((row, i) => {
            const jur     = namaJurusan(row.jur);
            const prodi   = namaProdi(row.prodi);
            const status  = namaStatus(row.status);
            const stBadge = badgeStatus(row.status);
            const jk      = namaJK(row.jk);
            const tgl     = formatTgl(row.tgl_lahir);
            const no      = start + i + 1;

            return `<tr>
                <td><input type="checkbox" class="row-chk" name="nim[]" value="${escHtml(row.nim)}" onchange="updateSel()"></td>
                <td>${no}</td>
                <td><span class="badge-nim">${escHtml(row.nim)}</span></td>
                <td style="font-weight:600;">${escHtml(row.nama)}</td>
                <td>${jur}</td>
                <td>${prodi}</td>
                <td style="color:var(--text-muted);">${escHtml(row.email || '')}</td>
                <td><span class="badge-status badge-${stBadge}">${status}</span></td>
                <td>${jk}</td>
                <td>${tgl}</td>
                <td>
                    <div class="action-group" style="justify-content:center;">
                        <button type="button" class="btn-detail btn-open-detail"
                            data-row="${encodeURIComponent(JSON.stringify(row))}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a class="btn-edit" href="edit.php?nim=${encodeURIComponent(row.nim)}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="btn-delete" onclick="hapusMahasiswa('${escHtml(row.nim)}', '${escHtml(row.nama)}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    // Pasang event listener tombol detail
    document.querySelectorAll('.btn-open-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = JSON.parse(decodeURIComponent(btn.dataset.row));
            openDetail(row);
        });
    });

    // Update counter & pagination
    document.getElementById('rowCount').textContent = total + ' data';
    renderPagination(total, totalPages);
}

// =============================================
//  PAGINATION
// =============================================
function renderPagination(total, totalPages) {
    const pgInfo = document.getElementById('pgInfo');
    const pgBtns = document.getElementById('pgBtns');

    const start = (currentPage - 1) * PER_PAGE;
    const end   = Math.min(start + PER_PAGE, total);

    pgInfo.textContent = total === 0
        ? 'Tidak ada data'
        : `Menampilkan ${start + 1}–${end} dari ${total} data`;

    pgBtns.innerHTML = '';

    const prev = document.createElement('button');
    prev.className = 'pg-btn';
    prev.innerHTML = '<i class="fas fa-chevron-left"></i> Prev';
    prev.disabled  = currentPage === 1;
    prev.onclick   = () => { currentPage--; renderTable(); };
    pgBtns.appendChild(prev);

    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === currentPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = ((_p) => () => { currentPage = _p; renderTable(); })(p);
        pgBtns.appendChild(btn);
    }

    const next = document.createElement('button');
    next.className = 'pg-btn';
    next.innerHTML = 'Next <i class="fas fa-chevron-right"></i>';
    next.disabled  = currentPage === totalPages;
    next.onclick   = () => { currentPage++; renderTable(); };
    pgBtns.appendChild(next);
}

// =============================================
//  FILTER & SEARCH
// =============================================
function applyFilter() {
    const q   = document.getElementById('searchInput').value.toLowerCase();
    const jur = document.getElementById('filterJur').value.toLowerCase();
    const st  = document.getElementById('filterStatus').value.toLowerCase();

    filteredData = allData.filter(row => {
        const txt = [row.nim, row.nama, row.email,
                     namaJurusan(row.jur), namaProdi(row.prodi), namaStatus(row.status)]
                    .join(' ').toLowerCase();
        return txt.includes(q)
            && (jur === '' || namaJurusan(row.jur).toLowerCase().includes(jur))
            && (st  === '' || namaStatus(row.status).toLowerCase().includes(st));
    });

    currentPage = 1;
    renderTable();
}

// =============================================
//  HAPUS via API (DELETE)
// =============================================
async function hapusMahasiswa(nim, nama) {
    if (!confirm(`Yakin mau hapus data ${nama}?`)) return;

    try {
        const res = await fetch('http://localhost/project_uas/api/mahasiswa/delete.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nim })
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast('Data mahasiswa berhasil dihapus', 'success');
            // Hapus dari state lokal langsung, tanpa reload
            allData      = allData.filter(r => r.nim !== nim);
            filteredData = filteredData.filter(r => r.nim !== nim);
            renderTable();
        } else {
            showToast('Gagal menghapus: ' + (data.message || 'Error'), 'error');
        }
    } catch (err) {
        showToast('Gagal menghapus: ' + err.message, 'error');
    }
}

// =============================================
//  MODAL DETAIL
// =============================================
function openDetail(row) {
    const jur     = namaJurusan(row.jur);
    const prodi   = namaProdi(row.prodi);
    const agama   = namaAgama(row.agama);
    const status  = namaStatus(row.status);
    const stBadge = badgeStatus(row.status);
    const jk      = namaJK(row.jk);
    const tgl     = formatTgl(row.tgl_lahir);
    const fotoSrc = `../../foto/${escHtml(row.foto || '')}`;
    const avatar  = `https://ui-avatars.com/api/?name=${encodeURIComponent(row.nama)}&background=2980b9&color=fff&size=100`;

    document.getElementById('modalDetailBody').innerHTML = `
        <div style="text-align:center; padding-bottom:12px;">
            <img src="${fotoSrc}" class="detail-foto" onerror="this.src='${avatar}'">
            <div class="detail-nama">${escHtml(row.nama)}</div>
            <span class="detail-nim-badge">${escHtml(row.nim)}</span>
        </div>
        <hr>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-envelope me-1"></i>Email</span><span class="detail-val">${escHtml(row.email || '-')}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-map-marker-alt me-1"></i>Tempat Lahir</span><span class="detail-val">${escHtml(row.tmp_lahir || '-')}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-calendar me-1"></i>Tanggal Lahir</span><span class="detail-val">${tgl}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-venus-mars me-1"></i>Jenis Kelamin</span><span class="detail-val">${jk}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-building me-1"></i>Jurusan</span><span class="detail-val">${jur}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-book me-1"></i>Prodi</span><span class="detail-val">${prodi}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-pray me-1"></i>Agama</span><span class="detail-val">${agama}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-info-circle me-1"></i>Status</span><span class="detail-val"><span class="badge-status badge-${stBadge}">${status}</span></span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-home me-1"></i>Alamat</span><span class="detail-val">${escHtml(row.alamat || '-')}</span></div>
    `;

    document.getElementById('modalEditBtn').href = `edit.php?nim=${encodeURIComponent(row.nim)}`;

    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

// =============================================
//  TOAST NOTIFICATION
// =============================================
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toastWrap');
    const el   = document.createElement('div');
    el.className = `toast-msg toast-${type}`;
    el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'}"></i> ${escHtml(msg)}`;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

// =============================================
//  CHECKBOX
// =============================================
function toggleAll(src) {
    document.querySelectorAll('.row-chk').forEach(c => c.checked = src.checked);
}
function updateSel() {
    const all = document.querySelectorAll('.row-chk');
    const chk = document.querySelectorAll('.row-chk:checked');
    document.getElementById('chkAll').checked = all.length > 0 && all.length === chk.length;
}

// =============================================
//  EVENT LISTENERS
// =============================================
document.getElementById('searchInput').addEventListener('input', applyFilter);
document.getElementById('filterJur').addEventListener('change', applyFilter);
document.getElementById('filterStatus').addEventListener('change', applyFilter);

// =============================================
//  INIT
// =============================================
loadData();
</script>
</body>
</html>