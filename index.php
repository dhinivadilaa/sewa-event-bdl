<?php
// FILE: index.php (Halaman Landing + Login & Registrasi)
session_start();

// Redirect jika sudah login
if (isset($_SESSION['hak_akses'])) {
    $hak_akses = strtolower($_SESSION['hak_akses']);
    $redirect = [
        'admin' => 'views/admin/index.php',
        'staff' => 'views/staff/index.php',
        'vendor' => 'views/vendor/index.php',
        'customer' => 'views/customer/index.php'
    ];

    if (isset($redirect[$hak_akses])) {
        header("Location: " . $redirect[$hak_akses]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Event & Pakaian BDL | Login & Register</title>

    <!-- CSS Utama -->
    <link rel="stylesheet" href="assets/css/style.css"> 

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- =======================
         HERO / LANDING SECTION
    ======================== -->
    <section class="hero">
        <div class="hero-content">
            <h1>Sewa Event & Pakaian BDL</h1>
            <p class="tagline">
                Solusi lengkap untuk kebutuhan penyewaan dekorasi, perlengkapan acara, dan pakaian adat / kostum.
                Mudah, cepat, dan terpercaya.
            </p>

            <div class="hero-buttons">
                <button id="goLogin" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
                <button id="goRegister" class="btn-outline"><i class="fas fa-user-plus"></i> Register</button>
            </div>

            <a href="views/customer/index.php" class="guest-link">
                Lanjut sebagai Tamu (Lihat Katalog)
            </a>
        </div>
    </section>

    <!-- =======================
         BOX LOGIN / REGISTER
    ======================== -->
    <div class="form-section" id="formSection">

        <div class="login-container">
            <h2 id="form-title"><i class="fas fa-gem"></i> Login ke Akun Anda</h2>
            <p id="form-description">Silakan login untuk melanjutkan</p>

            <?php
            // Pesan error / sukses
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message"><i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']); 
            }
            if (isset($_SESSION['success_message'])) {
                echo '<p class="success-message"><i class="fas fa-check-circle"></i> ' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            ?>

            <!-- FORM LOGIN -->
            <form id="login-form" action="login_process.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>

            <!-- FORM REGISTER -->
            <form id="register-form" class="hidden" action="register_process.php" method="POST">
                <h3>Daftar sebagai Customer</h3>

                <input type="text" name="nama_customer" placeholder="Nama Lengkap" required>
                <input type="text" name="username" placeholder="Username Baru" required>
                <input type="password" name="password" placeholder="Password (Min 6 Karakter)" required>
                <input type="text" name="no_telp" placeholder="Nomor Telepon" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="alamat" placeholder="Alamat Lengkap" required></textarea>

                <button type="submit" name="register" class="btn-primary"><i class="fas fa-user-plus"></i> Daftar</button>
            </form>

            <p class="switch-link">
                <a href="#" id="toggle-form">Belum punya akun? <b>Daftar di sini</b></a>
            </p>
        </div>
    </div>

<script>
// Elemen
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const toggleLink = document.getElementById('toggle-form');
const formTitle = document.getElementById('form-title');
const formDescription = document.getElementById('form-description');

const goLogin = document.getElementById('goLogin');
const goRegister = document.getElementById('goRegister');
const formSection = document.getElementById('formSection');

// Tampilkan box login/register saat klik tombol landing
goLogin.onclick = () => {
    formSection.classList.add("show");
    loginForm.classList.remove("hidden");
    registerForm.classList.add("hidden");
    formTitle.innerHTML = `<i class="fas fa-sign-in-alt"></i> Login ke Akun Anda`;
    formDescription.innerText = "Silakan login untuk melanjutkan";
};

goRegister.onclick = () => {
    formSection.classList.add("show");
    loginForm.classList.add("hidden");
    registerForm.classList.remove("hidden");
    formTitle.innerHTML = `<i class="fas fa-user-plus"></i> Daftar Akun Baru`;
    formDescription.innerText = "Buat akun baru untuk melanjutkan";
};

// Tombol toggle
toggleLink.onclick = (e) => {
    e.preventDefault();

    const isLoginShown = !loginForm.classList.contains("hidden");

    if (isLoginShown) {
        loginForm.classList.add("hidden");
        registerForm.classList.remove("hidden");
        formTitle.innerHTML = `<i class="fas fa-user-plus"></i> Daftar Akun Baru`;
        formDescription.innerText = "Buat akun baru untuk melanjutkan";
        toggleLink.innerHTML = `Sudah punya akun? <b>Login di sini</b>`;
    } else {
        loginForm.classList.remove("hidden");
        registerForm.classList.add("hidden");
        formTitle.innerHTML = `<i class="fas fa-sign-in-alt"></i> Login ke Akun Anda`;
        formDescription.innerText = "Silakan login untuk melanjutkan";
        toggleLink.innerHTML = `Belum punya akun? <b>Daftar di sini</b>`;
    }
};
</script>

</body>
</html>
