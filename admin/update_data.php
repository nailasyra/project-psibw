<!doctype html>
<html>
<head>
<title> update data </title>
</head>

<body>

<?php 
include("../config/koneksi.php"); 
if (!$conn) { 
  die("Connection failed: " . mysqli_connect_error()); 
} 
		if(isset($_POST['update'])) {
			$nim = $_POST['nim'];
			$nama = $_POST['nama'];
			$jur = $_POST['jur'];
			$prodi = $_POST['prodi'];
			$email = $_POST['email'];
			$agama = $_POST['agama'];
			$status = $_POST['status'];
			$tmp_lahir = $_POST['tmp_lahir'];
			$tgl_lahir = $_POST['tgl_lahir'];
			$tgl =substr($tgl_lahir,0,2);
			$bln =substr($tgl_lahir,3,2);
			$thn =substr($tgl_lahir,6,4);
			$tgllahir = $thn. "-"  .$bln."-".$tgl;
			$jk = $_POST['jk'];
			$alamat = $_POST['alamat'];
			$sql = "UPDATE mahasiswa set nim = '$nim',nama = '$nama', jur = '$jur', prodi = '$prodi',  email= '$email', agama = '$agama',status ='$status', tmp_lahir = '$tmp_lahir',tgl_lahir = '$tgl_lahir',jk = '$jk',alamat = '$alamat' where nim = '$nim' ";
 
		if(mysqli_query($conn,$sql)) {
			header("location:dasboard.php");
		}else{
			header("location:edit.php");
			}
		}
 ?>
 
 </body>
 </html>