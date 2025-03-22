<?php
$id_admin = $_SESSION['admin'];
$query_cabang_admin = mysqli_query($con, "SELECT id_cabang FROM tb_admin WHERE id_admin = '$id_admin'");
$data_cabang_admin = mysqli_fetch_assoc($query_cabang_admin);
$id_cabang_admin = $data_cabang_admin['id_cabang'];

// Ambil nama cabang admin
$query_nama_cabang_admin = mysqli_query($con, "SELECT nama_cabang FROM tb_cabang WHERE id_cabang = '$id_cabang_admin'");
$data_nama_cabang_admin = mysqli_fetch_assoc($query_nama_cabang_admin);
$nama_cabang_admin = $data_nama_cabang_admin['nama_cabang'];
?>
<div class="page-inner">
  <div class="page-header">
    <h4 class="page-title">Tambah Siswa</h4>
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
        <a href="#">Data Siswa</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Tambah Siswa</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h3 class="h4">Form Entry Siswa</h3>
        </div>
        <div class="card-body">
          <form action="?page=siswa&act=proses" method="post" enctype="multipart/form-data">

            <table cellpadding="3" style="font-weight: bold;">
              <tr>
                <td>Nama Siswa </td>
                <td>:</td>
                <td><input type="text" class="form-control" name="nama" placeholder="Nama lengkap" autofocus required></td>
              </tr>
              <tr>
                <td>Kelas </td>
                <td>:</td>
                <td>
                  <select class="form-control" name="kelas" required>
                    <option value="" disabled selected>Pilih kelas</option>
                    <?php
                    $sqlKelas = mysqli_query($con, "SELECT * FROM tb_kelas ORDER BY id_kelas ASC");
                    while ($kelas = mysqli_fetch_array($sqlKelas)) {
                      echo "<option value='$kelas[id_kelas]'>$kelas[kelas]</option>";
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>Umur </td>
                <td>:</td>
                <td><input type="text" class="form-control" name="umur" placeholder="Umur siswa" required></td>
              </tr>

              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                  <select class="form-control" name="jk">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </td>
              </tr>

              <tr>
                <td>Cabang </td>
                <td>:</td>
                <td>
                  <input type="text" class="form-control" name="cabang" value="<?php echo $nama_cabang_admin; ?>" readonly>
                  <input type="hidden" name="cabang" value="<?php echo $id_cabang_admin; ?>">
                </td>

              <tr>
                <td>No WhatsApp</td>
                <td>:</td>
                <td>
                  <input type="text" class="form-control" name="telp" placeholder="Masukkan no WhatsApp " required maxlength="13" pattern="\d*">
                </td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td><input type="email" class="form-control" name="email" placeholder="Masukkan email " required></td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                  <select name="status" class="form-control" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                  </select>
                </td>
              </tr>
              <!-- <tr>
                <td>Pas Foto</td>
                <td>:</td>
                <td><input type="file" class="form-control" name="foto"></td>
              </tr> -->
              <tr>
                <td colspan="3">
                  <button name="saveSiswa" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                  <a href="javascript:history.back()" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Batal</a>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>