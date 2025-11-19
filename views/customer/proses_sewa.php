<?php
// FILE: proses_sewa.php (Logika Penyimpanan Transaksi Sewa)
session_start();
require_once '../../config/koneksi.php'; // Koneksi database utama dari root

// Cek apakah form checkout telah disubmit
if (!isset($_POST['proses_checkout'])) {
    header('Location: views/customer/index.php');
    exit();
}

// 1. Pengecekan Ketersediaan Data
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    $_SESSION['error_message'] = "Keranjang kosong. Proses sewa dibatalkan.";
    header('Location: views/customer/keranjang.php');
    exit();
}

// Pengecekan Guest (Pastikan hanya Customer yang sudah login yang bisa memproses)
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] !== 'Customer' || $_SESSION['username'] === 'Guest') {
    $_SESSION['error_message'] = "Anda harus login sebagai Customer untuk menyelesaikan pemesanan.";
    header('Location: index.php'); // Redirect ke login page
    exit();
}

// 2. Ambil & Sanitasi Data POST
$id_customer = $_SESSION['id_referensi']; // ID Customer yang login
// Asumsi ID Staff (Petugas Transaksi) - ambil dari data dummy (misal ID Staff 1: Rina Petugas)
$id_staff = 1; 

$tgl_sewa = date('Y-m-d');
$tgl_ambil = mysqli_real_escape_string($koneksi, $_POST['tgl_ambil']);
$tgl_kembali = mysqli_real_escape_string($koneksi, $_POST['tgl_kembali']);

// Data penerima (bisa digunakan untuk update profil customer di masa depan)
$nama_penerima = mysqli_real_escape_string($koneksi, $_POST['nama_penerima']);
$alamat_ambil = mysqli_real_escape_string($koneksi, $_POST['alamat_ambil']);
$no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);


// 3. Hitung Ulang Total Biaya & DP
$total_sewa_global = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total_sewa_global += $item['harga'] * $item['jumlah'];
}

$persen_dp = 30; // 30% Uang Muka
$uang_muka = ceil($total_sewa_global * ($persen_dp / 100));

// 4. Generate Nomor Sewa (Contoh: SEW-YYYYMMDD-XXX)
$date_part = date('Ymd');
// Query untuk mendapatkan nomor urut terakhir hari ini
$query_no_urut = "SELECT COUNT(*) as total FROM sewa_header WHERE tgl_sewa = '$tgl_sewa'";
$data_no_urut = mysqli_fetch_assoc(mysqli_query($koneksi, $query_no_urut));
$no_urut = $data_no_urut['total'] + 1;
$no_sewa = 'SEW-' . $date_part . '-' . str_pad($no_urut, 3, '0', STR_PAD_LEFT);


// 5. START DATABASE TRANSACTION (Penting untuk Konsistensi Data)
mysqli_begin_transaction($koneksi);
$success = true;

try {
    // A. INSERT INTO sewa_header
    $query_header = "INSERT INTO sewa_header (no_sewa, tgl_sewa, id_customer, id_staff, tgl_ambil_rencana, tgl_kembali_rencana, total_biaya, uang_muka, status_pembayaran, status_sewa) 
                     VALUES ('$no_sewa', '$tgl_sewa', '$id_customer', '$id_staff', '$tgl_ambil', '$tgl_kembali', '$total_sewa_global', '$uang_muka', 'DP', 'Draft')";
    
    if (!mysqli_query($koneksi, $query_header)) {
        $success = false;
        throw new Exception("Gagal menyimpan header sewa: " . mysqli_error($koneksi));
    }

    // B. LOOP & INSERT INTO detail (Pakaian & Layanan)
    foreach ($_SESSION['keranjang'] as $keranjang_id => $item) {
        $item_id = $item['id'];
        $harga_satuan = $item['harga'];
        $jumlah = $item['jumlah'];
        $subtotal = $harga_satuan * $jumlah;

        if ($item['jenis'] == 'Pakaian') {
            // 1. Insert ke sewa_detail_pakaian
            $query_detail = "INSERT INTO sewa_detail_pakaian (no_sewa, id_pakaian, harga_sewa_saat_transaksi, jumlah, subtotal) 
                             VALUES ('$no_sewa', '$item_id', '$harga_satuan', '$jumlah', '$subtotal')";

            if (!mysqli_query($koneksi, $query_detail)) {
                $success = false;
                throw new Exception("Gagal menyimpan detail pakaian: " . mysqli_error($koneksi));
            }

            // 2. Update Stok Pakaian (Stok dikurangi 1 per transaksi, karena 'jumlah' adalah lama sewa)
            // CATATAN: Logika stok ini sederhana. Untuk sistem yang lebih kompleks, 'jumlah' di keranjang harusnya unit pakaian.
            $query_update_stok = "UPDATE pakaian SET stok = stok - 1 WHERE id_pakaian = '$item_id' AND stok >= 1";
            if (!mysqli_query($koneksi, $query_update_stok)) {
                $success = false;
                throw new Exception("Gagal update stok pakaian: " . mysqli_error($koneksi));
            }
            
        } elseif ($item['jenis'] == 'Layanan') {
            // Insert ke sewa_detail_layanan
            $query_detail = "INSERT INTO sewa_detail_layanan (no_sewa, id_layanan, biaya_saat_transaksi) 
                             VALUES ('$no_sewa', '$item_id', '$harga_satuan')";

            if (!mysqli_query($koneksi, $query_detail)) {
                $success = false;
                throw new Exception("Gagal menyimpan detail layanan: " . mysqli_error($koneksi));
            }
        }
    }

    // C. Jika semua query sukses, COMMIT transaksi
    mysqli_commit($koneksi);
    
    // D. Bersihkan Keranjang & Redirect
    unset($_SESSION['keranjang']);
    $_SESSION['success_message'] = "Pemesanan berhasil dengan No. Sewa **$no_sewa**. Silakan lakukan pembayaran Uang Muka (DP).";
    header('Location: views/customer/riwayat_sewa.php');

} catch (Exception $e) {
    // Jika terjadi error di mana pun, lakukan ROLLBACK
    mysqli_rollback($koneksi);
    $_SESSION['error_message'] = "Transaksi Gagal: " . $e->getMessage() . ". Pesanan Anda belum tersimpan.";
    // Redirect kembali ke keranjang
    header('Location: views/customer/keranjang.php');
}
exit();

?>