<?php
require_once 'config.php';

// ---- Filter & Pagination ----
$per_halaman  = 10;
$halaman      = max(1, (int)($_GET['hal'] ?? 1));
$offset       = ($halaman - 1) * $per_halaman;

$filter_keperluan = $_GET['keperluan'] ?? '';
$keperluan_valid  = ['Kunjungan', 'Rapat', 'Pengiriman', 'Wawancara', 'Lainnya'];

$where  = '';
$params = [];
if (in_array($filter_keperluan, $keperluan_valid, true)) {
    $where    = 'WHERE keperluan = :keperluan';
    $params[':keperluan'] = $filter_keperluan;
}

$pdo = getDB();

// Total baris
$total = (int) $pdo->prepare("SELECT COUNT(*) FROM tamu $where");
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM tamu $where");
$stmt_count->execute($params);
$total       = (int) $stmt_count->fetchColumn();
$total_hal   = (int) ceil($total / $per_halaman);

// Data
$stmt = $pdo->prepare("
    SELECT id, nama, email, no_telepon, asal, keperluan, pesan, rating,
           tanggal, waktu_masuk, dibuat_pada
    FROM   tamu
    $where
    ORDER BY dibuat_pada DESC
    LIMIT  :limit OFFSET :offset
");
foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
$stmt->bindValue(':limit',  $per_halaman, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,      PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$sukses = isset($_GET['sukses']);

function bintang(int|null $r): string {
    if ($r === null) return '<span style="color:#9ca3af">—</span>';
    return str_repeat('★', $r) . str_repeat('☆', 5 - $r);
}

function badge(string $k): string {
    $map = [
        'Kunjungan'  => '#dbeafe:#1e40af',
        'Rapat'      => '#dcfce7:#166534',
        'Pengiriman' => '#fef9c3:#854d0e',
        'Wawancara'  => '#fce7f3:#9d174d',
        'Lainnya'    => '#f3f4f6:#374151',
    ];
    [$bg, $fg] = explode(':', $map[$k] ?? '#f3f4f6:#374151');
    return "<span style=\"background:$bg;color:$fg;
        padding:2px 10px;border-radius:999px;font-size:.78rem;font-weight:600;\">$k</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Tamu</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --primary:  #1a56db;
    --surface:  #ffffff;
    --bg:       #f3f4f6;
    --border:   #e5e7eb;
    --text:     #111827;
    --muted:    #6b7280;
    --radius:   8px;
  }
  body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; padding: 2rem 1rem; }
  .container { max-width: 900px; margin: 0 auto; }

  .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
  .page-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--primary); }

  .btn { display: inline-block; padding: .55rem 1.25rem; border-radius: var(--radius); font-size: .875rem; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid transparent; }
  .btn-primary { background: var(--primary); color: #fff; }
  .btn-primary:hover { background: #1e429f; }
  .btn-outline { background: var(--surface); color: var(--primary); border-color: var(--primary); }
  .btn-outline:hover { background: #eff6ff; }

  .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; border-radius: var(--radius); padding: .75rem 1rem; margin-bottom: 1rem; }

  /* Filter */
  .filter-bar { display: flex; gap: .75rem; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; }
  .filter-bar select { padding: .45rem .75rem; border: 1px solid var(--border); border-radius: var(--radius); font-size: .875rem; }
  .filter-bar label { font-size: .875rem; color: var(--muted); }

  /* Stat */
  .stats { display: flex; gap: .75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
  .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: .75rem 1rem; flex: 1; min-width: 130px; }
  .stat-card .num { font-size: 1.5rem; font-weight: 700; color: var(--primary); }
  .stat-card .lbl { font-size: .78rem; color: var(--muted); margin-top: 2px; }

  /* Table */
  .table-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; background: var(--surface); border-radius: calc(var(--radius)*1.5); overflow: hidden; border: 1px solid var(--border); }
  thead { background: #f9fafb; }
  th { text-align: left; padding: .7rem 1rem; font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); }
  td { padding: .8rem 1rem; font-size: .875rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #f9fafb; }

  .rating-stars { color: #f59e0b; letter-spacing: -1px; }

  /* Pagination */
  .pagination { display: flex; gap: .5rem; margin-top: 1.25rem; align-items: center; flex-wrap: wrap; }
  .page-link { display: inline-block; padding: .4rem .85rem; border: 1px solid var(--border); border-radius: var(--radius); font-size: .875rem; color: var(--primary); text-decoration: none; background: var(--surface); }
  .page-link:hover { background: #eff6ff; }
  .page-link.active { background: var(--primary); color: #fff; border-color: var(--primary); }
  .page-link.disabled { color: var(--muted); pointer-events: none; }

  .empty { text-align: center; padding: 3rem; color: var(--muted); }
</style>
</head>
<body>
<div class="container">

  <div class="page-header">
    <h1>📋 Daftar Tamu</h1>
    <a href="index.php" class="btn btn-primary">+ Isi Buku Tamu</a>
  </div>

  <?php if ($sukses): ?>
    <div class="alert-success">✅ Data tamu berhasil disimpan. Terima kasih!</div>
  <?php endif; ?>

  <?php
  // Hitung statistik global
  $s = $pdo->query("SELECT COUNT(*) AS total,
      SUM(CASE WHEN tanggal = CURDATE() THEN 1 ELSE 0 END) AS hari_ini,
      ROUND(AVG(rating),1) AS avg_rating
      FROM tamu")->fetch();
  ?>
  <div class="stats">
    <div class="stat-card"><div class="num"><?= number_format($s['total']) ?></div><div class="lbl">Total Tamu</div></div>
    <div class="stat-card"><div class="num"><?= $s['hari_ini'] ?></div><div class="lbl">Hari Ini</div></div>
    <div class="stat-card"><div class="num"><?= $s['avg_rating'] ?? '—' ?></div><div class="lbl">Rata‑rata Rating</div></div>
  </div>

  <div class="filter-bar">
    <label for="f_keperluan">Filter keperluan:</label>
    <form method="GET" style="display:flex;gap:.5rem;align-items:center;">
      <select name="keperluan" id="f_keperluan" onchange="this.form.submit()">
        <option value="">Semua</option>
        <?php foreach ($keperluan_valid as $opt): ?>
          <option value="<?= $opt ?>" <?= $filter_keperluan === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
      <noscript><button type="submit" class="btn btn-outline">Terapkan</button></noscript>
    </form>
    <span style="margin-left:auto;font-size:.85rem;color:var(--muted);"><?= $total ?> entri ditemukan</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email / Telepon</th>
          <th>Asal</th>
          <th>Keperluan</th>
          <th>Tanggal &amp; Jam</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="empty">Belum ada data tamu.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td style="color:var(--muted);font-size:.8rem;"><?= $offset + $i + 1 ?></td>
            <td>
              <strong><?= htmlspecialchars($r['nama']) ?></strong>
              <?php if ($r['pesan']): ?>
                <br><span style="font-size:.78rem;color:var(--muted);" title="<?= htmlspecialchars($r['pesan']) ?>">
                  "<?= mb_strimwidth(htmlspecialchars($r['pesan']), 0, 40, '…') ?>"
                </span>
              <?php endif; ?>
            </td>
            <td>
              <?= htmlspecialchars($r['email']) ?>
              <?php if ($r['no_telepon']): ?>
                <br><span style="font-size:.8rem;color:var(--muted);"><?= htmlspecialchars($r['no_telepon']) ?></span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($r['asal'] ?? '—') ?></td>
            <td><?= badge($r['keperluan']) ?></td>
            <td>
              <?= date('d M Y', strtotime($r['tanggal'])) ?>
              <br><span style="font-size:.8rem;color:var(--muted);"><?= substr($r['waktu_masuk'], 0, 5) ?> WIB</span>
            </td>
            <td class="rating-stars"><?= bintang($r['rating'] ? (int)$r['rating'] : null) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php
    $base = '?keperluan=' . urlencode($filter_keperluan) . '&hal=';
    ?>
    <a href="<?= $base . max(1, $halaman - 1) ?>" class="page-link <?= $halaman <= 1 ? 'disabled' : '' ?>">‹ Prev</a>
    <?php for ($p = 1; $p <= $total_hal; $p++): ?>
      <a href="<?= $base . $p ?>" class="page-link <?= $p === $halaman ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <a href="<?= $base . min($total_hal, $halaman + 1) ?>" class="page-link <?= $halaman >= $total_hal ? 'disabled' : '' ?>">Next ›</a>
  </div>
  <?php endif; ?>

</div>
</body>
</html>
