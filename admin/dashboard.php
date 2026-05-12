
<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
}

if($_SESSION['role'] != 1){
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial;
        }

        body{
            display:flex;
        }

        .sidebar{
            width:250px;
            height:100vh;
            background:#2c3e50;
            color:white;
            padding:20px;
        }

        .sidebar h2{
            margin-bottom:30px;
        }

        .sidebar ul{
            list-style:none;
        }

        .sidebar ul li{
            margin:20px 0;
        }

        .sidebar ul li a{
            color:white;
            text-decoration:none;
        }

        .main{
            flex:1;
            background:#ecf0f1;
        }

        .header{
            background:white;
            padding:20px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
        }

        .content{
            padding:20px;
        }

    </style>

</head>
<body>

    <div class="sidebar">

        <h2>Portal UNRI</h2>

        <ul>
            <li><a href="dasboard.php">Dashboard</a></li>
            <li><a href="tabelmahasiswa/mahasiswa.php">Mahasiswa</a></li>
            <li><a href="tabeldosen/dosen.php">dosen</a></li>
            <li><a href="tabelmatkul/matkul.php">matakuliah</a></li>
            <li><a href="#">Tukar Password</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="header">
            <h2>Dashboard Admin</h2>
        </div>

        <div class="content">
            <h3>Selamat Datang Admin</h3>
        </div>

    </div>

</body>
</html>