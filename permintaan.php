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
						<h4 class="page-title">Data Permintaan</h4>
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
								<a href="#">Permintaan</a>
							</li>
						</ul>
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
										<form action="controller/tambah1.php"method="POST">
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
													<p class="small">Silahkan Isi Data Permintaan</p>
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
																	<label>Direktorat</label>
																	<select class="form-control" name="direktorat" placeholder = "--Pilih--" required>
                                                                        <option value="">--Pilih--</option>
                                                                        <option value ="Direktorat Medis">Direktorat Medis</option>
                                                                        <option value="Direktorat Keperwatan">Direktorat Keperwatan</option>
                                                                        <option value="Direktorat Umum">Direktorat Umum</option>
                                                                        <option value="Direktorat Keuangan">Direktorat Keuangan</option>
                                                                        <option value="Direktorat SDM">Direktorat SDM</option>
                                                                        <option value="Direktorat Eksekutif">Direktorat Eksekutif</option>
                                                                        <option value="Komite">Komite</option>
                                                                        <option value=" Lain"> Lain </option>
                                                                    </select>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
																	<label>Unit</label>
                                                                    <input type = "text" class = "form-control" name = "unit" placeholder = "Diisi" required>
                                                                    <!-- <div id ="unitlist"> </div>  -->
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group form-group-default">
																	<label>Jenis Barang</label>
																	<input type = "text" class = "form-control" name = "jenis_barang"  placeholder ="Isikan" required>
                                                                    
																</div>
                                                            </div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                    <label>Merk</label>
																	<input type = "text" class = "form-control" name = "merk" placeholder = "Merk" required>
																</div>
															</div>
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
																	<label>Inputer</label>
																	<input type = "text" class = "form-control" name = "inputer" placeholder ="Inputer" required>
																</div>
                                                            </div>
                                                           
                                                            <div class="col-md-6">
																<div class="form-group form-group-default">
                                                                    <label>Kerusakan</label>
																	<textarea class = "form-control" name = "kerusakan" placeholder ="Tolong Diisi" required></textarea>
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
													<!-- <th>No_Inventaris</th> -->
													<th>Direktorat</th>
													<th>Unit</th>
													<th>Merk</th>
													<th>Inputer</th>
													<th>Jenis Barang</th>
													<th>Kerusakan</th>
													<th>Tanggal Permintaan</th>
													<th>Waktu Permintaan</th>
													<th>Status</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
											<?php

											include 'model/koneksi.php';
											$no=0;
											$data =mysqli_query($koneksi, "select * from permintaan where status = '1' order by ID desc");
											while($isi =mysqli_fetch_array($data)) {
												$no++;
												?>

												<tr>
													<td><?php echo $no ?></td>
													<!-- <td><?php echo $isi['no_inventaris']; ?></td> -->
													<td><?php echo $isi['direktorat'] ?></td>
													<td><?php echo $isi['unit']; ?></td>
													<td><?php echo $isi['merk'];?></td>
													<td><?php echo $isi['inputer'];?></td>
													<td><?php echo $isi['jenis_barang'];?></td>
													<td><?php echo $isi['kerusakan'];?></td>
													<td><?php echo $isi['tgl_mulai'];?></td>
													<td><?php echo substr($isi['waktu_mulai'],0,5) ;?></td>
													<td>
														<?php 
															if ($isi['status']== 1){
																echo "<font color= red > belum dikerjakan </font>";
															
															} else if($isi['status']== 0) {
																echo "status belum ada";

															}else if($isi['status']==2){
																echo "<font color= orange >status dikerjakan</font>";
															}

														?>
												
													</td>
													
													<td>
														`<div class="form-button-action">
															<button type="button" data-toggle="modal" title="" class="btn btn-link btn-primary btn-lg" data-target="#edit_permintaan <?php echo $isi['ID'] ?>" >
																<i class="fa fa-edit"></i>
															</button>

															<!-- Modal untuk menampilkan edit -->
															<div class="modal fade" id="edit_permintaan <?php echo $isi['ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<form action="controller/update_permintaan.php" method = "POST">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body">
																<form action="controller/update_permintaan.php" method = "POST">
																	<!-- membuat di dalam form  -->
																	<div class="row">
																																		
																		<!-- <div class="col-sm-6">
																			<div class="form-group form-group-default">
																				<label>No Inventaris</label>
																				<input type="hidden" class="form-control" name="ID" value="<?php echo $isi['ID']; ?>">
                                        
																				<input type="text" class="form-control" placeholder="Nomor Harus Sesuai" name ="no_inventaris" required>
																			</div>
																		</div> -->
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Direktorat</label>
																				<select class="form-control" name="direktorat" placeholder = "--Pilih--" required>
																					<option value="">--Pilih--</option>
																					<option value ="Direktorat Medis">Direktorat Medis</option>
																					<option value="Direktorat Keperwatan">Direktorat Keperwatan</option>
																					<option value="Direktorat Umum">Direktorat Umum</option>
																					<option value="Direktorat Keuangan">Direktorat Keuangan</option>
																					<option value="Direktorat SDM">Direktorat SDM</option>
																					<option value="Direktorat Eksekutif">Direktorat Eksekutif</option>
																					<option value="Komite">Komite</option>
																					<option value=" Lain"> Lain </option>
																				</select>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Unit</label>
																				<input type = "text" class = "form-control" name = "unit" value="<?php echo $isi['unit']; ?>" placeholder = "Diisi" required>
																				<!-- <div id ="unitlist"> </div>  -->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Merk</label>
																				<input type = "text" class = "form-control" name = "merk" value="<?php echo $isi['merk']; ?>" placeholder = "Merk" required>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Inputer</label>
																				<input type = "text" class = "form-control" value="<?php echo $isi['inputer']; ?>" name = "inputer" placeholder ="Inputer" required>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group form-group-default">
																				<label>Jenis Barang</label>
																				<input type = "text" class = "form-control" name = "jenis_barang" id = "jenis_barang" value="<?php echo $isi['jenis_barang']; ?>" placeholder ="Isikan" required>
																				<div id = "jenis_baranglist"> </div>
																			</div>
																		</div>
																		<div class="col-sm-12">
																			<div class="form-group form-group-default">
																				<label>Kerusakan</label>
																				<textarea class = "form-control" name = "kerusakan" placeholder ="Tolong Diisi" required><?php echo $isi['kerusakan']; ?></textarea>
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

															<a href="controller/hapus_permintaan.php?ID=<?php echo $isi['ID'];  ?>" class="btn btn-link btn-danger" data-original-title="Remove">
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