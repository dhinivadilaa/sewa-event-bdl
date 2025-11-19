<?php
// FILE: views/includes/header.php

// Pastikan file ini di-include dari level views/admin/ atau views/customer/
$path_to_logout = '../../logout.php'; 
?>
<header class="header">
    <h3><i class="fas fa-gem"></i> Sewa Event BDL</h3>
    <div class="user-info">
        <?php if(isset($_SESSION['username'])): ?>
            <span>Halo, **<?php echo htmlspecialchars($_SESSION['username']); ?>** (<?php echo htmlspecialchars($_SESSION['hak_akses']); ?>)</span>
            <a href="<?php echo $path_to_logout; ?>" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="../../index.php" class="logout-btn">Login</a>
        <?php endif; ?>
    </div>
</header>