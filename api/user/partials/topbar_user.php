<?php
require_once __DIR__ . '/../../helpers/auth_cookie.php';
$user     = getAuthUser();
$name     = $user['name'] ?? 'Guest';
$username = $user['username'] ?? 'guest';
$placeholder = isset($search_placeholder) ? $search_placeholder : "Cari data...(Comming Soon)";
?>

<header class="topbar-modern">
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
                <a href="/logout" style="color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('profileDropdownTrigger').onclick = function(e) {
        e.stopPropagation();
        document.getElementById('profileMenu').classList.toggle('show');
    };
    window.onclick = function() {
        const menu = document.getElementById('profileMenu');
        if (menu && menu.classList.contains('show')) {
            menu.classList.remove('show');
        }
    };
</script>