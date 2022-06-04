<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$genset=$_POST['genset'];
$ups=$_POST['ups'];
$dispenser=$_POST['dispenser'];
$pompa_kolam=$_POST['pompa_kolam'];
$jam_dinding=$_POST['jam_dinding'];
$kursi=$_POST['kursi'];
$mesin_pengaduk_adonan=$_POST['mesin_pengaduk_adonan'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli($koneksi, "update laporan set nama ='$nama', tgl = '$tgl', ruangan = '$ruangan', genset = '$genset', ups = '$ups', dispenser = '$dispenser', pompa_kolam = '$pompa_kolam', jam_dinding = '$jam_dinding', kursi = '$kursi', mesin_pengaduk_adonan = '$meisn_pengaduk_adonan', perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_dody.php'</script>";

?>