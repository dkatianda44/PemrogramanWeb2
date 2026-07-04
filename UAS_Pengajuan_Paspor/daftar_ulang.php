<?php
require 'config.php';
require 'includes/functions.php';

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM daftar_ulang WHERE no_daftar_ulang=$id");
    header("Location: daftar_ulang.php");
    exit;
}

// ---------- SIMPAN / UPDATE ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_daftar = (int)$_POST['no_daftar'];
    $tgl_datang = $_POST['tgl_datang'];
    $keperluan  = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $ktp        = isset($_POST['ktp']) ? 'Ada' : 'Tidak';
    $kk         = isset($_POST['kk']) ? 'Ada' : 'Tidak';
    $ijazah     = isset($_POST['ijazah_akte']) ? 'Ada' : 'Tidak';

    $hari_daftar_ulang = nama_hari_indo(date('Y-m-d'));
    $tgl_daftar_ulang  = date('Y-m-d');
    $hari_datang       = nama_hari_indo($tgl_datang);

    // ambil data jadwal asli dari tabel pendaftar
    $qp = mysqli_query($koneksi, "SELECT * FROM pendaftar WHERE no_daftar=$no_daftar");
    $pendaftar = mysqli_fetch_assoc($qp);
    $nama_pemohon = mysqli_real_escape_string($koneksi, $pendaftar['nama_pemohon']);

    // Keterangan OK hanya jika tanggal datang SESUAI jadwal pendaftaran
    if ($tgl_datang === $pendaftar['tanggal']) {
        $keterangan = 'OK';
    } else {
        $keterangan = 'Tidak';
    }

    if (!empty($_POST['no_daftar_ulang'])) {
        // MODE EDIT
        $id = (int)$_POST['no_daftar_ulang'];

        // pertahankan no_antrian lama jika sudah OK sebelumnya & tetap OK, kalau tidak reset
        $qold = mysqli_query($koneksi, "SELECT no_antrian, keterangan FROM daftar_ulang WHERE no_daftar_ulang=$id");
        $old  = mysqli_fetch_assoc($qold);

        if ($keterangan === 'OK') {
            if ($old['keterangan'] === 'OK' && $old['no_antrian']) {
                $no_antrian = $old['no_antrian']; // tetap pakai nomor lama
            } else {
                $qmax = mysqli_query($koneksi, "SELECT MAX(no_antrian) AS mx FROM daftar_ulang");
                $rowmax = mysqli_fetch_assoc($qmax);
                $no_antrian = (int)($rowmax['mx'] ?? 0) + 1;
            }
            $no_antrian_sql = $no_antrian;
        } else {
            $no_antrian_sql = "NULL";
        }

        mysqli_query($koneksi, "UPDATE daftar_ulang SET
            no_daftar=$no_daftar,
            nama_pemohon='$nama_pemohon',
            hari_daftar_ulang='$hari_daftar_ulang',
            tgl_daftar_ulang='$tgl_daftar_ulang',
            hari_datang='$hari_datang',
            tgl_datang='$tgl_datang',
            ktp='$ktp', kk='$kk', ijazah_akte='$ijazah',
            keperluan='$keperluan',
            keterangan='$keterangan',
            no_antrian=$no_antrian_sql
            WHERE no_daftar_ulang=$id");

    } else {
        // MODE TAMBAH
        if ($keterangan === 'OK') {
            $qmax = mysqli_query($koneksi, "SELECT MAX(no_antrian) AS mx FROM daftar_ulang");
            $rowmax = mysqli_fetch_assoc($qmax);
            $no_antrian_sql = (int)($rowmax['mx'] ?? 0) + 1;
        } else {
            $no_antrian_sql = "NULL";
        }

        mysqli_query($koneksi, "INSERT INTO daftar_ulang
            (no_daftar, nama_pemohon, hari_daftar_ulang, tgl_daftar_ulang, hari_datang, tgl_datang, ktp, kk, ijazah_akte, keperluan, keterangan, no_antrian)
            VALUES
            ($no_daftar, '$nama_pemohon', '$hari_daftar_ulang', '$tgl_daftar_ulang', '$hari_datang', '$tgl_datang', '$ktp', '$kk', '$ijazah', '$keperluan', '$keterangan', $no_antrian_sql)");
    }

    header("Location: daftar_ulang.php");
    exit;
}

// ---------- MODE EDIT: AMBIL DATA ----------
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q  = mysqli_query($koneksi, "SELECT * FROM daftar_ulang WHERE no_daftar_ulang=$id");
    $edit_data = mysqli_fetch_assoc($q);
}

// daftar pendaftar untuk dropdown (beserta info jadwal, dipakai JS)
$list_pendaftar = mysqli_query($koneksi, "SELECT * FROM pendaftar ORDER BY no_daftar ASC");
$pendaftar_arr = [];
while ($p = mysqli_fetch_assoc($list_pendaftar)) { $pendaftar_arr[] = $p; }

$data = mysqli_query($koneksi, "SELECT * FROM daftar_ulang ORDER BY no_daftar_ulang DESC");

$active = 'daftar_ulang';
include 'includes/header.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3"><?= $edit_data ? 'Ubah Data Daftar Ulang' : 'Input Data Daftar Ulang' ?></h5>
    <form method="POST" class="row g-3">
      <?php if ($edit_data): ?>
        <input type="hidden" name="no_daftar_ulang" value="<?= $edit_data['no_daftar_ulang'] ?>">
      <?php endif; ?>

      <div class="col-md-6">
        <label class="form-label">No. Daftar</label>
        <select name="no_daftar" id="selectPendaftar" class="form-select" required onchange="tampilkanJadwal()">
          <option value="">-- Pilih Pendaftar --</option>
          <?php foreach ($pendaftar_arr as $p): ?>
            <option value="<?= $p['no_daftar'] ?>"
              data-hari="<?= $p['hari'] ?>"
              data-tanggal="<?= $p['tanggal'] ?>"
              data-jam="<?= substr($p['jam'],0,5) ?>"
              <?= (isset($edit_data['no_daftar']) && $edit_data['no_daftar'] == $p['no_daftar']) ? 'selected' : '' ?>>
              #<?= $p['no_daftar'] ?> - <?= htmlspecialchars($p['nama_pemohon']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="text-muted" id="infoJadwal">Jadwal wajib datang akan tampil di sini.</small>
      </div>

      <div class="col-md-6">
        <label class="form-label">Keperluan</label>
        <select name="keperluan" class="form-select" required>
          <?php foreach (['Paspor Baru','Perpanjangan','Penggantian'] as $opt): ?>
            <option value="<?= $opt ?>" <?= (($edit_data['keperluan'] ?? '') == $opt) ? 'selected' : '' ?>><?= $opt ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Tanggal Datang (aktual)</label>
        <input type="date" name="tgl_datang" class="form-control" required
               value="<?= $edit_data['tgl_datang'] ?? date('Y-m-d') ?>">
        <small class="text-muted">Isi sesuai tanggal pemohon benar-benar datang. Hari otomatis dihitung.</small>
      </div>

      <div class="col-md-6">
        <label class="form-label d-block">Kelengkapan Berkas</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="ktp" id="ktp"
            <?= (($edit_data['ktp'] ?? '') == 'Ada') ? 'checked' : '' ?>>
          <label class="form-check-label" for="ktp">KTP</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="kk" id="kk"
            <?= (($edit_data['kk'] ?? '') == 'Ada') ? 'checked' : '' ?>>
          <label class="form-check-label" for="kk">KK</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="ijazah_akte" id="ijazah_akte"
            <?= (($edit_data['ijazah_akte'] ?? '') == 'Ada') ? 'checked' : '' ?>>
          <label class="form-check-label" for="ijazah_akte">Ijazah/Akte</label>
        </div>
      </div>

      <div class="col-12">
        <small class="text-muted">
          * Keterangan <strong>OK</strong> hanya diberikan jika tanggal datang <strong>sesuai</strong> dengan jadwal
          pendaftaran. Jika OK, No. Antrian otomatis diberikan oleh sistem.
        </small>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
        <?php if ($edit_data): ?>
          <a href="daftar_ulang.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Data Pendaftar Ulang</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Keperluan</th>
            <th>KTP</th>
            <th>KK</th>
            <th>Ijazah/Akte</th>
            <th>Keterangan</th>
            <th>No. Antrian</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($data) == 0): ?>
            <tr><td colspan="9" class="text-center text-muted">Belum ada data daftar ulang</td></tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
              <tr>
                <td>#<?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= htmlspecialchars($row['keperluan']) ?></td>
                <td><?= $row['ktp'] ?></td>
                <td><?= $row['kk'] ?></td>
                <td><?= $row['ijazah_akte'] ?></td>
                <td>
                  <span class="badge <?= $row['keterangan']=='OK' ? 'badge-ok' : 'badge-tidak' ?>">
                    <?= $row['keterangan'] ?>
                  </span>
                </td>
                <td><?= $row['no_antrian'] ?? '-' ?></td>
                <td>
                  <a href="daftar_ulang.php?edit=<?= $row['no_daftar_ulang'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  <a href="daftar_ulang.php?hapus=<?= $row['no_daftar_ulang'] ?>" class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function tampilkanJadwal() {
  const sel = document.getElementById('selectPendaftar');
  const opt = sel.options[sel.selectedIndex];
  const info = document.getElementById('infoJadwal');
  if (opt && opt.value) {
    info.innerHTML = "Wajib datang: <strong>" + opt.dataset.hari + ", " + opt.dataset.tanggal + "</strong> pukul " + opt.dataset.jam;
  } else {
    info.innerHTML = "Jadwal wajib datang akan tampil di sini.";
  }
}
document.addEventListener('DOMContentLoaded', tampilkanJadwal);
</script>

<?php include 'includes/footer.php'; ?>
