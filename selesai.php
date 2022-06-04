<!DOCTYPE html>
<html lang="en">
<head>

<?php
include('halaman/asset_atas.php');
?>
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<?php
				include('halaman/logo.php');
			?>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<?php
				include('halaman/header.php');
			?>
			<!-- End Navbar -->
		</div>
		<!-- Sidebar -->
		<?php
			include('halaman/sidebar.php');
		?>
		<div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Permintaan Selesai</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Permintaan</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Selesai</a>
							</li>
						</ul>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="card">
								
								<div class="card-body">
									<!-- Modal -->
									
									<div class="table-responsive">
										<table id="add-row" class="display table table-striped table-hover" >
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
													<th>Tanggal Mulai</th>
													<th>Waktu Mulai</th>
													<th>Tanggal Selesai</th>
													<th>Waktu Selesai</th>
													<th>Foto_Before</th>
													<th>Foto_After</th>
													<th>Teknisi</th>
													<th>Partner 01</th>
													<th>Partner 02</th>
													<th>Partner 03</th>
													<th>Partner 04</th>
													
													
												</tr>
											</thead>
											<tbody>
											<?php

											include 'model/koneksi.php';
											$no=0;
											$data =mysqli_query($koneksi, "select * from permintaan where status = '3' order by ID desc");
											while($isi =mysqli_fetch_array($data)) {
												$no++;
												?>

												<tr>
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
													<td><?php echo $isi['tgl_selesai'];?></td>
													<td><?php echo substr($isi['waktu_selesai'],0,5) ;?></td>
													<td><a data-target="#modal_before <?php echo $isi['ID'];?>" data-toggle="modal"><img src = 'images/<?php echo $isi['foto_before'];?>' width = "100%"></a>
													<!-- =============================================================================================== -->

													<!--  modal gambar before -->
													

													<!-- Modal -->
													<div class="modal fade" id="modal_before <?php echo $isi['ID'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel">Foto Before</h5>
															<!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> -->
														</div>
														<div class="modal-body">
															<img src = 'images/<?php echo $isi['foto_before'];?>' width = "100%">
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
														</div>
														</div>
													</div>
													</div>

													<!-- =============================================================================================== -->
													

													</td>

													<td><a data-target="#modal_after <?php echo $isi['ID'];?>" data-toggle="modal"><img src = 'images/<?php echo $isi['foto_after'];?>' width = "100%"></a>
													<!-- =============================================================================================== -->

													<!--  modal gambar before -->
													

													<!-- Modal -->
													<div class="modal fade" id="modal_after <?php echo $isi['ID'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel">Foto After</h5>
															<!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> -->
														</div>
														<div class="modal-body">
															<img src = 'images/<?php echo $isi['foto_after'];?>' width = "100%">
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
														</div>
														</div>
													</div>
													</div>

													<!-- =============================================================================================== -->
													<td><?php echo $isi['teknisi'];?></td>
													<td><?php echo $isi['partner']; ?></td>
													<td><?php echo $isi['partner2']; ?></td>
													<td><?php echo $isi['partner3']; ?></td>
													<td><?php echo $isi['partner4']; ?></td>

													</td>
														
													<?php	
														}
													?>
												</tr>	
											</tbody>	
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer class="footer">
				<?php
					include('halaman/footer.php');
				?>
			</footer>
		</div>
		
					<!-- <div class="switch-block">
						<h4>Sidebar</h4>
						<div class="btnSwitch">
							<button type="button" class="selected changeSideBarColor" data-color="white"></button>
							<button type="button" class="changeSideBarColor" data-color="dark"></button>
							<button type="button" class="changeSideBarColor" data-color="dark2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Background</h4>
						<div class="btnSwitch">
							<button type="button" class="changeBackgroundColor" data-color="bg2"></button>
							<button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
							<button type="button" class="changeBackgroundColor" data-color="bg3"></button>
							<button type="button" class="changeBackgroundColor" data-color="dark"></button>
						</div>
					</div>
				</div>
			</div>
			<div class="custom-toggle">
				<i class="flaticon-settings"></i>
			</div> -->
		</div>
		<!-- End Custom template -->
	</div>
	<!--   Core JS Files   -->
	<?php
		include('halaman/asset_bawah2.php');
	?>
</body>
</html>
<script>
    $(document).ready(function(){  
      $('#jenis_barang').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#jenis_baranglist').fadeIn();  
                          $('#jenis_baranglist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#jenis_barang').val($(this).text());  
           $('#jenis_baranglist').fadeOut();  
      });  
 });  
</script>

<!-- <script>
    $(document).ready(function(){  
      $('#unit').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search2.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#unitlist').fadeIn();  
                          $('#unitlist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#unit').val($(this).text());  
           $('#unitlist').fadeOut();  
      });  
 });  
</script> -->