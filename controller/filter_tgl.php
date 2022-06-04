<?php

$tgl_awal =$_POST['TanggalAwal'];
$tgl_akhir =$_POST['TanggalAkhir'];


echo "<script>alert('Menampilkan Data Dari $tgl_awal Sampai $tgl_akhir');window.location='../laporan_selesai.php?awal=$tgl_awal&akhir=$tgl_akhir'</script>"


?>