<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
}

if($_SESSION['role'] != 2){
    header("Location: ../login.php");
}
?>
<h1>ini dasbord mahasiswa</h1>