<?php
// FILE: dhinivadilaa/sewa-event-bdl/sewa-event-bdl-18315c3e0bfe31e7ed6e87b1a95c9b75fe8170bd/index.php (Halaman Login & Registrasi)
session_start();

// Cek jika pengguna sudah login, langsung redirect ke dashboard yang sesuai (diambil dari login_process.php)
if (isset($_SESSION['hak_akses'])) {
    $hak_akses = strtolower($_SESSION['hak_akses']);
    
    $redirect_path = '';
    if ($hak_akses == 'admin') {
        $redirect_path = 'views/admin/index.php';
    } elseif ($hak_akses == 'staff') {
        // Asumsi folder views/staff ada
        $redirect_path = 'views/staff/index.php';
    } elseif ($hak_akses == 'vendor') {
        // Asumsi folder views/vendor ada
        $redirect_path = 'views/vendor/index.php';
    } elseif ($hak_akses == 'customer') {
        $redirect_path = 'views/customer/index.php';
    }
    
    if ($redirect_path) {
        header('Location: ' . $redirect_path);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register | Sewa Event & Pakaian BDL</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Gaya tambahan untuk form register */
        .form-toggle-link {
            cursor: pointer;
            color: var(--primary-color);
            margin-top: 15px;
            display: block;
            text-decoration: underline;
        }
        .register-form {
            display: none; /* Sembunyikan form register secara default */
        }
        .error-message, .success-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: left;
        }
        .error-message {
            background-color: #f8d7da;
            color: var(--danger-color);
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1><i class="fas fa-gem"></i> Sewa Event & Pakaian</h1>
        <p id="form-description">Silakan **Login** untuk melanjutkan</p>

        <?php
        // Tampilkan pesan error atau sukses
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error-message"><i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']); 
        }
        if (isset($_SESSION['success_message'])) {
            echo '<p class="success-message"><i class="fas fa-check-circle"></i> ' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form id="login-form" action="login_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>

        <form id="register-form" action="register_process.php" method="POST" class="register-form">
            <h3 style="text-align: center; margin-bottom: 15px;">Daftar Akun Customer</h3>
            <input type="text" name="nama_customer" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username Baru" required>
            <input type="password" name="password" placeholder="Password (Min 6 Karakter)" required>
            <input type="text" name="no_telp" placeholder="Nomor Telepon Aktif" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="alamat" placeholder="Alamat Lengkap" required></textarea>
            <button type="submit" name="register"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
        </form>

        <p class="mt-3">
            <a href="#" id="toggle-form" class="form-toggle-link">Belum punya akun? **Daftar Disini**</a>
        </p>
        
        <p class="mt-3">
            <a href="views/customer/index.php">Lanjutkan sebagai **Tamu** (Lihat Katalog)</a>
        </p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const toggleLink = document.getElementById('toggle-form');
        const formDescription = document.getElementById('form-description');
        let isLoginFormVisible = true;

        // Cek jika ada pesan error dari proses registrasi, tampilkan kembali form register
        const errorOrSuccess = document.querySelector('.error-message, .success-message');
        if (errorOrSuccess && errorOrSuccess.textContent.includes('Gagal menyimpan')) {
             // Jika gagal register, tampilkan form register kembali
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            toggleLink.innerHTML = 'Sudah punya akun? **Login Disini**';
            formDescription.innerHTML = 'Silakan **Daftar** untuk membuat akun';
            isLoginFormVisible = false;
        }

        toggleLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (isLoginFormVisible) {
                // Tampilkan register, sembunyikan login
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                toggleLink.innerHTML = 'Sudah punya akun? **Login Disini**';
                formDescription.innerHTML = 'Silakan **Daftar** untuk membuat akun';
                isLoginFormVisible = false;
            } else {
                // Tampilkan login, sembunyikan register
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                toggleLink.innerHTML = 'Belum punya akun? **Daftar Disini**';
                formDescription.innerHTML = 'Silakan **Login** untuk melanjutkan';
                isLoginFormVisible = true;
            }
        });
    </script>
</body>
</html>