<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">Laporan Gaji </h4>
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
				<a href="#">Laporan Gaji </a>
			</li>
		</ul>
	</div>
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header" style="background-color:#1572e8;">
					<div class="card-title" style="color:white;">Filter Laporan Gaji </div>
				</div>
				<div class="card-body">
					<form action="modul/laporan/cetak_gaji.php" method="GET" target="_blank" onsubmit="return checkFilters();">
						<table cellpadding="4" style="width: 100%; font-weight: bold;">
							<tr>
								<td>Cabang </td>
								<td>
									<select class="form-control" name="cabang" required id="cetak_cabang">
										<option value="" disabled selected>Pilih Cabang</option>
										<?php
										$sqlCabang = mysqli_query($con, "SELECT * FROM tb_cabang ORDER BY id_cabang ASC");
										while ($cabang = mysqli_fetch_array($sqlCabang)) {
											echo "<option value='" . $cabang['id_cabang'] . "'>" . $cabang['nama_cabang'] . "</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Bulan </td>
								<td>
									<select name="bulan" class="form-control" id="cetak_bulan">
										<option value="" disabled selected>Pilih Bulan</option>
										<option value="01">Januari</option>
										<option value="02">Februari</option>
										<option value="03">Maret</option>
										<option value="04">April</option>
										<option value="05">Mei</option>
										<option value="06">Juni</option>
										<option value="07">Juli</option>
										<option value="08">Agustus</option>
										<option value="09">September</option>
										<option value="10">Oktober</option>
										<option value="11">November</option>
										<option value="12">Desember</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Tahun </td>
								<td>
									<select name="tahun" class="form-control" id="cetak_tahun">
										<option value="" disabled selected>Pilih Tahun</option>
										<?php
										$current_year = date('Y');
										for ($year = $current_year; $year >= 2024; $year--) {
											$selected = ($year == $filter_tahun) ? 'selected' : '';
											echo "<option value='$year' $selected>$year</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center;">
									<button type="button" class="btn btn-primary btn-block" onclick="printPDF()">
										<i class="fa fa-print"></i> Cetak Laporan Gaji
									</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function printPDF() {
		let cabang = document.querySelector('select[name="cabang"]').value;
		let bulan = document.querySelector('select[name="bulan"]').value;
		let tahun = document.querySelector('select[name="tahun"]').value;

		// Periksa apakah cabang, bulan, dan tahun sudah dipilih
		if (!cabang || !bulan || !tahun) {
			alert("Silakan pilih cabang, bulan, dan tahun sebelum mencetak.");
			return false; // Menghentikan proses pencetakan jika cabang, bulan atau tahun belum dipilih
		}

		// Buka halaman pencetakan
		window.open("modul/laporan/cetak_gaji.php?cabang=" + cabang + "&bulan=" + bulan + "&tahun=" + tahun, "_blank");
	}
</script>