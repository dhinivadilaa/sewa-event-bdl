<?php
// FILE: views/admin/index.php (Dashboard Admin)
session_start();

// Cek Otentikasi & Hak Akses
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] !== 'Admin') {
    header('Location: ../../index.php'); // Redirect ke login jika belum login/bukan admin
    exit();
}

require_once '../../config/koneksi.php';

// Ambil Nama Admin dari tabel admin
$id_admin = $_SESSION['id_referensi'];
$query_admin = "SELECT nama_admin FROM admin WHERE id_admin = '$id_admin'";
$data_admin = mysqli_fetch_assoc(mysqli_query($koneksi, $query_admin));

$nama_pengguna = $data_admin['nama_admin'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include('../includes/header.php'); // Include Header ?>
    
    <div class="wrapper">
        <?php include('../includes/sidebar_admin.php'); // Include Sidebar Admin ?>
        
        <div class="main-content">
            <h2>Halo, **<?php echo $nama_pengguna; ?>** (Admin Utama)</h2>
            <hr>
            
            <h3>📝 Ringkasan Sistem</h3>
            <div class="summary-boxes">
                <?php
                $q_pakaian = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pakaian"));
                $q_customer = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM customer"));
                $q_sewa_aktif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sewa_header WHERE status_sewa = 'Aktif'"));
                ?>
                <div class="box">
                    <h4>Total Pakaian</h4>
                    <p><?php echo $q_pakaian['total']; ?> Jenis</p>
                </div>
                <div class="box">
                    <h4>Pelanggan Terdaftar</h4>
                    <p><?php echo $q_customer['total']; ?> Orang</p>
                </div>
                <div class="box">
                    <h4>Sewa Aktif</h4>
                    <p><?php echo $q_sewa_aktif['total']; ?> Transaksi</p>
                </div>
            </div>
            
            </div>
    </div>
    
    <?php include('../includes/footer.php'); // Include Footer ?>
</body>
</html>