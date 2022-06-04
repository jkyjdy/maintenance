<?php 
 date_default_timezone_set('Asia/jakarta');
$koneksi = mysqli_connect("localhost","u7718053_restoku","u7718053_restoku","u7718053_restoku");
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
?>