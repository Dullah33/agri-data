<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fallback: jika session 'name' belum ada (login lama), ambil dari first_name atau set Guest
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    $_SESSION['name'] = $_SESSION['first_name'] ?? 'Guest';
}
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'guest';
}

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
                <span class="user-name" style="display: block; font-weight: 600; color: #1e293b;">
                    <?= htmlspecialchars($_SESSION['name']); ?>
                </span>
                <span class="user-role" style="font-size: 12px; color: #64748b;">Petani Aktif</span>
            </div>
            <div class="avatar-topbar" style="width: 40px; height: 40px; background: linear-gradient(135deg,#2D6A4F,#40916C); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
                <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
            </div>

            <div class="profile-dropdown" id="profileMenu">
                <a href="profile_user.php"><i class="fa-solid fa-user-gear"></i> Pengaturan Profil</a>
                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 5px 0;">
                <a href="#" id="triggerLogoutUser" style="color: #ef4444;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
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
