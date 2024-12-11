<?php
// Konfigurasi Database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'DesainHub';

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mulai sesi
session_start();

// Menentukan path asset
define('BASE_URL', 'http://localhost/desainhub/');
define('ASSETS_PATH_IMG', BASE_URL . 'assets/images/');
define('ASSETS_PATH_PAGES', BASE_URL . 'pages/');
?>
