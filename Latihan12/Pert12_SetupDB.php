<?php
// Koneksi ke MySQL tanpa memilih database dulu
$host = "localhost";
$user = "root";
$pass = "";

$con = mysqli_connect($host, $user, $pass);

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1. Buat database artikel_db
$sql_db = "CREATE DATABASE IF NOT EXISTS artikel_db";
if (mysqli_query($con, $sql_db)) {
    echo "✅ Database 'artikel_db' berhasil dibuat.<br>";
} else {
    echo "❌ Error membuat database: " . mysqli_error($con) . "<br>";
}

// 2. Buat database lat_dbase (untuk latihan 1 & 2)
$sql_db2 = "CREATE DATABASE IF NOT EXISTS lat_dbase";
if (mysqli_query($con, $sql_db2)) {
    echo "✅ Database 'lat_dbase' berhasil dibuat.<br>";
} else {
    echo "❌ Error membuat database: " . mysqli_error($con) . "<br>";
}

// 3. Pilih database lat_dbase dan buat tabel tbl_mhs
mysqli_select_db($con, "lat_dbase");

$sql_tbl_mhs = "CREATE TABLE IF NOT EXISTS tbl_mhs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Age INT
)";

if (mysqli_query($con, $sql_tbl_mhs)) {
    echo "✅ Tabel 'tbl_mhs' berhasil dibuat.<br>";
} else {
    echo "❌ Error membuat tabel: " . mysqli_error($con) . "<br>";
}

// 4. Insert data contoh untuk latihan UPDATE & DELETE
mysqli_query($con, "INSERT INTO tbl_mhs (FirstName, LastName, Age) VALUES 
    ('Karina', 'Suwandi', 25),
    ('Budi', 'Prabowo', 28)
");

echo "✅ Data contoh berhasil ditambahkan.<br>";

// 5. Pilih database artikel_db dan buat tabel artikel
mysqli_select_db($con, "artikel_db");

$sql_tbl_artikel = "CREATE TABLE IF NOT EXISTS artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    isi TEXT,
    tanggal DATE
)";

if (mysqli_query($con, $sql_tbl_artikel)) {
    echo "✅ Tabel 'artikel' berhasil dibuat.<br>";
} else {
    echo "❌ Error membuat tabel: " . mysqli_error($con) . "<br>";
}

mysqli_close($con);
echo "<br>🎉 Setup selesai! Sekarang bisa jalankan file latihan lainnya.";
?>