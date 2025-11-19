<?php
// FILE: index.php (Halaman Login)
session_start();

// Cek jika pengguna sudah login, langsung redirect ke dashboard yang sesuai
if (isset($_SESSION['hak_akses'])) {
    $hak_akses = strtolower($_SESSION['hak_akses']);
    header('Location: views/' . $hak_akses . '/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Sewa Event</title>
    <link rel="stylesheet" href="assets/css/style.css"> </head>
<body>
    <div class="login-container">
        <h1>Sewa Event & Pakaian</h1>
        <p>Silakan Login untuk melanjutkan</p>

        <?php
        // Tampilkan pesan error jika ada (setelah redirect dari login_process.php)
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
        }
        ?>

        <form action="login_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        
        <p class="mt-3">
            <a href="views/customer/index.php">Lanjutkan sebagai Customer (Lihat Katalog)</a>
        </p>
    </div>
</body>
</html>