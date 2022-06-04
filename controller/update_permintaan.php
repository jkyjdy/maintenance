<?php
// koneksi database
include '../model/koneksi.php';

// menangkap data yang dikirm dari form 

$ID = $_POST['ID'];
$no_inventaris = $_POST['no_inventaris'];
$direktorat = $_POST['direktorat'];
$unit = $_POST['unit'];
$merk = $_POST['merk'];
$inputer = $_POST['inputer'];
$jenis_barang = $_POST['jenis_barang'];
$kerusakan = $_POST['kerusakan'];
$status = 1;
$tgl_mulai = date ('Y-m-d');

// update data ke data base

mysqli_query($koneksi, "update permintaan set no_inventaris = '$no_inventaris', direktorat = '$direktorat', unit = '$unit', merk = '$merk', inputer = '$inputer', jenis_barang = '$jenis_barang', kerusakan ='$kerusakan', status = '$status', tgl_mulai = '$tgl_mulai' where ID ='$ID'");
echo"<script>alert('Data berhasil diedit');window.location='../permintaan.php'</script>";

?>