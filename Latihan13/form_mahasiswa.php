<?php
require_once 'config.php';

$success = '';
$error   = '';

// ── HAPUS DATA ──────────────────────────────────────────────
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id = ?");
    $stmt->bind_param("i", $id_hapus);
    $stmt->execute();
    $stmt->close();
    header("Location: form_mahasiswa.php?deleted=1");
    exit;
}
if (isset($_GET['deleted'])) $success = "Data mahasiswa berhasil dihapus.";

// ── SIMPAN DATA ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim     = trim($_POST['nim']     ?? '');
    $nama    = trim($_POST['nama']    ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $alamat  = trim($_POST['alamat']  ?? '');
    $notelp  = trim($_POST['notelp']  ?? '');

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($alamat) || empty($notelp)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Cek NIM duplikat
        $cek = $conn->prepare("SELECT id FROM mahasiswa WHERE nim = ?");
        $cek->bind_param("s", $nim);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = "NIM <strong>{$nim}</strong> sudah terdaftar!";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO mahasiswa (nim, nama, jurusan, alamat, notelp) VALUES (?,?,?,?,?)"
            );
            $stmt->bind_param("sssss", $nim, $nama, $jurusan, $alamat, $notelp);
            if ($stmt->execute()) {
                $success = "Data mahasiswa <strong>{$nama}</strong> (NIM: {$nim}) berhasil disimpan!";
                // Kosongkan POST agar form bersih
                $_POST = [];
            } else {
                $error = "Gagal menyimpan data: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        }
        $cek->close();
    }
}

// ── AMBIL SEMUA DATA ────────────────────────────────────────
$result   = $conn->query("SELECT * FROM mahasiswa ORDER BY created_at DESC");
$students = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Data Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:       #E8A020;
            --gold-light: #F5C45A;
            --gold-dark:  #B87D10;
            --cream:      #FDFAF4;
            --white:      #FFFFFF;
            --gray-50:    #F9F7F2;
            --gray-100:   #EDEBE4;
            --gray-300:   #C8C4B8;
            --gray-500:   #8C8880;
            --gray-700:   #4A4844;
            --dark:       #1E1C18;
            --red:        #E05252;
            --green:      #4CAF50;
            --shadow-lg:  0 12px 40px rgba(30,28,24,.14);
            --radius:     10px;
            --tr:         .22s cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            padding: 2rem 1rem 4rem;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(232,160,32,.10) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(232,160,32,.07) 0%, transparent 55%);
        }

        .page-wrap {
            max-width: 860px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* ── CARD ── */
        .card {
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: cardIn .5s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(24px) scale(.97); }
            to   { opacity:1; transform:translateY(0)    scale(1);   }
        }

        /* ── HEADER ── */
        .card-header {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            padding: 1.8rem 2.5rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content:''; position:absolute; top:-40px; right:-40px;
            width:140px; height:140px; border-radius:50%;
            background:rgba(255,255,255,.10);
        }
        .card-header h1 {
            font-family:'Playfair Display',serif;
            font-size:1.5rem; font-weight:700;
            color:#fff; position:relative; z-index:1;
        }
        .card-header p {
            font-size:.82rem; color:rgba(255,255,255,.80);
            margin-top:.25rem; font-weight:300; position:relative; z-index:1;
        }

        /* ── BODY ── */
        .card-body { padding: 2rem 2.5rem 2.2rem; }

        /* ── ALERT ── */
        .alert {
            border-radius: var(--radius);
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.4rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            animation: alertIn .3s ease both;
        }
        @keyframes alertIn {
            from { opacity:0; transform:translateY(-6px); }
            to   { opacity:1; transform:translateY(0);    }
        }
        .alert-success { background:#F0FBF0; border-left:3px solid var(--green); color:#276127; }
        .alert-error   { background:#FFF3F3; border-left:3px solid var(--red);   color:#7B1C1C; }

        /* ── FORM GRID ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.1rem 1.4rem;
        }
        .form-group { display:flex; flex-direction:column; gap:.4rem; }
        .form-group.full { grid-column: 1 / -1; }

        label { font-size:.82rem; font-weight:500; color:var(--gray-700); letter-spacing:.02em; }
        label span.req { color:var(--gold); margin-left:2px; }

        input[type="text"], input[type="tel"], select, textarea {
            width:100%; padding:.65rem 1rem;
            border:1.5px solid var(--gray-100);
            border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;
            font-size:.88rem; color:var(--dark);
            background:var(--gray-50);
            transition:border-color var(--tr), box-shadow var(--tr), background var(--tr);
            outline:none; -webkit-appearance:none; appearance:none;
        }
        input:focus, select:focus, textarea:focus {
            border-color:var(--gold);
            background:var(--white);
            box-shadow:0 0 0 3px rgba(232,160,32,.15);
        }
        input::placeholder, textarea::placeholder { color:var(--gray-300); }

        .select-wrap { position:relative; }
        .select-wrap::after {
            content:''; position:absolute; right:13px; top:50%;
            transform:translateY(-50%);
            border-left:5px solid transparent; border-right:5px solid transparent;
            border-top:6px solid var(--gray-500); pointer-events:none;
        }
        .select-wrap:focus-within::after { border-top-color:var(--gold); }

        textarea { resize:vertical; min-height:70px; }

        /* ── BUTTONS ── */
        .btn-group { display:flex; gap:.75rem; margin-top:1.6rem; }
        .btn {
            flex:1; padding:.7rem 1.2rem;
            border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;
            font-size:.88rem; font-weight:500;
            cursor:pointer; border:none;
            transition:all var(--tr);
        }
        .btn-primary {
            background:linear-gradient(135deg,var(--gold-light) 0%,var(--gold) 50%,var(--gold-dark) 100%);
            background-size:200% 100%; background-position:right center;
            color:#fff; box-shadow:0 3px 12px rgba(232,160,32,.35);
        }
        .btn-primary:hover { background-position:left center; transform:translateY(-1px); box-shadow:0 5px 18px rgba(232,160,32,.45); }
        .btn-secondary { background:transparent; color:var(--gray-500); border:1.5px solid var(--gray-100); }
        .btn-secondary:hover { border-color:var(--gray-300); color:var(--gray-700); background:var(--gray-50); }

        /* ── TABLE ── */
        .table-section { animation: cardIn .5s .15s cubic-bezier(.22,1,.36,1) both; }
        .table-header {
            background:linear-gradient(135deg,var(--gold) 0%,var(--gold-dark) 100%);
            padding:1.2rem 2.5rem;
            display:flex; align-items:center; justify-content:space-between;
        }
        .table-header h2 { font-family:'Playfair Display',serif; font-size:1.1rem; color:#fff; }
        .table-header .badge {
            background:rgba(255,255,255,.25); color:#fff;
            font-size:.75rem; font-weight:600;
            padding:.25rem .65rem; border-radius:20px;
        }

        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:.85rem; }
        thead { background:var(--gray-50); }
        thead th {
            padding:.75rem 1rem; text-align:left;
            font-size:.75rem; font-weight:600;
            color:var(--gray-500); text-transform:uppercase;
            letter-spacing:.06em; white-space:nowrap;
            border-bottom:1.5px solid var(--gray-100);
        }
        tbody tr { transition:background var(--tr); }
        tbody tr:hover { background:var(--gray-50); }
        tbody td {
            padding:.75rem 1rem;
            border-bottom:1px solid var(--gray-100);
            color:var(--gray-700); vertical-align:top;
        }
        .nim-badge {
            background:rgba(232,160,32,.12); color:var(--gold-dark);
            font-size:.78rem; font-weight:600;
            padding:.2rem .55rem; border-radius:6px; white-space:nowrap;
        }
        .jurusan-pill {
            background:var(--gray-50); color:var(--gray-700);
            font-size:.78rem; padding:.2rem .55rem; border-radius:6px;
            border:1px solid var(--gray-100); white-space:nowrap;
        }
        .btn-hapus {
            background:transparent; border:1.5px solid #f5c0c0;
            color:var(--red); border-radius:6px;
            padding:.25rem .6rem; font-size:.78rem;
            cursor:pointer; transition:all var(--tr);
            font-family:'DM Sans',sans-serif; font-weight:500;
        }
        .btn-hapus:hover { background:#fff0f0; border-color:var(--red); }

        .empty-state {
            padding:3rem 1rem; text-align:center;
            color:var(--gray-300); font-size:.9rem;
        }
        .empty-state svg { opacity:.35; margin-bottom:.75rem; }

        .card-footer {
            padding:.85rem 2.5rem; font-size:.76rem;
            color:var(--gray-300); text-align:center;
            border-top:1px solid var(--gray-100);
        }

        @media(max-width:600px) {
            .form-grid { grid-template-columns:1fr; }
            .card-header, .card-body, .table-header { padding-left:1.25rem; padding-right:1.25rem; }
            tbody td, thead th { padding:.65rem .75rem; }
        }
    </style>
</head>
<body>
<div class="page-wrap">

    <!-- ══ FORM CARD ══ -->
    <div class="card">
        <div class="card-header">
            <h1>Form Input Data Mahasiswa</h1>
            <p>Isi semua field yang tersedia dengan benar</p>
        </div>
        <div class="card-body">

            <?php if ($success): ?>
            <div class="alert alert-success"><span>✓</span><span><?= $success ?></span></div>
            <?php elseif ($error): ?>
            <div class="alert alert-error"><span>✕</span><span><?= $error ?></span></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-grid">

                    <!-- NIM -->
                    <div class="form-group">
                        <label for="nim">ID Mahasiswa / NIM <span class="req">*</span></label>
                        <input type="text" id="nim" name="nim" placeholder="Cth: 2021001001"
                               value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
                    </div>

                    <!-- Nama -->
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="nama" name="nama" placeholder="Nama lengkap mahasiswa"
                               value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                    </div>

                    <!-- Jurusan -->
                    <div class="form-group">
                        <label for="jurusan">Jurusan <span class="req">*</span></label>
                        <div class="select-wrap">
                            <select id="jurusan" name="jurusan">
                                <option value="">- Pilih Jurusan -</option>
                                <?php
                                $jurusanList = [
                                    'Teknik Informatika','Sistem Informasi','Manajemen',
                                    'Akuntansi','Hukum','Kedokteran','Psikologi'
                                ];
                                foreach ($jurusanList as $j):
                                    $sel = (($_POST['jurusan'] ?? '') === $j) ? 'selected' : '';
                                    echo "<option value=\"{$j}\" {$sel}>{$j}</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- No. Telp -->
                    <div class="form-group">
                        <label for="notelp">No. Telp <span class="req">*</span></label>
                        <input type="tel" id="notelp" name="notelp" placeholder="Cth: 08123456789"
                               value="<?= htmlspecialchars($_POST['notelp'] ?? '') ?>">
                    </div>

                    <!-- Alamat -->
                    <div class="form-group full">
                        <label for="alamat">Alamat <span class="req">*</span></label>
                        <textarea id="alamat" name="alamat" placeholder="Alamat lengkap mahasiswa"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                    </div>

                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset"  class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
        <div class="card-footer">Sistem Informasi Akademik &mdash; Data bersifat rahasia</div>
    </div>

    <!-- ══ TABLE CARD ══ -->
    <div class="card table-section">
        <div class="table-header">
            <h2>Data Mahasiswa Terdaftar</h2>
            <span class="badge"><?= count($students) ?> mahasiswa</span>
        </div>

        <?php if (empty($students)): ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <p>Belum ada data mahasiswa yang tersimpan.</p>
        </div>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $i => $mhs): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="nim-badge"><?= htmlspecialchars($mhs['nim']) ?></span></td>
                        <td><?= htmlspecialchars($mhs['nama']) ?></td>
                        <td><span class="jurusan-pill"><?= htmlspecialchars($mhs['jurusan']) ?></span></td>
                        <td><?= nl2br(htmlspecialchars($mhs['alamat'])) ?></td>
                        <td><?= htmlspecialchars($mhs['notelp']) ?></td>
                        <td>
                            <a href="?hapus=<?= $mhs['id'] ?>"
                               onclick="return confirm('Hapus data <?= htmlspecialchars($mhs['nama']) ?>?')"
                               style="text-decoration:none">
                                <button class="btn-hapus">Hapus</button>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
