<?php
if (!isset($con)) {
    $con = mysqli_connect("localhost", "root", "", "db_presen");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
}

// Mendapatkan data dari form POST
$data_to_export = json_decode($_POST['data_to_export'], true);
$id_cabang = $_POST['id'];

// Mengambil nama cabang dari database
$cabang_query = mysqli_query($con, "SELECT nama_cabang FROM tb_cabang WHERE id_cabang = '$id_cabang'");
$cabang_data = mysqli_fetch_assoc($cabang_query);
$nama_cabang = $cabang_data['nama_cabang'] ?? 'Unknown';

// Membuat objek Spreadsheet baru
require_once '../../../assets/phpspreadsheet-master/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menyiapkan judul dan header kolom
$sheet->setCellValue('A1', 'Data Siswa')
    ->setCellValue('A2', 'Cabang: ' . $nama_cabang);

$sheet->mergeCells('A1:J1');
$sheet->mergeCells('A2:J2');

$sheet->setCellValue('A4', 'No')
    ->setCellValue('B4', 'Nama Siswa')
    ->setCellValue('C4', 'Kelas')
    ->setCellValue('D4', 'Umur')
    ->setCellValue('E4', 'Jenis Kelamin')
    ->setCellValue('F4', 'No WhatsApp')
    ->setCellValue('G4', 'Cabang')
    ->setCellValue('H4', 'Status');

// Style untuk header kolom
$headerStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FF47506D'
        ]
    ],
    'font' => [
        'color' => [
            'argb' => Color::COLOR_WHITE
        ],
        'bold' => true
    ]
];

$sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

// Mendefinisikan baris awal di Excel
$row = 5;

// Loop untuk mengambil data siswa
foreach ($data_to_export as $s) {
    $sheet->setCellValue('A' . $row, $s['no'])
        ->setCellValue('B' . $row, $s['nama_siswa'])
        ->setCellValue('C' . $row, $s['kelas'])
        ->setCellValue('D' . $row, $s['umur'])
        ->setCellValue('E' . $row, $s['jk'])

        ->setCellValue('F' . $row, $s['no_telp'])
        ->setCellValue('G' . $row, $s['nama_cabang'])
        ->setCellValue('H' . $row, $s['status']);

    $row++;
}

// Mengatur judul sheet
$sheet->setTitle('Data Siswa');

// Nama file dengan format Data_siswa_tahun.xls
$filename = 'Data_siswa_' . date('Y') . '.xls';

// Header untuk file Excel
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

// Simpan file Excel ke output
$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit;
