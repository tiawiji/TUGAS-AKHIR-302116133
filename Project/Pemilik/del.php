<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$del = mysqli_query($con, "DELETE FROM tb_admin WHERE id_admin=$_GET[id]");
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
                            window.location='?page=admin';
                        }, 3000);   
                        </script>";
}
