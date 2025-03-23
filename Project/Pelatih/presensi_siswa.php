<?php
$id_mengajar = $_GET['id_mengajar'];

$query_jadwal_siswa = mysqli_query($con, "
    SELECT tb_jadwal_siswa.*, tb_siswa.nama_siswa, tb_kelas.kelas, tb_mengajar.tanggal
    FROM tb_jadwal_siswa
    INNER JOIN tb_siswa ON tb_jadwal_siswa.id_siswa = tb_siswa.id_siswa
    INNER JOIN tb_mengajar ON tb_jadwal_siswa.id_mengajar = tb_mengajar.id_mengajar
    INNER JOIN tb_kelas ON tb_mengajar.id_kelas = tb_kelas.id_kelas
    WHERE tb_jadwal_siswa.id_mengajar = '$id_mengajar'
");
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Daftar Siswa </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="dashboard.php">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Jadwal Mengajar </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Daftar Siswa</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Hapus nama siswa hanya jika siswa tidak hadir. Nama siswa dapat dihapus hanya pada tanggal yang dijadwalkan.
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><b>Daftar Siswa yang Diajar</b></h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="background-color: #47506D; color: white;">No</th>
                                        <th style="background-color: #47506D; color: white;">Nama Siswa</th>
                                        <th style="background-color: #47506D; color: white;">Kelas</th>

                                        <th style="background-color: #47506D; color: white;">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($j = mysqli_fetch_assoc($query_jadwal_siswa)) {
                                        $disableDelete = '';
                                        if (strtotime($j['tanggal']) < strtotime(date('Y-m-d'))) {
                                            $disableDelete = 'disabled';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?>.</td>
                                            <td><?= $j['nama_siswa']; ?></td>
                                            <td><?= $j['kelas']; ?></td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-delete-siswa" data-id="<?= $j['id_jadwal_siswa'] ?>" <?= $disableDelete ?>><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menggunakan event delegation untuk menangani klik pada semua tombol hapus
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete-siswa')) {
                e.preventDefault();

                const btn = e.target.closest('.btn-delete-siswa');
                const jadwalSiswaId = btn.getAttribute('data-id');

                // Tampilkan peringatan SweetAlert dengan gambar sebagai ikon di atas judul dan teks
                Swal.fire({
                    title: 'Anda yakin ingin menghapus?',
                    text: 'Anda tidak akan dapat mengembalikan ini!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus itu!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke halaman penghapusan dengan parameter ID
                        window.location.href = `?page=jadwal&act=del&id_jadwal_siswa=${jadwalSiswaId}&id_mengajar=${<?= $id_mengajar ?>}`;
                    }
                });
            }
        });
    });
</script>