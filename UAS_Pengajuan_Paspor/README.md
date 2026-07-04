# Sistem Pengajuan Paspor — Kantor Imigrasi Cabang

Aplikasi UAS Pemrograman Web II — dibuat dengan **PHP native (mysqli)** + **MySQL** + **Bootstrap 5**.

## Fitur (sesuai soal)

1. **Daftar** — Input pendaftaran. Hari/tanggal/jam kedatangan **otomatis dijadwalkan sistem**
   berdasarkan kuota **maksimal 5 orang per hari**. Jika satu hari sudah penuh, pendaftar
   berikutnya otomatis dialihkan ke hari berikutnya.
2. **Daftar Ulang** — Input kelengkapan berkas (KTP/KK/Ijazah-Akte: Ada/Tidak). Sistem
   mengecek apakah tanggal kedatangan **sesuai** jadwal pendaftaran:
   - Sesuai → Keterangan = **OK** dan mendapat **No. Antrian otomatis**.
   - Tidak sesuai → Keterangan = **Tidak** (tanpa nomor antrian).
3. **Pengurusan** — Memproses antrian yang keterangannya OK:
   - Jika KTP, KK, dan Ijazah/Akte **semua Ada** → Berkas = **Lengkap**, Status = **Diterima**,
     Keterangan = **OK**, Pembayaran = **Rp 355.000**.
   - Jika tidak lengkap → Status = **Ditolak**, Pembayaran = **Rp 0**.
   - Halaman ini juga menampilkan **Total Pendapatan** (akumulasi pembayaran yang diterima).

## Cara Menjalankan (XAMPP / Laragon)

1. Copy folder `paspor_app` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin**, buat database baru bernama `db_paspor` lalu **import**
   file `database.sql` (sudah termasuk data dummy).
   - Atau jalankan lewat terminal:
     ```
     mysql -u root -p < database.sql
     ```
3. Cek kembali konfigurasi koneksi di `config.php` jika user/password MySQL kamu berbeda
   dari default XAMPP (`root` tanpa password).
4. Jalankan Apache & MySQL di XAMPP/Laragon.
5. Buka browser: `http://localhost/paspor_app/`

## Struktur Folder

```
paspor_app/
├── config.php              -> koneksi database
├── database.sql            -> struktur tabel + data dummy
├── index.php                -> redirect ke daftar.php
├── daftar.php                -> Menu 1: Input Daftar
├── daftar_ulang.php          -> Menu 2: Input Data Daftar Ulang
├── pengurusan.php             -> Menu 3: Pengurusan Berkas & Pendapatan
└── includes/
    ├── functions.php        -> fungsi jadwal otomatis, format tanggal/rupiah
    ├── header.php           -> navbar & head HTML
    └── footer.php           -> penutup HTML
```

## Catatan

- Data dummy sudah berisi contoh 1 hari (2026-07-02) yang penuh 5 orang, sehingga pendaftar
  ke-6 otomatis terjadwal ke tanggal berikutnya (2026-07-03) — untuk mendemonstrasikan
  logika kuota.
- Silakan sesuaikan lagi tampilan/validasi jika dosen meminta ketentuan tambahan.
