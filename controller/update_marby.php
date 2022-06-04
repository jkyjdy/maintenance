<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$genset=$_POST['genset'];
$kompor=$_POST['kompor'];
$mesin_penggiling_merica=$_POST['mesin_penggiling_merica'];
$oven=$_POST['oven'];
$pemanas_air=$_POST['pemanas_air'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', genset = '$genset', kompor = '$kompor', mesin_penggiling_merica = '$mesin_penggiling_merica', oven = '$oven', pemanas_air = '$pemanas_air', perbaikan = '$perbaikan', lain = '$lain' where ID ='$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_marby.php'</script>";

?>