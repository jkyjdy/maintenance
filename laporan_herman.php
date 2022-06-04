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
						<h4 class="page-title">Data Laporan Harian</h4>
						<!-- <ul class="breadcrumbs">
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
								<a href="#">Permintaan</a>
							</li>
						</ul> -->
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title"></h4>
										<button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Tambah
										</button>
									</div>
								</div>
								<div class="card-body">
									<!-- Modal -->
									<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
										<form action="controller/tambah_herman.php"method="POST">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header no-bd">
													<h5 class="modal-title">
														<span class="fw-mediumbold">
														Tambah</span> 
														<span class="fw-light">
															Data
														</span>
													</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<p class="small">Silahkan Isi Laporan Harian</p>
													<form>
														<div class="row">
															<!-- <div class="col-sm-6">
																<div class="form-group form-group-default">
																	<label>No Inventaris</label>
																	<input type="text" class="form-control" placeholder="Nomor Harus Sesuai" name ="no_inventaris" required>
																</div>
															</div> -->
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Nama</label>
                                                                    <input type = "text" class = "form-control" name = "nama" value = "<?php echo $_SESSION['nama']; ?>" placeholder = "Diisi" readonly >
																</div>
															</div>
                                                            <!-- <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Ruangan</label>
                                                                    <input type = "text" class = "form-control" name = "ruangan" placeholder = "Diisi" >
																</div>
															</div> -->
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Mesin Cuci</label>
                                                                    <input type = "text" class = "form-control" name = "mesin_cuci" placeholder = "Diisi" >
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Mesin Pengering</label>
                                                                    <input type = "text" class = "form-control" name = "mesin_pengering" placeholder = "Diisi" >
																</div>
															</div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Mesin Pemeras</label>
                                                                    <input type = "text" class = "form-control" name = "mesin_pemeras" placeholder = "Diisi" >
																</div>
															</div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Petunjuk Arah</label>
                                                                    <input type = "text" class = "form-control" name = "petunjuk_arah" placeholder = "Diisi" >
																</div>
                                                            </div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Setrika Uap</label>
                                                                    <input type = "text" class = "form-control" name = "setrika_uap" placeholder = "Diisi" >
                                                                    
																</div>
                                                            </div>
                                                        
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                    <label>Perbaikan</label>
																	<textarea class = "form-control" name = "perbaikan" placeholder ="Tolong Diisi" ></textarea>
																</div>
                                                            </div>
                                                            <div class="col-md-12">
																<div class="form-group form-group-default">
                                                                    <label>Lain-Lain</label>
																	<textarea class = "form-control" name = "lain" placeholder ="Tolong Diisi" ></textarea>
																</div>
                                                            </div>
														</div>
													</form>
												</div>
												<div class="modal-footer no-bd">
													<button type="submit"  class="btn btn-primary">Simpan</button>
													<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
										</form>
									</div>

									<div class="table-responsive">
										<table id="add-row" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No </th>
													<th>Nama</th>
													<th>Tanggal</th>
                                                    <!-- <th>Ruangan</th> -->
                                                    <th>Mesin Cuci</th>
													<th>Mesin Pengering</th>
													<th>Mesin Pemeras</th>
													<th>Petunjuk Arah</th>
													<th>Setrika Uap</th>
													<th>Perbaikan</th>
													<th>Lain</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
											<?php

											include 'model/koneksi.php';
											$no=0;
											$data =mysqli_query($koneksi, "select * from laporan where nama = 'herman' ");
											while($isi =mysqli_fetch_array($data)) {
												$no++;
												?>

												<tr>
													<td><?php echo $no ?></td>
													<td><?php echo $isi['nama']; ?></td>
													<td><?php echo $isi['tgl']; ?></td>
                                                   
													<td><?php echo $isi['mesin_cuci']; ?></td>
													<td><?php echo $isi['mesin_pengering'] ?></td>
													<td><?php echo $isi['mesin_pemeras']; ?></td>
													<td><?php echo $isi['petunjuk_arah'];?></td>
                                                    <td><?php echo $isi['setrika_uap'];?></td>
													<td><?php echo $isi['perbaikan'];?></td>
													<td><?php echo $isi['lain'];?></td>	
													<td>
														<div class="form-button-action">
															<button type="button" data-toggle="modal" title="" class="btn btn-link btn-primary btn-lg" data-target="#edit_herman <?php echo $isi['ID'] ?>" >
																<i class="fa fa-edit"></i>
															</button>

															<!-- Modal untuk menampilkan edit -->
															<div class="modal fade" id="edit_herman <?php echo $isi['ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<form action="controller/update_herman.php" method = "POST">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body">
																<form action="controller/update_herman.php" method = "POST">
																	<!-- membuat di dalam form  -->
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Nama</label>
																				<input type="hidden" class="form-control" name="ID" value="<?php echo $isi['ID']; ?>">
																				<input type = "text" class = "form-control" name = "nama" value="<?php echo $isi['nama']; ?>" placeholder = "Diisi" readonly required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
                                                                        </div>
                                                                        
																																		
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Mesin Cuci</label>
																				<input type = "text" class = "form-control" name = "mesin_cuci" value="<?php echo $isi['mesin_cuci']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div> 
																	    </div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Mesin Pengering</label>
																				<input type = "text" class = "form-control" name = "mesin_pengering" value="<?php echo $isi['mesin_pengering']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Mesin Pemeras</label>
																				<input type = "text" class = "form-control" name = "mesin_pemeras" value="<?php echo $isi['mesin_pemeras']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Petunjuk Arah</label>
																				<input type = "text" class = "form-control" name = "petunjuk_arah" value="<?php echo $isi['petunjuk_arah']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Setrika Uap</label>
																				<input type = "text" class = "form-control" name = "setrika_uap" value="<?php echo $isi['setrika_uap']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Perbaikan</label>
																				<input type = "text" class = "form-control" name = "perbaikan" value="<?php echo $isi['perbaikan']; ?>"  placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group form-group-default">
																				<label>Lain</label>
																				<input type = "text" class = "form-control" name = "lain" value="<?php echo $isi['lain']; ?>"  placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																	<button type="submit" class="btn btn-primary">Save changes</button>
																	
																</div>
																</div>
															</div>
															</form>
															</div>

															<a href="controller/hapus_herman.php?ID=<?php echo $isi['ID'];  ?>" class="btn btn-link btn-danger" data-original-title="Remove">
																<i class="fa fa-times"></i>
															</a>
													
														</div>
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