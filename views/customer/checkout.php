<?php
// FILE: views/customer/checkout.php (Proses Checkout)
session_start();

require_once '../../config/koneksi.php'; 

// Cek sesi (Guest/Customer)
if (!isset($_SESSION['hak_akses'])) {
    // Redirect jika belum login atau bukan customer/guest
    header('Location: ../../index.php');
    exit();
}

// Cek apakah keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    $_SESSION['error_message'] = "Keranjang Anda kosong. Silakan pilih item sewa terlebih dahulu.";
    header('Location: keranjang.php');
    exit();
}

$nama_pengguna = $_SESSION['username'];
$total_sewa_global = 0;
$persen_dp = 30; // Uang muka 30%

// --- Logika Pengambilan Data Customer ---
$is_guest = ($nama_pengguna === 'Guest');
$data_customer = ['nama' => '', 'alamat' => '', 'no_telp' => ''];

if (!$is_guest) {
    // Jika customer sudah login, ambil data detail dari tabel customer
    $id_referensi = $_SESSION['id_referensi'];
    $query_cust = "SELECT nama_customer, alamat, no_telp FROM customer WHERE id_customer = '$id_referensi'";
    $result_cust = mysqli_query($koneksi, $query_cust);
    
    if ($result_cust && mysqli_num_rows($result_cust) == 1) {
        $data_db = mysqli_fetch_assoc($result_cust);
        $data_customer = [
            'nama' => $data_db['nama_customer'],
            'alamat' => $data_db['alamat'],
            'no_telp' => $data_db['no_telp']
        ];
    }
}
// ----------------------------------------
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout & Pembayaran | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .checkout-layout {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }

        .checkout-form-section {
            flex: 2;
            padding: 20px;
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .checkout-summary-section {
            flex: 1;
            padding: 20px;
            background-color: #e9f7ff;
            border-radius: 10px;
            border: 1px solid var(--primary-color);
            height: fit-content;
        }

        .checkout-form-section input[type="text"],
        .checkout-form-section input[type="date"],
        .checkout-form-section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ced4da;
            font-size: 15px;
        }

        .summary-total {
            font-size: 18px;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid var(--primary-color);
        }
        
        .summary-dp {
            color: var(--danger-color);
            font-weight: 700;
            padding: 10px 0;
            margin-bottom: 10px;
        }

        .btn-pesan {
            width: 100%;
            background-color: var(--success-color);
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-pesan:hover {
            background-color: #1e7e34;
        }
        
        .alert-guest {
            background-color: #f8d7da;
            color: var(--danger-color);
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1200px; margin: 90px auto 30px auto;">
            <h2><i class="fas fa-credit-card"></i> Konfirmasi & Checkout</h2>
            <p>Lengkapi detail pemesanan dan konfirmasi pesanan Anda.</p>
            <a href="keranjang.php" style="margin-bottom: 20px; display: inline-block;"><i class="fas fa-arrow-left"></i> Kembali ke Keranjang</a>
            <hr>
            
            <?php if ($is_guest): ?>
                <div class="alert-guest">
                    <i class="fas fa-exclamation-triangle"></i> Anda sedang melakukan pemesanan sebagai **Guest**. Setelah ini, Anda akan diminta untuk **Login atau Registrasi** agar pesanan dapat diproses.
                </div>
            <?php endif; ?>

            <form action="proses_sewa.php" method="POST" class="checkout-layout">
                
                <div class="checkout-form-section">
                    <h3><i class="fas fa-user-circle"></i> Data Penerima Sewa</h3>
                    <input type="text" name="nama_penerima" placeholder="Nama Penerima Sewa" value="<?php echo htmlspecialchars($data_customer['nama']); ?>" required>
                    
                    <textarea name="alamat_ambil" placeholder="Alamat Ambil/Kirim (Lengkap)" required><?php echo htmlspecialchars($data_customer['alamat']); ?></textarea>
                    
                    <input type="text" name="no_telp" placeholder="Nomor Telepon Aktif" value="<?php echo htmlspecialchars($data_customer['no_telp']); ?>" required>

                    <h3 style="margin-top: 30px;"><i class="fas fa-calendar-alt"></i> Detail Waktu Sewa</h3>
                    <label>Tanggal Rencana Ambil:</label>
                    <input type="date" name="tgl_ambil" required>
                    
                    <label>Tanggal Rencana Kembali:</label>
                    <input type="date" name="tgl_kembali" required>
                </div>

                <div class="checkout-summary-section">
                    <h3><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>
                    
                    <?php foreach ($_SESSION['keranjang'] as $item): 
                        $subtotal = $item['harga'] * $item['jumlah'];
                        $total_sewa_global += $subtotal;
                    ?>
                        <div class="summary-item">
                            <span><?php echo htmlspecialchars($item['nama']); ?> (<?php echo $item['jumlah']; ?> <?php echo ($item['jenis'] == 'Pakaian' ? 'Hari' : 'Unit'); ?>)</span>
                            <span>Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php
                    $dp_nominal = ceil($total_sewa_global * ($persen_dp / 100));
                    $sisa_bayar = $total_sewa_global - $dp_nominal;
                    ?>

                    <div class="summary-total" style="border-top: none;">
                        <span>TOTAL BIAYA SEWA:</span>
                        <span style="color: var(--primary-color);">Rp. <?php echo number_format($total_sewa_global, 0, ',', '.'); ?></span>
                    </div>

                    <div class="summary-total summary-dp">
                        <span>MINIMUM UANG MUKA (<?php echo $persen_dp; ?>%):</span>
                        <span style="color: var(--danger-color);">Rp. <?php echo number_format($dp_nominal, 0, ',', '.'); ?></span>
                    </div>

                    <p style="margin-top: 10px; font-size: 14px; color: var(--secondary-color); text-align: center;">Sisa pembayaran sebesar Rp. <?php echo number_format($sisa_bayar, 0, ',', '.'); ?> akan dibayarkan saat pengambilan barang/sebelum acara.</p>

                    <button type="submit" class="btn-pesan" name="proses_checkout" style="margin-top: 20px;">
                        <i class="fas fa-money-check-alt"></i> Bayar Uang Muka (DP)
                    </button>
                    <p style="text-align: center; margin-top: 10px; font-size: 12px; color: var(--danger-color);">*Harap lengkapi semua data dengan benar.</p>
                </div>
            </form>
            
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>