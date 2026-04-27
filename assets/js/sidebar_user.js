// assets/js/sidebar_user.js
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('logoutModal');
    const btnBatal = document.getElementById('btnBatalLogout');

    if (modal && btnBatal) {
        // Klik tombol batal
        btnBatal.onclick = () => modal.classList.remove('active');

        // Klik di area gelap (overlay) untuk menutup
        window.onclick = (e) => {
            if (e.target == modal) {
                modal.classList.remove('active');
            }
        };
    }
});
