<?php
// FILE: views/customer/keranjang.php (Keranjang Belanja Customer)
session_start();

require_once '../../config/koneksi.php'; 

// Cek sesi (Guest/Customer)
if (!isset($_SESSION['hak_akses'])) {
    // Redirect ke login jika bukan customer/guest
    header('Location: ../../index.php');
    exit();
}

$nama_pengguna = $_SESSION['username'];

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang']) || !is_array($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$total_sewa_global = 0; // Total biaya semua item dalam keranjang

// --- Logika Penanganan Aksi Keranjang (Hapus Item) ---
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = $_GET['id'];
    
    // Periksa apakah ID ada di keranjang (baik pakaian atau layanan)
    if (isset($_SESSION['keranjang'][$id_hapus])) {
        unset($_SESSION['keranjang'][$id_hapus]);
        $_SESSION['success_message'] = "Item berhasil dihapus dari keranjang.";
    }
    // Redirect untuk menghindari pengiriman ulang form/link
    header('Location: keranjang.php');
    exit();
}

// Catatan: Logika Tambah Item/Update Jumlah akan diimplementasikan di katalog_pakaian.php / katalog_event.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling Khusus untuk Keranjang */
        .keranjang-table-container {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .keranjang-table {
            width: 100%;
            border-collapse: collapse;
        }

        .keranjang-table th, .keranjang-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .keranjang-table th {
            background-color: var(--light-bg);
            font-weight: 600;
        }

        .item-info img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .item-info {
            display: flex;
            align-items: center;
        }

        .total-box {
            background-color: #e9f7ff;
            border: 1px solid var(--primary-color);
            padding: 20px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .checkout-btn {
            background-color: var(--success-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .checkout-btn:hover {
            background-color: #1e7e34;
            text-decoration: none;
        }

        .delete-btn {
            color: var(--danger-color);
            transition: color 0.2s;
        }
        .delete-btn:hover {
            color: #c82333;
            text-decoration: none;
        }

        .empty-cart-message {
            text-align: center;
            padding: 50px;
            border: 1px dashed var(--secondary-color);
            border-radius: 8px;
            color: var(--secondary-color);
        }
        
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1000px; margin: 90px auto 30px auto;">
            <h2><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>
            <p>Tinjau pakaian dan layanan event yang akan Anda sewa.</p>
            <hr>
            
            <?php 
            // Tampilkan pesan sukses jika ada
            if (isset($_SESSION['success_message'])) {
                echo '<p style="color: var(--success-color); background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> ' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            
            if (empty($_SESSION['keranjang'])): ?>

                <div class="empty-cart-message">
                    <i class="fas fa-box-open" style="font-size: 40px; margin-bottom: 15px;"></i>
                    <p>Keranjang Anda masih kosong. Mari mulai berbelanja!</p>
                    <a href="index.php" class="btn-tambah" style="margin-top: 20px;">Lihat Katalog</a>
                </div>

            <?php else: ?>
            
                <div class="keranjang-table-container">
                    <table class="keranjang-table">
                        <thead>
                            <tr>
                                <th>Item Sewa</th>
                                <th>Jenis</th>
                                <th>Harga Satuan</th>
                                <th>Lama Sewa / Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['keranjang'] as $id => $item): 
                                
                                // Hitung subtotal. Anggap 'jumlah' adalah lama sewa (hari) untuk Pakaian, atau kuantitas untuk Layanan
                                $subtotal = $item['harga'] * $item['jumlah'];
                                $total_sewa_global += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="item-info">
                                        <?php if ($item['jenis'] == 'Pakaian'): ?>
                                            <img src="../../assets/img/pakaian_default.jpg" alt="Pakaian">
                                        <?php else: ?>
                                            <i class="fas fa-camera" style="font-size: 24px; color: var(--secondary-color); margin-right: 15px;"></i>
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($item['nama']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($item['jenis']); ?></td>
                                <td>Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    if ($item['jenis'] == 'Pakaian') {
                                        echo $item['jumlah'] . ' Hari';
                                    } else {
                                        echo $item['jumlah'] . ' Unit';
                                    }
                                    ?>
                                </td>
                                <td>**Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?>**</td>
                                <td>
                                    <a href="keranjang.php?action=hapus&id=<?php echo htmlspecialchars($id); ?>" class="delete-btn" title="Hapus Item">
                                        <i class="fas fa-times-circle"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="total-box">
                    <span>TOTAL BIAYA SEWA KESELURUHAN:</span>
                    <span>Rp. <?php echo number_format($total_sewa_global, 0, ',', '.'); ?></span>
                </div>
                <div style="text-align: right; margin-top: 15px;">
                    <a href="checkout.php" class="checkout-btn">
                        <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                    </a>
                </div>

            <?php endif; ?>
            
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>