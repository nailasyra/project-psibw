<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
}

if($_SESSION['role'] != 3){
    header("Location: ../login.php");
}
?>
<h1>ini dasbor dosen</h1>