<?php $placeholder = isset($search_placeholder) ? $search_placeholder : "Cari data (Coming Soon)"; ?>

<link rel="stylesheet" href="/public/assets/css/topbar_admin.css">

<header class="topbar-modern">
    <div class="search-box-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="<?= htmlspecialchars($placeholder) ?>" disabled>
    </div>
    <div class="topbar-right">
        <div class="profile-trigger" id="profileDropdownTrigger">
            <div class="user-info-text">
                <span class="user-name"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></span>
                <span class="user-role">Super Admin</span>
            </div>
            <div class="avatar-topbar"><?= strtoupper(substr($user['name'] ?? 'Admin', 0, 1)) ?></div>
            <div class="profile-dropdown" id="profileMenu">
                <div class="dropdown-header">
                    <strong>Sistem Admin</strong>
                    <span>@<?= htmlspecialchars($user['username'] ?? 'admin') ?></span>
                </div>
                <hr>
                <a href="/admin/profile"><i class="fa-solid fa-user-shield"></i> Profil Admin</a>
                <a href="/logout" id="triggerLogoutDropdown"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('profileDropdownTrigger');
    const menu = document.getElementById('profileMenu');
    const logoutTrigger = document.getElementById('triggerLogoutDropdown');
    const modal = document.getElementById('logoutModal');
    trigger.onclick = (e) => { e.stopPropagation(); menu.classList.toggle('show'); };
    window.onclick = () => menu.classList.remove('show');
    if (logoutTrigger && modal) {
        logoutTrigger.onclick = (e) => { e.preventDefault(); modal.classList.add('active'); };
    }
});
</script>
