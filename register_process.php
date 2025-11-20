<?php
// FILE: register_process.php (Logika Registrasi Customer Baru)
session_start();
require_once 'config.php'; // Hubungkan ke database
// Pastikan config.php menggunakan mysqli

if (isset($_POST['register'])) {
    // 1. Ambil dan Sanitasi Data Input
    $nama_customer = mysqli_real_escape_string($koneksi, $_POST['nama_customer']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; // BELUM DI-HASH!
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // 2. Validasi Sederhana
    if (strlen($password) < 6) {
        $_SESSION['error_message'] = "Password minimal 6 karakter.";
        header('Location: index.php');
        exit();
    }

    // 3. Cek apakah Username sudah ada di tabel pengguna
    $query_check = "SELECT username FROM pengguna WHERE username = '$username'";
    $result_check = mysqli_query($koneksi, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['error_message'] = "Username **$username** sudah digunakan. Mohon gunakan username lain.";
        header('Location: index.php');
        exit();
    }
    
    // 4. START DATABASE TRANSACTION
    mysqli_begin_transaction($koneksi);
    $success = true;
    
    try {
        // A. INSERT data ke tabel `customer`
        $query_customer = "INSERT INTO customer (nama_customer, alamat, no_telp, email) 
                           VALUES ('$nama_customer', '$alamat', '$no_telp', '$email')";
        
        if (!mysqli_query($koneksi, $query_customer)) {
            $success = false;
            throw new Exception("Gagal menyimpan data Customer.");
        }
        
        // Ambil ID Customer yang baru dibuat
        $new_customer_id = mysqli_insert_id($koneksi);
        
        // B. INSERT data ke tabel `pengguna`
        // CATATAN: Pada sistem nyata, 'password' harus di-HASH menggunakan password_hash()
        $hak_akses = 'Customer';
        
        $query_pengguna = "INSERT INTO pengguna (username, password, hak_akses, id_referensi)
                           VALUES ('$username', '$password', '$hak_akses', '$new_customer_id')";
                           
        if (!mysqli_query($koneksi, $query_pengguna)) {
            $success = false;
            throw new Exception("Gagal menyimpan data Pengguna.");
        }

        // C. Jika semua sukses, COMMIT transaksi
        mysqli_commit($koneksi);
        
        $_SESSION['success_message'] = "Registrasi berhasil! Silakan **Login** dengan username Anda.";
        header('Location: index.php');
        
    } catch (Exception $e) {
        // D. Jika ada error, ROLLBACK transaksi
        mysqli_rollback($koneksi);
        $_SESSION['error_message'] = "Registrasi Gagal: Terjadi kesalahan database. " . $e->getMessage();
        header('Location: index.php');
    }
    
    exit();
}
?>