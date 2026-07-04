<?php
// Mengubah nama hari Inggris (hasil date()) menjadi Bahasa Indonesia
function nama_hari_indo($tanggal) {
    $hari_map = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];
    $hari_en = date('l', strtotime($tanggal));
    return $hari_map[$hari_en];
}

// Slot jam yang tersedia per hari (kapasitas 5 orang/hari)
function slot_jam() {
    return ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '13:00:00'];
}

/**
 * Mencari jadwal (hari, tanggal, jam) kedatangan secara otomatis.
 * Kapasitas 1 hari maksimal 5 orang. Jika tanggal yang dicek sudah
 * penuh (>=5 pendaftar), maju otomatis ke hari berikutnya.
 *
 * @param mysqli $koneksi
 * @param string $tgl_mulai   tanggal awal pengecekan (biasanya tgl_daftar)
 * @param int|null $exclude_id no_daftar yang dikecualikan dari hitungan (untuk mode edit)
 */
function cari_jadwal($koneksi, $tgl_mulai, $exclude_id = null) {
    $slots   = slot_jam();
    $tanggal = $tgl_mulai;

    while (true) {
        $where = "tanggal = '" . mysqli_real_escape_string($koneksi, $tanggal) . "'";
        if ($exclude_id) {
            $where .= " AND no_daftar != " . (int)$exclude_id;
        }
        $q   = mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM pendaftar WHERE $where");
        $row = mysqli_fetch_assoc($q);
        $jumlah = (int)$row['jml'];

        if ($jumlah < 5) {
            return [
                'hari'    => nama_hari_indo($tanggal),
                'tanggal' => $tanggal,
                'jam'     => $slots[$jumlah],
            ];
        }
        // sudah penuh 5 orang -> maju ke hari berikutnya
        $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'));
    }
}

function format_tanggal_indo($tanggal) {
    if (!$tanggal) return '-';
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('d', strtotime($tanggal));
    $m = (int)date('n', strtotime($tanggal));
    $y = date('Y', strtotime($tanggal));
    return "$d {$bulan[$m]} $y";
}

function format_rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}
?>
