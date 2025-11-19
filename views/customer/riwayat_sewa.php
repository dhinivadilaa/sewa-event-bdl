<?php
// FILE: views/customer/riwayat_sewa.php (Riwayat Sewa Customer)
session_start();

require_once '../../config/koneksi.php'; 

// Cek Otentikasi & Hak Akses
// Customer hanya bisa melihat riwayat jika mereka login. Guest tidak boleh melihat.
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] !== 'Customer' || $_SESSION['username'] === 'Guest') {
    // Jika belum login sebagai customer, arahkan ke halaman login
    header('Location: ../../index.php');
    exit();
}

$id_customer_login = $_SESSION['id_referensi']; // ID Customer diambil dari sesi
$nama_pengguna = $_SESSION['username'];

// Query untuk mengambil riwayat sewa (sewa_header) untuk customer yang sedang login
$query_riwayat = "
    SELECT 
        no_sewa, 
        tgl_sewa, 
        tgl_ambil_rencana, 
        tgl_kembali_rencana, 
        total_biaya, 
        status_pembayaran, 
        status_sewa 
    FROM 
        sewa_header 
    WHERE 
        id_customer = '$id_customer_login'
    ORDER BY 
        tgl_sewa DESC
";
$result_riwayat = mysqli_query($koneksi, $query_riwayat);

// Menambahkan penanganan error query yang aman
if (!$result_riwayat) {
    die("Error Query: " . mysqli_error($koneksi) . " | Query gagal. Cek tabel 'sewa_header'.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Sewa | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling Khusus untuk Tabel Riwayat */
        .riwayat-table-container {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .riwayat-table {
            width: 100%;
            border-collapse: collapse;
        }

        .riwayat-table th, .riwayat-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            font-size: 14px;
        }

        .riwayat-table th {
            background-color: var(--light-bg);
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 13px;
            color: white;
            text-align: center;
            display: inline-block;
        }

        /* Warna Status */
        .status-Aktif { background-color: #ffc107; color: #343a40; }
        .status-Selesai { background-color: var(--success-color); }
        .status-DP { background-color: var(--warning-color); color: #343a40; }
        .status-Lunas { background-color: var(--success-color); }
        .status-Batal { background-color: var(--danger-color); }
        .status-Draft { background-color: var(--secondary-color); }

        .detail-link {
            font-size: 14px;
            color: var(--primary-color);
            transition: color 0.2s;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="wrapper" style="display: block;">
        <div class="main-content" style="margin-left: 0; max-width: 1200px; margin: 90px auto 30px auto;">
            <h2><i class="fas fa-history"></i> Riwayat Transaksi Sewa</h2>
            <p>Lihat status dan detail semua pesanan sewa Anda, **<?php echo htmlspecialchars($nama_pengguna); ?>**.</p>
            <hr>
            
            <a href="index.php" style="margin-bottom: 20px; display: inline-block;"><i class="fas fa-arrow-left"></i> Kembali ke Katalog</a>

            <div class="riwayat-table-container">
                <?php if (mysqli_num_rows($result_riwayat) > 0): ?>
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>No. Sewa</th>
                                <th>Tgl. Pesan</th>
                                <th>Tgl. Ambil (Rencana)</th>
                                <th>Tgl. Kembali (Rencana)</th>
                                <th>Total Biaya</th>
                                <th>Pembayaran</th>
                                <th>Status Sewa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($riwayat = mysqli_fetch_assoc($result_riwayat)): 
                                // Ambil kelas CSS sesuai status
                                $status_sewa_class = 'status-' . htmlspecialchars($riwayat['status_sewa']);
                                $status_bayar_class = 'status-' . htmlspecialchars($riwayat['status_pembayaran']);
                            ?>
                            <tr>
                                <td>**<?php echo htmlspecialchars($riwayat['no_sewa']); ?>**</td>
                                <td><?php echo date('d M Y', strtotime($riwayat['tgl_sewa'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($riwayat['tgl_ambil_rencana'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($riwayat['tgl_kembali_rencana'])); ?></td>
                                <td>Rp. **<?php echo number_format($riwayat['total_biaya'], 0, ',', '.'); ?>**</td>
                                <td><span class="status-badge <?php echo $status_bayar_class; ?>"><?php echo htmlspecialchars($riwayat['status_pembayaran']); ?></span></td>
                                <td><span class="status-badge <?php echo $status_sewa_class; ?>"><?php echo htmlspecialchars($riwayat['status_sewa']); ?></span></td>
                                <td>
                                    <a href="#" class="detail-link">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="box">
                        <p style="text-align: center; color: var(--secondary-color);">Anda belum memiliki riwayat transaksi sewa.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>