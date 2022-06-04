<?php
include '../model/koneksi.php';

$ID = $_GET['id'];
$partner = $_POST['partner'];
$partner2 = $_POST['partner2'];
$partner3 = $_POST['partner3'];
$partner4 = $_POST['partner4'];

$query = "update permintaan set partner='$partner', partner2='$partner2',partner3='$partner3',partner4='$partner4' where ID = '$ID'";
$sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query

header("location:../sedang_dikerjakan.php");

?>