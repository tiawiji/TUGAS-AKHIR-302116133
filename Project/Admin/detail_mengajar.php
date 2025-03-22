<?php
$nama_pelatih = '';

if (isset($_GET['id'])) {
    $id_pelatih = $_GET['id'];

    // Query to get the name of the coach based on id_pelatih
    $query_nama_pelatih = mysqli_query($con, "SELECT nama_pelatih FROM tb_pelatih WHERE id_pelatih = '$id_pelatih'");
    $result_nama_pelatih = mysqli_fetch_assoc($query_nama_pelatih);
    $nama_pelatih = $result_nama_pelatih['nama_pelatih'];

    // Initialize default values for filters
    $filter_bulan = isset($_POST['filter_bulan']) ? $_POST['filter_bulan'] : '';
    $filter_tahun = isset($_POST['filter_tahun']) ? $_POST['filter_tahun'] : '';

    // Build the SQL query with dynamic filtering
    $sql_filter = "";
    if (!empty($filter_bulan) && !empty($filter_tahun)) {
        $sql_filter = " AND MONTH(tb_mengajar.tanggal) = '$filter_bulan' AND YEAR(tb_mengajar.tanggal) = '$filter_tahun'";
    } elseif (!empty($filter_bulan)) {
        $sql_filter = " AND MONTH(tb_mengajar.tanggal) = '$filter_bulan'";
    } elseif (!empty($filter_tahun)) {
        $sql_filter = " AND YEAR(tb_mengajar.tanggal) = '$filter_tahun'";
    }

    // Final query with filters applied
    $query_mengajar = mysqli_query($con, "
        SELECT 
            tb_mengajar.id_mengajar, 
            tb_mengajar.tanggal, 
            tb_kelas.kelas, 
            tb_mengajar.jam, 
            tb_lokasi.lokasi, 
            tb_presensi.materi,
            tb_presensi.foto_mengajar,
            GROUP_CONCAT(tb_siswa.nama_siswa SEPARATOR ', ') AS daftar_siswa,
            tb_mengajar.id_pelatih
        FROM 
            tb_mengajar
        INNER JOIN 
            tb_presensi ON tb_mengajar.id_mengajar = tb_presensi.id_mengajar
        INNER JOIN 
            tb_kelas ON tb_mengajar.id_kelas = tb_kelas.id_kelas
        INNER JOIN 
            tb_lokasi ON tb_mengajar.id_lokasi = tb_lokasi.id_lokasi
        LEFT JOIN 
            tb_jadwal_siswa ON tb_mengajar.id_mengajar = tb_jadwal_siswa.id_mengajar
        LEFT JOIN 
            tb_siswa ON tb_jadwal_siswa.id_siswa = tb_siswa.id_siswa
        WHERE 
            tb_mengajar.id_pelatih = '$id_pelatih'
            $sql_filter
        GROUP BY 
            tb_mengajar.id_mengajar;
    ");
} else {
    echo "ID Pelatih tidak ditemukan.";
}
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Detail Mengajar</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="index.php">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Rekap Presensi</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Presensi Pelatih</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="mb-3">
                        <form action="" method="post" class="d-flex align-items-center">
                            <div class="form-group mb-0 mr-2">
                                <select class="form-control" id="filter_bulan" name="filter_bulan">
                                    <option value="">Pilih Bulan</option>
                                    <?php
                                    $bulan = [
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember'
                                    ];
                                    foreach ($bulan as $num => $nama) {
                                        $selected = ($num == $filter_bulan) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$nama</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-0 mr-2">
                                <select class="form-control" id="filter_tahun" name="filter_tahun">
                                    <option value="">Pilih Tahun</option>
                                    <?php
                                    $current_year = date('Y');
                                    for ($year = $current_year; $year >= 2024; $year--) {
                                        $selected = ($year == $filter_tahun) ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-eye"></i> Tampilkan Data
                            </button>
                        </form>
                    </div>
                    <div class="alert alert-info" role="alert">
                        Menampilkan Data untuk bulan
                        <?php
                        if (!empty($filter_bulan) && !empty($filter_tahun)) {
                            $nama_bulan = $bulan[$filter_bulan]; // assuming $bulan is defined with month names
                            echo "$nama_bulan $filter_tahun";
                        } elseif (!empty($filter_bulan)) {
                            $nama_bulan = $bulan[$filter_bulan];
                            echo "$nama_bulan";
                        } elseif (!empty($filter_tahun)) {
                            echo "$filter_tahun";
                        } else {
                            echo "Semua waktu";
                        }
                        ?>.
                    </div>


                    <p style="font-size: 15px;" class="card-title">Nama Pelatih: <strong style="font-size: 15px; font-weight: bold;"><?= $nama_pelatih; ?></strong></p>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="background-color: #47506D; color: white;">No</th>
                                    <th style="background-color: #47506D; color: white;">Tanggal/Hari</th>
                                    <th style="background-color: #47506D; color: white;">Kelas</th>
                                    <th style="background-color: #47506D; color: white;">Jam</th>
                                    <th style="background-color: #47506D; color: white;">Nama Lokasi</th>
                                    <th style="background-color: #47506D; color: white;">Materi</th>
                                    <th style="background-color: #47506D; color: white;">Bukti Mengajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['id'])) {
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($query_mengajar)) {
                                        $tanggal = formatTanggalIndonesia($row['tanggal']);
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $row['kelas']; ?></td>
                                            <td><?= $row['jam']; ?></td>
                                            <td><?= $row['lokasi']; ?></td>
                                            <td><?= $row['materi']; ?></td>
                                            <td>
                                                <!-- Tombol Tampilkan Bukti Mengajar -->
                                                <a href="#" class="btn btn-sm" style="background-color: #7B68EE; color: white;" data-toggle="modal" data-target="#buktiMengajarModal<?= $no; ?>">
                                                    <i class="fas fa-image"></i> Tampilkan
                                                </a>

                                                <!-- Modal Bukti Mengajar -->
                                                <div class="modal fade" id="buktiMengajarModal<?= $no; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Bukti Mengajar</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Foto Mengajar -->
                                                                <?php if (!empty($row['foto_mengajar'])) : ?>
                                                                    <img src="../assets/img/bukti_mengajar/<?= $row['foto_mengajar']; ?>" class="img-fluid" alt="Bukti Mengajar">
                                                                <?php else : ?>
                                                                    <p>Tidak ada foto bukti mengajar tersedia.</p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Tidak ada data yang tersedia.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>