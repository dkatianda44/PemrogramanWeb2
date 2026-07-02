<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "password_salah",
    "pemrogramanweb2"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>