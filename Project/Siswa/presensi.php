<?php
$id_mengajar = $_GET['id_mengajar']; // Ambil nilai id_mengajar dari URL

// Gunakan $id_mengajar untuk mengambil data dari tabel tb_jadwal_siswa
$query_jadwal_siswa = mysqli_query($con, "SELECT tb_siswa.nama_siswa, tb_jadwal_siswa.pertemuan_ke FROM tb_jadwal_siswa 
INNER JOIN tb_siswa ON tb_jadwal_siswa.id_siswa = tb_siswa.id_siswa 
WHERE tb_jadwal_siswa.id_mengajar = '$id_mengajar'");

// Ambil informasi kelas dan tanggal dari tabel tb_mengajar
$query_kelas_mengajar = mysqli_query($con, "SELECT tb_kelas.kelas, tb_mengajar.tanggal FROM tb_mengajar 
INNER JOIN tb_kelas ON tb_mengajar.id_kelas = tb_kelas.id_kelas
WHERE tb_mengajar.id_mengajar = '$id_mengajar'");
$kelas = mysqli_fetch_assoc($query_kelas_mengajar);
$nama_kelas = isset($kelas['kelas']) ? strtoupper($kelas['kelas']) : 'Nama Kelas Tidak Tersedia';
$tanggal_jadwal = isset($kelas['tanggal']) ? $kelas['tanggal'] : date('Y-m-d'); // Ambil tanggal dari jadwal, jika tidak ada gunakan tanggal hari ini

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="page-inner">
    <div class="page-header">
        <ul class="breadcrumbs" style="font-weight: bold;">
            <li class="nav-home">
                <a href="dashboard.php">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">KELAS (<?= $nama_kelas; ?>)</a>
            </li>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- <div>
                            <span class="badge badge-primary" style="padding: 7px;font-size: 14px;">
                                Pertemuan Ke : <b><?= $pertemuan_ke; ?></b>
                            </span>
                        </div> -->
                        <input type="hidden" name="id_mengajar" value="<?= $id_mengajar ?>"> <!-- Tambahkan input hidden untuk id_mengajar -->
                        <div style="margin-bottom: 10px;">
                            <label for=""><b>Tanggal</b></label>
                            <input type="date" name="tgl_presensi" class="form-control" value="<?= $tanggal_jadwal ?>">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label for=""><b>Materi yang diajar</b></label>
                            <input type="text" name="materi" class="form-control" placeholder="Masukkan materi yang diajarkan" required>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label for=""><b>Foto mengajar</b></label>
                            <input type="file" name="foto_mengajar" accept="image/*" capture="camera" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="presen" class="btn btn-success">
                    <i class="fa fa-check"></i> Selesai
                </button>
                <a href="javascript:history.back()" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Batal</a>
            </div>
        </div>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap nilai dari form
    $id_mengajar = $_POST['id_mengajar'];
    $materi = $_POST['materi'];
    $tgl = $_POST['tgl_presensi'];
    $foto_mengajar = $_FILES['foto_mengajar'];

    // Tentukan direktori tujuan untuk menyimpan file
    $target_dir = "../assets/img/bukti_mengajar/";

    // Ambil nama file dan tambahkan timestamp untuk menghindari duplikasi nama file
    $file_name = basename($foto_mengajar["name"]);
    $file_name = time() . '_' . $file_name;
    $target_file = $target_dir . $file_name;

    // Cek apakah file yang diunggah adalah gambar
    $is_image = getimagesize($foto_mengajar["tmp_name"]);
    if ($is_image !== false) {
        // Coba pindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($foto_mengajar["tmp_name"], $target_file)) {
            // Jika berhasil, lanjutkan dengan query INSERT ke database
            $query_insert = "INSERT INTO tb_presensi (id_mengajar, tgl_presensi, materi, foto_mengajar) 
                            VALUES ('$id_mengajar', '$tgl', '$materi', '$file_name')";

            // Jalankan query INSERT dan periksa keberhasilannya
            if (mysqli_query($con, $query_insert)) {
                echo "
                    <script>
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                        });
                        Toast.fire({
                          icon: 'success',
                          title: 'Berhasil! Presensi berhasil ditambahkan'
                        });
                        setTimeout(function () { 
                           window.location.replace('?page=jadwal');
                        }, 3000);   
                        </script>";
            } else {
                echo "
                    <script>
                    swal('Gagal', 'Terjadi kesalahan saat menyimpan data presensi.', 'error');    
                    </script>";
            }
        } else {
            // Jika gagal memindahkan file, tampilkan pesan kesalahan
            echo "
                <script>
                swal('Gagal', 'Terjadi kesalahan saat mengunggah foto.', 'error');    
                </script>";
        }
    } else {
        // Jika file yang diunggah bukan gambar, tampilkan pesan kesalahan
        echo "
            <script>
            swal('Gagal', 'File yang diunggah bukan gambar.', 'error');    
            </script>";
    }
}
?>