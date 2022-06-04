<?php

//koneksi database

include '../model/koneksi.php';

$ID = $_GET['id'];
$tgl_selesai = date ('Y-m-d');
$waktu_selesai = date ('H:i:s');

// Ambil Data yang Dikirim dari Form

$nama_file = $_FILES['after_foto']['name'];
$ukuran_file = $_FILES['after_foto']['size'];
$tipe_file = $_FILES['after_foto']['type'];
$tmp_file = $_FILES['after_foto']['tmp_name'];


// Set path folder tempat menyimpan gambarnya
$path = "../images/".$nama_file;

// ====================================================================================================================


if($tipe_file == "image/jpeg" || $tipe_file == "image/png"){ // Cek apakah tipe file yang diupload adalah JPG / JPEG / PNG
    // Jika tipe file yang diupload JPG / JPEG / PNG, lakukan :
    if($ukuran_file <= 20000000){ // Cek apakah ukuran file yang diupload kurang dari sama dengan 1MB
      // Jika ukuran file kurang dari sama dengan 1MB, lakukan :
      // Proses upload
      if(move_uploaded_file($tmp_file, $path)){ // Cek apakah gambar berhasil diupload atau tidak
        // Jika gambar berhasil diupload, Lakukan :  
        // Proses simpan ke Database
        $query = "update permintaan set status= '3', tgl_selesai='$tgl_selesai',waktu_selesai='$waktu_selesai', foto_after='$nama_file' where ID = '$ID'";
        $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query
        
        if($sql){ // Cek jika proses simpan ke database sukses atau tidak
          // Jika Sukses, Lakukan :
          header("location: ../sedang_dikerjakan.php"); // Redirectke halaman index.php
        }else{
          // Jika Gagal, Lakukan :
          echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
          echo "<br><a href='../sedang_dikerjakan.php'>Kembali Ke Form</a>";
        }
      }else{
        // Jika gambar gagal diupload, Lakukan :
        echo "Maaf, Gambar gagal untuk diupload.";
        echo "<br><a href='../sedang_dikerjakan.php'>Kembali Ke Form</a>";
      }
    }else{
      // Jika ukuran file lebih dari 1MB, lakukan :
      echo "Maaf, Ukuran gambar yang diupload tidak boleh lebih dari 1MB";
      echo "<br><a href='../sedang_dikerjakan.php'>Kembali Ke Form</a>";
    }
  }else{
    // Jika tipe file yang diupload bukan JPG / JPEG / PNG, lakukan :
    echo "Maaf, Tipe gambar yang diupload harus JPG / JPEG / PNG.";
    echo "<br><a href='../sedang_dikerjakan.php'>Kembali Ke Form</a>";
  }

// mysqli_query($koneksi, "update permintaan set status= '2', tgl_pengerjaan='$tgl_pengerjaan',waktu_pengerjaan='$waktu_pengerjaan' where ID = '$ID'");

// header("location:../list_permintaan.php");
?>