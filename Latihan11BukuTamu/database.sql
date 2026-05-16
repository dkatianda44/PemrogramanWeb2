-- ============================================
-- DATABASE: Buku Tamu
-- ============================================

CREATE DATABASE IF NOT EXISTS buku_tamu
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE buku_tamu;

CREATE TABLE IF NOT EXISTS tamu (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100)  NOT NULL COMMENT 'Nama lengkap tamu',
    email       VARCHAR(150)  NOT NULL COMMENT 'Alamat email',
    no_telepon  VARCHAR(20)   DEFAULT NULL COMMENT 'Nomor telepon',
    asal        VARCHAR(100)  DEFAULT NULL COMMENT 'Kota / instansi asal',
    keperluan   ENUM(
                    'Kunjungan',
                    'Rapat',
                    'Pengiriman',
                    'Wawancara',
                    'Lainnya'
                ) NOT NULL DEFAULT 'Kunjungan' COMMENT 'Keperluan kedatangan',
    pesan       TEXT          DEFAULT NULL COMMENT 'Pesan / komentar tamu',
    rating      TINYINT       DEFAULT NULL COMMENT 'Penilaian 1–5 bintang',
    tanggal     DATE          NOT NULL COMMENT 'Tanggal kunjungan',
    waktu_masuk TIME          NOT NULL COMMENT 'Jam masuk',
    dibuat_pada TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Index untuk pencarian cepat
CREATE INDEX idx_tanggal   ON tamu (tanggal);
CREATE INDEX idx_keperluan ON tamu (keperluan);

-- Data contoh
INSERT INTO tamu
    (nama, email, no_telepon, asal, keperluan, pesan, rating, tanggal, waktu_masuk)
VALUES
    ('Muhammad Daffa Katianda',   'dkatianda@gmail.com',   '087789686036', 'Jakarta',   'Rapat',      'Ruangan rapat sangat nyaman.',    5, CURDATE(), '09:00:00'),
    ('Suryanita Lestari',    'suryanitalestari@gmail.com',   '089876543210', 'Bandung',   'Kunjungan',  'Pelayanan ramah dan profesional.', 4, CURDATE(), '10:30:00'),
    ('Jennatira Zevannya Katianda',    'babyJenna@gmail.com',  NULL,           'Surabaya',  'Wawancara',  NULL,                               NULL, CURDATE(), '13:00:00');
