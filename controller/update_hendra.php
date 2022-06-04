<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan = $_POST['ruangan'];
$kipas_angin = $_POST['kipas_angin'];
$ac = $_POST['ac'];
$exhaust = $_POST['exhaust'];
$kulkas=$_POST['kulkas'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama='$nama', tgl = '$tgl', ruangan = '$ruangan', kipas_angin = '$kipas_angin', ac = '$ac', exhaust = '$exhaust', kulkas = '$kulkas', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_hendra.php'</script>";

?>