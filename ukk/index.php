<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skanser Resto</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
        <!-- -----------------------------------------------------------------  -->
        <!-- bagian section header dan sidebar dipisah agar jika ada perubahan
        lebih mudah.

        misalkan mau ubah url makanan, tinggal ubah file sidebar di
        folder halaman, tidak perlu merubah di semua halamannya. -->
        <!-- -----------------------------------------------------------------  -->
        <?php 
            include('halaman/header.php');
        ?>
    </header>
    <div class="sidebar">
        <?php
            include('halaman/sidebar.php');
        ?>
    </div>
    <div class="content">
        <h1>
            SELAMAT DATANG DI HALAMAN ADMINISTRATOR
        </h1>
        <P>Halaman ini digunakan untuk mengelola seluruh administrasi restoran mulai menambahkan karyawan, makanan dan manajemen pdata penjualan</P>
    </div>
</body>
</html>