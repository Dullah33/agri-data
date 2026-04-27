<?php
require_once __DIR__ . '/../helpers/jwt_helper.php';

$token = $_COOKIE['token'] ?? null;
$user = $token ? verifyJWT($token) : null;

$name = $user['name'] ?? 'Guest';
$username = $user['username'] ?? 'guest';

$placeholder = isset($search_placeholder) ? $search_placeholder : "Cari data...";
?>

<link rel="stylesheet" href="../../assets/css/topbar_user.css">

<header class="topbar-modern">
    <div class="search-box-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="<?= htmlspecialchars($placeholder) ?>">
    </div>

    <div class="topbar-right">
        <div class="profile-trigger" id="profileDropdownTrigger">
            <div class="user-info-text" style="text-align: right; margin-right: 15px;">
                <span class="user-name">
                    <?= htmlspecialchars($name); ?>
                </span>
                <span class="user-role">Petani Aktif</span>
            </div>

            <div class="avatar-topbar">
                <?= strtoupper(substr($name, 0, 1)); ?>
            </div>

            <div class="profile-dropdown" id="profileMenu">
                <a href="profile_user.php">Pengaturan Profil</a>
                <hr>
                <a href="/pages/logout.php" style="color:red;">Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('profileDropdownTrigger').onclick = function(e) {
        e.stopPropagation();
        document.getElementById('profileMenu').classList.toggle('show');
    };
    document.getElementById('triggerLogoutUser').onclick = function(e) {
        e.preventDefault();
        document.getElementById('logoutModal').classList.add('active');
    };
    window.onclick = function() {
        document.getElementById('profileMenu').classList.remove('show');
    };
</script>