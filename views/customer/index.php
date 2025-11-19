<?php
// FILE: views/customer/index.php (Halaman Pilihan Kategori Utama)
session_start();

require_once '../../config/koneksi.php'; 

// Set sesi hak akses sebagai customer jika belum ada (mode Guest/Lihat Katalog)
if (!isset($_SESSION['hak_akses'])) {
    $_SESSION['hak_akses'] = 'Customer';
    $_SESSION['username'] = 'Guest';
}

$nama_pengguna = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Kategori | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .category-grid {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 50px;
        }
        .category-card {
            width: 300px;
            text-align: center;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .category-card i {
            font-size: 60px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        .category-card h3 {
            color: var(--dark-text);
            margin-bottom: 15px;
            font-weight: 600;
        }
        /* Style untuk tombol navigasi cepat di atas kategori */
        .quick-nav a {
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1000px; margin: 90px auto 30px auto;">
            
            <div class="quick-nav" style="text-align: right; margin-bottom: 25px;">
                
                <a href="keranjang.php" style="background-color: var(--warning-color); color: var(--dark-text);">
                    <i class="fas fa-shopping-cart"></i> Lihat Keranjang 
                    <?php 
                    // Hitung jumlah item di keranjang jika ada
                    if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
                        echo ' (' . count($_SESSION['keranjang']) . ')';
                    }
                    ?>
                </a>
                
                <?php if ($_SESSION['username'] !== 'Guest'): ?>
                    <a href="riwayat_sewa.php" style="background-color: var(--secondary-color); color: white;">
                        <i class="fas fa-history"></i> Riwayat Sewa
                    </a>
                <?php endif; ?>
                
            </div>
            <h2><i class="fas fa-hand-point-right"></i> Halo, **<?php echo htmlspecialchars($nama_pengguna); ?>**. Apa yang Anda cari?</h2>
            <p>Pilih kategori utama untuk melihat katalog dan layanan yang tersedia.</p>
            <hr>
            
            <div class="category-grid">
                
                <a href="katalog_pakaian.php" class="category-card">
                    <i class="fas fa-tshirt"></i>
                    <h3>Sewa Pakaian & Kostum</h3>
                    <p>Gaun Pesta, Jas Formal, Kebaya, dan Pakaian Adat.</p>
                </a>
                
                <a href="katalog_event.php" class="category-card">
                    <i class="fas fa-camera"></i>
                    <h3>Layanan Event & Vendor</h3>
                    <p>Fotografer, Make Up Artist (MUA), Dekorasi, dan Staf Bantuan.</p>
                </a>
                
            </div>
            
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>