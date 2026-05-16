<?php
// ============================================================
// Proses penyimpanan data buku tamu
// ============================================================

require_once 'config.php';

// Hanya terima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ---- Fungsi bantu ----
function bersihkan(string $nilai): string
{
    return htmlspecialchars(trim($nilai), ENT_QUOTES, 'UTF-8');
}

function nullable(string $nilai): ?string
{
    $nilai = trim($nilai);
    return $nilai !== '' ? $nilai : null;
}

// ---- Ambil & bersihkan input ----
$nama       = bersihkan($_POST['nama']       ?? '');
$email      = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$no_telepon = nullable($_POST['no_telepon']  ?? '');
$asal       = nullable($_POST['asal']        ?? '');
$keperluan  = bersihkan($_POST['keperluan']  ?? '');
$pesan      = nullable($_POST['pesan']       ?? '');
$rating_raw = trim($_POST['rating']          ?? '');
$tanggal    = bersihkan($_POST['tanggal']    ?? '');
$waktu      = bersihkan($_POST['waktu']      ?? '');

// ---- Validasi ----
$errors = [];

if ($nama === '') {
    $errors[] = 'Nama lengkap wajib diisi.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Alamat email tidak valid.';
}
$keperluan_valid = ['Kunjungan', 'Rapat', 'Pengiriman', 'Wawancara', 'Lainnya'];
if (!in_array($keperluan, $keperluan_valid, true)) {
    $errors[] = 'Keperluan tidak valid.';
}
if ($tanggal === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    $errors[] = 'Tanggal kunjungan tidak valid.';
}
if ($waktu === '' || !preg_match('/^\d{2}:\d{2}$/', $waktu)) {
    $errors[] = 'Waktu masuk tidak valid.';
}

$rating = null;
if ($rating_raw !== '') {
    $rating = (int) $rating_raw;
    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Rating harus antara 1 dan 5.';
    }
}

// Jika ada error, kembalikan ke form
if (!empty($errors)) {
    $query = http_build_query([
        'error'  => implode('|', $errors),
        'nama'   => $nama,
        'email'  => $email,
        'telp'   => $no_telepon,
        'asal'   => $asal,
        'keperlu'=> $keperluan,
        'tgl'    => $tanggal,
        'waktu'  => $waktu,
    ]);
    header("Location: index.php?$query");
    exit;
}

// ---- Simpan ke database ----
try {
    $pdo = getDB();

    $sql = "
        INSERT INTO tamu
            (nama, email, no_telepon, asal, keperluan, pesan, rating, tanggal, waktu_masuk)
        VALUES
            (:nama, :email, :no_telepon, :asal, :keperluan, :pesan, :rating, :tanggal, :waktu)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nama'       => $nama,
        ':email'      => $email,
        ':no_telepon' => $no_telepon,
        ':asal'       => $asal,
        ':keperluan'  => $keperluan,
        ':pesan'      => $pesan,
        ':rating'     => $rating,
        ':tanggal'    => $tanggal,
        ':waktu'      => $waktu . ':00',
    ]);

    header('Location: daftar.php?sukses=1');
    exit;

} catch (PDOException $e) {
    // Catat ke log server (jangan tampilkan ke pengguna di production)
    error_log('Buku tamu error: ' . $e->getMessage());
    header('Location: index.php?error=' . urlencode('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'));
    exit;
}
