<?php

//function starat

session_start();

//cek apakah session terdaftar



    //session terdafar, saatnya logout/keluar

    // session_unset();
    session_destroy();

    //variabel session salah, user tidak seharusnya ada di halaman ini. kembali ke login 

    header("location:login.php");

?>