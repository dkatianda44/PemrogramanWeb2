<?php
// ============================================================
//  config.php  —  Konfigurasi koneksi database
//  Sesuaikan nilai di bawah dengan pengaturan MySQL Anda
// ============================================================

define('DB_HOST',   'localhost');
define('DB_USER',   'root');       // ganti jika bukan root
define('DB_PASS',   '');           // isi password MySQL Anda
define('DB_NAME',   'db_mahasiswa');
define('DB_CHARSET','utf8mb4');

// Buat koneksi MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Atur charset
$conn->set_charset(DB_CHARSET);

// Cek koneksi
if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;color:#c0392b;padding:20px;">
            <strong>Koneksi database gagal!</strong><br>
            ' . htmlspecialchars($conn->connect_error) . '<br><br>
            Pastikan:<br>
            1. MySQL / XAMPP sudah berjalan<br>
            2. Database <em>db_mahasiswa</em> sudah dibuat (jalankan <em>database.sql</em>)<br>
            3. Username & password di <em>config.php</em> sudah benar
         </div>');
}
