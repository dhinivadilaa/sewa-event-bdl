<?php
// FILE: views/customer/index.php (Katalog Pakaian untuk Customer)
session_start();

require_once '../../config/koneksi.php'; // Menggunakan koneksi.php dari config

// Query untuk mengambil data pakaian
$query_pakaian = "SELECT id_pakaian, nama_pakaian, deskripsi, harga_sewa, stok, gambar FROM pakaian WHERE stok > 0 ORDER BY nama_pakaian ASC";
$result_pakaian = mysqli_query($koneksi, $query_pakaian);

// Set sesi hak akses sebagai customer jika belum ada
if (!isset($_SESSION['hak_akses'])) {
    $_SESSION['hak_akses'] = 'Customer';
    $_SESSION['username'] = 'Guest';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Pakaian | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include('../includes/header.php'); // Include Header ?>
    
    <div class="wrapper" style="display: block;"> <div class="main-content" style="margin-left: 0;">
            <h2><i class="fas fa-store"></i> Katalog Pakaian & Event</h2>
            <p>Temukan pakaian dan perlengkapan event terbaik untuk acara Anda.</p>
            <hr>
            
            <div class="catalog-grid">
                <?php if (mysqli_num_rows($result_pakaian) > 0): ?>
                    <?php while ($pakaian = mysqli_fetch_assoc($result_pakaian)): ?>
                    <div class="product-card">
                        <img src="../../assets/img/pakaian_default.jpg" alt="<?php echo htmlspecialchars($pakaian['nama_pakaian']); ?>">
                        <div class="product-card-body">
                            <h5><?php echo htmlspecialchars($pakaian['nama_pakaian']); ?></h5>
                            <p class="price">Rp. <?php echo number_format($pakaian['harga_sewa'], 0, ',', '.'); ?> / hari</p>
                            <p class="stok"><i class="fas fa-boxes"></i> Stok: **<?php echo $pakaian['stok']; ?>**</p>
                            <a href="#" class="rent-btn">
                                <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1;">Mohon maaf, saat ini belum ada pakaian yang tersedia dalam katalog.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include('../includes/footer.php'); // Include Footer ?>
</body>
</html>