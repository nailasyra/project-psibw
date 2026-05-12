<?php

session_start();

include 'config/koneksi.php';

$username = $_POST['username'];
$pass = $_POST['pass'];

$query = mysqli_query($conn, "SELECT * FROM user 
WHERE username='$username' AND pass='$pass'");

$data = mysqli_fetch_assoc($query);

if($data){

    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    // ADMIN
    if($data['role'] == 1){

        header("Location: admin/dashboard.php");

    // MAHASISWA
    } elseif($data['role'] == 2){

        header("Location: mahasiswa/dashboard.php");

    // DOSEN
    } elseif($data['role'] == 3){

        header("Location: dosen/dashboard.php");

    }

} else {

    echo "Login gagal";

}

?>