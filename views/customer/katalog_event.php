<?php
// FILE: views/customer/katalog_event.php (Katalog Layanan Event)
session_start();

require_once '../../config/koneksi.php'; // Menggunakan koneksi.php dari config

// Cek sesi (Guest/Customer)
if (!isset($_SESSION['hak_akses'])) {
    $_SESSION['hak_akses'] = 'Customer';
    $_SESSION['username'] = 'Guest';
}

$nama_pengguna = $_SESSION['username'];

// --- LOGIKA TAMBAH KE KERANJANG ---
if (isset($_GET['action']) && $_GET['action'] == 'tambah' && isset($_GET['id'])) {
    $id_layanan_tambah = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // 1. Ambil detail layanan dari database
    $query_item = "SELECT id_layanan, nama_layanan, biaya_layanan FROM layanan_event WHERE id_layanan = '$id_layanan_tambah'";
    $result_item = mysqli_query($koneksi, $query_item);

    if ($result_item && mysqli_num_rows($result_item) == 1) {
        $item = mysqli_fetch_assoc($result_item);
        
        // Buat ID unik untuk keranjang (contoh: 1-Layanan)
        $keranjang_id = $item['id_layanan'] . '-Layanan'; 

        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        // Set default jumlah (layanan biasanya 1 paket)
        $jumlah = 1;

        // Cek apakah item sudah ada di keranjang
        if (isset($_SESSION['keranjang'][$keranjang_id])) {
            $_SESSION['keranjang'][$keranjang_id]['jumlah'] += $jumlah;
        } else {
            $_SESSION['keranjang'][$keranjang_id] = [
                'id'        => $item['id_layanan'],
                'nama'      => $item['nama_layanan'],
                'harga'     => (int)$item['biaya_layanan'],
                'jumlah'    => $jumlah,
                'jenis'     => 'Layanan'
            ];
        }
        
        $_SESSION['success_message'] = $item['nama_layanan'] . " berhasil ditambahkan ke keranjang.";
        
    } else {
        $_SESSION['error_message'] = "Layanan tidak ditemukan.";
    }

    // Redirect ke halaman keranjang
    header('Location: keranjang.php');
    exit();
}
// --- END LOGIKA ---


// Query daftar layanan
$query_layanan = "SELECT id_layanan, nama_layanan, deskripsi, biaya_layanan FROM layanan_event ORDER BY nama_layanan ASC";
$result_layanan = mysqli_query($koneksi, $query_layanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Layanan Event | Sewa Event</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Tema warna utama */
        .btn-purple,
        .rent-btn {
            background-color: #8A4DFF !important;
            color: white !important;
        }

        .link-purple {
            color: #8A4DFF !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1200px; margin: 90px auto 30px auto;">
            
            <h2><i class="fas fa-camera"></i> Katalog Layanan Event</h2>
            <p>Pilih vendor dan layanan tambahan untuk kesuksesan acara Anda.</p>

            <!-- Link Kembali dengan warna tema -->
            <a href="index.php" class="link-purple" style="margin-bottom: 20px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Kembali ke Pilihan Kategori
            </a>

            <hr>

            <div class="catalog-grid">
                <?php if ($result_layanan && mysqli_num_rows($result_layanan) > 0): ?>
                    <?php while ($layanan = mysqli_fetch_assoc($result_layanan)): ?>
                    <div class="product-card">
                        <div style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f1f1f1; font-size: 50px; color: var(--secondary-color);">
                            <i class="fas fa-tags"></i>
                        </div>

                        <div class="product-card-body">
                            <h5><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h5>
                            <p class="price">Rp. <?php echo number_format($layanan['biaya_layanan'], 0, ',', '.'); ?></p>
                            <p class="stok" style="height: 40px; overflow: hidden; font-size: 13px;">
                                <?php echo htmlspecialchars($layanan['deskripsi']); ?>
                            </p>

                            <!-- Tombol pesan layanan warna ungu -->
                            <a href="katalog_event.php?action=tambah&id=<?php echo htmlspecialchars($layanan['id_layanan']); ?>" 
                               class="rent-btn btn-purple">
                                <i class="fas fa-calendar-alt"></i> Pesan Layanan
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1;">Mohon maaf, saat ini belum ada layanan event yang tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>
