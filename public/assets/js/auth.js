document.addEventListener('DOMContentLoaded', () => {
    // 1. Password Visibility Toggle (for all toggle buttons)
    const toggleButtons = document.querySelectorAll('.password-toggle');
    
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // Find the input in the same wrapper
            const wrapper = this.closest('.password-wrapper');
            const passwordInput = wrapper.querySelector('input');
            
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    this.setAttribute('aria-label', 'Ẩn mật khẩu');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    this.setAttribute('aria-label', 'Hiện mật khẩu');
                }
            }
        });
    });

    // 2. Form Validation & Loading State for Login
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const passwordInput = document.getElementById('password');
        const passwordError = document.getElementById('password-error');
        const submitBtn = document.getElementById('submit-btn');
        const generalError = document.getElementById('general-error');

        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;

            emailError.style.display = 'none';
            passwordError.style.display = 'none';
            if (generalError) generalError.style.display = 'none';

            if (!emailInput.value.trim()) {
                emailError.textContent = 'Vui lòng nhập email hoặc tên đăng nhập.';
                emailError.style.display = 'block';
                isValid = false;
            }

            if (!passwordInput.value.trim()) {
                passwordError.textContent = 'Vui lòng nhập mật khẩu.';
                passwordError.style.display = 'block';
                isValid = false;
            }

            if (isValid) {
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> ĐANG ĐĂNG NHẬP...';

                setTimeout(() => {
                    if (emailInput.value === 'error@test.com') {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        if (generalError) {
                            generalError.textContent = 'Email hoặc mật khẩu không chính xác.';
                            generalError.style.display = 'block';
                        }
                    } else {
                        window.location.href = 'index.html';
                    }
                }, 1500);
            }
        });
    }

    // 3. Form Validation & Loading State for Register
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        const fullnameInput = document.getElementById('fullname');
        const fullnameError = document.getElementById('fullname-error');
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const passwordInput = document.getElementById('password');
        const passwordError = document.getElementById('password-error');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const confirmPasswordError = document.getElementById('confirm-password-error');
        const submitBtn = document.getElementById('submit-btn');
        const generalError = document.getElementById('general-error');

        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;

            // Reset errors
            fullnameError.style.display = 'none';
            emailError.style.display = 'none';
            passwordError.style.display = 'none';
            confirmPasswordError.style.display = 'none';
            if (generalError) generalError.style.display = 'none';

            if (!fullnameInput.value.trim()) {
                fullnameError.textContent = 'Vui lòng nhập họ và tên.';
                fullnameError.style.display = 'block';
                isValid = false;
            }

            if (!emailInput.value.trim()) {
                emailError.textContent = 'Vui lòng nhập địa chỉ email.';
                emailError.style.display = 'block';
                isValid = false;
            }

            if (!passwordInput.value.trim() || passwordInput.value.length < 6) {
                passwordError.textContent = 'Vui lòng nhập mật khẩu (ít nhất 6 ký tự).';
                passwordError.style.display = 'block';
                isValid = false;
            }

            if (confirmPasswordInput.value !== passwordInput.value) {
                confirmPasswordError.textContent = 'Mật khẩu xác nhận không khớp.';
                confirmPasswordError.style.display = 'block';
                isValid = false;
            }

            if (isValid) {
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> ĐANG TẠO TÀI KHOẢN...';

                setTimeout(() => {
                    window.location.href = 'login.html';
                }, 1500);
            }
        });
    }
});
