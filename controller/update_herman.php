<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$mesin_cuci=$_POST['mesin_cuci'];
$mesin_pengering=$_POST['mesin_pengering'];
$mesin_pemeras=$_POST['mesin_pemeras'];
$petunjuk_arah=$_POST['petunjuk_arah'];
$setrika_uap=$_POST['setrika_uap'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', mesin_cuci = '$mesin_cuci', mesin_pengering = '$mesin_pengering', mesin_pemeras = '$mesin_pemeras', petunjuk_arah = '$petunjuk_arah', setrika_uap = '$setrika_uap', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_herman.php'</script>";

?>