<?php
require_once __DIR__ . '/../../helpers/auth_cookie.php';
$user     = getAuthUser();
$name     = $user['name'] ?? 'Guest';
$username = $user['username'] ?? 'guest';
$placeholder = isset($search_placeholder) ? $search_placeholder : "Cari data...(Comming Soon)";
?>

<header class="topbar-modern">
    <!-- Tombol hamburger untuk mobile -->
    <button class="sidebar-toggle" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="search-box-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="<?= htmlspecialchars($placeholder) ?>">
    </div>

    <div class="topbar-right">
        <div class="profile-trigger" id="profileDropdownTrigger">
            <div class="user-info-text" style="text-align:right; margin-right:15px; display:flex; flex-direction:column;">
                <span class="user-name"><?= htmlspecialchars($name) ?></span>
                <span class="user-role">Petani Aktif</span>
            </div>

            <div class="avatar-topbar"><?= strtoupper(substr($name, 0, 1)) ?></div>

            <div class="profile-dropdown" id="profileMenu">
                <a href="/user/profile"><i class="fa-solid fa-user-pen"></i> Pengaturan Profil</a>
                <hr style="margin: 5px 0; border: none; border-top: 1px solid #f1f5f9;">
                <a href="/logout" style="color: #ef4444;" id="triggerLogoutDropdown"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger   = document.getElementById('profileDropdownTrigger');
    const menu      = document.getElementById('profileMenu');
    const logoutBtn = document.getElementById('triggerLogoutDropdown');
    const modal     = document.getElementById('logoutModal');

    if (trigger && menu) {
        trigger.onclick = function(e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        };
        window.addEventListener('click', function() {
            if (menu.classList.contains('show')) menu.classList.remove('show');
        });
    }

    // Jika ada modal logout, gunakan modal; jika tidak, biarkan langsung redirect
    if (logoutBtn && modal) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('active');
            menu.classList.remove('show');
        });
    }
});
</script>
