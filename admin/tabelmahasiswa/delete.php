<!doctype html> 
<html> 
<head> 
 <title>Delete Data</title> 
</head> 
<body> 
<?php 
include("config/koneksi.php"); 
if (!$conn) { 
   die("Connection failed: " . mysqli_connect_error()); 
} 
$nim = $_GET['nim']; 
$sql= "DELETE FROM mahasiswa WHERE nim='$nim'"; 
if(mysqli_query($conn, $sql)){ 
     echo "<script> 
        alert('Data berhasil dihapus!'); 
        window.location='index.php'; 
   </script>"; 
   } else { 
      echo "<script> 
          alert('Gagal menghapus data!'); 
          window.location='index.php'; 
     </script>"; 
        } 
?> 
</body> 
</html>