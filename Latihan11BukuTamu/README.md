# 📖 Buku Tamu — PHP + MySQL

## Struktur File

```
buku_tamu/
├── database.sql   ← Script pembuatan database & tabel
├── config.php     ← Konfigurasi koneksi database
├── index.php      ← Form buku tamu (halaman utama)
├── simpan.php     ← Proses penyimpanan ke database
└── daftar.php     ← Halaman daftar semua tamu
```

## Field yang Tersimpan

| Field        | Tipe              | Keterangan                              |
|--------------|-------------------|-----------------------------------------|
| id           | INT (PK)          | ID otomatis                             |
| nama         | VARCHAR(100)      | Nama lengkap tamu *(wajib)*             |
| email        | VARCHAR(150)      | Alamat email *(wajib)*                  |
| no_telepon   | VARCHAR(20)       | Nomor telepon *(opsional)*              |
| asal         | VARCHAR(100)      | Kota atau instansi asal *(opsional)*    |
| keperluan    | ENUM              | Kunjungan / Rapat / Pengiriman / Wawancara / Lainnya *(wajib)* |
| pesan        | TEXT              | Pesan atau komentar *(opsional)*        |
| rating       | TINYINT (1–5)     | Penilaian pelayanan *(opsional)*        |
| tanggal      | DATE              | Tanggal kunjungan *(wajib)*             |
| waktu_masuk  | TIME              | Jam masuk *(wajib)*                     |
| dibuat_pada  | TIMESTAMP         | Waktu record dibuat (otomatis)          |

## Cara Instalasi

### 1. Buat Database
Jalankan `database.sql` di MySQL/MariaDB:
```bash
mysql -u root -p < database.sql
```
Atau buka di **phpMyAdmin → Import → Pilih file database.sql**.

### 2. Sesuaikan Konfigurasi
Edit `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'buku_tamu');
define('DB_USER', 'root');    // username MySQL Anda
define('DB_PASS', '');        // password MySQL Anda
```

### 3. Upload ke Server
Letakkan semua file di folder web server, contoh:
- **XAMPP/WAMP**: `htdocs/buku_tamu/`
- **Laravel Herd / Valet**: folder project
- **VPS/cPanel**: `public_html/buku_tamu/`

### 4. Akses di Browser
```
http://localhost/buku_tamu/index.php    ← Form input
http://localhost/buku_tamu/daftar.php   ← Daftar tamu
```

## Keamanan yang Sudah Diterapkan
- **PDO + Prepared Statements** — mencegah SQL Injection
- **htmlspecialchars** — mencegah XSS pada output
- **FILTER_SANITIZE_EMAIL / FILTER_VALIDATE_EMAIL** — validasi email
- **Validasi server-side** — semua input divalidasi di PHP sebelum disimpan
- **Password/API key** — tidak ada kredensial yang ter-expose di frontend

## Kebutuhan Server
- PHP ≥ 8.0
- MySQL ≥ 5.7 atau MariaDB ≥ 10.3
- Ekstensi PHP: `pdo_mysql`
