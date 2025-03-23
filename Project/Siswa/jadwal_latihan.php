<?php
// Pastikan session dimulai jika belum dimulai sebelumnya
session_start();

$id_login = $_SESSION['siswa'];

// Tampilkan data mengajar yang terkait dengan siswa yang sedang login
$mengajar = mysqli_query($con, "SELECT tb_mengajar.*, tb_lokasi.lokasi, tb_jadwal_siswa.pertemuan_ke, tb_pelatih.nama_pelatih 
                                FROM tb_mengajar
                                INNER JOIN tb_lokasi ON tb_mengajar.id_lokasi = tb_lokasi.id_lokasi
                                INNER JOIN tb_pelatih ON tb_mengajar.id_pelatih = tb_pelatih.id_pelatih
                                INNER JOIN tb_jadwal_siswa ON tb_mengajar.id_mengajar = tb_jadwal_siswa.id_mengajar
                                WHERE tb_jadwal_siswa.id_siswa = '$id_login'
                                ORDER BY tb_mengajar.tanggal DESC
                                LIMIT 1");

// Periksa apakah ada jadwal mengajar
$jumlah_jadwal = mysqli_num_rows($mengajar);

?>

<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">Jadwal Latihan</h4>
		<ul class="breadcrumbs">
			<li class="nav-home">
				<a href="index.php">
					<i class="flaticon-home"></i>
				</a>
			</li>
		</ul>
	</div>

	<style>
		ul.no-bullets {
			list-style-type: none;
			padding-left: 0;
		}

		ul.no-bullets li {
			margin-bottom: 5px;
		}

		.jadwal-card {
			margin-bottom: 20px;
		}

		.jadwal-card .alert {
			margin-bottom: 0;
		}

		.jadwal-card h3 {
			margin-top: 0;
		}

		.jadwal-card hr {
			margin: 10px 0;
		}
	</style>

	<div class="row mt-4">
		<?php if ($jumlah_jadwal > 0) { ?>
			<?php
			date_default_timezone_set('Asia/Jakarta'); // Sesuaikan timezone dengan lokasi Anda

			while ($jd = mysqli_fetch_assoc($mengajar)) {
				$tanggal = formatTanggalIndonesia($jd['tanggal']);
			?>
				<div class="col-md-5 col-xs-12 jadwal-card">
					<div class="alert alert-info alert-dismissible" role="alert">
						<!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button> -->
						<strong>
							<h3>Pertemuan ke <?= $jd['pertemuan_ke']; ?></h3>
						</strong>
						<hr>
						<ul class="no-bullets">
							<li>
								<i class="fas fa-calendar-alt"></i> Tanggal Latihan: <?= $tanggal; ?>
							</li>
							<li>
								<i class="fas fa-clock"></i> Jam: <?= $jd['jam']; ?>
							</li>
							<li>
								<i class="fas fa-map-marker-alt"></i> Lokasi: <?= $jd['lokasi']; ?>
							</li>
							<li>
								<i class="fas fa-user"></i> Nama Pelatih: <?= $jd['nama_pelatih']; ?>
							</li>
						</ul>
						<hr>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="col-md-12">
				<div class="alert alert-warning" role="alert">
					<strong>Perhatian!</strong> Tidak ada jadwal latihan yang tersedia saat ini.
				</div>
			</div>
		<?php } ?>
	</div>
</div>