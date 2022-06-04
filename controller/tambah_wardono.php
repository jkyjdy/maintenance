<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    
</head>

<body>
    <?php
// koneksi database
include '../model/koneksi.php';
$nama=$_POST['nama'];
$tgl = date ('Y-m-d');
$ruangan=$_POST['ruangan'];
$pemakaian_o2_1_m3=$_POST['pemakaian_o2_1_m3'];
$pemakaian_o2_6_m3=$_POST['pemakaian_o2_6_m3'];
$pemakaian_liquid_o2=$_POST['pemakaian_liquid_o2'];
$pemakaian_n20=$_POST['pemakaian_n20'];
$maintenance_bed=$_POST['maintenance_bed'];
$maintenance_kursi_roda=$_POST['maintenance_kursi_roda'];
$maintenance_flowmeter_tabung=$_POST['maintenance_flowmeter_tabung'];
$maintenance_flowmeter_dinding=$_POST['maintenance_flowmeter_dinding'];
$maintenance_troli_medis=$_POST['maintenance_troli_medis'];
$perbaikan =$_POST['perbaikan'];
$lain=$_POST['lain'];

mysqli_query($koneksi, "insert into laporan (ID,nama,tgl,ruangan,pemakaian_o2_1_m3,pemakaian_o2_6_m3,pemakaian_liquid_o2,pemakaian_n20,maintenance_bed,maintenance_kursi_roda,maintenance_flowmeter_tabung,maintenance_flowmeter_dinding,maintenance_troli_medis,perbaikan,lain) values ('','$nama','$tgl','$ruangan','$pemakaian_o2_1_m3','$pemakaian_o2_6_m3','$pemakaian_liquid_o2','$pemakaian_n20','$maintenance_bed','$maintenance_kursi_roda','$maintenance_flowmeter_tabung','$maintenance_flowmeter_dinding','$maintenance_troli_medis','$perbaikan','$lain')");

echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>";
    echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>";
    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js'></script>";

    echo"<script>
      swal({ title: 'Berhasil',
         text: 'Laporan Berhasil Ditambahkan',
         showConfirmButton: false,
         timer: 1500,
         type: 'success'}).then(okay => {
           if (okay) {
            window.location.href = '../laporan_wardono.php';
          }
        });
      </script>";


    ?>
</body>
</html>