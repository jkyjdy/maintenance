<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$almari=$_POST['almari'];
$antena=$_POST['antena'];
$receiver=$_POST['receiver'];
$tv=$_POST['tv'];
$meja=$_POST['meja'];
$pintu=$_POST['pintu'];
$ventilasi=$_POST['ventilasi'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', almari = '$almari', antena = '$antena', receiver = '$receiver', tv = '$tv', meja = '$meja', pintu ='$pintu', ventilasi = '$ventilasi', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_sunday.php'</script>";

?>