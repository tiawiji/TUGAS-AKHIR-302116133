<?php
$query_mengajar = mysqli_query($con, "
SELECT
tb_mengajar.id_mengajar,
tb_mengajar.tanggal,
tb_kelas.kelas,
tb_mengajar.jam,
tb_lokasi.lokasi,
tb_jadwal_siswa.pertemuan_ke,
tb_pelatih.nama_pelatih
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
LEFT JOIN
tb_pelatih ON tb_mengajar.id_pelatih = tb_pelatih.id_pelatih
WHERE
tb_jadwal_siswa.id_siswa = '$id_login'
GROUP BY
tb_mengajar.id_mengajar;
");

?>

<div class="page-inner">
    <h4 class="page-title">Daftar Kehadiran</h4>
    <div class="page-header">
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="index.php">
                    <i class="flaticon-home"></i>
                </a>
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
                                    <th style="background-color: #47506D; color: white;">Tanggal/Hari</th>
                                    <th style="background-color: #47506D; color: white;">Kelas</th>
                                    <th style="background-color: #47506D; color: white;">Jam</th>
                                    <th style="background-color: #47506D; color: white;">Lokasi</th>
                                    <th style="background-color: #47506D; color: white;">Pertemuan Ke</th>
                                    <th style="background-color: #47506D; color: white;">Nama Pelatih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
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
                                        <td><?= $row['pertemuan_ke']; ?></td>
                                        <td><?= $row['nama_pelatih']; ?></td>
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