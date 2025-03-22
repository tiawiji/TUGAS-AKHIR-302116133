<div class="page-inner">
  <div class="page-header">
    <h4 class="page-title">Data Siswa</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="index.php">
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
                <i class="fas fa-user"></i>
              </div>
            </div>
            <div class="col-7 col-stats">
              <div class="numbers">
                <p class="card-category">Total Siswa</p>
                <h4 class="card-title"><?php echo $jumlahSiswa; ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <a href="?page=siswa&act=add" class="btn btn-primary btn-sm text-white">
              <i class="fa fa-plus"></i> Tambah Siswa
            </a>
          </div>
        </div>
        <div class="card-body">
          <?php
          $data_to_export = [];
          $no = 1;
          $siswa = mysqli_query($con, "SELECT s.*, k.kelas, c.nama_cabang
        FROM tb_siswa s
        INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
        INNER JOIN tb_cabang c ON s.id_cabang = c.id_cabang
        WHERE s.id_cabang = '$id_cabang'
        ORDER BY s.status ASC"); // Mengurutkan berdasarkan nama_siswa secara ascending

          while ($s = mysqli_fetch_assoc($siswa)) {
            $data_to_export[] = [
              'no' => $no,
              'nama_siswa' => $s['nama_siswa'],
              'kelas' => $s['kelas'],
              'umur' => $s['umur'],
              'jk' => $s['jk'],
              'no_telp' => $s['no_telp'],
              'nama_cabang' => $s['nama_cabang'],
              'status' => $s['status'],
              'foto' => $s['foto'],
              'id_siswa' => $s['id_siswa']
            ];
            $no++;
          }
          ?>

          <form id="export-form" action="modul/siswa/cetak.php" method="POST">
            <input type="hidden" name="id" value="<?= $id_cabang ?>">
            <input type="hidden" name="data_to_export" value="<?= htmlspecialchars(json_encode($data_to_export)); ?>">
            <button type="submit" class="btn btn-success">
              <i class="fa fa-file-excel-o"></i> Export to Excel
            </button>
          </form>
          <br>
          <div class="table-responsive" style="overflow-x: auto;">
            <table id="basic-datatables" class="display table table-striped table-hover">
              <thead>
                <tr>
                  <th style="background-color: #47506D; color: white;">No</th>
                  <th style="background-color: #47506D; color: white;">Nama Siswa</th>
                  <th style="background-color: #47506D; color: white;">Kelas</th>
                  <th style="background-color: #47506D; color: white;">Umur</th>
                  <th style="background-color: #47506D; color: white;">Jenis Kelamin</th>
                  <th style="background-color: #47506D; color: white;">No WhatsApp</th>
                  <th style="background-color: #47506D; color: white;">Cabang</th>
                  <th style="background-color: #47506D; color: white;">Status</th>
                  <th style="background-color: #47506D; color: white;">Foto</th>
                  <th style="background-color: #47506D; color: white;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data_to_export as $s) { ?>
                  <tr>
                    <td><?= $s['no']; ?>.</td>
                    <td><?= $s['nama_siswa']; ?></td>
                    <td><?= $s['kelas']; ?></td>
                    <td><?= $s['umur']; ?></td>
                    <td><?= $s['jk']; ?></td>
                    <td><?= $s['no_telp']; ?></td>
                    <td><?= $s['nama_cabang']; ?></td>
                    <td>
                      <?php if ($s['status'] == 'Aktif') : ?>
                        <span class="badge badge-success" style="background-color: #28a745;">Aktif</span>
                      <?php else : ?>
                        <span class="badge badge-danger" style="background-color: #dc3545;">Tidak Aktif</span>
                      <?php endif; ?>
                    </td>
                    <td><img src="../assets/img/user/<?= $s['foto'] ?>" width="45" height="45"></td>
                    <td>
                      <a class="btn btn-info btn-sm" href="?page=siswa&act=edit&id=<?= $s['id_siswa'] ?>"><i class="far fa-edit"></i></a>
                      <button class="btn btn-danger btn-sm btn-delete-siswa" data-id="<?= $s['id_siswa'] ?>"><i class="fas fa-trash"></i></button>
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
      if (e.target.classList.contains('btn-delete-siswa')) {
        e.preventDefault();

        const siswaId = e.target.getAttribute('data-id');

        // Tampilkan peringatan SweetAlert dengan gambar sebagai ikon di atas judul dan teks
        Swal.fire({
          title: 'Anda yakin ingin menghapus?',
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
            window.location.href = `?page=siswa&act=del&id=${siswaId}`;
          }
        });
      }
    });
  });
</script>