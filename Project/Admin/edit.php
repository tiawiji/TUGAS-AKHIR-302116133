	<?php

	$edit = mysqli_query($con, "SELECT * FROM tb_siswa WHERE id_siswa='$_GET[id]' ");
	foreach ($edit as $d) ?>
	<div class="page-inner">
		<div class="page-header">
			<h4 class="page-title">Siswa</h4>
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
					<a href="#">Edit Siswa</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header d-flex align-items-center">
						<h3 class="h4">Form Edit Siswa</h3>
					</div>
					<div class="card-body">


						<form action="?page=siswa&act=proses" method="post" enctype="multipart/form-data">
							<input name="id" type="hidden" value="<?= $d['id_siswa'] ?>">

							<table cellpadding="3" style="font-weight: bold;">
								<tr>
									<td>Nama Siswa </td>
									<td>:</td>
									<td><input type="text" class="form-control" name="nama" value="<?= $d['nama_siswa'] ?>"></td>
								</tr>
								<tr>
									<td>Kelas Siswa</td>
									<td>:</td>
									<td>
										<select class="form-control" name="kelas">
											<option>Pilih Kelas</option>
											<?php
											$sqlKelas = mysqli_query($con, "SELECT * FROM tb_kelas
    ORDER BY id_kelas ASC");
											while ($kelas = mysqli_fetch_array($sqlKelas)) {

												if ($kelas['id_kelas'] == $d['id_kelas']) {
													$selected = "selected";
												} else {
													$selected = '';
												}
												echo "<option value='$kelas[id_kelas]' $selected>$kelas[kelas]</option>";
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td> Umur</td>
									<td>:</td>
									<td><input type="text" class="form-control" name="umur" value="<?= $d['umur'] ?>"></td>
								</tr>
								<tr>
									<td>Jenis Kelamin</td>
									<td>:</td>
									<td>
										<select class="form-control" name="jk">
											<option value="Laki-laki" <?= ($d['jk'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
											<option value="Perempuan" <?= ($d['jk'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>No WhatsApp</td>
									<td>:</td>
									<td><input type="text" class="form-control" name="telp" required maxlength="13" pattern="\d*" value="<?= $d['no_telp'] ?>"></td>
								</tr>
								<tr>
									<td>Email </td>
									<td>:</td>
									<td><input type="email" class="form-control" name="email" value="<?= $d['email'] ?>"></td>
								</tr>
								<tr>
									<td>Status</td>
									<td>:</td>
									<td>
										<select name="status" class="form-control">
											<option value="Aktif" <?= ($status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
											<option value="Tidak Aktif" <?= ($status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
										</select>
									</td>
								</tr>

								<td colspan="3">
									<button name="editSiswa" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
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