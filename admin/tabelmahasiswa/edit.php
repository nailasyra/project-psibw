<?php
session_start();
include("../../config/koneksi.php");

$nim = $_GET['nim'] ?? '';
if (!$nim) {
    header("Location: mahasiswa.php");
    exit;
}

$nim_esc = mysqli_real_escape_string($conn, $nim);
$sql     = "SELECT * FROM mahasiswa WHERE nim='$nim_esc'";
$pilih   = mysqli_query($conn, $sql);
$row     = mysqli_fetch_assoc($pilih);

if (!$row) {
    header("Location: mahasiswa.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa — SIAKAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #2980b9;
            --primary-dark: #1f6391;
            --sidebar-bg: #1a3a5c;
            --green: #27ae60;
            --red: #e74c3c;
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
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 16px;
        }

        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 560px;
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .form-header {
            background: var(--sidebar-bg);
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .form-header .header-icon {
            width: 42px; height: 42px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
            flex-shrink: 0;
        }

        .form-header h2 { font-size: 16px; font-weight: 700; color: white; line-height: 1.3; }
        .form-header p  { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }

        /* NIM badge di header */
        .nim-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            color: white;
            font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }

        /* ===== BODY ===== */
        .form-body { padding: 28px; }

        .section-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 14px; padding-bottom: 6px;
            border-bottom: 1.5px solid var(--border);
        }

        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--text); margin-bottom: 6px;
        }

        .form-group label span.required { color: var(--red); margin-left: 2px; }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="email"],
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 9px 12px;
            border: 1.5px solid var(--border); border-radius: 8px;
            font-family: inherit; font-size: 13px; color: var(--text);
            background: white; outline: none;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(41,128,185,0.12);
        }

        /* NIM field readonly */
        .form-group input[readonly] {
            background: #f8fafc;
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .form-group textarea { resize: vertical; min-height: 72px; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .divider { height: 1px; background: var(--border); margin: 20px 0; }

        /* ===== FOOTER ===== */
        .form-footer {
            padding: 18px 28px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center;
            justify-content: space-between;
            gap: 10px; flex-wrap: wrap;
        }

        .btn-back {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border: 1.5px solid var(--border);
            border-radius: 8px; font-family: inherit;
            font-size: 13px; font-weight: 600;
            color: var(--text-muted); background: white;
            cursor: pointer; text-decoration: none; transition: all 0.2s;
        }
        .btn-back:hover { border-color: var(--primary); color: var(--primary); }

        .btn-group { display: flex; gap: 10px; }

        .btn-reset {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border: 1.5px solid var(--border);
            border-radius: 8px; font-family: inherit;
            font-size: 13px; font-weight: 600;
            color: var(--text-muted); background: white;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-reset:hover { border-color: var(--red); color: var(--red); }

        .btn-update {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px; background: var(--green);
            border: none; border-radius: 8px;
            font-family: inherit; font-size: 13px; font-weight: 600;
            color: white; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-update:hover { background: #1e8449; transform: translateY(-1px); }
        .btn-update:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* ===== TOAST ===== */
        .toast-wrap {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999; display: flex; flex-direction: column; gap: 8px;
        }
        .toast-msg {
            padding: 12px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            display: flex; align-items: center; gap: 10px;
            animation: slideIn 0.25s ease;
        }
        .toast-success { background: #eafaf1; color: #1e8449; border: 1.5px solid #a9dfbf; }
        .toast-error   { background: #fdedec; color: #c0392b; border: 1.5px solid #f1948a; }
        @keyframes slideIn {
            from { transform: translateX(60px); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
    </style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

<div class="form-card">

    <!-- HEADER -->
    <div class="form-header">
        <div class="header-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        <div>
            <h2>Edit Data Mahasiswa</h2>
            <p>Sistem Informasi Akademik — FMIPA Universitas Riau</p>
            <span class="nim-badge"><i class="fas fa-id-badge" style="margin-right:4px;"></i><?= htmlspecialchars($row['nim']) ?></span>
        </div>
    </div>

    <!-- BODY -->
    <div class="form-body">

        <!-- Identitas -->
        <div class="section-label"><i class="fas fa-id-card" style="margin-right:6px;"></i>Identitas Mahasiswa</div>

        <div class="form-group">
            <label>NIM <span class="required">*</span></label>
            <input type="text" id="nim" name="nim"
                   value="<?= htmlspecialchars($row['nim']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" name="nama"
                   value="<?= htmlspecialchars($row['nama']) ?>"
                   placeholder="Nama lengkap mahasiswa">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($row['email'] ?? '') ?>"
                   placeholder="email@student.unri.ac.id">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jurusan <span class="required">*</span></label>
                <select id="jur" name="jur">
                    <?php
                    $jur_map = [
                        '160301' => 'Matematika',
                        '160302' => 'Fisika',
                        '160303' => 'Kimia',
                        '160304' => 'Biologi',
                        '160305' => 'Ilmu Komputer',
                    ];
                    foreach ($jur_map as $val => $label):
                    ?>
                        <option value="<?= $val ?>" <?= $row['jur'] == $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Program Studi <span class="required">*</span></label>
                <select id="prodi" name="prodi">
                    <?php
                    $prodi_map = [
                        '160311' => 'Matematika',
                        '160312' => 'Statistika',
                        '160313' => 'Fisika',
                        '160314' => 'Kimia',
                        '160315' => 'Biologi',
                        '160316' => 'Sistem Informasi',
                        '160317' => 'Manajemen Informatika',
                    ];
                    foreach ($prodi_map as $val => $label):
                    ?>
                        <option value="<?= $val ?>" <?= $row['prodi'] == $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select id="status" name="status">
                    <?php
                    $status_map = [
                        1 => 'Aktif',
                        2 => 'Masa Langkau',
                        3 => 'Alpa Studi',
                        4 => 'Semhas',
                        5 => 'Kompre',
                        6 => 'Alumni',
                    ];
                    foreach ($status_map as $val => $label):
                    ?>
                        <option value="<?= $val ?>" <?= $row['status'] == $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Agama</label>
                <select id="agama" name="agama">
                    <?php
                    $agama_map = [
                        1 => 'Islam',
                        2 => 'Kristen',
                        3 => 'Katholik',
                        4 => 'Hindu',
                        5 => 'Buddha',
                        6 => 'Konghuchu',
                    ];
                    foreach ($agama_map as $val => $label):
                    ?>
                        <option value="<?= $val ?>" <?= $row['agama'] == $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Data Pribadi -->
        <div class="section-label"><i class="fas fa-user" style="margin-right:6px;"></i>Data Pribadi</div>

        <div class="form-row">
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select id="jk" name="jk">
                    <option value="1" <?= $row['jk'] == 1 ? 'selected' : '' ?>>Laki-Laki</option>
                    <option value="0" <?= $row['jk'] == 0 ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                       value="<?= htmlspecialchars($row['tgl_lahir'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" id="tmp_lahir" name="tmp_lahir"
                   value="<?= htmlspecialchars($row['tmp_lahir'] ?? '') ?>"
                   placeholder="Kota tempat lahir">
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea id="alamat" name="alamat"
                      placeholder="Alamat lengkap mahasiswa"><?= htmlspecialchars($row['alamat'] ?? '') ?></textarea>
        </div>

    </div><!-- /form-body -->

    <!-- FOOTER -->
    <div class="form-footer">
        <a href="mahasiswa.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="btn-group">
            <button type="button" class="btn-reset" onclick="resetForm()">
                <i class="fas fa-undo"></i> Reset
            </button>
            <button type="button" class="btn-update" id="btnUpdate" onclick="updateData()">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </div>

</div><!-- /form-card -->

<!-- Simpan nilai awal untuk reset -->
<script>
// Ambil nilai awal dari PHP sebelum user ubah apapun
const originalData = {
    nama     : <?= json_encode($row['nama'])      ?>,
    email    : <?= json_encode($row['email'] ?? '') ?>,
    jur      : <?= json_encode($row['jur'])       ?>,
    prodi    : <?= json_encode($row['prodi'])      ?>,
    status   : <?= json_encode($row['status'])     ?>,
    agama    : <?= json_encode($row['agama'])      ?>,
    jk       : <?= json_encode($row['jk'])         ?>,
    tgl_lahir: <?= json_encode($row['tgl_lahir'] ?? '') ?>,
    tmp_lahir: <?= json_encode($row['tmp_lahir'] ?? '') ?>,
    alamat   : <?= json_encode($row['alamat'] ?? '')   ?>,
};

function val(id) { return document.getElementById(id).value.trim(); }

function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toastWrap');
    const el   = document.createElement('div');
    el.className = `toast-msg toast-${type}`;
    el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'}"></i> ${msg}`;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

// Reset ke nilai awal dari database
function resetForm() {
    document.getElementById('nama').value      = originalData.nama;
    document.getElementById('email').value     = originalData.email;
    document.getElementById('tgl_lahir').value = originalData.tgl_lahir;
    document.getElementById('tmp_lahir').value = originalData.tmp_lahir;
    document.getElementById('alamat').value    = originalData.alamat;

    ['jur','prodi','status','agama','jk'].forEach(id => {
        const sel = document.getElementById(id);
        for (let opt of sel.options) {
            opt.selected = (opt.value == originalData[id]);
        }
    });

    showToast('Form dikembalikan ke data awal', 'success');
}

async function updateData() {
    const nama = val('nama');
    if (!nama) { showToast('Nama wajib diisi', 'error'); return; }

    const btn = document.getElementById('btnUpdate');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const payload = {
        nim      : val('nim'),
        nama,
        email    : val('email'),
        jur      : val('jur'),
        prodi    : val('prodi'),
        agama    : val('agama'),
        status   : val('status'),
        jk       : val('jk'),
        tgl_lahir: val('tgl_lahir'),
        tmp_lahir: val('tmp_lahir'),
        alamat   : val('alamat'),
        foto     : <?= json_encode($row['foto'] ?? '') ?>,
    };

    try {
        const res  = await fetch('http://localhost/project_uas/api/mahasiswa/put.php', {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast('Data berhasil diperbarui!', 'success');
            setTimeout(() => window.location.href = 'mahasiswa.php', 1500);
        } else {
            showToast('Gagal: ' + (data.message || 'Terjadi kesalahan'), 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Update';
        }
    } catch (err) {
        showToast('Gagal terhubung ke server: ' + err.message, 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Update';
    }
}
</script>

</body>
</html>