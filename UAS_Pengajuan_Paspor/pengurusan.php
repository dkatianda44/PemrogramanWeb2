<?php
require 'config.php';
require 'includes/functions.php';

// ---------- PROSES PENGURUSAN (dari data daftar_ulang yang keterangan-nya OK) ----------
if (isset($_GET['proses'])) {
    $id = (int)$_GET['proses'];
    $q  = mysqli_query($koneksi, "SELECT * FROM daftar_ulang WHERE no_daftar_ulang=$id");
    $row = mysqli_fetch_assoc($q);

    if ($row) {
        // Berkas lengkap hanya jika KTP, KK, dan Ijazah/Akte semuanya "Ada"
        if ($row['ktp'] === 'Ada' && $row['kk'] === 'Ada' && $row['ijazah_akte'] === 'Ada') {
            $berkas     = 'Lengkap';
            $status     = 'Diterima';
            $keterangan = 'OK';
            $pembayaran = 355000;
        } else {
            $berkas     = 'Tidak Lengkap';
            $status     = 'Ditolak';
            $keterangan = '-';
            $pembayaran = 0;
        }

        $nama = mysqli_real_escape_string($koneksi, $row['nama_pemohon']);
        mysqli_query($koneksi, "INSERT INTO pengurusan
            (no_antrian, no_daftar_ulang, no_daftar, nama_pemohon, berkas, status, keterangan, pembayaran)
            VALUES
            ({$row['no_antrian']}, {$row['no_daftar_ulang']}, {$row['no_daftar']}, '$nama', '$berkas', '$status', '$keterangan', $pembayaran)");
    }
    header("Location: pengurusan.php");
    exit;
}

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pengurusan WHERE id=$id");
    header("Location: pengurusan.php");
    exit;
}

// Daftar antrian yang siap diproses: keterangan OK, punya no_antrian, dan belum ada di tabel pengurusan
$siap_proses = mysqli_query($koneksi, "
    SELECT du.* FROM daftar_ulang du
    WHERE du.keterangan = 'OK'
      AND du.no_antrian IS NOT NULL
      AND du.no_daftar_ulang NOT IN (SELECT no_daftar_ulang FROM pengurusan)
    ORDER BY du.no_antrian ASC
");

$data = mysqli_query($koneksi, "SELECT * FROM pengurusan ORDER BY no_antrian ASC");

$total_pendapatan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT SUM(pembayaran) AS total FROM pengurusan WHERE status='Diterima'")
)['total'] ?? 0;

$active = 'pengurusan';
include 'includes/header.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Antrian Siap Diproses</h5>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>No. Antrian</th>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>KTP</th>
            <th>KK</th>
            <th>Ijazah/Akte</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($siap_proses) == 0): ?>
            <tr><td colspan="7" class="text-center text-muted">Tidak ada antrian yang siap diproses</td></tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($siap_proses)): ?>
              <tr>
                <td><?= $row['no_antrian'] ?></td>
                <td>#<?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= $row['ktp'] ?></td>
                <td><?= $row['kk'] ?></td>
                <td><?= $row['ijazah_akte'] ?></td>
                <td>
                  <a href="pengurusan.php?proses=<?= $row['no_daftar_ulang'] ?>" class="btn btn-sm btn-success">
                    Proses Berkas
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Data Pengurusan Paspor</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>No. Antrian</th>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Berkas</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Pembayaran</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($data) == 0): ?>
            <tr><td colspan="8" class="text-center text-muted">Belum ada data pengurusan</td></tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
              <tr>
                <td><?= $row['no_antrian'] ?></td>
                <td>#<?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= $row['berkas'] ?></td>
                <td>
                  <span class="badge <?= $row['status']=='Diterima' ? 'badge-ok' : 'badge-tidak' ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
                <td><?= $row['keterangan'] ?></td>
                <td><?= format_rupiah($row['pembayaran']) ?></td>
                <td>
                  <a href="pengurusan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
        <?php if (mysqli_num_rows($data) > 0): ?>
        <tfoot>
          <tr class="table-light">
            <th colspan="6" class="text-end">Total Pendapatan</th>
            <th colspan="2"><?= format_rupiah($total_pendapatan) ?></th>
          </tr>
        </tfoot>
        <?php endif; ?>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
