<?php
include '../../config/koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$query = mysqli_query($conn, "SELECT * FROM mahasiswa");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data);
?>