<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="page-header">
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
			<a href="#">Akun Saya</a>
		</li>
	</ul>
</div>

<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Pengaturan Akun</h4>
		</div>
		<div class="card-body">
			<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
				<li class="nav-item">
					<a class="nav-link" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-selected="true">Ganti Password</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">Ganti Foto</a>
				</li>
			</ul>
			<div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
				<div class="tab-pane fade show" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
					<hr>
					<form action="" method="post">
						<div class="form-group">
							<input name="passLama" type="text" class="form-control" placeholder="Password Lama">
						</div>
						<div class="form-group">
							<input name="pass1" type="text" class="form-control" placeholder="Password Baru">
						</div>
						<div class="form-group">
							<button name="changePassword" type="submit" class="btn btn-success btn-block">Ganti Password</button>
						</div>

					</form>

					<?php
					if (isset($_POST['changePassword'])) {
						$passLama = $_POST['passLama'];
						$passBaru = $_POST['pass1'];

						// Ambil password lama dari database berdasarkan id_admin atau sesuai kebutuhan
						$query = mysqli_query($con, "SELECT password FROM tb_admin WHERE id_admin='$data[id_admin]'");
						$row = mysqli_fetch_assoc($query);
						$passDatabase = $row['password'];

						// Verifikasi password lama dengan password yang diinputkan menggunakan password_verify
						if (password_verify($passLama, $passDatabase)) {
							// Jika password lama cocok, hash password baru
							$newPass = password_hash($passBaru, PASSWORD_DEFAULT);

							// Update password baru ke dalam database
							$set = mysqli_query($con, "UPDATE tb_admin SET password='$newPass' WHERE id_admin='$data[id_admin]' ");

							if ($set) {
								// Berhasil mengubah password, tampilkan pesan sukses
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
                    title: 'Berhasil! Password berhasil diperbarui'
                });
                setTimeout(function () { 
                    window.location.replace('?page=akun');
                }, 3000);   
                </script>";
							} else {
								// Jika gagal update password, tampilkan pesan error
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
                    icon: 'error',
                    title: 'Gagal! Terjadi kesalahan saat mengubah password'
                });
                </script>";
							}
						} else {
							// Jika password lama tidak cocok, tampilkan pesan error
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
                icon: 'error',
                title: 'Gagal! Password lama tidak cocok'
            });
            </script>";
						}
					}
					?>


				</div>
				<div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
					<form action="" method="post" enctype="multipart/form-data">



						<div class="form-group">
							<label>Foto Profile</label>
							<p>
								<center>
									<img src="../assets/img/user/<?= $data['foto'] ?>" class="img-thumbnail" style="height: 90px;width: 90px;">

								</center>
							</p>
							<input type="file" name="foto">
							<input type="hidden" name="id" value="<?= $data['id_admin'] ?>">
						</div>
						<div class="form-group">

							<button name="updateProfile" type="submit" class="btn btn-primary btn-block">Simpan</button>
						</div>

					</form>
					<?php
					if (isset($_POST['updateProfile'])) {

						$gambar = @$_FILES['foto']['name'];
						if (!empty($gambar)) {
							move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/user/$gambar");
							$ganti = mysqli_query($con, "UPDATE tb_admin SET foto='$gambar' WHERE id_admin='$_POST[id]' ");
							if ($ganti) {
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
                          title: 'Berhasil! Foto berhasil diubah'
                        });
                        setTimeout(function () { 
                            window.location.replace('?page=akun');
                        }, 3000);   
                        </script>";
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<a href="javascript:history.back()" class="btn btn-default btn-block mb-1"><i class="fas fa-arrow-circle-left"></i> Kembali</a>