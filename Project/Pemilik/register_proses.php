<?php
include '../config/db.php';

// Detail pemilik baru
$nama_lengkap = "Melni Sartika Sari C.HydroT";
$email = "melni@gmail.com";
$password_plain = "melni@gmail.com"; // Password asli
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT); // Hash password
$aktif = "1";
$foto = ""; // Jika ada URL atau path ke foto, tambahkan di sini

// Buat query
$sql = "INSERT INTO tb_pemilik (nama_lengkap, email, password, aktif, foto) VALUES (?, ?, ?, ?, ?)";

// Siapkan statement
$stmt = $con->prepare($sql);

// Bind parameter
$stmt->bind_param("sssss", $nama_lengkap, $email, $password_hashed, $aktif, $foto);

// Eksekusi statement
if ($stmt->execute()) {
    echo "Data pemilik baru berhasil ditambahkan.";
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}

// Tutup statement dan koneksi
$stmt->close();
$con->close();
