<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					
					<ul class="nav nav-primary">
						<li class="nav-item active">
							<a  href="index.php" class="collapsed" aria-expanded="false">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>

								<!-- <span class="caret"></span> simbol dropdown  -->
							</a>
							
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Components</h4>
						</li>
						<li class="nav-item">
							<?php
								if (($_SESSION['role']== 'inputer') or ($_SESSION['role']=='administrator') or ($_SESSION['role']=='direktorat medis') or ($_SESSION['role']=='direktorat keperawatan') or ($_SESSION['role']=='direktorat umum') or ($_SESSION['role']=='direktorat keuangan') 
								or ($_SESSION['role']=='direktorat sdm') or ($_SESSION['role']=='Direktorat eksekutif') or ($_SESSION['role']=='komite-komite') or ($_SESSION['role']=='lain')){

								
							?>
							<a data-toggle="collapse" href="#base">
								<i class="fas fa-layer-group"></i>
								<p>Permintaan</p>
								<span class="caret"></span>
							</a>
							<?php
							}
							?>
							<div class="collapse" id="base">
								<ul class="nav nav-collapse">
									<li>
										<a href="permintaan.php">
											<span class="sub-item">Permintaan</span>
										</a>
									</li>
									<li>
										<a href="proses_permintaan.php">
											<span class="sub-item">Proses</span>
										</a>
									</li>
									<li>
										<a href="selesai.php">
											<span class="sub-item">Selesai</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">

							<?php 
								if ($_SESSION['role']=='teknisi' or $_SESSION['role']=='administrator'){
									
								
							?>

							<a data-toggle="collapse" href="#sidebarLayouts"> 

								<i class="fas fa-th-list"></i>

								<p>Maintenance</p>
								<span class="caret"></span> 

							</a>
							<?php
								}
							?>

							<div class="collapse" id="sidebarLayouts">
								<ul class="nav nav-collapse">
									<li>
										<a href="list_permintaan.php">
											<span class="sub-item">List Permintaan</span>
										</a>
									</li>
									<li>
										<a href="sedang_dikerjakan.php">
											<span class="sub-item">Sedang Di Kerjakan</span>
										</a>
									</li>
									<li>
										<a href="laporan_selesai.php?awal=&akhir=">
											<span class="sub-item">Laporan Selesai</span>
										</a>
									</li>
									<!-- <li>
										<a href="static-sidebar.html">
											<span class="sub-item">Static Sidebar</span>
										</a>
									</li>
									<li>
										<a href="icon-menu.html">
											<span class="sub-item">Icon Menu</span>
										</a>
									</li> -->
								</ul>
							</div>
						</li>
						
						<li class="nav-item">
							<?php 
								if ($_SESSION['role']=='teknisi' or $_SESSION['role']=='administrator'){
										
									
							?>
							<a data-toggle="collapse" href="#forms">
								<i class="fas fa-pen-square"></i> 
								<p>Forms</p>
								<span class="caret"></span>
							</a>
							
							<?php
								}
							?>
							<div class="collapse" id="forms">
								<ul class="nav nav-collapse">
									<?php
									if ($_SESSION['nama']=='Hendra' or $_SESSION['role']=='administrator'){
									?>
									<li>
										<a href="laporan_hendra.php">
											<span class="sub-item">Laporan Hendra</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Felix' or $_SESSION['role']=='administrator'){
									?>

										<a href="laporan_felix.php">
											<span class="sub-item">Laporan Felix</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Dody' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_dody.php">
											<span class="sub-item">Laporan Dody</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Marbi' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_marby.php">
											<span class="sub-item">Laporan Marby</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Sunday' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_sunday.php">
											<span class="sub-item">Laporan Sunday</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Wardono' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_wardono.php">
											<span class="sub-item">Laporan Wardono</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Riyanto' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_riyanto.php">
											<span class="sub-item">Laporan Riyanto</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Fendy' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_fendy.php">
											<span class="sub-item">Laporan Fendy</span>
										</a>
									</li>
									<?php
									}
									?>
									<li>
									<?php
									if ($_SESSION['nama']=='Herman' or $_SESSION['role']=='administrator'){
									?>
										<a href="laporan_herman.php">
											<span class="sub-item">Laporan Herman</span>
										</a>
									</li>
									<?php
									}
									?>
								</ul>
							</div>
						</li> 
						<!-- <li class="nav-item">
							<a data-toggle="collapse" href="#tables">
								<i class="fas fa-table"></i>
								<p>Tables</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="tables">
								<ul class="nav nav-collapse">
									<li>
										<a href="tables/tables.html">
											<span class="sub-item">Basic Table</span>
										</a>
									</li>
									<li>
										<a href="tables/datatables.html">
											<span class="sub-item">Datatables</span>
										</a>
									</li>
								</ul>
							</div>
						</li> -->
						<!-- <li class="nav-item">
							<a data-toggle="collapse" href="#maps">
								<i class="fas fa-map-marker-alt"></i>
								<p>Maps</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="maps">
								<ul class="nav nav-collapse">
									<li>
										<a href="maps/jqvmap.html">
											<span class="sub-item">JQVMap</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#charts">
								<i class="far fa-chart-bar"></i>
								<p>Charts</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="charts">
								<ul class="nav nav-collapse">
									<li>
										<a href="charts/charts.html">
											<span class="sub-item">Chart Js</span>
										</a>
									</li>
									<li>
										<a href="charts/sparkline.html">
											<span class="sub-item">Sparkline</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a href="widgets.html">
								<i class="fas fa-desktop"></i>
								<p>Widgets</p>
								<span class="badge badge-success">4</span>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#submenu">
								<i class="fas fa-bars"></i>
								<p>Menu Levels</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="submenu">
								<ul class="nav nav-collapse">
									<li>
										<a data-toggle="collapse" href="#subnav1">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav1">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a data-toggle="collapse" href="#subnav2">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav2">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a href="#">
											<span class="sub-item">Level 1</span>
										</a>
									</li>
								</ul>
							</div>
						</li> -->
						<!-- <li class="mx-4 mt-2">
							<a href="http://themekita.com/atlantis-bootstrap-dashboard.html" class="btn btn-primary btn-block"><span class="btn-label mr-2"> <i class="fa fa-heart"></i> </span>Buy Pro</a> 
						</li> --> 
					</ul>
				</div>
			</div>
		</div>