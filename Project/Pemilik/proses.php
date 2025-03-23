<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
if (isset($_POST['saveAdmin'])) {
    session_start();
    $email = $_POST['email'];
    $password = password_hash($email, PASSWORD_DEFAULT);

    // Cek apakah email sudah ada di tb_admin
    $checkEmailAdmin = mysqli_query($con, "SELECT * FROM tb_admin WHERE email = '$email'");
    if (mysqli_num_rows($checkEmailAdmin) > 0) {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email sudah digunakan untuk admin, silakan gunakan email lain.',
                showConfirmButton: false,
                timer: 3000
            });
            setTimeout(function () { 
                window.location.replace('?page=admin&act=add');
            }, 3000);
            </script>";
        exit;
    }

    // Cek apakah email sudah ada di tb_pelatih
    $checkEmailPelatih = mysqli_query($con, "SELECT * FROM tb_pelatih WHERE email = '$email'");
    if (mysqli_num_rows($checkEmailPelatih) > 0) {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Email sudah digunakan untuk pelatih, silakan gunakan email lain.',
                showConfirmButton: false,
                timer: 3000
            });
            setTimeout(function () { 
                window.location.replace('?page=admin&act=add');
            }, 3000);
            </script>";
        exit;
    }

    // Jika email belum digunakan di tb_admin maupun tb_pelatih, lanjutkan untuk menyimpan data admin baru
    $save = mysqli_query($con, "INSERT INTO tb_admin (nama_admin, email, no_telp, id_cabang, status, password)
        VALUES ('$_POST[nama]', '$email', '$_POST[telp]', '$_POST[cabang]', '$_POST[status]', '$password')");
    if ($save) {
        echo "
            <script>
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
              title: 'Data berhasil disimpan'
            });
            setTimeout(function () { 
                window.location.replace('?page=admin');
            }, 3000);   
            </script>";
    } else {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Terjadi kesalahan saat menyimpan data admin.',
                showConfirmButton: false,
                timer: 3000
            });
            </script>";
    }
}

if (isset($_POST['editAdmin'])) {
    $email = $_POST['email'];
    $password = password_hash($email, PASSWORD_DEFAULT); // Buat hash baru dari email sebagai password

    $editAdmin = mysqli_query($con, "UPDATE tb_admin SET 
        nama_admin='$_POST[nama]',
        email='$email',
        no_telp='$_POST[telp]',
        status='$_POST[status]',
        password='$password' WHERE id_admin='$_POST[id]' ");

    if ($editAdmin) {
        echo "
            <script>
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
              title: 'Data berhasil diubah'
            });
            setTimeout(function () { 
                window.location.replace('?page=admin');
            }, 3000);   
            </script>";
    } else {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Terjadi kesalahan saat mengubah data admin.',
                showConfirmButton: false,
                timer: 3000
            });
            </script>";
    }
}
?>