<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUK</title>
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
        <div class="tabel">
            <h2>
                Data Produk
            </h2>
                <table style="width:95%" border="1">
                    <tr align="left">
                        <th>No</th>
                        <th>Kd Prod</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                        include('../controller/koneksi.php');

                        $query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
                        $no = 1;
                        while($data = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['kode_produk'] ?></td>
                        <td><?php echo $data['nama_produk'] ?></td>
                        <td><?php echo $data['kategori'] ?></td>
                        <td>Rp.<?php echo $data['harga_beli'] ?>,-</td>
                        <td>Rp.<?php echo $data['harga_jual'] ?>,-</td>
                        <td><a href="../controller/hapusmakanan.php?id=<?php echo $data['id'] ?>">Hapus</a></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
                HJGHJ
        </div>
        <div class="form">
            aaaa
            <form action="../controller/tambahmenu.php" method="post">
                <b>Tambah Data</b><hr>
                Nama Menu :<br>
                <input type="text" name="nama" placeholder="Cth : nasi, kopi dll" id="">
                <br><br>
                Kategori :<br>
                <input type="radio" name="kategori" value="Makanan">Makanan <br>
                <input type="radio" name="kategori" value="Minuman">Minuman 
                <br><br>
                Harga :<br>
                <input type="number" name="harga" placeholder="Masukkan Harga" id="">
                <br><br>
                Gambar :<br>
                <input type="file" accept="image/*" onchange="loadFile(event)" name="file" id="">
                <p align="center"><img id="output" src="https://wiraraja.ac.id/images/berita/empty.jpg" width="100" height="100"></p>
                <button type="submit">Simpan Data</button>
            </form>
        </div>
        
    </div>
</body>
</html>