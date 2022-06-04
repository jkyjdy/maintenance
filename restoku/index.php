<!DOCTYPE html>
<?php
    session_start();
    if (!isset($_SESSION['username'])){
        header("Location: login");
    }elseif($_SESSION['role'] != 'Kasir'){
        header("Location: login");
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
<body bgcolor="honeydew">
    <div class="container">
        <form action="controller/buatpesanan.php" name="form2" method="post" target="_blank">
        <div class="row">
            <h1>Form Pemesanan Makanan dan Minuman</h1>
            <p align="right"><a href="login/logout.php">>> Keluar</a></p>
            <hr>
        </div>
        <div class="row">
            <div class="table-makanan">
                <h2><u>Menu Maknanan</u></h2>
                <?php
                    include('model/koneksi.php');
                    $query = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id DESC");
                    while($data = mysqli_fetch_array($query)){
                ?>
                <div class="menu">
                    <img src="img/menu/<?php echo $data['gambar'] ?>" alt="gambar">
                    <b><?php echo $data['nama'] ?></b><hr>
                    Rp.<?php echo $data['harga'] ?>,00
                    <table>
                        <tr>
                            <td><input type="checkbox" id="checkbox" name="pesanan[]" value="<?php echo $data['nama'] ?>"></td>
                            <td><input type="number" name="harga<?php echo $data['id'] ?>" onfocus="this.select()" onChange="pemesanan()" value="0" id="number" style="width:100%"></td>
                        </tr>
                    </table>
                    
                    
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="table-pesanan">
                <b>Data Pesanan</b><hr>
                
                    Nama Pemesan :<br>
                    <input type="text" name="nama" id=""><br><br>
                    Nominal Pesanan<br>
                    <input type="text" name="total" readonly id="">
                    <br><br>
                    Diskon (*jika pembelian > 50k)<br>
                    <input type="text" name="Diskon" readonly="readonly"/>
                    <br><br>
                    Total Bayar<br>
                    <input type="text" name="Bayar" readonly="readonly" align="right"/>
                    <hr>
                    <p align="right">
                        You Logged as,<br>
                        <b><?php echo $_SESSION['name'] ?></b>
                    </p>
                    <input type="button" value="BATAL" onClick="resetForm()" />
                    <input type="submit" value="SIMPAN" />
            </div>
        </div>
        </form>
        
    </div>
    <script language="JavaScript" type="text/javascript">
        function pemesanan(){
            var nota = document.form2;
            
            // var number = document.getElementById(number);
            // var kotak = document.getElementById(checkbox);
            
            // if( number > 0 ){
            //     $("#kotak").prop('checked', true);
            // }
            
            <?php
                $query = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id DESC");
                while($data = mysqli_fetch_array($query)){
            ?>
            var hrg<?php echo $data['id'] ?> = <?php echo $data['harga'] ?> * eval(nota.harga<?php echo $data['id'] ?>.value);
            <?php
                }
            ?>
            // bentuk javascript untuk parsing data aslinya
            // var hrg2 = 12000 * eval(nota.harga2.value);
            // var hrg3 = 17000 * eval(nota.harga3.value);
            // var hrg4 = 9000 * eval(nota.harga4.value);
            // kemudian dubah dan diintegrasikan dengan PHP agar bisa terhubung dengan database
            // dibuat perulangan menggunakan while
            
            var totalHarga = 
                        // hrg4+
                        // hrg3+
                        // mencari total harga juga sama, diambil dari database
                        <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id DESC");
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        hrg<?php echo $data['id'] ?>+
                        <?php
                            }
                        ?>
                        0;
            
                if (totalHarga > 50000){
                    // memberikan diskon jika totalHarga lebih dari 50000
                    nota.total.value = totalHarga;
                    nota.Diskon.value = 10000;
                    nota.Bayar.value = totalHarga - eval(nota.Diskon.value);
                     } else {
                        //  jika tidak kurang dari 50000 berarti
                        nota.total.value = totalHarga;
                        nota.Diskon.value = 0;
                        nota.Bayar.value = totalHarga - eval(nota.Diskon.value);
                     }
        }
        function resetForm(){
            document.form2.reset();
        }
    </script>
</body>
</html>