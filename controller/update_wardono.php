<?php

// koneksi database

include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$pemakaian_o2_1_m3=$_POST['pemakaian_o2_1_m3'];
$pemakaian_o2_6_m3=$_POST['pemakaian_o2_6_m3'];
$pemakaian_liquid_o2=$_POST['pemakaian_liquid_o2'];
$pemakaian_n20=$_POST['pemakaian_n20'];
$maintenance_bed=$_POST['maintenance_bed'];
$maintenance_kursi_roda=$_POST['maintenance_kursi_roda'];
$maintenance_flowmeter_tabung=$_POST['maintenance_flowmeter_tabung'];
$maintenance_flowmeter_dinding=$_POST['maintenance_flowmeter_dinding'];
$maintenance_troli_medis=$_POST['maintenance_troli_medis'];
$perbaikan =$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "update laporan set nama = '$nama', tgl = '$tgl', ruangan = '$ruangan', pemakaian_o2_1_m3 = '$pemakaian_o2_1_m3', pemakaian_o2_6_m3 = '$pemakaian_o2_6_m3', pemakaian_liquid_o2 ='$pemakaian_liquid_o2', pemakaian_n20 = '$pemakaian_n20', maintenance_bed = '$maintenance_bed', maintenance_kursi_roda = '$maintenance_kursi_roda', maintenance_flowmeter_tabung = '$maintenance_flowmeter_tabung', maintenance_flowmeter_dinding = '$maintenance_flowmeter_dinding', maintenance_troli_medis = '$maintenance_troli_medis', perbaikan = '$perbaikan', lain ='$lain' where ID = '$ID' ");
echo"<script>alert('Data berhasil diedit');window.location='../laporan_wardono.php'</script>";

?>