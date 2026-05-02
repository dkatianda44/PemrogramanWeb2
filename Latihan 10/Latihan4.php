<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "Pemrogramanweb2");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// query insert
$sql1 = "INSERT INTO tbl_mhs (FirstName, LastName, Age)
         VALUES ('Karina', 'Suwandi', 29)";

$sql2 = "INSERT INTO tbl_mhs (FirstName, LastName, Age)
         VALUES ('Glenn', 'Gandari', 32)";

// eksekusi query
if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)) {
    echo "Data berhasil ditambahkan";
} else {
    echo "Error: " . mysqli_error($conn);
}

// tutup koneksi
mysqli_close($conn);
?>