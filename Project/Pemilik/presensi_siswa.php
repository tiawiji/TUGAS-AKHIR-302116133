<?php
// Inisialisasi variabel
$data_to_export = [];
$no = 1;

// Mendapatkan semua cabang untuk dropdown filter
$query_cabang = "SELECT * FROM tb_cabang";
$result_cabang = mysqli_query($con, $query_cabang);

// Mendapatkan filter cabang dari request
$filter_cabang = isset($_GET['filter_cabang']) ? $_GET['filter_cabang'] : '';

// Query untuk mengambil data siswa dan jumlah pertemuan berdasarkan cabang
$query = "
    SELECT 
        s.id_siswa,
        s.nama_siswa,
        k.kelas,
        COUNT(j.id_jadwal_siswa) as jumlah_pertemuan,
        c.nama_cabang
    FROM 
        tb_jadwal_siswa j
    INNER JOIN 
        tb_siswa s ON j.id_siswa = s.id_siswa
    INNER JOIN 
        tb_kelas k ON s.id_kelas = k.id_kelas
    INNER JOIN 
        tb_cabang c ON s.id_cabang = c.id_cabang
    WHERE 
        s.id_siswa";

// Menambahkan filter cabang jika ada
if ($filter_cabang) {
    $query .= " AND s.id_cabang = '$filter_cabang'";
}

$query .= " GROUP BY 
                s.id_siswa, s.nama_siswa, k.kelas, c.nama_cabang
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
        'jumlah_pertemuan' => $row['jumlah_pertemuan'],
        'nama_cabang' => $row['nama_cabang']
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
                    <!-- Form Filter Cabang -->
                    <form method="GET" action="">
                        <input type="hidden" name="page" value="presensi">
                        <input type="hidden" name="act" value="presensi_siswa">
                        <div class="filter-container">
                            <div class="form-group">
                                <label for="filter_cabang">Filter Cabang</label>
                                <select name="filter_cabang" id="filter_cabang" class="form-control">
                                    <option value="">Semua Cabang</option>
                                    <?php while ($row_cabang = mysqli_fetch_assoc($result_cabang)) { ?>
                                        <option value="<?= $row_cabang['id_cabang']; ?>" <?= ($filter_cabang == $row_cabang['id_cabang']) ? 'selected' : ''; ?>>
                                            <?= $row_cabang['nama_cabang']; ?>
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
                                    <th style="background-color: #47506D; color: white;">Cabang</th>
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
                                        <td><?= $data['nama_cabang']; ?></td>
                                        <td>
                                            <a href="?page=presensi&act=detail_siswa&id_siswa=<?= $data['id_siswa']; ?>" class="btn btn-secondary btn-sm">
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