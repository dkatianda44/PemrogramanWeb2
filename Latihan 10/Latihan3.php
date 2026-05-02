<?php
// koneksi ke MySQL
$conn = mysqli_connect("localhost", "root", "", "Pemrogramanweb2");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// query buat tabel
$sql = "CREATE TABLE IF NOT EXISTS tbl_mhs (
    mhsID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(15),
    LastName VARCHAR(15),
    Age INT,
    PRIMARY KEY (mhsID)
)";

// eksekusi pembuatan tabel
if (mysqli_query($conn, $sql)) {
    echo "Tabel berhasil dibuat <br>";
} else {
    echo "Error membuat tabel: " . mysqli_error($conn) . "<br>";
}

// input data
$insert = "INSERT INTO tbl_mhs (FirstName, LastName, Age)
           VALUES ('Anjar', 'Prabowo', 25)";

if (mysqli_query($conn, $insert)) {
    echo "Data berhasil ditambahkan";
} else {
    echo "Error insert data: " . mysqli_error($conn);
}

// tutup koneksi
mysqli_close($conn);
?>