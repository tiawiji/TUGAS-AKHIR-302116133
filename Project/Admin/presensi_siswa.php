<?php
// Inisialisasi variabel
$data_to_export = [];
$no = 1;

// Mendapatkan semua kelas untuk dropdown filter
$query_kelas = "SELECT * FROM tb_kelas";
$result_kelas = mysqli_query($con, $query_kelas);

// Mendapatkan filter kelas dari request
$filter_kelas = isset($_GET['filter_kelas']) ? $_GET['filter_kelas'] : '';

// Query untuk mengambil data siswa dan jumlah pertemuan
$query = "
    SELECT 
        s.id_siswa,
        s.nama_siswa,
        k.kelas,
        COUNT(j.id_jadwal_siswa) as jumlah_pertemuan
    FROM 
        tb_jadwal_siswa j
    INNER JOIN 
        tb_siswa s ON j.id_siswa = s.id_siswa
    INNER JOIN 
        tb_kelas k ON s.id_kelas = k.id_kelas
    WHERE 
        s.id_cabang = '$id_cabang'";

// Menambahkan filter kelas jika ada
if ($filter_kelas) {
    $query .= " AND k.id_kelas = '$filter_kelas'";
}

$query .= " GROUP BY 
                s.id_siswa, s.nama_siswa, k.kelas
            ORDER BY 
                s.nama_siswa ASC";

// Eksekusi query
$result = mysqli_query($con, $query);

// Memproses hasil query
while ($row = mysqli_fetch_assoc($result)) {
    $data_to_export[] = [
        'no' => $no,
        'id_siswa' => $row['id_siswa'],
        'nama_siswa' => $row['nama_siswa'],
        'kelas' => $row['kelas'],
        'jumlah_pertemuan' => $row['jumlah_pertemuan']
    ];
    $no++;
}
?>

<div class="page-inner">
    <h4 class="page-title">Presensi Siswa</h4>
    <div class="page-header">
        <ul class="breadcrumbs" style="font-weight: bold;">
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
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Form Filter Kelas -->
                    <form method="GET" action="">
                        <input type="hidden" name="page" value="presensi">
                        <input type="hidden" name="act" value="presensi_siswa">
                        <div class="filter-container">
                            <div class="form-group">
                                <label for="filter_kelas">Filter Kelas</label>
                                <select name="filter_kelas" id="filter_kelas" class="form-control">
                                    <option value="">Semua Kelas</option>
                                    <?php while ($row_kelas = mysqli_fetch_assoc($result_kelas)) { ?>
                                        <option value="<?= $row_kelas['id_kelas']; ?>" <?= ($filter_kelas == $row_kelas['id_kelas']) ? 'selected' : ''; ?>>
                                            <?= $row_kelas['kelas']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fa fa-eye"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                    <br>
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="background-color: #47506D; color: white;">No</th>
                                    <th style="background-color: #47506D; color: white;">Nama Siswa</th>
                                    <th style="background-color: #47506D; color: white;">Kelas</th>
                                    <th style="background-color: #47506D; color: white;">Jumlah Pertemuan</th>
                                    <th style="background-color: #47506D; color: white;">Detail Harian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_to_export as $data) { ?>
                                    <tr>
                                        <td><?= $data['no']; ?></td>
                                        <td><?= $data['nama_siswa']; ?></td>
                                        <td><?= $data['kelas']; ?></td>
                                        <td><?= $data['jumlah_pertemuan']; ?></td>
                                        <td>
                                            <a href="?page=presensi&act=detail_siswa&id_siswa=<?= $data['id_siswa']; ?>&id_cabang=<?= $id_cabang; ?>" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</body>

</html>