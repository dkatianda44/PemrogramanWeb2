<?php
require 'config.php';
require 'includes/functions.php';

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pendaftar WHERE no_daftar=$id");
    header("Location: daftar.php");
    exit;
}

// ---------- SIMPAN / UPDATE ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = mysqli_real_escape_string($koneksi, trim($_POST['nama_pemohon']));
    $tgl_daftar = $_POST['tgl_daftar'];

    if (!empty($_POST['no_daftar'])) {
        // MODE EDIT: jadwal dihitung ulang otomatis, mengecualikan record ini sendiri dari hitungan kuota
        $id = (int)$_POST['no_daftar'];
        $jadwal = cari_jadwal($koneksi, $tgl_daftar, $id);
        mysqli_query($koneksi, "UPDATE pendaftar SET
            nama_pemohon='$nama',
            tgl_daftar='$tgl_daftar',
            hari='{$jadwal['hari']}',
            tanggal='{$jadwal['tanggal']}',
            jam='{$jadwal['jam']}'
            WHERE no_daftar=$id");
    } else {
        // MODE TAMBAH: cari jadwal otomatis sesuai kuota (maks 5 orang/hari)
        $jadwal = cari_jadwal($koneksi, $tgl_daftar);
        mysqli_query($koneksi, "INSERT INTO pendaftar (nama_pemohon, tgl_daftar, hari, tanggal, jam)
            VALUES ('$nama', '$tgl_daftar', '{$jadwal['hari']}', '{$jadwal['tanggal']}', '{$jadwal['jam']}')");
    }
    header("Location: daftar.php");
    exit;
}

// ---------- MODE EDIT: AMBIL DATA ----------
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q  = mysqli_query($koneksi, "SELECT * FROM pendaftar WHERE no_daftar=$id");
    $edit_data = mysqli_fetch_assoc($q);
}

$data = mysqli_query($koneksi, "SELECT * FROM pendaftar ORDER BY no_daftar DESC");

$active = 'daftar';
include 'includes/header.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3"><?= $edit_data ? 'Ubah Data Pendaftar' : 'Input Pendaftaran' ?></h5>
    <form method="POST" class="row g-3">
      <?php if ($edit_data): ?>
        <input type="hidden" name="no_daftar" value="<?= $edit_data['no_daftar'] ?>">
      <?php endif; ?>

      <div class="col-md-6">
        <label class="form-label">Nama Pemohon</label>
        <input type="text" name="nama_pemohon" class="form-control" required
               value="<?= htmlspecialchars($edit_data['nama_pemohon'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tanggal Daftar</label>
        <input type="date" name="tgl_daftar" class="form-control" required
               value="<?= $edit_data['tgl_daftar'] ?? date('Y-m-d') ?>">
      </div>

      <div class="col-12">
        <small class="text-muted">
          * Hari, tanggal, dan jam kedatangan akan <strong>otomatis dijadwalkan sistem</strong>
          berdasarkan kuota maksimal 5 orang per hari. Jika hari tersebut sudah penuh,
          pemohon otomatis dijadwalkan ke hari berikutnya.
        </small>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
        <?php if ($edit_data): ?>
          <a href="daftar.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Data Pendaftar</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Tgl Daftar</th>
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($data) == 0): ?>
            <tr><td colspan="7" class="text-center text-muted">Belum ada data pendaftar</td></tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
              <tr>
                <td>#<?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= format_tanggal_indo($row['tgl_daftar']) ?></td>
                <td><?= $row['hari'] ?></td>
                <td><?= format_tanggal_indo($row['tanggal']) ?></td>
                <td><?= substr($row['jam'],0,5) ?></td>
                <td>
                  <a href="daftar.php?edit=<?= $row['no_daftar'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  <a href="daftar.php?hapus=<?= $row['no_daftar'] ?>" class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Hapus data pendaftar ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
