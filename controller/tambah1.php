
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

//menangkap data yang dikirm dari form

// $no_inventaris = $_POST['no_inventaris'];
$direktorat = $_POST['direktorat'];
$unit =$_POST['unit'];
$merk =$_POST['merk'];
$inputer =$_POST['inputer'];
$jenis_barang =$_POST['jenis_barang'];
$kerusakan =$_POST['kerusakan'];
$tgl_mulai= date('Y-m-d');
$waktu_mulai= date('H:i');
$status = 1;


//menginput data ke database

mysqli_query($koneksi,"insert into permintaan (ID,direktorat,unit,merk,inputer,jenis_barang,kerusakan,status,tgl_mulai,waktu_mulai) values('','$direktorat','$unit','$merk','$inputer','$jenis_barang','$kerusakan','$status','$tgl_mulai','$waktu_mulai')");

// mengalihkan halaman kembali ke permitaan.php
// header("location:../permintaan.php");

// echo "<script>alert('Permintaan berhasil di buat');window.location='../permintaan.php'</script>";

    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>";
    echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>";
    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js'></script>";

    echo"<script>
      swal({ title: 'Berhasil',
         text: 'Permintaan Berhasil Ditambahkan',
         showConfirmButton: false,
         timer: 1500,
         type: 'success'}).then(okay => {
           if (okay) {
            window.location.href = '../permintaan.php';
          }
        });
      </script>";


    ?>

</body>
</html> 