<?php
include('../model/koneksi.php');
$nama       = $_POST['nama'];
$username      = $_POST['username'];
$password     = $_POST['password'];
$role      = $_POST['role'];

$query = mysqli_query($koneksi, "INSERT INTO akun (nama,username,password,role) VALUES ('$nama','$username','$password','$role')");

if ($query){
    echo"<script>alert('Akun berhasil tersimpan'); window.location='../administrator/akun.php'</script>";
}else{
    echo"Kesalahan Sistem";
}

?>