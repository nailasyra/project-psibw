<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Dosen</title>
			<style>
body {
    font-family: Arial, sans-serif;
   
}

table {
    width: 45%;
    margin: 10px auto;
    border-collapse: separate; 
    border-spacing: 0; 
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 3px solid #be86a1; 
}

th {
    background-color: #d17fac; 
    color: white;
    padding: 3px;
    border: 1px solid #f298c3;
}

td {
    padding: 6px;
	font-size: 14px;
}

tr:nth-child(even) {
    background-color: #dfb7c2;
}

a {
    text-decoration: none;
    color: rgb(94, 36, 88);
}

input[type=submit], input[type=reset] {
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin: 5px;
    transition: 0.3s;
	color: #ffffff;
	background-color: #ab7c96;
}

input[type=text], select {
    width: 70%;
    padding: 5px;
    border: 1px solid #e393ba;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
    background-color: #fff;
    transition: 0.3s;
	box-sizing: border-box;
}

td input, td select {
    margin: 3px 0;
}
</style>
</head>
<body>
<?php 
 include("../config/koneksi.php"); 
 $nim=$_GET['nim']; 
 $sql= "SELECT * FROM dosen WHERE nidn='$nidn'"; 
 $pilih = mysqli_query($conn,$sql); 
 $row = mysqli_fetch_array($pilih); 
 ?> 

	<form name="edit" action="update_data.php" method="post">
	<table>
	<tr><td colspan="3" style="text-align:center;"><b> FORM EDIT DATA DOSEN</b></td></tr>
	
	<tr><td>NIDN</td><td>:</td><td><input type=text name=nidn size=8
	value="<?php echo $row['nidn'];?>"></td></tr>
	
	<tr><td>NAMA</td><td>:</td><td><input type=text name=nama size=30
	value="<?php echo $row['nama'];?>"></td></tr></td></tr>
	
	<tr><td>JUR</td><td>:</td><td><select name=jur>
		<?php
			if ($row['jur'] == '160301') {
		?>
			<option value="<?php echo $row['jur']; ?>">Matematika</option>
        <?php
			} elseif ($row['jur'] == '160302') {
        ?>
            <option value="<?php echo $row['jur']; ?>">Fisika</option>
        <?php
			} elseif ($row['jur'] == '160303') {
        ?>
            <option value="<?php echo $row['jur']; ?>">Kimia</option>
        <?php
			} elseif ($row['jur'] == '160304') {
        ?>
            <option value="<?php echo $row['jur']; ?>">Biologi</option>
        <?php
			} elseif ($row['jur'] == '160305') {
        ?>
            <option value="<?php echo $row['jur']; ?>">Ilmu Komputer</option>
        <?php
			}
        ?>
									<option value=160301>Matematika</option>
									<option value=160302>Fisika</option>
									<option value=160303>Kimia</option>
									<option value=160304>Biologi</option>
									<option value=160305>Ilmu Komputer</option>
									</select>
	</td></tr>

	</td></tr>
	<tr><td>EMAIL</td><td>:</td><td><input type=text name=email size=30
	value="<?php echo $row['email'];?>"></td></tr></td></tr>
	
	<tr><td>AGAMA</td><td>:</td><td><select name=agama>
		<?php
			if ($row['agama'] == '1') {
		?>
			<option value="<?php echo $row['agama']; ?>">Islam</option>
        <?php
			} elseif ($row['agama'] == '2') {
        ?>
            <option value="<?php echo $row['agama']; ?>">Kristen</option>
        <?php
			} elseif ($row['agama'] == '3') {
        ?>
            <option value="<?php echo $row['agama']; ?>">Katholik</option>
        <?php
			} elseif ($row['agama'] == '4') {
        ?>
            <option value="<?php echo $row['agama']; ?>">Hindu</option>
        <?php
			} elseif ($row['agama'] == '5') {
        ?>
            <option value="<?php echo $row['agama']; ?>">Budha</option>
		<?php
			} elseif ($row['agama'] == '6') {
        ?>
            <option value="<?php echo $row['agama']; ?>">Konghuchu</option>
        <?php
			}
        ?>
									<option value=1>Islam</option>
									<option value=2>Kristen</option>
									<option value=3>Katholik</option>
									<option value=4>Hindu</option>
									<option value=5>Budha</option>
									<option value=6>Konghuchu</option>
									</select>
	</td></tr>
	<tr><td>STATUS</td><td>:</td><td><select name=status>
		<?php
			if ($row['status'] == '1') {
		?>
			<option value="<?php echo $row['status']; ?>">Aktif</option>
        <?php
			} elseif ($row['status'] == '2') {
        ?>
            <option value="<?php echo $row['status']; ?>">Masa Langkau</option>
        <?php
			} elseif ($row['status'] == '3') {
        ?>
            <option value="<?php echo $row['status']; ?>">Alpha Studi</option>
        <?php
			} elseif ($row['status'] == '4') {
        ?>
            <option value="<?php echo $row['status']; ?>">Semhas</option>
        <?php
			} elseif ($row['status'] == '5') {
        ?>
            <option value="<?php echo $row['status']; ?>">Kompre</option>
		<?php
			} elseif ($row['status'] == '6') {
        ?>
            <option value="<?php echo $row['status']; ?>">Alumni</option>
        <?php
			}
        ?>
									<option value=1>Aktif</option>
									<option value=2>Masa Langkau</option>
									<option value=3>Alpha Studi</option>
									<option value=4>Semhas</option>
									<option value=5>Kompre</option>
									<option value=6>Alumni</option>
									</select>
	</td></tr>
	
	<tr><td>TMP. LAHIR</td><td>:</td><td><input type=text name=tmp_lahir size=30
	value="<?php echo $row['tmp_lahir'];?>"></td></tr>
	
	<tr><td>TGL. LAHIR</td><td>:</td><td><input type=text name=tgl_lahir size=8
	value="<?php echo $row['tgl_lahir'];?>"> format(yyyy-mm-dd)</td></tr>
	
	<tr><td>J. KELAMIN</td><td>:</td><td><select name=jk>
		<?php
			if ($row['jk'] == '1') {
		?>
			<option value="<?php echo $row['jk']; ?>">laki-Laki</option>
        <?php
			} elseif ($row['jk'] == '0') {
        ?>
            <option value="<?php echo $row['jk']; ?>">Perempuan</option>
        
        <?php
			}
        ?>
									<option value=1>Laki-Laki</option>
									<option value=0>Perempuan</option>
									</select>
	</td></tr>	
	<tr><td>ALAMAT</td><td>:</td><td><input type=text name=alamat size=30 
value="<?php echo $row['alamat'];?>"></td></tr>
<tr><td>ALAMAT</td><td>:</td><td><input type=text name=pendidikan size=30 
value="<?php echo $row['pendidikan'];?>"></td></tr>
<tr><td>ALAMAT</td><td>:</td><td><input type=text name=fotodosen size=30 
value="<?php echo $row['fotodosen'];?>"></td></tr> 
<tr><td colspan="3" style="text-align:center;"><input type=submit 
name=update value=UPDATE> 
<input type=reset name=reset value=RESET></td></tr> 
 </form> 
 </table> 