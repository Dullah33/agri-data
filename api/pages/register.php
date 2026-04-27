<?php
require __DIR__ . '/../config/koneksi.php';

if (isset($_POST['register'])) {
    $query_id = mysqli_query($conn, "SELECT MAX(id_user) as id_terbesar FROM users WHERE role='user'");
    $data_id = mysqli_fetch_assoc($query_id);
    $id_user_terakhir = $data_id['id_terbesar'];

    if ($id_user_terakhir) {
        $urutan = (int) substr($id_user_terakhir, 4, 3);
        $urutan++;
        $id_user = "USR-" . sprintf("%03s", $urutan);
    } else {
        $id_user = "USR-001";
    }

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob        = mysqli_real_escape_string($conn, $_POST['dob']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = mysqli_real_escape_string($conn, $_POST['password']);
    $role       = 'user';

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username atau Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (id_user, first_name, dob, address, phone, gender, username, email, password, role)
                  VALUES ('$id_user', '$name', '$dob', '$address', '$phone', '$gender', '$username', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil!. Silakan login.'); window.location='login.php';</script>";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - AgriData</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; min-height: 100vh; background: #f0f4f8; display: flex; justify-content: center; align-items: flex-start; padding: 40px 20px; }
        .reg-card { background: #fff; width: 100%; max-width: 680px; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); overflow: hidden; }
        .reg-header { background: linear-gradient(145deg, #2D6A4F, #1B4332); padding: 36px 48px; }
        .reg-header .logo { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .reg-header .logo .icon { width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
        .reg-header .logo span { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: #fff; }
        .reg-header h2 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .reg-header p { color: rgba(255,255,255,0.7); font-size: 14px; }
        .reg-body { padding: 40px 48px; }
        /* Steps indicator */
        .steps-bar { display: flex; align-items: center; gap: 0; margin-bottom: 36px; }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; border: 2px solid #E5E7EB; color: #9CA3AF; background: #fff; flex-shrink: 0; transition: all .3s; }
        .step-dot.active { background: #2D6A4F; border-color: #2D6A4F; color: #fff; }
        .step-dot.done { background: #D8F3DC; border-color: #74C69D; color: #2D6A4F; }
        .step-line { flex: 1; height: 2px; background: #E5E7EB; transition: background .3s; }
        .step-line.done { background: #74C69D; }
        .step-lbl { font-size: 12px; color: #9CA3AF; text-align: center; margin-top: 6px; }
        .steps-labels { display: flex; justify-content: space-between; margin-bottom: 32px; margin-top: -28px; }
        .steps-labels span { font-size: 12px; color: #9CA3AF; width: 32px; text-align: center; }
        .steps-labels span.active { color: #2D6A4F; font-weight: 600; }
        /* Error */
        .error-box { background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
        /* Form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 20px; }
        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: 1 / -1; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 14px; }
        .input-wrap input, .input-wrap select {
            width: 100%; padding: 12px 14px 12px 40px;
            border: 1.5px solid #E5E7EB; border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: 14px; color: #1A1A2E;
            background: #F9FAFB; outline: none; transition: all .2s; appearance: none;
        }
        .input-wrap input:focus, .input-wrap select:focus { border-color: #2D6A4F; background: #fff; box-shadow: 0 0 0 3px rgba(45,106,79,.1); }
        .btn-next, .btn-submit { width: 100%; padding: 14px; background: #2D6A4F; color: #fff; border: none; border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: all .2s; box-shadow: 0 4px 15px rgba(45,106,79,.3); margin-top: 24px; }
        .btn-next:hover, .btn-submit:hover { background: #40916C; transform: translateY(-1px); }
        .btn-back { width: 100%; padding: 13px; background: #F9FAFB; color: #6B7280; border: 1.5px solid #E5E7EB; border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: all .2s; margin-top: 10px; }
        .btn-back:hover { background: #F3F4F6; }
        .login-prompt { text-align: center; margin-top: 20px; font-size: 14px; color: #9CA3AF; }
        .login-prompt a { color: #2D6A4F; font-weight: 600; text-decoration: none; }
        #step2 { display: none; }
        @media (max-width: 640px) {
            .reg-header, .reg-body { padding: 28px 24px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="reg-card">
    <div class="reg-header">
        <div class="logo">
            <div class="icon"><i class="fa-solid fa-leaf"></i></div>
            <span>AgriData</span>
        </div>
        <h2>Buat Akun Baru</h2>
        <p>Daftarkan diri Anda sebagai petani di platform AgriData</p>
    </div>

    <div class="reg-body">
        <!-- Steps indicator -->
        <div class="steps-bar">
            <div class="step-dot active" id="dot1">1</div>
            <div class="step-line" id="line1"></div>
            <div class="step-dot" id="dot2">2</div>
        </div>
        <div class="steps-labels">
            <span class="active" id="lbl1">Data Pribadi</span>
            <span id="lbl2">Akun</span>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-box"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <div id="step1">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="name" placeholder="Nama lengkap Anda" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-calendar"></i>
                            <input type="date" name="dob" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-venus-mars"></i>
                            <select name="gender" required>
                                <option value="Male">Laki-laki</option>
                                <option value="Female">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Alamat</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-location-dot"></i>
                            <input type="text" name="address" placeholder="Alamat lengkap Anda" required>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Nomor Telepon</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" name="phone" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-next" onclick="nextStep()">
                    Lanjutkan <i class="fa-solid fa-arrow-right" style="margin-left:6px;"></i>
                </button>
                <div class="login-prompt">Sudah punya akun? <a href="login.php">Login</a></div>
            </div>

            <div id="step2">
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Username</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-at"></i>
                            <input type="text" name="username" placeholder="username_anda" required>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Email Address</label>
                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" placeholder="contoh@email.com" required>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Password</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="register" class="btn-submit">
                    <i class="fa-solid fa-check" style="margin-right:8px;"></i> Buat Akun Sekarang
                </button>
                <button type="button" class="btn-back" onclick="prevStep()">
                    <i class="fa-solid fa-arrow-left" style="margin-right:8px;"></i> Kembali
                </button>
                <div class="login-prompt">Sudah punya akun? <a href="login.php">Login</a></div>
            </div>

        </form>
    </div>
</div>

<script>
function nextStep() {
    let inputs = document.getElementById('step1').querySelectorAll('input, select');
    let isValid = true;
    inputs.forEach(i => { if (!i.checkValidity()) { i.reportValidity(); isValid = false; } });
    if (isValid) {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
        document.getElementById('dot1').classList.remove('active');
        document.getElementById('dot1').classList.add('done');
        document.getElementById('dot1').innerHTML = '<i class="fa-solid fa-check" style="font-size:11px;"></i>';
        document.getElementById('line1').classList.add('done');
        document.getElementById('dot2').classList.add('active');
        document.getElementById('lbl1').classList.remove('active');
        document.getElementById('lbl2').classList.add('active');
    }
}
function prevStep() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('dot1').classList.add('active');
    document.getElementById('dot1').classList.remove('done');
    document.getElementById('dot1').innerHTML = '1';
    document.getElementById('line1').classList.remove('done');
    document.getElementById('dot2').classList.remove('active');
    document.getElementById('lbl1').classList.add('active');
    document.getElementById('lbl2').classList.remove('active');
}
</script>

</body>
</html>
