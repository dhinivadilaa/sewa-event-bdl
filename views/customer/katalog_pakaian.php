<?php
// FILE: views/customer/katalog_pakaian.php (Katalog Pakaian)
session_start();

require_once '../../config/koneksi.php'; 

// Cek sesi (Guest/Customer)
if (!isset($_SESSION['hak_akses'])) {
    $_SESSION['hak_akses'] = 'Customer';
    $_SESSION['username'] = 'Guest';
}

$nama_pengguna = $_SESSION['username'];

// --- LOGIKA TAMBAH KE KERANJANG ---
if (isset($_POST['tambah_keranjang'])) {
    $id_pakaian_tambah = mysqli_real_escape_string($koneksi, $_POST['id_pakaian']);
    $lama_sewa = (int)$_POST['lama_sewa'];

    // Validasi input
    if ($lama_sewa < 1) {
        $_SESSION['error_message'] = "Lama sewa minimal 1 hari.";
        header('Location: katalog_pakaian.php');
        exit();
    }
    
    // 1. Ambil detail pakaian dari database
    $query_item = "SELECT id_pakaian, nama_pakaian, harga_sewa, stok FROM pakaian WHERE id_pakaian = '$id_pakaian_tambah'";
    $result_item = mysqli_query($koneksi, $query_item);

    if ($result_item && mysqli_num_rows($result_item) == 1) {
        $item = mysqli_fetch_assoc($result_item);
        
        // Cek stok (asumsi 1 item pakaian hanya bisa disewa 1 kali per transaksi, stok adalah jumlah unit)
        if ($item['stok'] < 1) {
             $_SESSION['error_message'] = $item['nama_pakaian'] . " sedang tidak tersedia (Stok 0).";
             header('Location: katalog_pakaian.php');
             exit();
        }

        // Buat ID unik untuk keranjang (contoh: GP001-Pakaian)
        $keranjang_id = $item['id_pakaian'] . '-Pakaian'; 

        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        // Masukkan atau perbarui item di keranjang
        $_SESSION['keranjang'][$keranjang_id] = [
            'id'        => $item['id_pakaian'],
            'nama'      => $item['nama_pakaian'],
            'harga'     => (int)$item['harga_sewa'],
            'jumlah'    => $lama_sewa, // Jumlah di sini adalah Lama Sewa (Hari)
            'jenis'     => 'Pakaian'
        ];
        
        $_SESSION['success_message'] = $item['nama_pakaian'] . " berhasil ditambahkan/diperbarui di keranjang (Lama Sewa: " . $lama_sewa . " Hari).";
        
    } else {
        $_SESSION['error_message'] = "Pakaian tidak ditemukan.";
    }

    // Redirect ke halaman keranjang untuk melihat hasilnya
    header('Location: keranjang.php');
    exit();
}
// --- END LOGIKA TAMBAH KE KERANJANG ---


// Query untuk mengambil data pakaian.
$query_pakaian = "SELECT id_pakaian, nama_pakaian, deskripsi, harga_sewa, stok, gambar FROM pakaian WHERE stok > 0 ORDER BY nama_pakaian ASC";
$result_pakaian = mysqli_query($koneksi, $query_pakaian);

// Tambahkan penanganan error query yang aman
if (!$result_pakaian) {
    die("Error Query: " . mysqli_error($koneksi) . " | Pastikan kolom 'deskripsi' dan 'gambar' sudah ada di tabel 'pakaian'.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Pakaian | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Penyesuaian Style untuk Katalog Pakaian agar lebih menarik */
        .product-card {
            /* Menggunakan gaya yang diwarisi dari style.css, tetapi diperjelas untuk kontras ungu */
            border: 1px solid #e9ecef;
        }

        .product-card img {
            /* Menyesuaikan ukuran gambar untuk tampilan kartu yang lebih baik */
            height: 250px; 
            object-fit: cover;
        }

        .product-card-body h5 {
            min-height: 40px; /* Memastikan tinggi judul seragam */
        }
        
        .product-card-body .price {
             /* Harga sewa per hari ditonjolkan dengan warna success */
            font-size: 18px;
            margin-bottom: 15px;
        }

        .product-card:hover {
            box-shadow: 0 5px 20px rgba(138, 77, 255, 0.2); /* Shadow ungu saat hover */
            transform: translateY(-4px);
        }

        .input-group-sewa {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .input-group-sewa label {
            flex-grow: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group-sewa input[type="number"] {
            width: 80px;
            padding: 6px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            text-align: center;
            box-sizing: border-box;
        }

    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1200px; margin: 90px auto 30px auto;">
            <h2><i class="fas fa-tshirt" style="color: var(--primary-color);"></i> Katalog Pakaian Tersedia</h2>
            <p>Pilihan terbaik untuk jas, gaun, dan pakaian adat. Pilih lama sewa (Hari) yang Anda butuhkan.</p>
            <a href="index.php" style="margin-bottom: 20px; display: inline-block;"><i class="fas fa-arrow-left"></i> Kembali ke Kategori</a>
            <hr>
            
            <?php 
            // Tampilkan pesan sukses atau error (Jika ada)
            if (isset($_SESSION['success_message'])) {
                echo '<p style="color: var(--success-color); background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> ' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
             if (isset($_SESSION['error_message'])) {
                echo '<p style="color: var(--danger-color); background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }
            ?>

            <div class="catalog-grid">
                <?php if (mysqli_num_rows($result_pakaian) > 0): ?>
                    <?php while ($pakaian = mysqli_fetch_assoc($result_pakaian)): ?>
                    <div class="product-card">
                        <img src="../../assets/img/pakaian_default.jpg" alt="<?php echo htmlspecialchars($pakaian['nama_pakaian']); ?>">
                        <div class="product-card-body">
                            <h5><?php echo htmlspecialchars($pakaian['nama_pakaian']); ?></h5>
                            <p class="price">Rp. <?php echo number_format($pakaian['harga_sewa'], 0, ',', '.'); ?> / hari</p>
                            
                            <?php if (!empty($pakaian['deskripsi'])): ?>
                                <p style="font-size: 13px; color: var(--secondary-color); margin-bottom: 10px; height: 35px; overflow: hidden;"><?php echo htmlspecialchars($pakaian['deskripsi']); ?></p>
                            <?php endif; ?>
                            
                            <p class="stok"><i class="fas fa-boxes"></i> Stok: **<?php echo $pakaian['stok']; ?>**</p>
                            
                            <form action="katalog_pakaian.php" method="POST">
                                <input type="hidden" name="id_pakaian" value="<?php echo htmlspecialchars($pakaian['id_pakaian']); ?>">
                                
                                <div class="input-group-sewa">
                                    <label for="lama_sewa_<?php echo $pakaian['id_pakaian']; ?>">
                                        Lama Sewa (Hari)
                                    </label>
                                    <input type="number" name="lama_sewa" id="lama_sewa_<?php echo $pakaian['id_pakaian']; ?>" 
                                        value="1" min="1" max="30" required>
                                </div>
                                
                                <button type="submit" name="tambah_keranjang" class="rent-btn" style="border: none;">
                                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                                </button>
                            </form>
                            </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1;">Mohon maaf, saat ini belum ada pakaian yang tersedia dalam katalog.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>