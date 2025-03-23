<?php
// Assuming you are using this script for deletion

// Assuming you are using this script for deletion

if (isset($_GET['id_jadwal_siswa']) && is_numeric($_GET['id_jadwal_siswa'])) {
  $id_jadwal_siswa = $_GET['id_jadwal_siswa'];
  $id_mengajar = $_GET['id_mengajar'];

  $del = mysqli_query($con, "DELETE FROM tb_jadwal_siswa WHERE id_jadwal_siswa=$id_jadwal_siswa");

  if ($del) {
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
              window.location='?page=jadwal&act=presensi_siswa&id_mengajar=$id_mengajar';
          }, 3000);   
      </script>";
  } else {
    echo "<script>
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Gagal menghapus data.'
          }).then(function() {
              window.location = '?page=jadwal&act=presensi_siswa&id_mengajar=$id_mengajar';
          });
      </script>";
  }
} else {
  echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Parameter ID jadwal siswa tidak ditemukan.'
      }).then(function() {
          window.location = '?page=jadwal&act=presensi_siswa&id_mengajar=$id_mengajar';
      });
  </script>";
}
