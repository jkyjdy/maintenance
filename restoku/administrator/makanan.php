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
        <div class="tabel">
            <h2>
                List Menu Resto
            </h2>
                <table style="width:95%" border="1">
                    <tr align="left">
                        <th>No</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th align="center">Gambar</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                        include('../model/koneksi.php');

                        $query = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id DESC");
                        $no = 1;
                        while($data = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td><?php echo $data['kategori'] ?></td>
                        <td>Rp.<?php echo $data['harga'] ?>,-</td>
                        <td align="center"><img src="../img/menu/<?php echo $data['gambar'] ?>" width="50" height="50"></td>
                        <td><a href="../controller/hapusmakanan.php?id=<?php echo $data['id'] ?>">Hapus</a></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
        </div>
        <div class="form">
            <form action="../controller/tambahmenu.php" method="post" enctype="multipart/form-data">
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
                <!-- kode javascript untuk menampilkan gambar sebelum di upload  -->
                <script type="text/javascript">
                    var loadFile = function(event) {
                        var output = document.getElementById('output');
                        output.src = URL.createObjectURL(event.target.files[0]);
                    };
                </script>
                <button type="submit">Simpan Data</button>
            </form>
        </div>
        
    </div>
</body>
</html>