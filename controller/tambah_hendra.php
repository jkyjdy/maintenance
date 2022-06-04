<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    
</head>

<body>
    <?php
// koneksi database
include '../model/koneksi.php';

$nama =$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$kipas_angin=$_POST['kipas_angin'];
$ac=$_POST['ac'];
$exhaust=$_POST['exhaust'];
$kulkas=$_POST['kulkas'];
$perbaikan=$_POST['lain'];
$lain=$_POST['lain'];

//manginput data ke database

mysqli_query($koneksi,"insert into laporan (ID,nama,tgl,ruangan,kipas_angin,ac,exhaust,kulkas,perbaikan,lain) values ('','$nama','$tgl','$ruangan','$kipas_angin','$ac','$exhaust','$kulkas','$perbaikan','$lain')");

echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>";
    echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>";
    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js'></script>";

    echo"<script>
      swal({ title: 'Berhasil',
         text: 'Laporan Berhasil Ditambahkan',
         showConfirmButton: false,
         timer: 1500,
         type: 'success'}).then(okay => {
           if (okay) {
            window.location.href = '../laporan_hendra.php';
          }
        });
      </script>";


    ?>
</body>
</html>