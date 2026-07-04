<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Pengajuan Paspor - Kantor Imigrasi Cabang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background:#f4f6f9; }
  .navbar-brand { font-weight:600; }
  .card { border:none; box-shadow:0 2px 10px rgba(0,0,0,.08); border-radius:12px; }
  .table thead { background:#0d6efd; color:#fff; }
  .table td, .table th { vertical-align:middle; }
  .badge-ok { background:#198754; }
  .badge-tidak { background:#dc3545; }
  footer { color:#888; font-size:.85rem; }
  .nav-link.active-menu { font-weight:700; text-decoration:underline; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="daftar.php">🛂 Pengajuan Paspor — Kantor Imigrasi Cabang</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '') == 'daftar' ? 'active-menu' : '' ?>" href="daftar.php">Daftar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '') == 'daftar_ulang' ? 'active-menu' : '' ?>" href="daftar_ulang.php">Daftar Ulang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '') == 'pengurusan' ? 'active-menu' : '' ?>" href="pengurusan.php">Pengurusan</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mb-5">
