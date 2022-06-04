<?php

include('../model/koneksi.php');
    $id = $_GET['id'];
    
    $query = mysqli_query($koneksi,"DELETE FROM menu WHERE id='$id'");
    
    if ($query){
    echo"<script>alert('Akun berhasil DIHAPUS'); window.location='../administrator/makanan.php'</script>";
    }else{
        echo"Kesalahan Sistem";
    }
?>