<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_bukutamu");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Menghitung total record
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM buku_tamu");
$row = mysqli_fetch_assoc($result);
$total_records = $row['total'];

// Konfigurasi pagination
$per_page = 5;
$total_pages = ceil($total_records / $per_page);

// Menentukan halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

// Menentukan offset
$offset = ($page - 1) * $per_page;

// Query mengambil data dengan limit
$sql = "SELECT * FROM buku_tamu ORDER BY id DESC LIMIT $offset, $per_page";
$hasil = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku Tamu</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        table { 
            border-collapse: collapse; 
            width: 100%;
            margin: 20px 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left;
        }
        th { background: #4CAF50; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .pagination { margin: 20px 0; }
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .pagination a:hover { background: #45a049; }
        .pagination .disabled {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            background: #ddd;
            color: #666;
            border-radius: 3px;
        }
        .info { margin: 10px 0; font-weight: bold; }
        .menu-link { margin-top: 20px; }
        .menu-link a { color: #4CAF50; margin-right: 15px; }
    </style>
</head>
<body>
    <h2>👀 Daftar Buku Tamu</h2>
    
    <div class="info">
        Total Data: <?php echo $total_records; ?> record
    </div>

    <table>
        <tr>
            <th width="5%">No</th>
            <th width="20%">Nama</th>
            <th width="20%">Email</th>
            <th width="40%">Komentar</th>
            <th width="15%">Tanggal</th>
        </tr>
        <?php
        if ($total_records > 0) {
            $no = $offset + 1;
            while ($data = mysqli_fetch_array($hasil)) {
                echo "<tr>
                    <td>$no</td>
                    <td>$data[nama]</td>
                    <td>$data[email]</td>
                    <td>$data[komentar]</td>
                    <td>$data[tanggal]</td>
                </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data buku tamu</td></tr>";
        }
        ?>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1">« Awal</a>
            <a href="?page=<?php echo $page - 1; ?>">‹ Sebelumnya</a>
        <?php else: ?>
            <span class="disabled">« Awal</span>
            <span class="disabled">‹ Sebelumnya</span>
        <?php endif; ?>

        <?php
        // Menampilkan nomor halaman
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                echo "<span class='disabled' style='background:#45a049;'>$i</span>";
            } else {
                echo "<a href='?page=$i'>$i</a>";
            }
        }
        ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Berikutnya ›</a>
            <a href="?page=<?php echo $total_pages; ?>">Akhir »</a>
        <?php else: ?>
            <span class="disabled">Berikutnya ›</span>
            <span class="disabled">Akhir »</span>
        <?php endif; ?>
    </div>

    <div class="menu-link">
        <a href="Pert11_Latihan3.php">✍️ Isi Buku Tamu</a>
        <a href="Pert11_Latihan2.php">🏠 Menu Utama</a>
    </div>

    <?php mysqli_close($conn); ?>
</body>
</html>