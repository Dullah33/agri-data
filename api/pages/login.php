<?php
require __DIR__ . '/../config/koneksi.php';
require __DIR__ . '/../helpers/auth_cookie.php';

// Sudah login → redirect langsung
$existing = getAuthUser();
if ($existing) {
    header("Location: /pages/" . ($existing['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php'));
    exit();
}

if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if ($row = mysqli_fetch_assoc($query)) {
        if (password_verify($password, $row['password'])) {

            setAuthCookie($row);

            if ($row['role'] === 'admin') {
                header("Location: /pages/dashboard_admin.php");
            } else {
                header("Location: /pages/dashboard_user.php");
            }
            exit();
        }
    }

    $error = "Email atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgriData</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;background:#f0f4f8}
        .left-panel{flex:1;background:linear-gradient(145deg,#2D6A4F 0%,#1B4332 100%);display:flex;flex-direction:column;justify-content:center;align-items:flex-start;padding:60px;position:relative;overflow:hidden}
        .left-panel::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:rgba(255,255,255,.05);border-radius:50%}
        .left-panel::after{content:'';position:absolute;bottom:-80px;left:-60px;width:300px;height:300px;background:rgba(116,198,157,.15);border-radius:50%}
        .panel-logo{display:flex;align-items:center;gap:12px;margin-bottom:60px;position:relative;z-index:1}
        .panel-logo .icon{width:44px;height:44px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
        .panel-logo span{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff}
        .panel-tagline{font-family:'Syne',sans-serif;font-size:clamp(28px,3vw,42px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:20px;position:relative;z-index:1}
        .panel-tagline span{color:#74C69D}
        .panel-sub{color:rgba(255,255,255,.7);font-size:16px;line-height:1.7;max-width:340px;position:relative;z-index:1}
        .right-panel{width:480px;display:flex;justify-content:center;align-items:center;padding:50px 48px;background:#fff}
        .login-box{width:100%}
        .login-box h2{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#1A1A2E;margin-bottom:6px}
        .login-box p{color:#9CA3AF;font-size:15px;margin-bottom:36px}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px}
        .input-wrap{position:relative}
        .input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:15px}
        .input-wrap input{width:100%;padding:13px 14px 13px 42px;border:1.5px solid #E5E7EB;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:15px;color:#1A1A2E;background:#F9FAFB;outline:none;transition:all .2s}
        .input-wrap input:focus{border-color:#2D6A4F;background:#fff;box-shadow:0 0 0 3px rgba(45,106,79,.1)}
        .error-box{background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;display:flex;align-items:center;gap:8px}
        .btn-login{width:100%;padding:15px;background:#2D6A4F;color:#fff;border:none;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:16px;font-weight:600;cursor:pointer;transition:all .2s;box-shadow:0 4px 15px rgba(45,106,79,.3)}
        .btn-login:hover{background:#40916C;transform:translateY(-1px);box-shadow:0 6px 20px rgba(45,106,79,.4)}
        .register-prompt{text-align:center;margin-top:24px;font-size:14px;color:#9CA3AF}
        .register-prompt a{color:#2D6A4F;font-weight:600;text-decoration:none}
        @media(max-width:768px){.left-panel{display:none}.right-panel{width:100%;padding:40px 28px}}
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="panel-logo">
            <div class="icon"><i class="fa-solid fa-leaf"></i></div>
            <span>AgriData</span>
        </div>
        <h2 class="panel-tagline">Selamat Datang <span>Kembali</span></h2>
        <p class="panel-sub">Platform digital terpadu untuk manajemen data pertanian Indonesia yang transparan dan efisien.</p>
    </div>
    <div class="right-panel">
        <div class="login-box">
            <h2>Login ke Akun</h2>
            <p>Masukkan email dan password Anda untuk melanjutkan</p>
            <?php if (isset($error)): ?>
                <div class="error-box"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" name="login" class="btn-login">Sign In</button>
            </form>
            <div class="register-prompt">
                Belum punya akun? <a href="/pages/register.php">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>
