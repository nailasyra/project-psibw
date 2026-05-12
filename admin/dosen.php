<?php

session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
}

if ($_SESSION['role'] != 1) {
    header("Location: ../login.php");
}

$query = mysqli_query($conn, "SELECT * FROM mahasiswa");

?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Data Mahasiswa</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
        }

        body {
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .main {
            flex: 1;
            background: #ecf0f1;
        }

        .header {
            background: white;
            padding: 20px;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;

        }

        table th {
            background: #34495e;
            color: white;
        }
    </style>

</head>

<body>

    <div class="sidebar">

        <h2>Portal UNRI</h2>

        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="mahasiswa.php">Mahasiswa</a></li>
            <li><a href="dosen.php">Dosen</a></li>
            <li><a href="matakuliah">Mata Kuliah</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="header">
            <tr>
                <td colspan="12" style="text-align: center; color: #a5557c;"><b>DATA MAHASISWA FMIPA</b></td>
                <td colspan="10" style="text-align:right;">
                    <a href="form.html" style="text-decoration:none;"> Tambah Data </a>
                </td>
            </tr>
        </div>

        <div class="content">

            <table>

                <tr>
                    <th>NO</th>
                    <th>NIM</th>
                    <th>NAMA</th>
                    <th>JUR</th>
                    <th>PRODI</th>
                    <th>EMAIL</th>
                    <th>AGAMA</th>
                    <th>STATUS</th>
                    <th>TMP. LAHIR</th>
                    <th>TGL. LAHIR</th>
                    <th>J. KELAMIN</th>
                    <th>ALAMAT</th>
                    <th>AKSI</th>
                </tr>

                <?php
                $no = 1;
                $tampil = mysqli_query($conn, "SELECT * FROM mahasiswa");
                foreach ($tampil as $row) {
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>" . $row['nim'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    if ($row['jur'] == 160301) {
                        echo "<td>Matematika</td>";
                    } else if ($row['jur'] == 160302) {
                        echo "<td>Fisika</td>";
                    } else if ($row['jur'] == 160303) {
                        echo "<td>Kimia</td>";
                    } else if ($row['jur'] == 160304) {
                        echo "<td>Biologi</td>";
                    } else if ($row['jur'] == 160305) {
                        echo "<td>Ilmu Komputer</td>";
                    } else {
                        echo "<td>-</td>";
                    }

                    if ($row['prodi'] == "160311") {
                        echo "<td>Matematika</td>";
                    } else if ($row['prodi'] == "160312") {
                        echo "<td>Statistik</td>";
                    } else if ($row['prodi'] == "160313") {
                        echo "<td>Fisika</td>";
                    } else if ($row['prodi'] == "160314") {
                        echo "<td>Kimia</td>";
                    } else if ($row['prodi'] == "160315") {
                        echo "<td>Biologi</td>";
                    } else if ($row['prodi'] == "160316") {
                        echo "<td>Sistem Informasi</td>";
                    } else if ($row['prodi'] == "160317") {
                        echo "<td>Manajemen Informatika</td>";
                    } else {
                        echo "<td>-</td>";
                    }
                    echo "<td>" . $row['email'] . "</td>";

                    if ($row['agama'] == "1") {
                        echo "<td>Islam</td>";
                    } elseif ($row['agama'] == "2") {
                        echo "<td>Kristen</td>";
                    } elseif ($row['agama'] == "3") {
                        echo "<td>Katholik</td>";
                    } elseif ($row['agama'] == "4") {
                        echo "<td>Hindu</td>";
                    } elseif ($row['agama'] == "5") {
                        echo "<td>Buddha</td>";
                    } elseif ($row['agama'] == "6") {
                        echo "<td>Konghuchu</td>";
                    } else {
                        echo "<td>-</td>";
                    }

                    if ($row['status'] == "1") {
                        echo "<td>Aktif</td>";
                    } elseif ($row['status'] == "2") {
                        echo "<td>Masa Langkau</td>";
                    } elseif ($row['status'] == "3") {
                        echo "<td>Alpa Studi</td>";
                    } elseif ($row['status'] == "4") {
                        echo "<td>Semhas</td>";
                    } elseif ($row['status'] == "5") {
                        echo "<td>Kompre</td>";
                    } elseif ($row['status'] == "6") {
                        echo "<td>Alumni</td>";
                    } else {
                        echo "<td>-</td>";
                    }

                    echo "<td>" . $row['tmp_lahir'] . "</td>";
                    echo "<td>";
                    $tgl = substr($row['tgl_lahir'], 8, 2);
                    $bln = substr($row['tgl_lahir'], 5, 2);
                    $thn = substr($row['tgl_lahir'], 0, 4);
                    echo $tgl . "-" . $bln . "-" . $thn;
                    echo "</td>";

                    if ($row['jk'] == "1") {
                        echo "<td>Laki-Laki</td>";
                    } else {
                        echo "<td>Perempuan</td>";
                    }

                    echo "<td>" . $row['alamat'] . "</td>";
                    ?>

                    <td style="text-align:center;">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#detail<?php echo $row['nim']; ?>">

                            Detail

                        </button>
                        <a href="edit.php?nim=<?php echo $row['nim']; ?>"
                            style="text-decoration:none;">Edit&nbsp;|&nbsp;</a>
                        <a href="delete.php?nim=<?php echo $row['nim']; ?>" style="text-decoration:none;"
                            onclick="return confirm('Yakin mau hapus data ini?')"> Delete</a>
                    </td>
                    </tr>
                    <div class="modal fade" id="detail<?php echo $row['nim']; ?>">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Detail Mahasiswa
                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <center>

                                       <?php
echo "<img src='../foto/".$row['foto']."'
width='120'
height='120'
style='border-radius:50%;
object-fit:cover;
margin-bottom:15px;'>";
?>

                                        <h3><?php echo $row['nama']; ?></h3>

                                    </center>

                                    <hr>

                                    <p><b>NIM :</b>
                                        <?php echo $row['nim']; ?></p>

                                    <p><b>Email :</b>
                                        <?php echo $row['email']; ?></p>

                                    <p><b>Tempat Lahir :</b>
                                        <?php echo $row['tmp_lahir']; ?></p>

                                    <p><b>Tanggal Lahir :</b>
                                        <?php echo $row['tgl_lahir']; ?></p>

                                    <p><b>Alamat :</b>
                                        <?php echo $row['alamat']; ?></p>

                                    <!-- JURUSAN -->
                                    <p><b>Jurusan :</b>

                                        <?php

                                        if ($row['jur'] == 160301) {
                                            echo "Matematika";
                                        } else if ($row['jur'] == 160302) {
                                            echo "Fisika";
                                        } else if ($row['jur'] == 160303) {
                                            echo "Kimia";
                                        } else if ($row['jur'] == 160304) {
                                            echo "Biologi";
                                        } else if ($row['jur'] == 160305) {
                                            echo "Ilmu Komputer";
                                        }

                                        ?>

                                    </p>

                                    <!-- PRODI -->
                                    <p><b>Prodi :</b>

                                        <?php

                                        if ($row['prodi'] == 160311) {
                                            echo "Matematika";
                                        } else if ($row['prodi'] == 160312) {
                                            echo "Statistik";
                                        } else if ($row['prodi'] == 160313) {
                                            echo "Fisika";
                                        } else if ($row['prodi'] == 160314) {
                                            echo "Kimia";
                                        } else if ($row['prodi'] == 160315) {
                                            echo "Biologi";
                                        } else if ($row['prodi'] == 160316) {
                                            echo "Sistem Informasi";
                                        } else if ($row['prodi'] == 160317) {
                                            echo "Manajemen Informatika";
                                        }

                                        ?>

                                    </p>

                                    <!-- AGAMA -->
                                    <p><b>Agama :</b>

                                        <?php

                                        if ($row['agama'] == 1) {
                                            echo "Islam";
                                        } elseif ($row['agama'] == 2) {
                                            echo "Kristen";
                                        } elseif ($row['agama'] == 3) {
                                            echo "Katholik";
                                        } elseif ($row['agama'] == 4) {
                                            echo "Hindu";
                                        } elseif ($row['agama'] == 5) {
                                            echo "Buddha";
                                        } elseif ($row['agama'] == 6) {
                                            echo "Konghuchu";
                                        }

                                        ?>

                                    </p>

                                    <!-- STATUS -->
                                    <p><b>Status :</b>

                                        <?php

                                        if ($row['status'] == 1) {
                                            echo "Aktif";
                                        } elseif ($row['status'] == 2) {
                                            echo "Masa Langkau";
                                        } elseif ($row['status'] == 3) {
                                            echo "Alpa Studi";
                                        } elseif ($row['status'] == 4) {
                                            echo "Semhas";
                                        } elseif ($row['status'] == 5) {
                                            echo "Kompre";
                                        } elseif ($row['status'] == 6) {
                                            echo "Alumni";
                                        }

                                        ?>

                                    </p>

                                    <!-- JK -->
                                    <p><b>Jenis Kelamin :</b>

                                        <?php

                                        if ($row['jk'] == 1) {
                                            echo "Laki-Laki";
                                        } else {
                                            echo "Perempuan";
                                        }

                                        ?>

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>
                    <?php

                    $no++;
                }
                ?>
            </table>

        </div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>