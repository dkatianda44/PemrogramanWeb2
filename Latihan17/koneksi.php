<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "pemrogramanweb2"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "Koneksi Berhasil";

?>