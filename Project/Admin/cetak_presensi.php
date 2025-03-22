<?php
// Mulai output buffering
ob_start();

require __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($con)) {
    $con = mysqli_connect("localhost", "root", "", "db_presen");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
}

// Fungsi untuk memformat tanggal ke dalam bahasa Indonesia
function formatTanggalIndonesia($tanggal)
{

    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $namaBulan = $bulan[date('F', strtotime($tanggal))];
    $tgl = date('d', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    return  $tgl . ' ' . $namaBulan . ' ' . $tahun;
}

// Mendapatkan id siswa dan id cabang dari URL
$id_siswa = isset($_GET['id_siswa']) ? intval($_GET['id_siswa']) : 0;
$id_cabang = isset($_GET['id_cabang']) ? intval($_GET['id_cabang']) : 0;

// Query untuk mengambil nama siswa, nama cabang, dan nama kelas
$query_info_siswa = "
    SELECT s.nama_siswa, c.nama_cabang, k.kelas AS nama_kelas
    FROM tb_siswa s
    INNER JOIN tb_cabang c ON s.id_cabang = c.id_cabang
    INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
    WHERE s.id_siswa = '{$id_siswa}' AND s.id_cabang = '{$id_cabang}'";

$result_info_siswa = mysqli_query($con, $query_info_siswa);
if ($row_info_siswa = mysqli_fetch_assoc($result_info_siswa)) {
    $nama_siswa = $row_info_siswa['nama_siswa'];
    $nama_cabang = $row_info_siswa['nama_cabang'];
    $nama_kelas = $row_info_siswa['nama_kelas'];
} else {
    echo "Data siswa tidak ditemukan.";
    exit();
}

// Query untuk mengambil data presensi harian siswa
$query_detail_harian = "
    SELECT 
        m.tanggal,
        j.pertemuan_ke,
        l.lokasi,
        p.nama_pelatih
    FROM 
        tb_jadwal_siswa j
    INNER JOIN 
        tb_mengajar m ON j.id_mengajar = m.id_mengajar
    INNER JOIN 
        tb_lokasi l ON m.id_lokasi = l.id_lokasi
    INNER JOIN 
        tb_pelatih p ON m.id_pelatih = p.id_pelatih
    WHERE 
        j.id_siswa = '{$id_siswa}'
    ORDER BY 
        m.tanggal ASC";

$result_detail_harian = mysqli_query($con, $query_detail_harian);

if (mysqli_num_rows($result_detail_harian) == 0) {
    echo "Tidak ada data presensi yang tersedia.";
    exit();
}

// Membuat konten HTML
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kehadiran Siswa</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            color: black;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header h2, .header h3 {
            margin: 5px 0;
        }
        .totals {
            margin-bottom: 20px;
        }
        .totals p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #47506D;
            color: white;
        }
        .footer-table {
            border: none;
            width: 100%;
            margin-top: 20px;
        }
        .footer-table td {
            border: none;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Marlin Swimming Club</h2>
            <h3>Daftar Kehadiran Siswa</h3>
            <br>
        </div>
        <div class="totals">
            <p><strong>Nama Siswa:</strong> ' . $nama_siswa . '</p>
            <p><strong>Kelas:</strong> ' . $nama_kelas . '</p>
            <p><strong>Cabang:</strong> ' . $nama_cabang . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pertemuan Ke</th>
                    <th>Nama Lokasi</th>
                    <th>Nama Pelatih</th>
                </tr>
            </thead>
            <tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result_detail_harian)) {
    $formatted_date = formatTanggalIndonesia($row['tanggal']);
    $html .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $formatted_date . '</td>
                    <td>' . $row['pertemuan_ke'] . '</td>
                    <td>' . $row['lokasi'] . '</td>
                    <td>' . $row['nama_pelatih'] . '</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
    </div>
</body>
</html>';

// Muat konten HTML ke Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Atur ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Bersihkan output buffering sebelum mengirim header
ob_end_clean();

// Nama file PDF menggunakan nama siswa
$nama_file = 'Daftar Kehadiran ' . str_replace(' ', '_', $nama_siswa) . '.pdf';

// Set header untuk PDF
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename={$nama_file}");

// Keluarkan hasil PDF
echo $dompdf->output();

// Tutup koneksi database
$con->close();
