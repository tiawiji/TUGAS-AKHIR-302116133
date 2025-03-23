<div class="page-inner">
  <div class="page-header">
    <h4 class="page-title">Tambah Admin</h4>
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
        <a href="#">Data Admin</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Tambah Admin</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h3 class="h4">Form Entry Admin</h3>
        </div>
        <div class="card-body">
          <form action="?page=admin&act=proses" method="post" enctype="multipart/form-data">
            <table cellpadding="3" style="font-weight: bold;">
              <tr>
                <td>Nama Admin </td>
                <td>:</td>
                <td><input type="text" class="form-control" name="nama" placeholder="Nama lengkap" autofocus required></td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td><input type="email" class="form-control" name="email" placeholder="Masukkan email " required></td>
              </tr>
              <tr>
                <td>No WhatsApp</td>
                <td>:</td>
                <td>
                  <input type="text" class="form-control" name="telp" placeholder="Masukkan no WhatsApp " required maxlength="13" pattern="\d*">
                </td>
              </tr>
              <tr>
                <td>Cabang </td>
                <td>:</td>
                <td>
                  <select class="form-control" name="cabang" required>
                    <option value="" disabled selected>Pilih Cabang</option>
                    <?php
                    $sqlCabang = mysqli_query($con, "SELECT * FROM tb_cabang ORDER BY id_cabang ASC");
                    while ($cabang = mysqli_fetch_array($sqlCabang)) {
                      echo "<option value='$cabang[id_cabang]'>$cabang[nama_cabang]</option>";
                    }
                    ?>
                  </select>
                </td>
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
              <tr>
                <td colspan="3">
                  <button name="saveAdmin" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>