-- ============================================================
--  Database: db_mahasiswa
--  Jalankan file ini di phpMyAdmin atau MySQL CLI
--  mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_mahasiswa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_mahasiswa;

-- Tabel mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id        INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    nim       VARCHAR(20)      NOT NULL UNIQUE,
    nama      VARCHAR(100)     NOT NULL,
    jurusan   VARCHAR(60)      NOT NULL,
    alamat    TEXT             NOT NULL,
    notelp    VARCHAR(20)      NOT NULL,
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh (opsional, hapus jika tidak perlu)
INSERT INTO mahasiswa (nim, nama, jurusan, alamat, notelp) VALUES
('2021001001', 'Budi Santoso',    'Teknik Informatika', 'Jl. Merdeka No. 10, Jakarta Pusat',   '081234567890'),
('2021001002', 'Siti Rahayu',     'Sistem Informasi',   'Jl. Sudirman No. 22, Bandung',         '082345678901'),
('2021001003', 'Ahmad Fauzi',     'Manajemen',          'Jl. Gatot Subroto No. 5, Surabaya',    '083456789012');
