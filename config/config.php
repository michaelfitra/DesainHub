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

// Mulai sesi jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/desainhub/');
}
if (!defined('ASSETS_PATH_IMG')) {
    define('ASSETS_PATH_IMG', BASE_URL . 'assets/images/');
}
if (!defined('ASSETS_PATH_PAGES')) {
    define('ASSETS_PATH_PAGES', BASE_URL . 'pages/');
}
?>
