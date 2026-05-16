<?php
include '../../config/koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Baca dari JSON body (fetch API) atau fallback ke query string
$input = json_decode(file_get_contents('php://input'), true);
$nim   = $input['nim'] ?? $_GET['nim'] ?? null;

if (!$nim) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "NIM wajib diisi"]);
    exit;
}

$nim   = mysqli_real_escape_string($conn, $nim);
$query = mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim='$nim'");

if ($query) {
    echo json_encode(["status" => "success", "message" => "Data mahasiswa berhasil dihapus"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>