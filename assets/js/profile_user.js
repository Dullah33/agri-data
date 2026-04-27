// assets/js/profile_user.js
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.onsubmit = function (e) {
        const password = document.querySelector('input[name="password"]').value;

        if (password !== '' && password.length < 6) {
            e.preventDefault();
            alert('Password baru minimal harus 6 karakter!');
        }
    };
});
