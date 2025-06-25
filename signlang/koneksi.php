<?php
$servername = "localhost"; // Biasanya localhost untuk XAMPP
$username = "root";      // Username default XAMPP
$password = "";          // Password default XAMPP (kosong)
$dbname = "c300_test4"; // Nama database yang Anda buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil!"; // Anda bisa mengaktifkan ini untuk menguji koneksi
