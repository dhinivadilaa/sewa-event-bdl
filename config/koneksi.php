<?php
// FILE: config/koneksi.php (Koneksi Database untuk Sub-direktori)

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
?>