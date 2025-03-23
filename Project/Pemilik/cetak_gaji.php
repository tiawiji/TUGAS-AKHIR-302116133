<?php
// Mulai output buffering
ob_start();

require __DIR__ . '/../../vendor/autoload.php';

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

    return $tgl . ' ' . $namaBulan . ' ' . $tahun;
}

// Mendapatkan data dari form GET
$id_cabang = $_GET['cabang'];
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Ambil nama cabang
$query_cabang = mysqli_query($con, "SELECT nama_cabang FROM tb_cabang WHERE id_cabang = '$id_cabang'");
$cabang = mysqli_fetch_assoc($query_cabang)['nama_cabang'];

// Ambil total pertemuan dan total gaji
$query_totals = "SELECT 
        COUNT(pr.id_presensi) AS total_pertemuan,
        CONCAT('Rp', FORMAT(SUM(k.harga), 0)) AS total_gaji_keseluruhan
    FROM 
        tb_pelatih p
    JOIN 
        tb_mengajar m ON p.id_pelatih = m.id_pelatih
    JOIN 
        tb_presensi pr ON m.id_mengajar = pr.id_mengajar
    JOIN 
        tb_kelas k ON m.id_kelas = k.id_kelas
    WHERE 
        p.id_cabang = '$id_cabang'";

if (!empty($filter_bulan) && !empty($filter_tahun)) {
    $query_totals .= " AND MONTH(pr.tgl_presensi) = '$filter_bulan' AND YEAR(pr.tgl_presensi) = '$filter_tahun'";
}

$result_totals = mysqli_query($con, $query_totals);
$totals = mysqli_fetch_assoc($result_totals);

// Ambil data detail
$query_gaji = "SELECT
        p.nama_pelatih,
        COUNT(pr.id_presensi) AS jumlah_pertemuan,
        CONCAT('Rp', FORMAT(SUM(k.harga), 0)) AS total_gaji
    FROM
        tb_pelatih p
    JOIN
        tb_mengajar m ON p.id_pelatih = m.id_pelatih
    JOIN
        tb_presensi pr ON m.id_mengajar = pr.id_mengajar
    JOIN
        tb_kelas k ON m.id_kelas = k.id_kelas
    WHERE
        p.id_cabang = '$id_cabang'";

if (!empty($filter_bulan) && !empty($filter_tahun)) {
    $query_gaji .= " AND MONTH(pr.tgl_presensi) = '$filter_bulan' AND YEAR(pr.tgl_presensi) = '$filter_tahun'";
}

$query_gaji .= " GROUP BY p.nama_pelatih ORDER BY p.nama_pelatih";

$result_gaji = mysqli_query($con, $query_gaji);

// Membuat konten HTML untuk laporan PDF
$bulan = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

if (mysqli_num_rows($result_gaji) == 0) {
    echo "Tidak ada data yang tersedia untuk filter yang dipilih.";
    exit();
}

$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Gaji</title>
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
            <h3>Daftar Gaji Pelatih</h3>
        </div>
        <div class="totals">
            <p><strong>Cabang:</strong> ' . $cabang . '</p>
            <p><strong>Bulan:</strong> ' . $bulan[$filter_bulan] . '</p>
            <p><strong>Tahun:</strong> ' . $filter_tahun . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelatih</th>
                    <th>Jumlah Pertemuan</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>';
$no = 1;
while ($row = mysqli_fetch_assoc($result_gaji)) {
    $html .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $row['nama_pelatih'] . '</td>
                    <td>' . $row['jumlah_pertemuan'] . '</td>
                    <td>' . $row['total_gaji'] . '</td>
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

// Buat nama file dinamis
$nama_file = "Daftar Gaji " . str_replace(' ', ' ', strtolower($cabang)) . " " . $bulan[$filter_bulan] . " " . $filter_tahun . ".pdf";

// Set header untuk PDF
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=$nama_file");

// Keluarkan hasil PDF
echo $dompdf->output();

// Tutup koneksi database
$con->close();
