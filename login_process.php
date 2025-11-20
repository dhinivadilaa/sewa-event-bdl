<?php
// FILE: login_process.php
session_start();
require_once 'config.php'; // Atau config/koneksi.php, sesuaikan path

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Ingat: JANGAN PERNAH menyimpan password tanpa hash di sistem nyata!

    // Sanitasi input
    $username = mysqli_real_escape_string($koneksi, $username);
    // Password tidak perlu disanitasi jika akan dicompare dengan hash

    // Query untuk mencari pengguna berdasarkan username
    $query = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Cek Password (Dalam kasus nyata, gunakan password_verify($password, $user['password']))
        if ($password == $user['password']) {
            
            // Login Berhasil
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['hak_akses'] = $user['hak_akses'];
            $_SESSION['id_referensi'] = $user['id_referensi']; // ID Customer, Staff, Vendor, atau Admin

            $hak_akses_lower = strtolower($user['hak_akses']);
            
            // Logika Redirect Berdasarkan Hak Akses
            // Asumsi: folder view Anda memiliki struktur views/{hak_akses}/index.php
            
            if ($hak_akses_lower == 'admin') {
                header('Location: views/admin/index.php');
            } elseif ($hak_akses_lower == 'staff') {
                header('Location: views/staff/index.php'); // Pastikan folder views/staff ada
            } elseif ($hak_akses_lower == 'vendor') {
                header('Location: views/vendor/index.php'); // Pastikan folder views/vendor ada
            } elseif ($hak_akses_lower == 'customer') {
                header('Location: views/customer/index.php');
            } else {
                // Role tidak dikenali
                $_SESSION['error_message'] = "Hak akses tidak valid.";
                header('Location: index.php');
            }
            exit();

        } else {
            // Password Salah
            $_SESSION['error_message'] = "Password salah.";
            header('Location: index.php');
            exit();
        }
    } else {
        // Username Tidak Ditemukan
        $_SESSION['error_message'] = "Username tidak ditemukan.";
        header('Location: index.php');
        exit();
    }
}
?>