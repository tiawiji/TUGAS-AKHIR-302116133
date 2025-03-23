<?php
$id_mengajar = $_GET['id_mengajar'];

$query_jadwal_siswa = mysqli_query($con, "
    SELECT tb_jadwal_siswa.*, tb_siswa.nama_siswa, tb_kelas.kelas
    FROM tb_jadwal_siswa
    INNER JOIN tb_siswa ON tb_jadwal_siswa.id_siswa = tb_siswa.id_siswa
    INNER JOIN tb_mengajar ON tb_jadwal_siswa.id_mengajar = tb_mengajar.id_mengajar
    INNER JOIN tb_kelas ON tb_mengajar.id_kelas = tb_kelas.id_kelas
    WHERE tb_jadwal_siswa.id_mengajar = '$id_mengajar'
");

?>

<div class="page-inner">
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
                <a href="#">Jadwal mengajar</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><b>Daftar Siswa</b></h4>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($j = mysqli_fetch_assoc($query_jadwal_siswa)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?>.</td>
                                            <td><?= $j['nama_siswa']; ?></td>
                                            <td><?= $j['kelas']; ?></td>
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