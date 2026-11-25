document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.password-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.closest('.password-wrapper')?.querySelector('input');
            const icon = button.querySelector('i');
            if (!input || !icon) return;

            const showPassword = input.type === 'password';
            input.type = showPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !showPassword);
            icon.classList.toggle('fa-eye-slash', showPassword);
            button.setAttribute('aria-label', showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    });

    document.querySelectorAll('.auth-wrapper form').forEach((form) => {
        form.addEventListener('submit', () => {
            if (!form.checkValidity()) return;
            const button = form.querySelector('button[type="submit"]');
            if (!button) return;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> ĐANG XỬ LÝ...';
        });
    });

    const successAlert = document.getElementById('general-success');
    if (successAlert && successAlert.dataset.redirectUrl) {
        setTimeout(() => {
            window.location.href = successAlert.dataset.redirectUrl;
        }, 2000);
    }
});
