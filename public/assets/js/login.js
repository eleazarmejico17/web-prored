// Código específico de login.html

document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility
    const togglePassword = () => {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    };
    if (document.getElementById('toggleIcon')) {
        document.getElementById('toggleIcon').addEventListener('click', togglePassword);
    }

    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const usuario = document.getElementById('usuario').value;
            const password = document.getElementById('password').value;
            const alertMessage = document.getElementById('alertMessage');
            if (usuario && password) {
                if (usuario === 'demo' && password === 'demo123') {
                    alertMessage.className = 'alert alert-success';
                    alertMessage.textContent = '¡Inicio de sesión exitoso! Redirigiendo...';
                    alertMessage.style.display = 'block';
                    setTimeout(() => {
                        alert('En producción, serías redirigido al dashboard del cliente.');
                    }, 1500);
                } else {
                    alertMessage.className = 'alert alert-error';
                    alertMessage.textContent = 'Usuario o contraseña incorrectos. Intenta nuevamente.';
                    alertMessage.style.display = 'block';
                }
            }
        });
    }
});
