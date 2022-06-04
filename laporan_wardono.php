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
										<form action="controller/tambah_wardono.php"method="POST">
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
                                                                    <input type = "text" class = "form-control" name = "nama" value = "<?php echo $_SESSION['nama']; ?>" placeholder = "Diisi" readonly>
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
                                                                <label>Pemakaian O2 1 m3</label>
                                                                    <input type = "text" class = "form-control" name = "pemakaian_o2_1_m3" placeholder = "Diisi" >
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Pemakaian O2 6 m3</label>
                                                                    <input type = "text" class = "form-control" name = "pemakaian_o2_6_m3" placeholder = "Diisi" >
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Pemakaian Liquid O2</label>
                                                                    <input type = "text" class = "form-control" name = "pemakaian_liquid_o2" placeholder = "Diisi" >
                                                                    
																</div>
                                                            </div>
                                                        
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Pemakaian N2O</label>
                                                                    <input type = "text" class = "form-control" name = "pemakaian_n20" placeholder = "Diisi" >
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Maintenace Bed</label>
                                                                    <input type = "text" class = "form-control" name = "maintenance_bed" placeholder = "Diisi" >
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Maintenace Kursi Roda</label>
                                                                    <input type = "text" class = "form-control" name = "maintenance_kursi_roda" placeholder = "Diisi" >
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Maintenance Flowmeter Tabung</label>
                                                                    <input type = "text" class = "form-control" name = "maintenance_flowmeter_tabung" placeholder = "Diisi" >
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Maintenance Flowmeter Dinding</label>
                                                                    <input type = "text" class = "form-control" name = "maintenance_flowmeter_dinding" placeholder = "Diisi" >
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                <label>Maintenace Troli Medis</label>
                                                                    <input type = "text" class = "form-control" name = "maintenance_troli_medis" placeholder = "Diisi" >
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
													<th>Pemakaian O2 1 m3</th>
													<th>Pemakaian O2 6 m3</th>
													<th>Pemakaian Liquid O2</th>
                                                    <th>Pemakaian N20</th>
													<th>Maintenace Bed</th>
                                                    <th>Maintenace Kursi Roda</th>
                                                    <th>Maintenance Flowmeter Tabung</th>
                                                    <th>Maintenance Flowmeter Dinding</th>
                                                    <th>Maintenace Troli Medis</th>
													<th>Perbaikan</th>
													<th>Lain</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
											<?php

											include 'model/koneksi.php';
											$no=0;
											$data =mysqli_query($koneksi, "select * from laporan where nama = 'wardono' ");
											while($isi =mysqli_fetch_array($data)) {
												$no++;
												?>

												<tr>
													<td><?php echo $no ?></td>
													<td><?php echo $isi['nama']; ?></td>
													<td><?php echo $isi['tgl']; ?></td>
                                                    
													<td><?php echo $isi['pemakaian_o2_1_m3']; ?></td>
													<td><?php echo $isi['pemakaian_o2_6_m3'] ?></td>
													<td><?php echo $isi['pemakaian_liquid_o2']; ?></td>
													<td><?php echo $isi['pemakaian_n20'];?></td>
                                                    <td><?php echo $isi['maintenance_bed'];?></td>
                                                    <td><?php echo $isi['maintenance_kursi_roda'];?></td>
                                                    <td><?php echo $isi['maintenance_flowmeter_tabung'];?></td>
                                                    <td><?php echo $isi['maintenance_flowmeter_dinding'];?></td>
                                                    <td><?php echo $isi['maintenance_troli_medis'];?></td>
													<td><?php echo $isi['perbaikan'];?></td>
													<td><?php echo $isi['lain'];?></td>	
													<td>
														<div class="form-button-action">
															<button type="button" data-toggle="modal" title="" class="btn btn-link btn-primary btn-lg" data-target="#edit_wardono <?php echo $isi['ID'] ?>" >
																<i class="fa fa-edit"></i>
															</button>

															<!-- Modal untuk menampilkan edit -->
															<div class="modal fade" id="edit_wardono <?php echo $isi['ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<form action="controller/update_wardono.php" method = "POST">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body">
																<form action="controller/update_wardono.php" method = "POST">
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
																				<label>Pemakaian O2 1 m3</label>
																				<input type = "text" class = "form-control" name = "pemakaian_o2_1_m3" value="<?php echo $isi['pemakaian_o2_1_m3']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div> 
																	    </div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Pemakaian O2 6 m3</label>
																				<input type = "text" class = "form-control" name = "pemakaian_o2_6_m3" value="<?php echo $isi['pemakaian_o2_6_m3']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Pemakaian Liquid O2</label>
																				<input type = "text" class = "form-control" name = "pemakaian_liquid_o2" value="<?php echo $isi['pemakaian_liquid_o2']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Pemakaian N20</label>
																				<input type = "text" class = "form-control" name = "pemakaian_n20" value="<?php echo $isi['pemakaian_n20']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Maintenance Bed</label>
																				<input type = "text" class = "form-control" name = "maintenance_bed" value="<?php echo $isi['maintenance_bed']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>	Maintenance Kursi Roda</label>
																				<input type = "text" class = "form-control" name = "maintenance_kursi_roda" value="<?php echo $isi['maintenance_kursi_roda']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Maintenance Flowmeter Tabung</label>
																				<input type = "text" class = "form-control" name = "maintenance_flowmeter_tabung" value="<?php echo $isi['maintenance_flowmeter_tabung']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Maintenance Flowmeter Dinding</label>
																				<input type = "text" class = "form-control" name = "maintenance_flowmeter_dinding" value="<?php echo $isi['maintenance_flowmeter_dinding']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
                                                                        <div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Maintenance Troli Medis</label>
																				<input type = "text" class = "form-control" name = "maintenance_troli_medis" value="<?php echo $isi['maintenance_troli_medis']; ?>" placeholder = "Diisi" required>
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

															<a href="controller/hapus_wardono.php?ID=<?php echo $isi['ID'];  ?>" class="btn btn-link btn-danger" data-original-title="Remove">
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