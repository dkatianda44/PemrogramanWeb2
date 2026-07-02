<?php
/****************************************************
Halaman ini merupakan halaman contoh penciptaan session. 
Perintah session_start() harus ditaruh di perintah pertama tanpa spasi.
*****************************************************/
session_start();

if (isset($_POST['Login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

   //periksa login
    if ($user == "DaffaKatianda" && $pass == "123") {
        // Menciptakan session
        $_SESSION['login'] = $user;
        
        // Menampilkan pesan sukses
        echo "<h1>Anda berhasil LOGIN</h1>";
        echo "<h2>Klik <a href='session2.php'>di sini (session2.php)</a> 
        untuk menuju ke halaman pemeriksaan session</h2>";
    } else {
        echo "<h1>Username atau Password Salah!</h1>";
        echo "<h2>Silakan <a href=''>Login Kembali</a></h2>";
    }

} else {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login here...</title>
    </head>
    <body>
        <form action="" method="post">
            <h2>Login Here...</h2>
            Username : <input type="text" name="user" required><br><br>
            Password : <input type="password" name="pass" required><br><br>
            <input type="submit" name="Login" value="Log In">
        </form>
    </body>
    </html>
<?php 
} 
?>