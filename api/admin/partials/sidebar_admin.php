<?php $page = isset($page) ? $page : ''; ?>

<aside class="sidebar-modern" id="sidebarAdmin">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
        <span class="brand-name">AgriData</span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($page == 'dashboard') ? 'active' : '' ?>">
                <a href="/admin/dashboard">
                    <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="<?= ($page == 'data_petani') ? 'active' : '' ?>">
                <a href="/admin/petani">
                    <i class="fa-solid fa-user-group"></i><span>Data Petani</span>
                </a>
            </li>
            <li class="nav-header" style="padding:15px 20px 5px;font-size:11px;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;margin-top:10px;">
                MANAJEMEN DATA
            </li>
            <li class="<?= ($page == 'data_panen') ? 'active' : '' ?>">
                <a href="/admin/petani?action=panen">
                    <i class="fa-solid fa-wheat-awn"></i><span>Data Panen</span>
                </a>
            </li>
            <li class="<?= ($page == 'data_lahan') ? 'active' : '' ?>">
                <a href="/admin/lahan">
                    <i class="fa-solid fa-seedling"></i><span>Data Lahan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Overlay untuk menutup sidebar di mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <div class="modal-icon-warning"><i class="fa-solid fa-right-from-bracket"></i></div>
        <h3>Konfirmasi Keluar</h3>
        <p>Apakah Anda yakin ingin keluar dari sistem Admin?</p>
        <div class="modal-actions">
            <button class="btn-cancel" id="btnBatalLogout">Batal</button>
            <a href="/logout" class="btn-confirm-logout">Ya, Keluar</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar  = document.getElementById('sidebarAdmin');
        const overlay  = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const modal    = document.getElementById('logoutModal');
        const btnBatal = document.getElementById('btnBatalLogout');

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
            window.onclick = (e) => {
                if (e.target == modal) modal.classList.remove('active');
            }
        }
    });
</script>
