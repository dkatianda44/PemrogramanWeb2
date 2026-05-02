<?php
// Koneksi ke MySQL tanpa database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "Koneksi ke MySQL berhasil<br>";

// Membuat database
$dbname = "db_bukutamu";
$query = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $query)) {
    echo "Database '$dbname' berhasil dibuat<br>";
} else {
    echo "Gagal membuat database: " . mysqli_error($conn) . "<br>";
}

// Memilih database
mysqli_select_db($conn, $dbname);

// Membuat tabel buku_tamu
$sql_tabel = "CREATE TABLE IF NOT EXISTS buku_tamu (
    id INT(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(id),
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    komentar TEXT NOT NULL,
    tanggal DATETIME NOT NULL
)";

if (mysqli_query($conn, $sql_tabel)) {
    echo "Tabel 'buku_tamu' berhasil dibuat<br>";
} else {
    echo "Gagal membuat tabel: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
echo "<br><a href='Pert11_Latihan2.php'>Lanjut ke Aplikasi</a>";
?>