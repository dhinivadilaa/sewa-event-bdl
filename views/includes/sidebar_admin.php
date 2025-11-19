<?php
// FILE: views/includes/sidebar_admin.php

// Tentukan menu aktif berdasarkan file yang sedang diakses
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <h4>Menu Admin</h4>
    <ul>
        <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="<?php echo ($current_page == 'pakaian.php') ? 'active' : ''; ?>">
            <a href="pakaian.php"><i class="fas fa-tshirt"></i> Data Pakaian</a>
        </li>
        <li class="<?php echo ($current_page == 'pelanggan.php') ? 'active' : ''; ?>">
            <a href="pelanggan.php"><i class="fas fa-users"></i> Data Pelanggan</a>
        </li>
        <li class="<?php echo ($current_page == 'sewa.php') ? 'active' : ''; ?>">
            <a href="sewa.php"><i class="fas fa-receipt"></i> Transaksi Sewa</a>
        </li>
        <li class="<?php echo ($current_page == 'laporan.php') ? 'active' : ''; ?>">
            <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
        </li>
    </ul>
</aside>