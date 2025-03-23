<?php
// Database connection
include('config.php');

// Filter data berdasarkan cabang yang dipilih
$filter_cabang = isset($_POST['filter_cabang']) ? $_POST['filter_cabang'] : '';

// Ambil nama cabang yang dipilih dengan prepared statement
$selectedCabangName = 'Semua Cabang';
if (!empty($filter_cabang)) {
  $stmt = $con->prepare("SELECT nama_cabang FROM tb_cabang WHERE id_cabang = ?");
  $stmt->bind_param('s', $filter_cabang);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $selectedCabangName = $row['nama_cabang'];
  }
  $stmt->close();
}

// Query untuk mendapatkan total jumlah admin
$query_jumlah_admin = "SELECT COUNT(*) AS jumlah FROM tb_admin";
if (!empty($filter_cabang)) {
  $query_jumlah_admin .= " WHERE id_cabang = ?";
}
$stmt = $con->prepare($query_jumlah_admin);
if (!empty($filter_cabang)) {
  $stmt->bind_param('s', $filter_cabang);
}
$stmt->execute();
$result = $stmt->get_result();
$jumlah = $result->fetch_assoc()['jumlah'];
$stmt->close();

// Query untuk mendapatkan data admin
$query = "SELECT p.*, c.nama_cabang FROM tb_admin p INNER JOIN tb_cabang c ON p.id_cabang = c.id_cabang";
if (!empty($filter_cabang)) {
  $query .= " WHERE p.id_cabang = ?";
}
$query .= " ORDER BY p.id_admin ASC";
$stmt = $con->prepare($query);
if (!empty($filter_cabang)) {
  $stmt->bind_param('s', $filter_cabang);
}
$stmt->execute();
$admin = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Data Admin</title>
  <!-- Include your CSS and JS files here -->
</head>

<body>
  <div class="page-inner">
    <div class="page-header">
      <h4 class="page-title">Data Admin</h4>
      <ul class="breadcrumbs">
        <li class="nav-home">
          <a href="dashboard.php">
            <i class="flaticon-home"></i>
          </a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-sm-4 col-md-4">
        <div class="card card-stats card-default card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-user-tie"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">Total Admin </p>
                  <h4 class="card-title"><?= $jumlah ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Filter Data Admin</div>
          </div>
          <div class="card-body">
            <form method="POST" action="">
              <div class="form-group">
                <select class="form-control" id="filter_cabang" name="filter_cabang">
                  <option value="">Pilih Cabang</option>
                  <?php
                  $cabangs = mysqli_query($con, "SELECT * FROM tb_cabang");
                  while ($cabang = mysqli_fetch_assoc($cabangs)) {
                    $selected = ($filter_cabang == $cabang['id_cabang']) ? 'selected' : '';
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
        </div>
        <div class="card">
          <div class="card-header">
            <a href="?page=admin&act=add" class="btn btn-primary btn-sm text-white"><i class="fa fa-plus"></i> Tambah Admin</a>
          </div>
          <div class="card-body">
            <div class="alert alert-info" role="alert">
              Menampilkan Data Admin untuk <?= $selectedCabangName ?>.
            </div>
            <div class="table-responsive" style="overflow-x: auto;">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th style="background-color: #47506D; color: white;">No</th>
                    <th style="background-color: #47506D; color: white;">Nama Admin</th>
                    <th style="background-color: #47506D; color: white;">No WhatsApp</th>
                    <th style="background-color: #47506D; color: white;">Cabang</th>
                    <th style="background-color: #47506D; color: white;">Status</th>
                    <th style="background-color: #47506D; color: white;">Foto</th>
                    <th style="background-color: #47506D; color: white;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  while ($p = $admin->fetch_assoc()) { ?>
                    <tr>
                      <td><?= $no++; ?>.</td>
                      <td><?= $p['nama_admin']; ?></td>
                      <td><?= $p['no_telp']; ?></td>
                      <td><?= $p['nama_cabang']; ?></td>
                      <td>
                        <?php if ($p['status'] == 'Aktif') : ?>
                          <span class="badge badge-success" style="background-color: #28a745;">Aktif</span>
                        <?php else : ?>
                          <span class="badge badge-danger" style="background-color: #dc3545;">Tidak Aktif</span>
                        <?php endif; ?>
                      </td>
                      <td><img src="../assets/img/user/<?= $p['foto'] ?>" width="45" height="45"></td>
                      <td>
                        <a class="btn btn-info btn-sm" href="?page=admin&act=edit&id=<?= $p['id_admin'] ?>"><i class="far fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm btn-delete-admin" data-id="<?= $p['id_admin'] ?>"><i class="fas fa-trash"></i></button>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan event delegation untuk menangani klik pada semua tombol hapus
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete-admin')) {
          e.preventDefault();

          const adminId = e.target.getAttribute('data-id');

          // Tampilkan peringatan SweetAlert dengan gambar sebagai ikon di atas judul dan teks
          Swal.fire({
            title: 'Anda yakin ingin mengahapus?',
            text: 'Anda tidak akan dapat mengembalikan ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus itu!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              // Redirect ke halaman penghapusan dengan parameter ID
              window.location.href = `?page=admin&act=del&id=${adminId}`;
            }
          });
        }
      });
    });
  </script>