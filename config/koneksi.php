<?php 
$conn = mysqli_connect("127.0.0.1", "root", "", "psibw", 3308);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>