<html>
    <head>
        <title>Nota</title>
        <style type="text/css">
        /* Kode CSS Untuk PAGE NOTA dengan ukuran kertas A4 */
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font: 12pt "Tahoma";
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            .page {
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                margin: 10mm auto;
                border: 1px #D3D3D3 solid;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            }
            .subpage {
                padding: 1cm;
                border: 5px red solid;
                text-align:center;
                height: 257mm;
                outline: 2cm #FFEAEA solid;
            }
            
            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 297mm;        
                }
                .page {
                    margin: 0;
                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <!--kode javascript untuk mencetak ketka halaman dibuka-->
    <script>
		window.print();
	</script>
	<!--ambil data nota dari database-->
	<?php
        include('model/koneksi.php');
        // ambil id dari link
        $id = $_GET['id'];
        $query = mysqli_query($koneksi, "SELECT * FROM pesanan where id = $id");
        $data = mysqli_fetch_array($query);
    ?>
        <div class="book">
            <div class="page">
                <div class="subpage">
                    <h1><b>RESTOKU</b></h1>
                    <font size="-1">Special For<br><b><?php echo $data['nama'] ?></b></font><br><br>
                    <font size="-1">No Antrian</font>
                    <br>
                    <font style="font-size:150px"><?php echo $data['id'] ?></font>
                    <hr>
                    <p align="left">
                        <b>Pesanan : </b> <br>
                        <?php echo $data['pesanan'] ?><br><br>
                        <b>Harga :</b><br>
                        Rp.<?php echo $data['harga'] ?>,-<br><br>
                        <b>Diskon : (*min pembelian 50k diskon 10k)</b><br>
                        Rp.<?php echo $data['diskon'] ?>,-<br><br>
                        <b>Total Bayar :</b><br>
                        Rp.<?php echo $data['totalbayar'] ?>,-<br><hr>
                        Terimakasih telah mampir ke restoku, semoga kaka suka dengan pelayanan kami :)
                    </p>
                    <p align="right">
                        <b>Kasir</b><br>
                        <?php echo $data['kasir'] ?>
                    </p>
                </div>    
            </div>
        
        </div>
    </body>
</html>