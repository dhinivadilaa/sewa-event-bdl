<?php
// FILE: views/includes/header.php

$path_to_logout = '../../logout.php';
?>

<style>
    /* ===============================
       HEADER THEME — WARNA #8A4DFF
       =============================== */
    .header {
        width: 100%;
        padding: 18px 30px;
        background: linear-gradient(90deg, #8A4DFF, #5C2CCB);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        position: fixed;
        top: 0;
        z-index: 1000;
    }

    .header h3 {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .header h3 i {
        font-size: 26px;
        color: #ffffff;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
    }

    .user-info span {
        color: #fdfdfd;
        font-weight: 500;
    }

    .logout-btn {
        background-color: #ffffff;
        color: #8A4DFF !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.25s;
        box-shadow: 0 3px 10px rgba(255,255,255,0.3);
    }

    .logout-btn:hover {
        background-color: #EBD8FF;
        box-shadow: 0 4px 12px rgba(255,255,255,0.45);
        transform: translateY(-2px);
    }

    @media(max-width: 600px) {
        .header {
            padding: 15px 20px;
        }
        .header h3 {
            font-size: 20px;
        }
    }
</style>

<header class="header">
    <h3><i class="fas fa-gem"></i> </h3>

    <div class="user-info">
        <?php if(isset($_SESSION['username'])): ?>
            <span>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['hak_akses']); ?>)</span>
            <a href="<?php echo $path_to_logout; ?>" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="../../index.php" class="logout-btn">Login</a>
        <?php endif; ?>
    </div>
</header>
