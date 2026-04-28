<?php

// PERBAIKAN: Path require yang benar (index.php berada di root folder, koneksi di api/config/)
require_once __DIR__ . '/config/koneksi.php';

$daftarFitur = [
    [
        "judul" => "Platform Overview",
        "deskripsi" => "AgriData adalah platform inovatif yang dirancang untuk menyederhanakan manajemen pertanian melalui integrasi data kelompok tani dan pemetaan lahan secara terpadu.",
        "icon" => '<svg class="feat-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
        "link" => "#",
        "is_highlight" => false
    ],
    [
        "judul" => "Smart Features",
        "deskripsi" => "Manfaatkan AgriData untuk mengumpulkan data krusial pertanian cerdas, tingkatkan pengambilan keputusan dengan analitik komprehensif dan transformasi digital.",
        "icon" => '<svg class="feat-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
        "link" => "#",
        "is_highlight" => true
    ],
    [
        "judul" => "Data Management",
        "deskripsi" => "AgriData menawarkan solusi manajemen data yang kuat, memungkinkan pemerintah daerah mengakses informasi real-time untuk pengambilan kebijakan yang efektif.",
        "icon" => '<svg class="feat-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
        "link" => "#",
        "is_highlight" => false
    ]
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriData - Smart Farming Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gp: #2D6A4F;
            --gm: #40916C;
            --gl: #74C69D;
            --gpal: #B7E4C7;
            --gultra: #D8F3DC;
            --cream: #F5F0E8;
            --dark: #1A1A2E;
            --body: #374151;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--body);
            overflow-x: hidden;
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 60px;
            background: rgba(245, 240, 232, 0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(45, 106, 79, 0.12);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gp);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--gp);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--body);
            font-size: 15px;
            font-weight: 500;
            transition: color .2s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gp);
            transition: width .3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--gp);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-cta {
            display: flex;
            gap: 10px;
        }

        .btn-ov {
            padding: 10px 22px;
            border: 2px solid var(--gp);
            border-radius: 100px;
            color: var(--gp);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-ov:hover {
            background: var(--gp);
            color: #fff;
        }

        .btn-os {
            padding: 10px 22px;
            background: var(--gp);
            border-radius: 100px;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all .2s;
            box-shadow: 0 4px 15px rgba(45, 106, 79, .3);
        }

        .btn-os:hover {
            background: var(--gm);
            transform: translateY(-1px);
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 60px 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245, 240, 232, .95) 0%, rgba(245, 240, 232, .80) 50%, rgba(45, 106, 79, .1) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 640px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gultra);
            color: var(--gp);
            padding: 8px 18px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 28px;
            border: 1px solid var(--gpal);
            animation: fadeUp .6s ease both;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(42px, 5vw, 66px);
            font-weight: 800;
            line-height: 1.05;
            color: var(--dark);
            margin-bottom: 22px;
            animation: fadeUp .6s ease .1s both;
        }

        .hero-title span {
            color: var(--gp);
            display: block;
        }

        .hero-desc {
            font-size: 17px;
            line-height: 1.75;
            color: #4B5563;
            margin-bottom: 40px;
            max-width: 520px;
            animation: fadeUp .6s ease .2s both;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            animation: fadeUp .6s ease .3s both;
        }

        .btn-hp {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: var(--gp);
            color: #fff;
            border-radius: 100px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all .3s;
            box-shadow: 0 8px 25px rgba(45, 106, 79, .35);
        }

        .btn-hp:hover {
            background: var(--gm);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(45, 106, 79, .45);
        }

        .btn-hs {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: #fff;
            color: var(--dark);
            border-radius: 100px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, .1);
            transition: all .3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .07);
        }

        .btn-hs:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
        }

        .hero-floats {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
            animation: fadeUp .6s ease .4s both;
        }

        .stat-pill {
            background: #fff;
            padding: 20px 26px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
            border: 1px solid rgba(0, 0, 0, .06);
            text-align: center;
            min-width: 148px;
        }

        .stat-pill .num {
            font-family: 'Syne', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--gp);
            line-height: 1;
        }

        .stat-pill .lbl {
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 4px;
            font-weight: 500;
        }

        /* FEATURES */
        .features {
            padding: 100px 60px;
            background: #fff;
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gpal), transparent);
        }

        .sec-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gm);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        .sec-label::before {
            content: '';
            width: 22px;
            height: 2px;
            background: var(--gm);
        }

        .sec-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(30px, 3.5vw, 46px);
            font-weight: 800;
            color: var(--dark);
            line-height: 1.15;
            margin-bottom: 56px;
            max-width: 520px;
        }

        .sec-title span {
            color: var(--gp);
        }

        .feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 26px;
        }

        .feat-card {
            background: var(--cream);
            border-radius: 24px;
            padding: 38px 34px;
            position: relative;
            overflow: hidden;
            transition: all .35s ease;
            border: 1px solid rgba(0, 0, 0, .05);
        }

        .feat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(45, 106, 79, .12);
        }

        .feat-card.hl {
            background: var(--gp);
        }

        .feat-card.hl .feat-desc,
        .feat-card.hl .feat-link {
            color: rgba(255, 255, 255, .8);
        }

        .feat-card.hl .feat-link {
            border-color: rgba(255, 255, 255, .4);
        }

        .feat-card.hl .feat-link:hover {
            background: rgba(255, 255, 255, .15);
            border-color: #fff;
            color: #fff;
        }

        .feat-icon-w {
            width: 58px;
            height: 58px;
            background: #fff;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gp);
            margin-bottom: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .07);
        }

        .feat-card.hl .feat-icon-w {
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .feat-svg {
            width: 26px;
            height: 26px;
        }

        .feat-title {
            font-family: 'Syne', sans-serif;
            font-size: 21px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .feat-card.hl .feat-title {
            color: #fff;
        }

        .feat-desc {
            font-size: 14px;
            line-height: 1.7;
            color: #6B7280;
            margin-bottom: 26px;
        }

        .feat-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1.5px solid var(--gpal);
            border-radius: 100px;
            color: var(--gp);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .feat-link:hover {
            background: var(--gp);
            border-color: var(--gp);
            color: #fff;
        }

        .feat-link i {
            font-size: 11px;
            transition: transform .2s;
        }

        .feat-link:hover i {
            transform: translateX(3px);
        }

        /* HOW IT WORKS */
        .how {
            padding: 100px 60px;
            background: var(--cream);
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 56px;
        }

        .step {
            text-align: center;
        }

        .step-n {
            width: 54px;
            height: 54px;
            background: var(--gp);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin: 0 auto 18px;
            position: relative;
        }

        .step-n::after {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 1.5px dashed var(--gpal);
        }

        .step-ttl {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .step-dsc {
            font-size: 14px;
            line-height: 1.65;
            color: #6B7280;
        }

        /* CTA */
        .cta {
            padding: 80px 60px;
            background: var(--gp);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
        }

        .cta::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -60px;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, .04);
            border-radius: 50%;
        }

        .cta h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(26px, 3.5vw, 44px);
            font-weight: 800;
            color: #fff;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .cta p {
            color: rgba(255, 255, 255, .8);
            font-size: 17px;
            margin-bottom: 34px;
            position: relative;
            z-index: 1;
        }

        .cta-acts {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .btn-cw {
            padding: 15px 34px;
            background: #fff;
            color: var(--gp);
            border-radius: 100px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all .3s;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
        }

        .btn-cw:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, .2);
        }

        .btn-cg {
            padding: 15px 34px;
            background: transparent;
            color: #fff;
            border-radius: 100px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, .5);
            transition: all .3s;
        }

        .btn-cg:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        /* FOOTER */
        footer {
            background: var(--dark);
            color: rgba(255, 255, 255, .55);
            padding: 36px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .foot-logo {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @media (max-width: 1024px) {
            nav {
                padding: 16px 30px;
            }

            .hero {
                padding: 100px 30px 60px;
            }

            .hero-floats {
                display: none;
            }

            .features,
            .how,
            .cta {
                padding: 70px 30px;
            }

            footer {
                padding: 28px 30px;
            }

            .feat-grid {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            nav {
                padding: 14px 20px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 90px 20px 50px;
            }

            .features,
            .how,
            .cta {
                padding: 50px 20px;
            }

            footer {
                padding: 22px 20px;
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .steps {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <div class="nav-logo">
            <div class="logo-icon"><i class="fa-solid fa-leaf"></i></div>
            <span class="logo-text">AgriData</span>
        </div>
        <ul class="nav-links">
            <li><a href="#" class="active">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#how">Cara Kerja</a></li>
        </ul>
        <div class="nav-cta">
            <!-- PERBAIKAN: Path link yang benar menuju api/pages/ -->
            <a href="/register" class="btn-ov">Daftar</a>
            <a href="/login" class="btn-os">Login</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg">
            <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?q=80&w=2000&auto=format&fit=crop" alt="Pertanian Indonesia">
        </div>
        <div class="hero-content">
            <div class="hero-badge"><i class="fa-solid fa-circle-check"></i> Platform Pertanian Digital Terpercaya</div>
            <h1 class="hero-title">Kelola Data Tani <span>Lebih Mudah & Cerdas</span></h1>
            <p class="hero-desc">Platform digital terpadu untuk pemantauan data kelompok tani, lahan, dan distribusi pupuk yang transparan dengan dukungan data BPS real-time.</p>
            <div class="hero-actions">
                <a href="/register" class="btn-hp"><i class="fa-solid fa-rocket"></i> Mulai Sekarang</a>
                <a href="/login" class="btn-hs"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            </div>
        </div>
        <div class="hero-floats">
            <div class="stat-pill">
                <div class="num">38</div>
                <div class="lbl">Provinsi Terpantau</div>
            </div>
            <div class="stat-pill">
                <div class="num">2023</div>
                <div class="lbl">Data Sensus BPS</div>
            </div>
            <div class="stat-pill">
                <div class="num">100%</div>
                <div class="lbl">Data Transparan</div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features" id="features">
        <div class="sec-label">Fitur Unggulan</div>
        <h2 class="sec-title">Inovasi Terbaru untuk <span>Pertanian Modern</span></h2>
        <div class="feat-grid">
            <?php foreach ($daftarFitur as $fitur): ?>
                <?php
                // PERBAIKAN: Variabel didefinisikan di dalam loop (tidak ada sebelumnya)
                $cardClass = $fitur['is_highlight'] ? 'feat-card hl' : 'feat-card';
                ?>
                <div class="<?= $cardClass ?>">
                    <div class="feat-icon-w"><?= $fitur['icon'] ?></div>
                    <h3 class="feat-title"><?= htmlspecialchars($fitur['judul']) ?></h3>
                    <p class="feat-desc"><?= htmlspecialchars($fitur['deskripsi']) ?></p>
                    <a href="<?= $fitur['link'] ?>" class="feat-link">Pelajari <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how" id="how">
        <div class="sec-label">Cara Kerja</div>
        <h2 class="sec-title">Mulai dalam <span>4 Langkah Mudah</span></h2>
        <div class="steps">
            <div class="step">
                <div class="step-n">1</div>
                <h4 class="step-ttl">Daftar Akun</h4>
                <p class="step-dsc">Buat akun sebagai petani dan lengkapi data profil untuk mulai mengakses platform.</p>
            </div>
            <div class="step">
                <div class="step-n">2</div>
                <h4 class="step-ttl">Verifikasi Admin</h4>
                <p class="step-dsc">Admin memverifikasi akun Anda dan mengaktifkan akses ke seluruh fitur platform.</p>
            </div>
            <div class="step">
                <div class="step-n">3</div>
                <h4 class="step-ttl">Lihat Data</h4>
                <p class="step-dsc">Akses data panen nasional dari BPS dan pantau statistik pertanian secara real-time.</p>
            </div>
            <div class="step">
                <div class="step-n">4</div>
                <h4 class="step-ttl">Ambil Keputusan</h4>
                <p class="step-dsc">Gunakan data dan visualisasi untuk pengambilan keputusan pertanian yang lebih cerdas.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Siap Meningkatkan Produktivitas Pertanian?</h2>
        <p>Bergabunglah dengan ribuan petani yang sudah menggunakan AgriData.</p>
        <div class="cta-acts">
            <a href="/register" class="btn-cw">Daftar Gratis</a>
            <a href="/login" class="btn-cg">Login ke Akun</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <span class="foot-logo">AgriData</span>
        <span>© 2024 AgriData Platform. Didukung data BPS Indonesia.</span>
    </footer>

</body>

</html>