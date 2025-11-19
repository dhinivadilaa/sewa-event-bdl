<?php
// FILE: views/admin/index.php (Dashboard Admin)
session_start();

// Cek Otentikasi & Hak Akses
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] !== 'Admin') {
    header('Location: ../../index.php'); // Redirect ke login jika belum login/bukan admin
    exit();
}

require_once '../../config/koneksi.php'; // Menggunakan koneksi.php dari config

// Ambil Nama Admin dari tabel admin
$id_admin = $_SESSION['id_referensi'];
// CATATAN: Pastikan ada tabel 'admin' dan 'id_referensi' cocok
$query_admin = "SELECT nama_admin FROM admin WHERE id_admin = '$id_admin'"; 
$result_admin = mysqli_query($koneksi, $query_admin);

$nama_pengguna = "Admin"; // Default jika data tidak ditemukan
if ($result_admin && mysqli_num_rows($result_admin) == 1) {
    $data_admin = mysqli_fetch_assoc($result_admin);
    $nama_pengguna = $data_admin['nama_admin'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php 
    include('../includes/header.php'); // Include Header
    
    // Override $nama_pengguna untuk sesi
    $_SESSION['username'] = $nama_pengguna; 
    ?>
    
    <div class="wrapper">
        <?php include('../includes/sidebar_admin.php'); // Include Sidebar Admin ?>
        
        <div class="main-content">
            <h2><i class="fas fa-user-shield"></i> Selamat Datang, **<?php echo htmlspecialchars($nama_pengguna); ?>**</h2>
            <hr>
            
            <h3><i class="fas fa-chart-line"></i> Ringkasan Sistem</h3>
            <div class="summary-boxes">
                <?php
                // Query data ringkasan
                $q_pakaian = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pakaian"));
                $q_customer = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM customer"));
                $q_sewa_aktif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sewa_header WHERE status_sewa = 'Aktif'"));
                ?>
                <div class="box">
                    <h4>Total Pakaian Tersedia</h4>
                    <p><i class="fas fa-tshirt"></i> <?php echo $q_pakaian['total']; ?> Jenis</p>
                </div>
                <div class="box">
                    <h4>Pelanggan Terdaftar</h4>
                    <p><i class="fas fa-users"></i> <?php echo $q_customer['total']; ?> Orang</p>
                </div>
                <div class="box">
                    <h4>Sewa Aktif Saat Ini</h4>
                    <p><i class="fas fa-receipt"></i> <?php echo $q_sewa_aktif['total']; ?> Transaksi</p>
                </div>
            </div>
            
            <h3><i class="fas fa-bell"></i> Notifikasi Terbaru</h3>
            <div class="box" style="width: 100%;">
                <p>Belum ada notifikasi penting. Data terakhir diakses: <?php echo date('d-m-Y H:i:s'); ?></p>
            </div>

        </div>
    </div>
    
    <?php include('../includes/footer.php'); // Include Footer ?>
</body>
</html>