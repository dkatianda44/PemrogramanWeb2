<?php
// 1. koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "Pemrogramanweb2");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. query ambil data
$sql = "SELECT * FROM tbl_mhs";
$result = mysqli_query($conn, $sql);

// cek query berhasil
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// 3. tampilkan data (INI BAGIAN while kamu)
while ($data = mysqli_fetch_assoc($result)) {
    echo $data['mhsID'] . " - " .
         $data['FirstName'] . " " .
         $data['LastName'] . " - " .
         $data['Age'] . "<br>";
}

// 4. tutup koneksi
mysqli_close($conn);
?>