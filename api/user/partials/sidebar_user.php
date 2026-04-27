<?php $page = isset($page) ? $page : ''; ?>

<link rel="stylesheet" href="/public/assets/css/sidebar_user.css">

<aside class="sidebar-modern">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
        <span class="brand-name">AgriData</span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($page == 'dashboard' || $page == '') ? 'active' : '' ?>">
                <a href="/api/user/dashboard.php"><i class="fa-solid fa-chart-pie"></i><span>Dashboard</span></a>
            </li>
            <li class="<?= ($page == 'data_panen') ? 'active' : '' ?>">
                <a href="/api/user/dashboard.php?view=panen"><i class="fa-solid fa-seedling"></i><span>Data Panen</span></a>
            </li>
        </ul>
    </nav>
</aside>

<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <h3 style="margin-bottom:15px;">Konfirmasi Keluar</h3>
        <p style="color:#64748b;margin-bottom:25px;">Apakah Anda yakin ingin keluar dari sistem?</p>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button onclick="document.getElementById('logoutModal').classList.remove('active')" style="padding:10px 20px;border-radius:8px;border:1px solid #cbd5e1;background:white;cursor:pointer;">Batal</button>
            <a href="/api/logout.php" style="padding:10px 20px;border-radius:8px;background:#ef4444;color:white;text-decoration:none;font-weight:600;">Ya, Keluar</a>
        </div>
    </div>
</div>
