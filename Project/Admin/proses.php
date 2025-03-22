<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
if (isset($_POST['saveSiswa'])) {
    session_start();
    $email = $_POST['email'];
    $password = password_hash($email, PASSWORD_DEFAULT);

    // Cek apakah email sudah ada di tb_siswa
    $checkEmailSiswa = mysqli_query($con, "SELECT * FROM tb_siswa WHERE email = '$email'");
    if (mysqli_num_rows($checkEmailSiswa) > 0) {
        echo "
        <script>
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Email sudah digunakan, silakan gunakan email lain.',
            showConfirmButton: false,
            timer: 3000
        });
        setTimeout(function () { 
            window.location.replace('?page=siswa&act=add');
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
            window.location.replace('?page=siswa&act=add');
        }, 3000);
        </script>";
        exit;
    }

    // Cek apakah email sudah ada di tb_admin
    $checkEmailAdmin = mysqli_query($con, "SELECT * FROM tb_admin WHERE email = '$email'");
    if (mysqli_num_rows($checkEmailAdmin) > 0) {
        echo "
        <script>
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Email sudah digunakan, silakan gunakan email lain.',
            showConfirmButton: false,
            timer: 3000
        });
        setTimeout(function () { 
            window.location.replace('?page=siswa&act=add');
        }, 3000);
        </script>";
        exit;
    }

    $save = mysqli_query($con, "INSERT INTO tb_siswa (nama_siswa, email, no_telp, id_kelas, umur, jk, status, password, id_cabang)
        VALUES ('$_POST[nama]', '$email', '$_POST[telp]', '$_POST[kelas]', '$_POST[umur]','$_POST[jk]', '$_POST[status]', '$password', '$_POST[cabang]')");
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
                window.location.replace('?page=siswa');
            }, 3000);   
            </script>";
    } else {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Terjadi kesalahan saat menyimpan data siswa.',
                showConfirmButton: false,
                timer: 3000
            });
            </script>";
    }
}

if (isset($_POST['editSiswa'])) {
    $email = $_POST['email'];
    $password = password_hash($email, PASSWORD_DEFAULT); // Buat hash baru dari email sebagai password

    $editSiswa = mysqli_query($con, "UPDATE tb_siswa SET 
        nama_siswa='$_POST[nama]',
        jk='$_POST[jk]',
        email='$email',
        no_telp='$_POST[telp]',
        umur='$_POST[umur]',
        status='$_POST[status]',
        id_kelas='$_POST[kelas]',
        password='$password' WHERE id_siswa='$_POST[id]' ");

    if ($editSiswa) {
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
                window.location.replace('?page=siswa');
            }, 3000);   
            </script>";
    } else {
        echo "
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Terjadi kesalahan saat mengubah data siswa.',
                showConfirmButton: false,
                timer: 3000
            });
            </script>";
    }
}
?>