<?php
// Pastikan id_mengajar tersedia dalam URL
if (isset($_GET['id_mengajar'])) {
    $id_mengajar = $_GET['id_mengajar'];

    // Lakukan kueri SQL untuk mendapatkan detail siswa dengan id_mengajar tertentu
    $query_detail_siswa = mysqli_query($con, "SELECT 
            tb_siswa.nama_siswa,
            tb_jadwal_siswa.ket
        FROM 
            tb_jadwal_siswa
        INNER JOIN 
            tb_siswa ON tb_jadwal_siswa.id_siswa = tb_siswa.id_siswa
        WHERE 
            tb_jadwal_siswa.id_mengajar = '$id_mengajar'
    ");
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Detail Siswa</title>
        <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- ganti dengan path CSS Anda -->
    </head>

    <body>
        <div class="page-inner">
            <h4 class="page-title">Detail Siswa</h4>
            <div class="page-header">
                <ul class="breadcrumbs" style="font-weight: bold;">
                    <li class="nav-home">
                        <a href="#">
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
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #47506D; color: white;">No</th>
                                            <th style="background-color: #47506D; color: white;">Nama Siswa</th>
                                            <th style="background-color: #47506D; color: white;">Keterangan</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query_detail_siswa)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['nama_siswa']; ?></td>
                                                <td><?= $row['ket']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="javascript:history.back()" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="path/to/bootstrap.js"></script> <!-- ganti dengan path JS Anda -->
    </body>

    </html>
<?php } else {
    // Jika id_mengajar tidak tersedia dalam URL, tampilkan pesan kesalahan atau redirect pengguna ke halaman lain.
    echo "Parameter id_mengajar tidak tersedia.";
}
?>