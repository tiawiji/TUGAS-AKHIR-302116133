<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Rekap Presensi</h4>
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
                <a href="#">Presensi Pelatih</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><strong>Daftar Presensi Pelatih</strong></div>
                    <!-- <a href="?page=karyawan&act=add" class="btn btn-primary btn-sm text-white"><i class="fa fa-plus"></i> Tambah Karyawan</a> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="background-color: #47506D; color: white;">No</th>
                                    <th style="background-color: #47506D; color: white;">Nama Pelatih</th>
                                    <th style="background-color: #47506D; color: white;">Detail mengajar pelatih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $pelatih = mysqli_query($con, "SELECT p.*, c.nama_cabang 
                                FROM tb_pelatih p
                                INNER JOIN tb_cabang c ON p.id_cabang = c.id_cabang
                                WHERE p.id_cabang = '$id_cabang'
                                ORDER BY p.nama_pelatih ASC");

                                foreach ($pelatih as $p) { ?>
                                    <tr>
                                        <td><?= $no++; ?>.</td>
                                        <td><?= $p['nama_pelatih']; ?></td>
                                        <td>
                                            <!-- Tombol Lihat Detail Karyawan -->
                                            <a href="?page=presensi&act=detail_mengajar&id=<?= $p['id_pelatih']; ?>" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
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