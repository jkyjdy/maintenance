<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$panel_listrik=$_POST['panel_listrik'];
$insect_killer=$_POST['insect_killer'];
$lampu=$_POST['lampu'];
$mesin_jahit=$_POST['mesin_jahit'];
$mesin_laminating=$_POST['mesin_laminating'];
$mesin_mangel=$_POST['mesin_mangel'];
$mixer=$_POST['mixer'];
$sealer_cup=$_POST['sealer_cup'];
$pemotong_kertas=$_POST['pemotong_kertas'];
$stopkontak=$_POST['stopkontak'];
$perbaikan=$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi,"update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', panel_listrik = '$panel_listrik', insect_killer = '$insect_killer', lampu = '$lampu', mesin_jahit = '$mesin_jahit', mesin_laminating = '$mesin_laminating', mesin_mangel = '$mesin_mangel', mixer = '$mixer', sealer_cup = '$sealer_cup', pemotong_kertas = '$pemotong_kertas', stopkontak = '$stopkontak' , perbaikan = '$perbaikan', lain = '$lain' where ID = '$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_felix.php'</script>";

?>