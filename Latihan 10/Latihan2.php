<?php
// koneksi ke server MySQL
$conn = mysqli_connect("localhost", "root", "");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// nama database
$dbname = "Pemrogramanweb2";

// query buat database
$sql = "CREATE DATABASE $dbname";

// eksekusi query
if (mysqli_query($conn, $sql)) {
    echo "Database $dbname berhasil dibuat";
} else {
    echo "Gagal membuat database: " . mysqli_error($conn);
}

// tutup koneksi
mysqli_close($conn);
?>