<!DOCTYPE html>
<html>
<head>
 <title>Mari Belajar Coding</title>
 <?php
 include '../model/koneksi.php';
 ?>
</head>
<body>

 <table>
                
  <form method="post" enctype="multipart/form-data" >
   <tr>
    <td>Pilih File</td>
    <td><input name="filemhsw" type="file" required="required"></td>
   </tr>
   <tr>
    <td></td>
    <td><input name="upload" type="submit" value="Import"></td>
   </tr>
  </form>
 </table>
 <?php
 if (isset($_POST['upload'])) {

  require('lib/php-excel-reader/excel_reader2.php');
  require('lib/SpreadsheetReader.php');

  //upload data excel kedalam folder uploads
  $target_dir = "uploads/".basename($_FILES['filemhsw']['name']);
  
  move_uploaded_file($_FILES['filemhsw']['tmp_name'],$target_dir);

  $Reader = new SpreadsheetReader($target_dir);

  foreach ($Reader as $Key => $Row)
  {
   // import data excel mulai baris ke-2 (karena ada header pada baris 1)
   if ($Key < 1) continue;   
   $query=mysqli_query($koneksi,"INSERT INTO import_excel(nim,nama,alamat,jurusan) VALUES ('".$Row[0]."', '".$Row[1]."','".$Row[2]."','".$Row[3]."')");
  }
  if ($query) {
    echo "Import data berhasil";
   }else{
    echo mysql_error();
   }
 }
 ?>
 <h2>Data Mahasiswa</h2>
 <table border="1">
  <tr>
   <th>No</th>
   <th>NIM</th>
   <th>Nama</th>
   <th>Alamat</th>   
   <th>Jurusan</th>
  </tr>
  <?php   
  $no=1;
  $data = mysqli_query($koneksi,"select * from import_excel");
  while($d = mysqli_fetch_array($data)){
   ?>
   <tr>
    <td><?=$no++; ?></td>
    <td><?=$d['nim']; ?></td>
    <td><?=$d['nama']; ?></td>
    <td><?=$d['alamat']; ?></td>    
    <td><?=$d['jurusan']; ?></td>
   </tr>
   <?php 
  }
  ?>
 </table>
</body>
</html>