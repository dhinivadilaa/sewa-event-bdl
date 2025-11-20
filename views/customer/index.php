<?php
// FILE: views/customer/index.php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['hak_akses'])) {
    $_SESSION['hak_akses'] = 'Customer';
    $_SESSION['username'] = 'Guest';
}

$nama_pengguna = $_SESSION['username'];
$jumlah_keranjang = (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) ? count($_SESSION['keranjang']) : 0;
$is_guest = ($_SESSION['username'] === 'Guest');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Kategori | Sewa Event BDL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ============================
           THEME COLOR — #8A4DFF
           ============================ */
        :root {
            --primary-purple: #8A4DFF;
            --purple-soft: #F3E8FF;
            --purple-dark: #5C2CCB;
        }

        .btn-purple {
            background-color: var(--primary-purple) !important;
            color: white !important;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(138, 77, 255, 0.3);
        }

        .btn-purple:hover {
            background-color: var(--purple-dark) !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(138, 77, 255, 0.45);
        }

        /* Badge */
        .cart-badge {
            background-color: #ff3b6a;
            color: white;
            padding: 3px 7px;
            border-radius: 50%;
            font-size: 12px;
        }

        /* Hero Section */
        .hero-section {
            background-color: var(--purple-soft);
            padding: 40px;
            border-radius: 15px;
            border: 2px solid var(--primary-purple);
            text-align: center;
            box-shadow: 0 6px 18px rgba(138, 77, 255, 0.15);
            margin-bottom: 40px;
        }

        .hero-section h2 {
            font-size: 30px;
            font-weight: 800;
            color: var(--primary-purple);
            margin-bottom: 12px;
        }

        .hero-section p {
            font-size: 18px;
            color: #444;
        }

        /* Quick nav buttons */
        .quick-nav {
            margin-top: 25px;
        }

        .quick-nav a {
            margin: 8px;
        }

        /* Category Cards */
        .category-grid {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 40px;
        }

        .category-card {
            width: 350px;
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            transition: 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
        }

        .category-card:hover {
            border-color: var(--primary-purple);
            transform: translateY(-6px);
            box-shadow: 0 6px 22px rgba(138, 77, 255, 0.25);
        }

        .category-card i {
            font-size: 70px;
            color: var(--primary-purple);
            margin-bottom: 20px;
        }

        .category-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .category-card p {
            color: #555;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .category-grid {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .category-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="wrapper" style="display: block;">
    <div class="main-content" style="margin-left: 0; max-width: 1000px; margin: 90px auto 30px auto;">

        <div class="hero-section">
            <h2>Selamat Datang, <?php echo htmlspecialchars($nama_pengguna); ?>!</h2>
            <p>Temukan kebutuhan event Anda — mulai dari kostum, dekorasi, vendor, hingga fotografer profesional.</p>

            <div class="quick-nav">

                <!-- Tombol Keranjang (warna ungu) -->
                <a href="keranjang.php" class="btn-purple">
                    <i class="fas fa-shopping-cart"></i>
                    Keranjang Anda
                    <?php if ($jumlah_keranjang > 0): ?>
                        <span class="cart-badge"><?php echo $jumlah_keranjang; ?></span>
                    <?php endif; ?>
                </a>

                <!-- Tombol Riwayat atau Login -->
                <?php if (!$is_guest): ?>
                    <a href="riwayat_sewa.php" class="btn-purple">
                        <i class="fas fa-history"></i> Riwayat Sewa
                    </a>
                <?php else: ?>
                    <a href="../../index.php" class="btn-purple">
                        <i class="fas fa-sign-in-alt"></i> Login / Daftar
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <h3 style="text-align:center; font-size: 24px; color:#333; margin-bottom:20px;">
            <i class="fas fa-th-large"></i> Pilih Kategori Pilihan Anda
        </h3>

        <div class="category-grid">
            
            <a href="katalog_pakaian.php" class="category-card">
                <i class="fas fa-tshirt"></i>
                <h3>Sewa Pakaian & Kostum</h3>
                <p>Tersedia berbagai jenis gaun, kostum adat, jas, dan pakaian pesta berkualitas.</p>
            </a>

            <a href="katalog_event.php" class="category-card">
                <i class="fas fa-camera"></i>
                <h3>Layanan Event & Vendor</h3>
                <p>Pesan fotografer, MUA, dekorasi, MC, dan layanan penting lainnya untuk event Anda.</p>
            </a>

        </div>

    </div>
</div>

<?php include('../includes/footer.php'); ?>
</body>
</html>
