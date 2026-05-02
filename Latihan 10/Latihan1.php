<?php
// hostname atau ip server
$servername = "localhost";

// username dan password
$dbusername = "root";
$dbpassword = "";

// membuat koneksi
$conn = mysqli_connect($servername, $dbusername, $dbpassword);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "ok....koneksi berhasil";
}
?>