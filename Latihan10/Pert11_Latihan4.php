<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_bukutamu");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mengambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$komentar = $_POST['komentar'];

// Query insert
$sql = "INSERT INTO buku_tamu (nama, email, komentar, tanggal) 
        VALUES ('$nama', '$email', '$komentar', NOW())";

// Eksekusi query
if (mysqli_query($conn, $sql)) {
    echo "<h3>✅ Data berhasil disimpan!</h3>";
} else {
    echo "<h3>❌ Gagal menyimpan: " . mysqli_error($conn) . "</h3>";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simpan Data</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        a { 
            display: inline-block; 
            margin: 10px 10px 0 0;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <a href="Pert11_Latihan3.php">Isi Lagi</a>
    <a href="Pert11_Latihan5.php">Lihat Buku Tamu</a>
    <a href="Pert11_Latihan2.php">Menu Utama</a>
</body>
</html>