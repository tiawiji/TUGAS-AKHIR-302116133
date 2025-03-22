<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $siswaId = $_GET['id'];

  // Hapus terlebih dahulu data terkait di tabel tb_jadwal_siswa
  $delJadwal = mysqli_query($con, "DELETE FROM tb_jadwal_siswa WHERE id_siswa=$siswaId");

  if ($delJadwal) {
    // Setelah data terkait dihapus, barulah hapus siswa dari tb_siswa
    $delSiswa = mysqli_query($con, "DELETE FROM tb_siswa WHERE id_siswa=$siswaId");

    if ($delSiswa) {
      echo "<script>
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                        });
                        Toast.fire({
                          icon: 'success',
                          title: 'Data berhasil dihapus'
                        });
                        setTimeout(function () { 
                            window.location='?page=siswa';
                        }, 3000);   
                        </script>";
    } else {
      echo "<script>
                alert('Gagal menghapus data siswa!');
                window.location.href = '?page=siswa';
                </script>";
    }
  } else {
    echo "<script>
            alert('Gagal menghapus data terkait siswa!');
            window.location.href = '?page=siswa';
            </script>";
  }
} else {
  // Redirect jika tidak ada ID yang valid
  header('Location: ?page=siswa');
  exit;
}
