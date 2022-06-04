<?php
include('../model/koneksi.php');
$nama       = $_POST['nama'];
$pesanan    = implode(" , ", $_POST['pesanan']);
$total      = $_POST['total'];
$diskon     = $_POST['Diskon'];
$bayar      = $_POST['Bayar'];
$kasir      = 'Anya Geraldine, S.Kom';

$query = mysqli_query($koneksi, "INSERT INTO pesanan (nama,pesanan,harga,diskon,totalbayar,kasir) VALUES ('$nama','$pesanan','$total','$diskon','$bayar','$kasir')");

if ($query){
    // ambil data pesanan terakhir
    $query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");
    $data = mysqli_fetch_array($query);
    // ambil id dari sql yang didapat
    $id = $data['id'];
    echo"<script>alert('Data berhasil tersimpan'); window.location='../nota.php?id=$id'</script>";
}else{
    echo"Kesalahan Sistem";
}

?>