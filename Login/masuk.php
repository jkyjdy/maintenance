<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>

<body>
<?php
//koneksi ke database
include '../model/koneksi.php';

// menangkap data yang dikirim dari form

$username = $_POST['username'];
$password = $_POST['password'];

// mengambil data dari database berdasarkan post//
$data=mysqli_query($koneksi, "select * from add_karyawan where username='$username' and password='$password'");
$isi=mysqli_fetch_array($data);

if($username==$isi['username'] AND $password==$isi['password']){
    //area ketika login berhasil
    session_start();

    $_SESSION['role']=$isi['role'];
    $role=$isi['role'];

    $_SESSION['username']=$isi['username'];
    $username=$isi['username'];

    $_SESSION['nama']=$isi['nama'];
    $nama=$isi['nama'];
    
    $_SESSION['status']="login";
    //  echo"<script>alert('Login Berhasil Sebagai $role');window.location='../index.php'</script>";
    // echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    // echo "swal('Good job!', 'You clicked the button!', 'success')";

    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>";
    echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>";
    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js'></script>";

    echo"<script>
      swal({ title: 'Hai, $nama',
         text: 'Selamat Anda Telah Login',
         showConfirmButton: false,
         timer: 3000,
         type: 'success'}).then(okay => {
           if (okay) {
            window.location.href = '../index.php';
          }
        });
      </script>";
    


    // -------------------------------------------------------------------?
}else{
    // echo"<script>alert('Login gagal');window.location='../login.php'</script>";

   echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js'></script>";


     echo"<script>
       swal({ title: 'Oops',
          text: 'Anda Gagal Login',
          showConfirmButton: false,
          timer: 3000,
          type: 'error'}).then(wrong => {
             if (wrong) {
              window.location.href = 'login.php';
            }
         });
       </script>";

}


?>
</body>
</html>

