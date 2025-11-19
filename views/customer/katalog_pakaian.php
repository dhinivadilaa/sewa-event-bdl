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
// ASUMSI: Kolom 'deskripsi' dan 'gambar' sudah ditambahkan ke tabel 'pakaian'
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
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1200px; margin: 90px auto 30px auto;">
            <h2><i class="fas fa-tshirt"></i> Katalog Pakaian Tersedia</h2>
            <p>Pilihan terbaik untuk jas, gaun, dan pakaian adat.</p>
            <a href="index.php" style="margin-bottom: 20px; display: inline-block;"><i class="fas fa-arrow-left"></i> Kembali ke Pilihan Kategori</a>
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
                            
                            <?php if (!empty($pakaian['deskripsi'])): ?>
                                <p style="font-size: 13px; color: var(--secondary-color); margin-bottom: 10px; height: 35px; overflow: hidden;"><?php echo htmlspecialchars($pakaian['deskripsi']); ?></p>
                            <?php endif; ?>
                            
                            <form action="katalog_pakaian.php" method="POST">
                                <input type="hidden" name="id_pakaian" value="<?php echo htmlspecialchars($pakaian['id_pakaian']); ?>">
                                
                                <label for="lama_sewa_<?php echo $pakaian['id_pakaian']; ?>" style="font-size: 14px; display: block; margin-top: 10px; margin-bottom: 5px; font-weight: 500;">
                                    Lama Sewa (Hari)
                                </label>
                                <input type="number" name="lama_sewa" id="lama_sewa_<?php echo $pakaian['id_pakaian']; ?>" 
                                       value="1" min="1" max="30" required 
                                       style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 6px; margin-bottom: 10px; box-sizing: border-box;">
                                
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