<?php
$host = "localhost";
$user = "root"; // Ganti dengan user database Anda
$pass = ""; // Ganti dengan password database Anda
$db = "sewa_event";

// Buat koneksi
$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
// echo "Koneksi berhasil!"; // Hapus baris ini setelah pengujian
?>