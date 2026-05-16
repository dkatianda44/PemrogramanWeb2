<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lat_dbase";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query UPDATE
$sql = "UPDATE tbl_mhs SET Age = '36' 
        WHERE FirstName = 'Karina' AND LastName = 'Suwandi'";

if (mysqli_query($con, $sql)) {
    echo "Data berhasil diupdate.";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>