<?php
// FILE: login_process.php (Logika Otentikasi)
session_start();
require_once 'config.php'; // Hubungkan ke database

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Password masih plaintext

    // Amankan input
    $username = mysqli_real_escape_string($koneksi, $username);
    
    // 1. Query untuk mendapatkan data pengguna berdasarkan username
    $query = "SELECT id_pengguna, username, password, hak_akses, id_referensi FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // 2. Verifikasi Password (di implementasi nyata, gunakan password_verify)
        // Karena kita menggunakan password plaintext ('password123') di dummy data,
        // kita verifikasi secara langsung.
        if ($password === $user['password']) { 
            
            // 3. Login Berhasil: Set Sesi
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['hak_akses'] = $user['hak_akses'];
            $_SESSION['id_referensi'] = $user['id_referensi'];
            
            $hak_akses_folder = strtolower($user['hak_akses']);
            
            // 4. Redirect ke Dashboard yang Sesuai
            header('Location: views/' . $hak_akses_folder . '/index.php');
            exit();

        } else {
            // Password salah
            $_SESSION['error_message'] = "Password salah.";
            header('Location: index.php');
            exit();
        }
    } else {
        // Username tidak ditemukan
        $_SESSION['error_message'] = "Username tidak terdaftar.";
        header('Location: index.php');
        exit();
    }
}
?>