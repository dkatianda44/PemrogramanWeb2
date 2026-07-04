-- =========================================================
-- DATABASE: SISTEM PENGAJUAN PASPOR - KANTOR IMIGRASI CABANG
-- UAS Pemrograman Web II
-- =========================================================

CREATE DATABASE IF NOT EXISTS db_paspor;
USE db_paspor;

-- ---------------------------------------------------------
-- TABEL 1: PENDAFTAR (Menu "Daftar")
-- ---------------------------------------------------------
CREATE TABLE pendaftar (
  no_daftar     INT AUTO_INCREMENT PRIMARY KEY,
  nama_pemohon  VARCHAR(100) NOT NULL,
  tgl_daftar    DATE NOT NULL,
  hari          VARCHAR(20) NOT NULL,   -- hari harus datang (auto)
  tanggal       DATE NOT NULL,          -- tanggal harus datang (auto, sesuai kuota)
  jam           TIME NOT NULL,          -- jam harus datang (auto, sesuai slot)
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- TABEL 2: DAFTAR ULANG (Menu "Daftar Ulang")
-- ---------------------------------------------------------
CREATE TABLE daftar_ulang (
  no_daftar_ulang   INT AUTO_INCREMENT PRIMARY KEY,
  no_daftar         INT NOT NULL,
  nama_pemohon      VARCHAR(100) NOT NULL,
  hari_daftar_ulang VARCHAR(20) NOT NULL,
  tgl_daftar_ulang  DATE NOT NULL,
  hari_datang       VARCHAR(20) NOT NULL,
  tgl_datang        DATE NOT NULL,
  ktp               ENUM('Ada','Tidak') NOT NULL,
  kk                ENUM('Ada','Tidak') NOT NULL,
  ijazah_akte       ENUM('Ada','Tidak') NOT NULL,
  keperluan         VARCHAR(50) NOT NULL,
  keterangan        ENUM('OK','Tidak') NOT NULL,   -- OK jika hari & tanggal datang sesuai jadwal
  no_antrian        INT DEFAULT NULL,               -- otomatis terisi hanya jika keterangan = OK
  FOREIGN KEY (no_daftar) REFERENCES pendaftar(no_daftar) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- TABEL 3: PENGURUSAN BERKAS (Menu "Pengurusan")
-- ---------------------------------------------------------
CREATE TABLE pengurusan (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  no_antrian      INT NOT NULL,
  no_daftar_ulang INT NOT NULL,
  no_daftar       INT NOT NULL,
  nama_pemohon    VARCHAR(100) NOT NULL,
  berkas          ENUM('Lengkap','Tidak Lengkap') NOT NULL,
  status          ENUM('Diterima','Ditolak') NOT NULL,
  keterangan      VARCHAR(20) NOT NULL,
  pembayaran      INT DEFAULT 0,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (no_daftar_ulang) REFERENCES daftar_ulang(no_daftar_ulang) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- DATA DUMMY
-- ---------------------------------------------------------

-- Contoh: tanggal 2026-07-02 sudah penuh 5 orang (sesuai kuota),
-- sehingga pendaftar ke-6 otomatis dialihkan ke 2026-07-03
INSERT INTO pendaftar (nama_pemohon, tgl_daftar, hari, tanggal, jam) VALUES
('Muhammad Daffa katianda',   '2026-07-01', 'Kamis', '2026-07-02', '08:00:00'),
('Suryanita Lestari',   '2026-07-01', 'Kamis', '2026-07-02', '09:00:00'),
('Jennaira Zevannya Katianda',     '2026-07-01', 'Kamis', '2026-07-02', '10:00:00'),
('Arie',   '2026-07-01', 'Kamis', '2026-07-02', '11:00:00'),
('Surya',     '2026-07-01', 'Kamis', '2026-07-02', '13:00:00'),
('Katin',  '2026-07-01', 'Kamis', '2026-07-03', '08:00:00');

INSERT INTO daftar_ulang
(no_daftar, nama_pemohon, hari_daftar_ulang, tgl_daftar_ulang, hari_datang, tgl_datang, ktp, kk, ijazah_akte, keperluan, keterangan, no_antrian) VALUES
(1, 'Muhammad Daffa Katianda', 'Jumat', '2026-07-02', 'Jumat', '2026-07-02', 'Ada',  'Ada',  'Ada',  'Paspor Baru',   'OK',    1),
(2, 'Suryanita Lestari', 'Jumat', '2026-07-02', 'Jumat', '2026-07-02', 'Ada',  'Tidak','Ada',  'Paspor Baru',   'OK',    2),
(3, 'Jennaira Zevannya Katianda',   'Jumat', '2026-07-02', 'Sabtu', '2026-07-03', 'Ada',  'Ada',  'Ada',  'Perpanjangan',  'Tidak', NULL);

INSERT INTO pengurusan
(no_antrian, no_daftar_ulang, no_daftar, nama_pemohon, berkas, status, keterangan, pembayaran) VALUES
(1, 1, 1, 'Muhammad Daffa Katianda', 'Lengkap',      'Diterima', 'OK', 355000),
(2, 2, 2, 'Suryanita Lestari', 'Tidak Lengkap','Ditolak',  '-',  0);
