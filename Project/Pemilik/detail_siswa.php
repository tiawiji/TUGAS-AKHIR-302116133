<?php

$id_siswa = isset($_GET['id_siswa']) ? intval($_GET['id_siswa']) : 0;
$id_cabang = isset($_GET['id_cabang']) ? intval($_GET['id_cabang']) : 0;
$nama_siswa = '';  // Default value
$nama_cabang = ''; // Default value
$nama_kelas = '';  // Default value

// Array untuk nama bulan dalam Bahasa Indonesia
$bulan = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];

// Query untuk mengambil nama siswa, nama cabang, dan nama kelas
$query_info_siswa = "
    SELECT s.nama_siswa, c.nama_cabang, k.kelas AS nama_kelas
    FROM tb_siswa s
    INNER JOIN tb_cabang c ON s.id_cabang = c.id_cabang
    INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
    WHERE s.id_siswa = '{$id_siswa}' AND c.id_cabang";

$result_info_siswa = mysqli_query($con, $query_info_siswa);
if ($row_info_siswa = mysqli_fetch_assoc($result_info_siswa)) {
    $nama_siswa = $row_info_siswa['nama_siswa'];
    $nama_cabang = $row_info_siswa['nama_cabang'];
    $nama_kelas = $row_info_siswa['nama_kelas'];
}

// Query untuk mengambil data presensi harian siswa
$query_detail_harian = "
    SELECT 
        m.tanggal,
        j.pertemuan_ke,
        l.lokasi,
        p.nama_pelatih
    FROM 
        tb_jadwal_siswa j
    INNER JOIN 
        tb_mengajar m ON j.id_mengajar = m.id_mengajar
    INNER JOIN 
        tb_lokasi l ON m.id_lokasi = l.id_lokasi
    INNER JOIN 
        tb_pelatih p ON m.id_pelatih = p.id_pelatih
    WHERE 
        j.id_siswa = '{$id_siswa}'
    ORDER BY 
        m.tanggal ASC";

$result_detail_harian = mysqli_query($con, $query_detail_harian);
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Detail Harian</h4>
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
                <a href="#">Rekap Presensi</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Presensi Siswa</a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="mb-3 d-flex justify-content-end">
                <form action="modul/presensi/rekap_siswa/cetak_presensi.php" method="GET" target="_blank">
                    <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                    <input type="hidden" name="id_cabang" value="<?= $id_cabang ?>">
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </form>
            </div>
            <p style="font-size: 15px;" class="card-title">Nama Siswa: <strong style="font-size: 15px; font-weight: bold;"><?= $nama_siswa; ?></strong></p>
            <p style="font-size: 15px;" class="card-title">Kelas: <strong style="font-size: 15px; font-weight: bold;"><?= $nama_kelas; ?></strong></p>
            <p style="font-size: 15px;" class="card-title">Nama Cabang: <strong style="font-size: 15px; font-weight: bold;"><?= $nama_cabang; ?></strong></p>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="background-color: #47506D; color: white;">No</th>
                            <th style="background-color: #47506D; color: white;">Tanggal</th>
                            <th style="background-color: #47506D; color: white;">Pertemuan Ke</th>
                            <th style="background-color: #47506D; color: white;">Nama Lokasi</th>
                            <th style="background-color: #47506D; color: white;">Nama Pelatih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result_detail_harian)) {
                            // Format tanggal dalam bahasa Indonesia
                            $tanggal = strtotime($row['tanggal']);
                            $tgl = date('d', $tanggal);
                            $namaBulan = $bulan[date('F', $tanggal)];
                            $tahun = date('Y', $tanggal);
                            $formatted_date = $tgl . ' ' . $namaBulan . ' ' . $tahun;
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $formatted_date; ?></td>
                                <td><?= $row['pertemuan_ke']; ?></td>
                                <td><?= $row['lokasi']; ?></td>
                                <td><?= $row['nama_pelatih']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>