<?php 
 date_default_timezone_set('Asia/jakarta');
$koneksi = mysqli_connect("localhost","u7718053_user_ukk","password_ukk","u7718053_ukk");
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
?>