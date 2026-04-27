<?php
require_once __DIR__ . '/../../helpers/auth_cookie.php';
$user     = getAuthUser();
$name     = $user['name'] ?? 'Guest';
$username = $user['username'] ?? 'guest';
$placeholder = isset($search_placeholder) ? $search_placeholder : "Cari data...";
?>

<link rel="stylesheet" href="/public/assets/css/topbar_user.css">

<header class="topbar-modern">
    <div class="search-box-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="<?= htmlspecialchars($placeholder) ?>">
    </div>
    <div class="topbar-right">
        <div class="profile-trigger" id="profileDropdownTrigger">
            <div class="user-info-text" style="text-align:right;margin-right:15px;">
                <span class="user-name"><?= htmlspecialchars($name) ?></span>
                <span class="user-role">Petani Aktif</span>
            </div>
            <div class="avatar-topbar"><?= strtoupper(substr($name, 0, 1)) ?></div>
            <div class="profile-dropdown" id="profileMenu">
                <a href="/user/profile">Pengaturan Profil</a>
                <hr>
                <a href="/logout" style="color:red;">Logout</a>
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
    document.getElementById('profileMenu').classList.remove('show');
};
</script>
