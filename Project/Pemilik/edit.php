<?php
$edit = mysqli_query($con, "SELECT * FROM tb_admin WHERE id_admin='$_GET[id]' ");
$d = mysqli_fetch_assoc($edit);

$status = $d['status'];

?>

<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">Edit Admin</h4>
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
				<a href="#">Edit Admin</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					<h3 class="h4">Form Edit Admin</h3>
				</div>
				<div class="card-body">
					<form action="?page=admin&act=proses" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="nama">Nama Admin</label>
							<input type="hidden" name="id" value="<?= $d['id_admin'] ?>">
							<input name="nama" type="text" class="form-control" value="<?= $d['nama_admin'] ?>">
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input name="email" type="email" class="form-control" value="<?= $d['email'] ?>">
						</div>
						<div class="form-group">
							<label for="telp">Nomor Telepon</label>
							<input name="telp" type="text" class="form-control" value="<?= $d['no_telp'] ?>">
						</div>

						<div class="form-group">
							<label for="status">Status</label>
							<select name="status" class="form-control">
								<option value="Aktif" <?= ($status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
								<option value="Tidak Aktif" <?= ($status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
							</select>
						</div>

						<td>
							<span class="badge <?= $badge_color ?>" style="background-color: <?= ($status == 'Aktif') ? '#28a745' : '#dc3545' ?>;"><?= $status_text ?></span>
						</td>
						<div class="form-group">
							<button name="editAdmin" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
							<a href="javascript:history.back()" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Batal</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>