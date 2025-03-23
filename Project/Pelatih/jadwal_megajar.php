<?php
// Pastikan session dimulai jika belum dimulai sebelumnya
session_start();

// tampilkan data mengajar
$id_login = $_SESSION['pelatih'];

// Tampilkan data mengajar yang terkait dengan pelatih yang sedang masuk
$mengajar = mysqli_query($con, "SELECT * FROM tb_mengajar 
                                INNER JOIN tb_lokasi ON tb_mengajar.id_lokasi = tb_lokasi.id_lokasi
                                INNER JOIN tb_kelas ON tb_mengajar.id_kelas = tb_kelas.id_kelas
                                WHERE tb_mengajar.id_pelatih = '$id_login'
                                ORDER BY tb_mengajar.tanggal DESC
                    LIMIT 3");

// Periksa apakah ada jadwal mengajar
$jumlah_jadwal = mysqli_num_rows($mengajar);
?>
<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">Jadwal Mengajar</h4>
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
	</style>

	<div class="row mt-4">
		<?php if ($jumlah_jadwal > 0) { ?>
			<?php
			date_default_timezone_set('Asia/Jakarta'); // Sesuaikan timezone dengan lokasi Anda
			$current_datetime = new DateTime();

			foreach ($mengajar as $jd) {
				// Periksa apakah presensi sudah terisi
				$id_mengajar = $jd['id_mengajar'];
				$query_presensi = mysqli_query($con, "SELECT * FROM tb_presensi WHERE id_mengajar = '$id_mengajar'");
				$presensi_terisi = mysqli_num_rows($query_presensi) > 0;

				// Cek apakah waktu saat ini sesuai dengan jadwal
				$jadwal_datetime = new DateTime($jd['tanggal'] . ' ' . $jd['jam']);
				$allow_presensi = $current_datetime >= $jadwal_datetime && $current_datetime->format('Y-m-d') === $jadwal_datetime->format('Y-m-d'); // Periksa hanya tanggal dan jam yang sama

				$tanggal = formatTanggalIndonesia($jd['tanggal']);
			?>
				<div class="col-md-5 col-xs-12">
					<div class="alert alert-info alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
						<strong>
							<h3><?= $jd['kelas']; ?></h3>
						</strong>
						<hr>
						<ul class="no-bullets">
							<li>
								<i class="fas fa-calendar-alt"></i> Tanggal: <?= $tanggal; ?>
							</li>
							<li>
								<i class="fas fa-clock"></i> Jam: <?= $jd['jam']; ?>
							</li>
							<li>
								<i class="fas fa-map-marker-alt"></i> Lokasi: <?= $jd['lokasi']; ?>
							</li>
						</ul>
						<hr>
						<?php if ($presensi_terisi) { ?>
							<button class="btn btn-default btn-block text-left" disabled>
								<i class="fas fa-clipboard-check"></i>
								Presensi Sudah Terisi
							</button>
						<?php } elseif ($allow_presensi) { ?>
							<a href="?page=jadwal&act=presensi&id_mengajar=<?= $jd['id_mengajar']; ?>" class="btn btn-default btn-block text-left">
								<i class="fas fa-clipboard-check"></i>
								Isi Presensi
							</a>
						<?php } else { ?>
							<button class="btn btn-default btn-block text-left" disabled>
								<i class="fas fa-clipboard-check"></i>
								Presensi tidak dapat diisi
							</button>
							<div class="alert alert-warning mt-2">
								Presensi belum dapat diisi sebelum <?= $tanggal; ?> jam <?= date('H:i', strtotime($jd['jam'])); ?>.
							</div>
						<?php } ?>

						<a href="?page=jadwal&act=presensi_siswa&id_mengajar=<?= $jd['id_mengajar']; ?>" class="btn btn-secondary btn-block text-left">
							<i class="fas fa-list-alt"></i>
							Daftar siswa
						</a>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="col-md-12">
				<div class="alert alert-warning" role="alert">
					<strong>Perhatian!</strong> Tidak ada jadwal mengajar yang tersedia saat ini.
				</div>
			</div>
		<?php } ?>
	</div>
</div>