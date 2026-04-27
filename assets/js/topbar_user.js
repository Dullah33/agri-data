// assets/js/topbar_user.js
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('profileDropdownTrigger');
    const menu = document.getElementById('profileMenu');
    const logoutInDropdown = document.getElementById('triggerLogoutDropdown');
    const mainLogoutModal = document.getElementById('logoutModal');

    // Toggle Dropdown
    if (trigger && menu) {
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        });

        // Tutup dropdown jika klik di luar area profil
        window.addEventListener('click', function () {
            menu.classList.remove('show');
        });
    }

    // Pemicu Modal Logout
    if (logoutInDropdown && mainLogoutModal) {
        logoutInDropdown.addEventListener('click', function (e) {
            e.preventDefault();
            mainLogoutModal.classList.add('active');
            menu.classList.remove('show');
        });
    }
});
