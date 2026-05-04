<?php $page = isset($page) ? $page : ''; ?>

<aside class="sidebar-modern" id="sidebarUser">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
        <span class="brand-name">AgriData</span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($page == 'dashboard' || $page == '') ? 'active' : '' ?>">
                <a href="/user/dashboard"><i class="fa-solid fa-chart-pie"></i><span>Dashboard</span></a>
            </li>
            <li class="<?= ($page == 'data_lahan') ? 'active' : '' ?>">
                <a href="/user/lahan"><i class="fa-solid fa-map-location-dot"></i><span>Data Lahan</span></a>
            </li>
            <li class="<?= ($page == 'data_panen') ? 'active' : '' ?>">
                <a href="/user/panen"><i class="fa-solid fa-seedling"></i><span>Data Panen</span></a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Overlay untuk menutup sidebar di mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <h3 style="margin-bottom:15px;">Konfirmasi Keluar</h3>
        <p style="color:#64748b;margin-bottom:25px;">Apakah Anda yakin ingin keluar dari sistem?</p>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button id="btnBatalLogout" style="padding:10px 20px;border-radius:8px;border:1px solid #cbd5e1;background:white;cursor:pointer;">Batal</button>
            <a href="/logout" style="padding:10px 20px;border-radius:8px;background:#ef4444;color:white;text-decoration:none;font-weight:600;">Ya, Keluar</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar   = document.getElementById('sidebarUser');
    const overlay   = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const modal     = document.getElementById('logoutModal');
    const btnBatal  = document.getElementById('btnBatalLogout');

    // Hamburger toggle
    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }

    // Modal logout
    if (modal && btnBatal) {
        btnBatal.onclick = () => modal.classList.remove('active');
        window.addEventListener('click', (e) => {
            if (e.target == modal) modal.classList.remove('active');
        });
    }
});
</script>
