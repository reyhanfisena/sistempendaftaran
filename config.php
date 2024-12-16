<?php
$servername = "localhost"; // Nama host
$username = "root"; // Nama pengguna database
$password = ""; // Kata sandi database
$dbname = "sistempendaftaran"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
