<?php 
include("config/koneksi.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['tambah'])) {
    $nim     = $_POST['nim'];
    $nama    = $_POST['nama'];
    $jur     = $_POST['jur'];
    $prodi   = $_POST['prodi'];
	$email   = $_POST['email'];
    $agama   = $_POST['agama'];
    $status  = $_POST['status'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $tgl = substr($tgl_lahir,0,2);
    $bln = substr($tgl_lahir,3,2);
    $thn = substr($tgl_lahir,6,4);
    $tgl_lahir = $thn . "-" . $bln . "-" . $tgl;
    $jk     = $_POST['jk'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO mahasiswa(nim,nama,jur,prodi,email,agama,status,tmp_lahir,tgl_lahir,jk,alamat)
            VALUES ('$nim','$nama','$jur','$prodi','$email', '$agama','$status','$tmp_lahir','$tgl_lahir','$jk','$alamat')";
    if (mysqli_query($conn, $sql)) {
        header("location:index.php");
    }else{
	 header("location:form.php");
}
}
?>
