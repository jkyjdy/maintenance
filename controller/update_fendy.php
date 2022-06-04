<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$baliho=$_POST['baliho'];
$kalkulator=$_POST['kalkulator'];
$kran=$_POST['kran'];
$pagar=$_POST['pagar'];
$troli=$_POST['troli'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];


mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', baliho = '$baliho', kalkulator = '$kalkulator', kran = '$kran', pagar = '$pagar', troli = '$troli', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_fendy.php'</script>";

?>