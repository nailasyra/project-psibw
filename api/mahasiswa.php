<?php
include '../config/koneksi.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch($method){

    // GET = tampil data
    case 'GET':
        $query = mysqli_query($conn, "SELECT * FROM mahasiswa");

        $data = [];
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // POST = tambah data
    case 'POST':
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];

        mysqli_query($conn,
        "INSERT INTO mahasiswa(nama,nim)
         VALUES('$nama','$nim')");

        echo json_encode([
            "status"=>"success"
        ]);
        break;

    // DELETE = hapus
    case 'DELETE':
        $id = $_GET['id'];

        mysqli_query($conn,
        "DELETE FROM mahasiswa WHERE id='$id'");

        echo json_encode([
            "status"=>"deleted"
        ]);
        break;
}
?>