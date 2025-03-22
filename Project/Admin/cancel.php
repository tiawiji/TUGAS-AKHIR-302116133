<?php
$id_mengajar = $_GET['id'];

// Hapus data terkait dari tabel tb_jadwal_siswa
$del_jadwal_siswa = mysqli_query($con, "DELETE FROM tb_jadwal_siswa WHERE id_mengajar = '$id_mengajar'");
if ($del_jadwal_siswa) {
	// Jika penghapusan dari tb_jadwal_siswa berhasil, lanjutkan untuk menghapus dari tb_mengajar
	$del_mengajar = mysqli_query($con, "DELETE FROM tb_mengajar WHERE id_mengajar = '$id_mengajar'");
	if ($del_mengajar) {
		// Redirect ke halaman jadwal setelah berhasil menghapus
		echo "<script>window.location='?page=jadwal';</script>";
	} else {
		// Tampilkan pesan jika gagal menghapus dari tb_mengajar
		echo "Gagal menghapus data dari tabel tb_mengajar";
	}
} else {
	// Tampilkan pesan jika gagal menghapus dari tb_jadwal_siswa
	echo "Gagal menghapus data dari tabel tb_jadwal_siswa";
}
