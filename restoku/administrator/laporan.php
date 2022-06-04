<!DOCTYPE html>
<?php
    session_start();
    if (!isset($_SESSION['username'])){
        header("Location: ../login");
    }elseif($_SESSION['role'] != ('Manager' || 'Admin')){
        header("Location: ../login");
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List menu pesanan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
        <div class="tabel" style="width:100%">
            <h2>
                Laporan Penjualan Restoku
            </h2>
                <table style="width:95%" border="1">
                    <tr align="left">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Pesanan</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                    <?php
                        include('../model/koneksi.php');

                        $query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");
                        $no = 1;
                        while($data = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td><?php echo $data['pesanan'] ?></td>
                        <td>Rp.<?php echo $data['harga'] ?>,-</td>
                        <td align="center">Rp.<?php echo $data['diskon'] ?>,-</td>
                        <td>Rp.<?php echo $data['totalbayar'] ?>,-</td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
        </div>
    </div>
</body>
</html>