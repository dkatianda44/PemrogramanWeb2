<?php
// ============================================================
// Konfigurasi Koneksi Database
// Sesuaikan nilai di bawah dengan pengaturan server Anda
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'buku_tamu');
define('DB_USER', 'root');       // ganti dengan username MySQL Anda
define('DB_PASS', '');           // ganti dengan password MySQL Anda
define('DB_CHAR', 'utf8mb4');

// ============================================================
// Buat koneksi PDO (lebih aman dari mysqli biasa)
// ============================================================
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHAR
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Jangan tampilkan detail error di production
            die('<p style="color:red;font-family:sans-serif">
                Koneksi database gagal. Periksa konfigurasi di <code>config.php</code>.<br>
                Detail: ' . htmlspecialchars($e->getMessage()) . '
            </p>');
        }
    }

    return $pdo;
}
