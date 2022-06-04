<?php
//koneksi database//
include '../model/koneksi.php';

// menangkap data ID yang dikirim dari url //

$ID = $_GET['ID'];

// menghapus data dari database //

mysqli_query($koneksi ,"delete from  laporan where ID='$ID'");

// mengalihkan halaman kembali ke laporan_hendra.php // 

echo"<script>alert('Data berhasil dihapus');window.location='../laporan_herman.php'</script>";
?>