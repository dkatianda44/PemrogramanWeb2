-- ============================================
-- Database: db_errorhandling
-- Untuk project PHP Error Handling
-- ============================================

CREATE DATABASE IF NOT EXISTS db_errorhandling
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_errorhandling;

-- --------------------------------------------
-- Tabel: mahasiswa
-- (digunakan di query_error.php)
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS mahasiswa (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nim   VARCHAR(20)  NOT NULL UNIQUE,
    nama  VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50),
    angkatan YEAR
);

-- --------------------------------------------
-- Data contoh
-- --------------------------------------------
INSERT INTO mahasiswa (nim, nama, jurusan, angkatan) VALUES
('221011400435', 'Muhammad Daffa Katianda',   'Teknik Informatika', 2021),
('221011400436', 'Siti Rahayu',    'Sistem Informasi',   2021),
('221011400437', 'Ahmad Fauzi',    'Teknik Informatika', 2022),
('221011400438', 'Dewi Lestari',   'Sistem Informasi',   2022),
('221011400439', 'Rizky Pratama',  'Teknik Informatika', 2023);
