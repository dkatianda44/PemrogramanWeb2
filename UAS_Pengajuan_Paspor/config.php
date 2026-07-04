<?php
// ==============================
// KONFIGURASI KONEKSI DATABASE
// ==============================
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "db_paspor";

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error() .
        "<br>Pastikan MySQL aktif dan database 'db_paspor' sudah dibuat (import database.sql).");
}

mysqli_set_charset($koneksi, "utf8mb4");
?>
