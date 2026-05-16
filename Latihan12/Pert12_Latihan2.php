<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lat_dbase";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query DELETE
$sql = "DELETE FROM tbl_mhs WHERE LastName = 'Prabowo'";

if (mysqli_query($con, $sql)) {
    echo "Data berhasil dihapus.";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>