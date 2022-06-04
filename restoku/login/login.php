<?php 
include '../model/koneksi.php';


$username = $_POST['username'];
$password = $_POST['password'];
 
$query = mysqli_query($koneksi, "select * from akun where username='$username' and password='$password'");
$data = mysqli_fetch_array($query);

if (($data['username'] == $username) && ($data['password'] == $password) && $data['role'] == 'Manager'){
    // jika login sebagai role manager
    session_start(); 
    $_SESSION['username'] = $username;
    $_SESSION['name'] = $data['nama'];
    $_SESSION['role'] = $data['role'];
    
    echo"<script>alert('Your Logged as Manager');  window.location='../administrator'</script>";
}else 
if (($data['username'] == $username) && ($data['password'] == $password) && $data['role'] == 'Admin'){
    // jika login sebagai role admin 
    session_start(); 
    $_SESSION['username'] = $username; 
    $_SESSION['name'] = $data['nama'];
    $_SESSION['role'] = $data['role'];
    echo"<script>alert('Your Logged as Admin');  window.location='../administrator'</script>";
}else 
if (($data['username'] == $username) && ($data['password'] == $password) && $data['role'] == 'Kasir'){
    // jika login sebagai role kasir 
    session_start(); 
    $_SESSION['username'] = $username; 
    $_SESSION['name'] = $data['nama'];
    $_SESSION['role'] = $data['role'];
    echo"<script>alert('Your Logged as Kasir');  window.location='../'</script>";
}else{
    echo"<script>alert('Periksa kata sandi anda');  window.location='index.php'</script>";
}

?>