<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$fire_alarm=$_POST['fire_alarm'];
$hydrant=$_POST['hydrant'];
$atap=$_POST['atap'];
$bangunan=$_POST['bangunan'];
$kloset=$_POST['kloset'];
$kran=$_POST['kran'];
$wastafel=$_POST['wastafel'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', fire_alarm = '$fire_alarm', hydrant = '$hydrant', atap = '$atap', bangunan = '$bangunan', kloset = '$kloset', kran = '$kran', wastafel = '$wastafel', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_riyanto.php'</script>";

?>