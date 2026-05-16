<?php
// ---- Ambil nilai prefill (jika kembali dari error validasi) ----
$nama     = htmlspecialchars($_GET['nama']    ?? '', ENT_QUOTES, 'UTF-8');
$email    = htmlspecialchars($_GET['email']   ?? '', ENT_QUOTES, 'UTF-8');
$telp     = htmlspecialchars($_GET['telp']    ?? '', ENT_QUOTES, 'UTF-8');
$asal     = htmlspecialchars($_GET['asal']    ?? '', ENT_QUOTES, 'UTF-8');
$keperlu  = htmlspecialchars($_GET['keperlu'] ?? '', ENT_QUOTES, 'UTF-8');
$tgl      = htmlspecialchars($_GET['tgl']     ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8');
$waktu    = htmlspecialchars($_GET['waktu']   ?? date('H:i'),   ENT_QUOTES, 'UTF-8');

// ---- Pesan error ----
$error_raw = $_GET['error'] ?? '';
$errors    = $error_raw !== '' ? explode('|', $error_raw) : [];

$keperluan_options = ['Kunjungan', 'Rapat', 'Pengiriman', 'Wawancara', 'Lainnya'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buku Tamu</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --primary:     #1a56db;
    --primary-dk:  #1e429f;
    --surface:     #ffffff;
    --bg:          #f3f4f6;
    --border:      #d1d5db;
    --text:        #111827;
    --muted:       #6b7280;
    --danger-bg:   #fef2f2;
    --danger-bdr:  #fca5a5;
    --danger-txt:  #991b1b;
    --radius:      8px;
  }

  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    padding: 2rem 1rem;
  }

  .container { max-width: 640px; margin: 0 auto; }

  /* ---- Header ---- */
  .page-header {
    text-align: center;
    margin-bottom: 1.75rem;
  }
  .page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary);
  }
  .page-header p {
    color: var(--muted);
    margin-top: .35rem;
    font-size: .95rem;
  }

  /* ---- Card ---- */
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: calc(var(--radius) * 1.5);
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.07);
  }

  /* ---- Error box ---- */
  .alert-error {
    background: var(--danger-bg);
    border: 1px solid var(--danger-bdr);
    border-radius: var(--radius);
    padding: .85rem 1rem;
    margin-bottom: 1.5rem;
    color: var(--danger-txt);
    font-size: .9rem;
  }
  .alert-error ul { padding-left: 1.25rem; }
  .alert-error li { margin-top: .25rem; }

  /* ---- Form ---- */
  .form-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
  .form-row.cols-2 { grid-template-columns: 1fr 1fr; }
  @media (max-width: 520px) { .form-row.cols-2 { grid-template-columns: 1fr; } }

  .form-group { display: flex; flex-direction: column; gap: .4rem; }

  label {
    font-size: .875rem;
    font-weight: 600;
    color: var(--text);
  }
  label .required { color: #dc2626; margin-left: 2px; }
  label .opt { color: var(--muted); font-weight: 400; font-size: .8rem; }

  input[type="text"],
  input[type="email"],
  input[type="tel"],
  input[type="date"],
  input[type="time"],
  select,
  textarea {
    width: 100%;
    padding: .6rem .85rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: .95rem;
    font-family: inherit;
    color: var(--text);
    background: var(--surface);
    transition: border-color .15s, box-shadow .15s;
  }
  input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,86,219,.15);
  }
  textarea { resize: vertical; min-height: 100px; }

  /* ---- Rating bintang ---- */
  .star-group {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 4px;
  }
  .star-group input { display: none; }
  .star-group label {
    font-size: 1.6rem;
    color: #d1d5db;
    cursor: pointer;
    line-height: 1;
    font-weight: 400;
  }
  .star-group input:checked ~ label,
  .star-group label:hover,
  .star-group label:hover ~ label {
    color: #f59e0b;
  }

  /* ---- Divider ---- */
  .section-title {
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--muted);
    margin: 1.5rem 0 1rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: .4rem;
  }

  /* ---- Tombol ---- */
  .btn-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.75rem;
    gap: 1rem;
    flex-wrap: wrap;
  }
  .btn-primary {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: var(--radius);
    padding: .7rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s;
  }
  .btn-primary:hover { background: var(--primary-dk); }

  .link-daftar {
    font-size: .875rem;
    color: var(--primary);
    text-decoration: none;
  }
  .link-daftar:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">

  <header class="page-header">
    <h1>📖 Buku Tamu</h1>
    <p>Terima kasih telah berkunjung. Mohon isi data di bawah ini.</p>
  </header>

  <div class="card">

    <?php if (!empty($errors)): ?>
    <div class="alert-error">
      <strong>Mohon perbaiki kesalahan berikut:</strong>
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <form action="simpan.php" method="POST" novalidate>

      <p class="section-title">Data Pribadi</p>

      <div class="form-row">
        <div class="form-group">
          <label for="nama">Nama Lengkap <span class="required">*</span></label>
          <input type="text" id="nama" name="nama"
                 value="<?= $nama ?>" placeholder="Contoh: Budi Santoso"
                 required maxlength="100">
        </div>
      </div>

      <div class="form-row cols-2">
        <div class="form-group">
          <label for="email">Email <span class="required">*</span></label>
          <input type="email" id="email" name="email"
                 value="<?= $email ?>" placeholder="nama@email.com"
                 required maxlength="150">
        </div>
        <div class="form-group">
          <label for="no_telepon">No. Telepon <span class="opt">(opsional)</span></label>
          <input type="tel" id="no_telepon" name="no_telepon"
                 value="<?= $telp ?>" placeholder="08xxxxxxxxxx" maxlength="20">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="asal">Asal / Instansi <span class="opt">(opsional)</span></label>
          <input type="text" id="asal" name="asal"
                 value="<?= $asal ?>" placeholder="Kota atau nama perusahaan" maxlength="100">
        </div>
      </div>

      <p class="section-title">Informasi Kunjungan</p>

      <div class="form-row cols-2">
        <div class="form-group">
          <label for="tanggal">Tanggal Kunjungan <span class="required">*</span></label>
          <input type="date" id="tanggal" name="tanggal"
                 value="<?= $tgl ?>" required>
        </div>
        <div class="form-group">
          <label for="waktu">Jam Masuk <span class="required">*</span></label>
          <input type="time" id="waktu" name="waktu"
                 value="<?= $waktu ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="keperluan">Keperluan <span class="required">*</span></label>
          <select id="keperluan" name="keperluan" required>
            <option value="">-- Pilih Keperluan --</option>
            <?php foreach ($keperluan_options as $opt): ?>
              <option value="<?= $opt ?>" <?= $keperlu === $opt ? 'selected' : '' ?>>
                <?= $opt ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="pesan">Pesan / Komentar <span class="opt">(opsional)</span></label>
          <textarea id="pesan" name="pesan"
                    placeholder="Tuliskan kesan, pesan, atau keperluan tambahan Anda..."
                    maxlength="1000"><?= htmlspecialchars($_GET['pesan'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
      </div>

      <p class="section-title">Penilaian</p>

      <div class="form-group">
        <label>Rating Pelayanan <span class="opt">(opsional)</span></label>
        <div class="star-group" role="radiogroup" aria-label="Rating pelayanan">
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
            <label for="star<?= $i ?>" title="<?= $i ?> bintang" aria-label="<?= $i ?> bintang">&#9733;</label>
          <?php endfor; ?>
        </div>
      </div>

      <div class="btn-row">
        <a href="daftar.php" class="link-daftar">📋 Lihat Daftar Tamu</a>
        <button type="submit" class="btn-primary">Kirim →</button>
      </div>

    </form>
  </div>
</div>
</body>
</html>
