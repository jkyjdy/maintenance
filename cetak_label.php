<!DOCTYPE html>
<html>
<head>
	<title>RSBM</title>
	
</head>
<body>
    <center>
        <h2>DATA LAPORAN MAINTENANCE</h2>
		<h4>BRAYAT MINULYA</h4>
        <?php
            $awal=$_POST['TanggalAwal'];
            $akhir=$_POST['TanggalAkhir'];
            
            
            
        ?>
        TanggalAwal:<?php echo $awal;?><br>
        TanggalAkhir:<?php echo $akhir;?>
 
    </center>
	<br>
	<table border="1"  style="width:100%">
		<thead>
			<tr>
                <th>No </th>
				<!-- <th>No Inventaris</th> -->
				<th>Direktorat</th>
				<th>Unit</th>
				<th>Merk</th>
				<th>Inputer</th>
				<th>Jenis Barang</th>
				<th>Kerusakan</th>
				<th>Tanggal Permintaan</th>
				<th>Waktu Permintaan</th>
				<th>Tanggal Pengerjaan</th>
				<th>Waktu Pengerjaan</th>
				<th>Tanggal Selesai</th>
				<th>Waktu Selesai</th>
                            
			</tr>
		</thead>
		
        <tbody>
        <?php
        include 'model/koneksi.php';
        $no=0;
        $data=mysqli_query($koneksi,"select * from permintaan where tgl_mulai between '$awal' and '$akhir' "); 
        while($isi=mysqli_fetch_array($data)){
            $no++;
            ?>
            <tr align="center">
                <td><?php echo $no ?></td>
				<!-- <td><?php echo $isi['no_inventaris']; ?></td> -->
				<td><?php echo $isi['direktorat']; ?></td>
				<td><?php echo $isi['unit']; ?></td>
				<td><?php echo $isi['merk'];?></td>
				<td><?php echo $isi['inputer'];?></td>
				<td><?php echo $isi['jenis_barang'];?></td>
				<td><?php echo $isi['kerusakan'];?></td>
			    <td><?php echo $isi['tgl_mulai'];?></td>
				<td><?php echo substr($isi['waktu_mulai'],0,5) ;?></td>
				<td><?php echo $isi['tgl_pengerjaan'];?></td>
				<td><?php echo substr ($isi['waktu_pengerjaan'],0,5);?></td>
				<td><?php echo $isi['tgl_selesai'];?></td>
				<td><?php echo substr ($isi['waktu_selesai'],0,5);?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
	</table>
    <script>
		window.print();
	</script>
</body>
</html>