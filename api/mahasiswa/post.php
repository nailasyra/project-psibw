<?php
include '../../config/koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Baca dari JSON body (fetch API) atau fallback ke $_POST (form biasa)
$input = json_decode(file_get_contents('php://input'), true);

$nim   = $input['nim']   ?? $_POST['nim']   ?? null;
$nama  = $input['nama']  ?? $_POST['nama']  ?? null;
$email = $input['email'] ?? $_POST['email'] ?? null;
$jur   = $input['jur']   ?? $_POST['jur']   ?? null;
$prodi = $input['prodi'] ?? $_POST['prodi'] ?? null;
$agama = $input['agama'] ?? $_POST['agama'] ?? null;
$status = $input['status'] ?? $_POST['status'] ?? null;
$jk    = $input['jk']    ?? $_POST['jk']    ?? null;
$tgl_lahir  = $input['tgl_lahir']  ?? $_POST['tgl_lahir']  ?? null;
$tmp_lahir  = $input['tmp_lahir']  ?? $_POST['tmp_lahir']  ?? null;
$alamat     = $input['alamat']     ?? $_POST['alamat']     ?? null;
$foto       = $input['foto']       ?? $_POST['foto']       ?? null;

if (!$nim || !$nama) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "NIM dan Nama wajib diisi"]);
    exit;
}

// Escape untuk keamanan
$nim       = mysqli_real_escape_string($conn, $nim);
$nama      = mysqli_real_escape_string($conn, $nama);
$email     = mysqli_real_escape_string($conn, $email ?? '');
$jur       = mysqli_real_escape_string($conn, $jur ?? '');
$prodi     = mysqli_real_escape_string($conn, $prodi ?? '');
$agama     = (int)($agama ?? 0);
$status    = (int)($status ?? 1);
$jk        = (int)($jk ?? 1);
$tgl_lahir = mysqli_real_escape_string($conn, $tgl_lahir ?? '');
$tmp_lahir = mysqli_real_escape_string($conn, $tmp_lahir ?? '');
$alamat    = mysqli_real_escape_string($conn, $alamat ?? '');
$foto      = mysqli_real_escape_string($conn, $foto ?? '');

$query = mysqli_query($conn,
    "INSERT INTO mahasiswa (nim, nama, email, jur, prodi, agama, status, jk, tgl_lahir, tmp_lahir, alamat, foto)
     VALUES ('$nim','$nama','$email','$jur','$prodi',$agama,$status,$jk,'$tgl_lahir','$tmp_lahir','$alamat','$foto')"
);

if ($query) {
    echo json_encode(["status" => "success", "message" => "Data mahasiswa berhasil ditambahkan"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>