<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "artikel_db";

// Koneksi ke MySQL dengan memilih database langsung
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connection) {
    die("❌ Tidak dapat terhubung dengan database '<b>$dbname</b>': " . mysqli_connect_error());
}

echo "✅ Koneksi ke database '<b>$dbname</b>' berhasil.";
?>