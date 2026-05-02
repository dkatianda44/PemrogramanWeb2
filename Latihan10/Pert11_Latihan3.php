<!DOCTYPE html>
<html>
<head>
    <title>Form Buku Tamu</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        .form-group { margin: 15px 0; }
        label { display: inline-block; width: 100px; }
        input, textarea { padding: 8px; width: 300px; }
        button { 
            padding: 10px 20px; 
            background: #4CAF50; 
            color: white; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #45a049; }
        .back { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>✍️ Isi Buku Tamu</h2>
    <form action="Pert11_Latihan4.php" method="post">
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Komentar:</label>
            <textarea name="komentar" rows="5" required></textarea>
        </div>
        <button type="submit">Simpan</button>
        <button type="reset">Reset</button>
    </form>
    <div class="back">
        <a href="Pert11_Latihan2.php">← Kembali ke Menu</a>
    </div>
</body>
</html>