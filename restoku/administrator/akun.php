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
                Manajemen Akun
            </h2>
                <table style="width:95%" border="1">
                    <tr align="left">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                        include('../model/koneksi.php');

                        $query = mysqli_query($koneksi, "SELECT * FROM akun ORDER BY id DESC");
                        $no = 1;
                        while($data = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td><?php echo $data['username'] ?></td>
                        <td><?php echo $data['password'] ?></td>
                        <td><?php echo $data['role'] ?></td>
                        <td><a href="../controller/hapusakun.php?id=<?php echo $data['id'] ?>">Hapus</a></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
        </div>
        <div class="form">
            <form action="../controller/tambahakun.php" method="post" enctype="multipart/form-data">
                <b>Tambah Data</b><hr>
                Nama :<br>
                <input type="text" name="nama" placeholder="Masukkan nama" id="">
                <br><br>
                Username :<br>
                <input type="text" name="username" placeholder="masukkan username" id="">
                 
                <br><br>
                Password :<br>
                <input type="password" name="password" placeholder="Masukkan password yang aman" id="">
                <br><br>
                Role :<br>
                <input type="radio" name="role" value="Admin">Admin <br>
                <input type="radio" name="role" value="Manager">Manager<br>
                <input type="radio" name="role" value="Kasir">Kasir
                <hr>
                <button type="submit">Simpan Data</button>
            </form>
        </div>
        
    </div>
</body>
</html>