<?php
session_start();
include '../config/db.php';


if (!isset($_SESSION['admin'])) {
?> <script>
		alert('Maaf ! Anda Belum Login !!');
		window.location = '../user.php';
	</script>
<?php
}
?>


<?php
$id_login = $_SESSION['admin'];

// Ambil id cabang admin yang sedang login
$query_id_cabang = mysqli_query($con, "SELECT id_cabang FROM tb_admin WHERE id_admin = '$id_login'");
$data_id_cabang = mysqli_fetch_assoc($query_id_cabang);
$id_cabang = $data_id_cabang['id_cabang'];

// jumlah user
$jumlahSiswa = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_siswa WHERE id_cabang = '$id_cabang'"));
// jumlah TASK	
$jumlahPelatih = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_pelatih WHERE id_cabang = '$id_cabang'"));
// jumlah kelas
$jumlahKelas = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_kelas WHERE id_kelas"));
//jumlah pendagtaran
$jumlahPendaftar = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_pendaftaran WHERE id_pendaftaran"));


$sql = mysqli_query($con, "SELECT * FROM tb_admin WHERE id_admin = '$id_login'") or die(mysqli_error($con));
$data = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Admin | Aplikasi Presensi</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="../assets/img/icon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Fonts and icons -->
	<script src="../assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Lato:300,400,700,900"]
			},
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['../assets/css/fonts.min.css']
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/atlantis.min.css">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="../assets/css/demo.css">
</head>

<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">

				<a href="index.php" class="logo">
					<!-- <img src="../assets/img/mts.png" alt="navbar brand" class="navbar-brand" width="40"> -->
					<b class="text-white">MARLIN SWIMMING </b>
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

				<div class="container-fluid">
					<!-- 	<div class="collapse" id="search-nav">
						<form class="navbar-left navbar-form nav-search mr-md-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pr-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control">
							</div>
						</form>
					</div> -->
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<!-- <li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li> -->


						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="../assets/img/user/<?= $data['foto'] ?>" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="../assets/img/user/<?= $data['foto'] ?>" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4><?= $data['nama_admin'] ?></h4>
												<p class="text-muted"><?= $data['email'] ?></p>
												<!-- <a href="?page=jadwal" class="btn btn-xs btn-secondary btn-sm">Jadwal Mengajar</a> -->
											</div>
										</div>
									</li>
									<li>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="?page=akun">Akun Saya</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="logout.php">Logout</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="../assets/img/user/<?= $data['foto'] ?>" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<?= $data['nama_admin'] ?>
									<span class="user-level">Admin</span>
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="?page=akun">
											<span class="link-collapse">Akun Saya</span>
										</a>
									</li>

								</ul>
							</div>
						</div>
					</div>
					<ul class="nav nav-primary">
						<li class="nav-item active">
							<a href="index.php" class="collapsed">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Main Utama</h4>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#base">
								<i class="fas fa-folder-open"></i>
								<p>Data Umum</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="base">
								<ul class="nav nav-collapse">
									<li>
										<a href="?page=kelas">
											<span class="sub-item">Kelas & Tarif</span>
										</a>
									</li>
									<li>
										<a href="?page=lokasi">
											<span class="sub-item">Daftar Lokasi</span>
										</a>
									</li>
									<li>
										<a href="?page=pendaftaran">
											<span class="sub-item">Pendaftaran</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a href="?page=pelatih" class="<?php echo ($_GET['page'] == 'pelatih' && empty($_GET['act'])) ? 'active' : ''; ?>">
								<i class="fas fa-user-tie"></i>
								<p>Data Pelatih</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="?page=siswa" class="<?php echo ($_GET['page'] == 'siswa' && empty($_GET['act'])) ? 'active' : ''; ?>">
								<i class="fas fa-swimmer"></i>
								<p>Data Siswa</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="?page=jadwal" class="<?php echo ($_GET['page'] == 'jadwal') ? 'active' : ''; ?>">
								<i class="fas fa-calendar-alt"></i>
								<p>Jadwal Mengajar</p>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#presensi" class="<?php echo ($_GET['act'] == 'presensi_pelatih' || $_GET['act'] == 'presensi_siswa') ? 'active' : ''; ?>">
								<i class="fas fa-clipboard-check"></i>
								<p>Rekap Presensi</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?php echo ($_GET['act'] == 'presensi_pelatih' || $_GET['act'] == 'presensi_siswa') ? 'show' : ''; ?>" id="presensi">
								<ul class="nav nav-collapse">
									<li>
										<a href="?page=presensi&act=presensi_pelatih" class="<?php echo ($_GET['act'] == 'presensi_pelatih') ? 'active' : ''; ?>">
											<span class="sub-item">Presensi Pelatih</span>
										</a>
									</li>
									<li>
										<a href="?page=presensi&act=presensi_siswa" class="<?php echo ($_GET['act'] == 'presensi_siswa') ? 'active' : ''; ?>">
											<span class="sub-item">Presensi Siswa</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#laporanSubmenu">
								<i class="fas fa-file-alt"></i>
								<p>Laporan</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="laporanSubmenu">
								<ul class="nav nav-collapse">
									<li>
										<a href="?page=laporan&act=laporan_gaji">
											<span class="sub-item">Laporan Gaji</span>
										</a>
									</li>
									<li>
										<a href="?page=laporan&act=slip_gaji">
											<span class="sub-item">Slip Gaji</span>
										</a>
									</li>
								</ul>
							</div>
						</li>

						<li class="nav-item active mt-3">
							<a href="#" onclick="confirmLogout()">
								<i class="fas fa-arrow-alt-circle-left"></i>
								<p>Logout</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->


		<div class="main-panel">
			<div class="content">

				<!-- Halaman dinamis -->
				<?php
				$page = @$_GET['page'];
				$act = @$_GET['act'];

				if ($page == 'pelatih') {
					if ($act == '') {
						include 'modul/pelatih/data.php';
					} elseif ($act == 'add') {
						include 'modul/pelatih/add.php';
					} elseif ($act == 'edit') {
						include 'modul/pelatih/edit.php';
					} elseif ($act == 'del') {
						include 'modul/pelatih/del.php';
					} elseif ($act == 'proses') {
						include 'modul/pelatih/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'jadwal') {
					if ($act == '') {
						include 'modul/jadwal/data.php';
					} elseif ($act == 'add') {
						include 'modul/jadwal/add.php';
					} elseif ($act == 'cancel') {
						include 'modul/jadwal/cancel.php';
					} elseif ($act == 'edit') {
						include 'modul/jadwal/edit.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'pendaftaran') {
					if ($act == '') {
						include 'modul/pendaftaran/data.php';
					} elseif ($act == 'add') {
						include 'modul/pendaftaran/add.php';
					} elseif ($act == 'del') {
						include 'modul/pendaftaran/del.php';
					} elseif ($act == 'edit') {
						include 'modul/pendaftaran/edit.php';
					} elseif ($act == 'proses') {
						include 'modul/pendaftaran/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'laporan') {
					if ($act == 'laporan_gaji') {
						include 'modul/laporan/laporan_gaji.php';
					} elseif ($act == 'slip_gaji') {
						include 'modul/laporan/slip_gaji.php';
					} elseif ($act == 'cancel') {
						include 'modul/jadwal/cancel.php';
					} elseif ($act == 'detail_kelas') {
						include 'modul/jadwal/detail_kelas.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'presensi') {
					if ($act == 'presensi_pelatih') {
						include 'modul/presensi/rekap_pelatih/presensi_pelatih.php';
					} elseif ($act == 'detail_mengajar') {
						include 'modul/presensi/rekap_pelatih/detail_mengajar.php';
					} elseif ($act == 'presensi_siswa') {
						include 'modul/presensi/rekap_siswa/presensi_siswa.php';
					} elseif ($act == 'detail_siswa') {
						include 'modul/presensi/rekap_siswa/detail_siswa.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'lokasi') {
					if ($act == '') {
						include 'modul/lokasi/data.php';
					} elseif ($act == 'add') {
						include 'modul/lokasi/add.php';
					} elseif ($act == 'del') {
						include 'modul/lokasi/del.php';
					} elseif ($act == 'edit') {
						include 'modul/lokasi/edit.php';
					} elseif ($act == 'proses') {
						include 'modul/lokasi/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'gaji') {
					if ($act == '') {
						include 'modul/gaji/gaji.php';
						// } elseif ($act == 'add') {
						// 	include 'modul/karyawan/add.php';
						// } elseif ($act == 'edit') {
						// 	include 'modul/karyawan/edit.php';
						// } elseif ($act == 'del') {
						// 	include 'modul/karyawan/del.php';
						// } elseif ($act == 'proses') {
						// 	include 'modul/karyawan/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'kelas') {
					if ($act == '') {
						include 'modul/kelas/data.php';
					} elseif ($act == 'add') {
						include 'modul/kelas/add.php';
					} elseif ($act == 'edit') {
						include 'modul/kelas/edit.php';
					} elseif ($act == 'del') {
						include 'modul/kelas/del.php';
					} elseif ($act == 'proses') {
						include 'modul/kelas/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'siswa') {
					if ($act == '') {
						include 'modul/siswa/data.php';
					} elseif ($act == 'add') {
						include 'modul/siswa/add.php';
					} elseif ($act == 'edit') {
						include 'modul/siswa/edit.php';
					} elseif ($act == 'del') {
						include 'modul/siswa/del.php';
					} elseif ($act == 'proses') {
						include 'modul/siswa/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'kelas') {
					if ($act == '') {
						include 'modul/kelas/data.php';
					} elseif ($act == 'add') {
						include 'modul/kelas/add.php';
					} elseif ($act == 'edit') {
						include 'modul/kelas/edit.php';
					} elseif ($act == 'del') {
						include 'modul/kelas/del.php';
					} elseif ($act == 'proses') {
						include 'modul/kelas/proses.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'rekap') {
					if ($act == '') {
						include 'modul/rekap/rekap_absen.php';
					} elseif ($act == 'rekap-perbulan') {
						include 'modul/rekap/rekap_perbulan.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'jadwal') {
					if ($act == '') {
						include 'modul/jadwal/data_mengajar.php';
					} elseif ($act == 'add') {
						include 'modul/jadwal/tambah.php';
					} elseif ($act == 'cancel') {
						include 'modul/jadwal/cancel.php';
					} else {
						echo "<b>Tidak ada Halaman</b>";
					}
				} elseif ($page == 'akun') {
					include 'modul/akun/akun.php';
				} elseif ($page == '') {
					include 'modul/home.php';
				} else {
					echo "<b>Tidak ada Halaman</b>";
				}
				?>



				<!-- end -->

			</div>

			<footer class="footer">
				<div class="container">
					<div class="copyright ml-auto">
						&copy; <?php echo date('Y'); ?>( Marlin Swimming Club | 2024)
					</div>
				</div>
			</footer>
		</div>


	</div>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		function confirmLogout() {
			Swal.fire({
				title: 'Logout',
				html: 'Apakah Anda yakin ingin Keluar?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Keluar',
				cancelButtonText: 'Batal',
				iconHtml: '<i class="fas fa-sign-out-alt"></i>' // Menggunakan ikon Font Awesome untuk logout
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = 'logout.php';
				}
			});
		}
	</script>

	<!-- Core JS Files -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="../assets/js/core/popper.min.js"></script>
	<!-- <script src="../assets/js/core/bootstrap.min.js"></script> -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- jQuery UI -->
	<script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>


	<!-- jQuery Scrollbar -->
	<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Datatables -->
	<script src="../assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Atlantis JS -->
	<script src="../assets/js/atlantis.min.js"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="../assets/js/setting-demo.js"></script>

	<!-- Datatables -->
	<script src="../assets/js/plugin/datatables/datatables.min.js"></script>
	<script src="../assets/js/plugin/datatables/dataTables.js"></script>
	<script src="../assets/js/plugin/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="../assets/js/plugin/datatables/dataTables.buttons.min.js"></script>
	<script src="../assets/js/plugin/datatables/buttons.bootstrap4.min.js"></script>
	<script src="../assets/js/plugin/datatables/jszip.min.js"></script>
	<script src="../assets/js/plugin/datatables/pdfmake.min.js"></script>
	<script src="../assets/js/plugin/datatables/vfs_fonts.js"></script>
	<script src="../assets/js/plugin/datatables/buttons.html5.min.js"></script>
	<script src="../assets/js/plugin/datatables/buttons.print.min.js"></script>
	<script src="../assets/js/plugin/datatables/buttons.colVis.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#basic-datatables').DataTable({});

			$('#multi-filter-select').DataTable({
				"pageLength": 5,
				initComplete: function() {
					this.api().columns().every(function() {
						var column = this;
						var select = $('<select class="form-control"><option value=""></option></select>')
							.appendTo($(column.footer()).empty())
							.on('change', function() {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);

								column
									.search(val ? '^' + val + '$' : '', true, false)
									.draw();
							});

						column.data().unique().sort().each(function(d, j) {
							select.append('<option value="' + d + '">' + d + '</option>')
						});
					});
				}
			});

			// Add Row
			$('#add-row').DataTable({
				"pageLength": 5,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#add-row').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
				]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>


</body>

</html>