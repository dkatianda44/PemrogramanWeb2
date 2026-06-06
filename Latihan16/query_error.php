<?php

mysqli_report(MYSQLI_REPORT_OFF);

include "koneksi.php";

$query = "SELECT * FROM mahasiswa";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

?>