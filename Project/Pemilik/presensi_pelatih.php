<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Rekap Presensi</h4>
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
                <a href="#">Presensi Pelatih</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <!-- Dropdown untuk filter cabang -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <select class="form-control" id="filter_cabang" name="filter_cabang">
                                <option value="">Semua Cabang</option>
                                <?php
                                $cabangs = mysqli_query($con, "SELECT * FROM tb_cabang");
                                while ($cabang = mysqli_fetch_assoc($cabangs)) {
                                    $selected = (isset($_POST['filter_cabang']) && $_POST['filter_cabang'] == $cabang['id_cabang']) ? 'selected' : '';
                                    echo "<option value='{$cabang['id_cabang']}' $selected>{$cabang['nama_cabang']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fa fa-eye"></i> Tampilkan Data
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        Menampilkan Presensi untuk
                        <?php
                        if (isset($_POST['filter_cabang']) && !empty($_POST['filter_cabang'])) {
                            $cabangTerpilih = mysqli_fetch_assoc(mysqli_query($con, "SELECT nama_cabang FROM tb_cabang WHERE id_cabang = '{$_POST['filter_cabang']}'"));
                            echo $cabangTerpilih['nama_cabang'];
                        } else {
                            echo "Semua Cabang";
                        }
                        ?>.
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="background-color: #47506D; color: white;">#</th>
                                    <th style="background-color: #47506D; color: white;">Nama Pelatih</th>
                                    <th style="background-color: #47506D; color: white;">Nama Cabang</th>
                                    <th style="background-color: #47506D; color: white;">Detail mengajar pelatih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (isset($_POST['filter_cabang']) && !empty($_POST['filter_cabang'])) {
                                    $id_cabang = $_POST['filter_cabang'];
                                    $query = "SELECT p.*, c.nama_cabang 
                                              FROM tb_pelatih p
                                              INNER JOIN tb_cabang c ON p.id_cabang = c.id_cabang
                                              WHERE p.id_cabang = '$id_cabang'
                                              ORDER BY p.nama_pelatih ASC";
                                } else {
                                    $query = "SELECT p.*, c.nama_cabang 
                                              FROM tb_pelatih p
                                              INNER JOIN tb_cabang c ON p.id_cabang = c.id_cabang
                                              ORDER BY p.nama_pelatih ASC";
                                }
                                $pelatih = mysqli_query($con, $query);

                                foreach ($pelatih as $p) { ?>
                                    <tr>
                                        <td><?= $no++; ?>.</td>
                                        <td><?= $p['nama_pelatih']; ?></td>
                                        <td><?= $p['nama_cabang']; ?></td>
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