// Manejo del Modal de Cookies
document.addEventListener('DOMContentLoaded', function() {
    const cookieModal = document.getElementById('cookieModal');
    const acceptCookieBtn = document.getElementById('acceptCookieBtn');
    const rejectCookieBtn = document.getElementById('rejectCookieBtn');
    const closeCookieBtn = document.getElementById('closeCookieBtn');

    // Verificar si el usuario ya aceptó/rechazó cookies
    function shouldShowCookieModal() {
        return !localStorage.getItem('cookieConsent');
    }

    // Mostrar modal si no hay consentimiento guardado
    if (shouldShowCookieModal()) {
        setTimeout(() => {
            cookieModal.classList.add('show');
        }, 1000);
    }

    // Aceptar cookies
    if (acceptCookieBtn) {
        acceptCookieBtn.addEventListener('click', function() {
            localStorage.setItem('cookieConsent', 'accepted');
            localStorage.setItem('cookieConsentDate', new Date().toISOString());
            cookieModal.classList.remove('show');
            hideCookieModal();
        });
    }

    // Rechazar cookies
    if (rejectCookieBtn) {
        rejectCookieBtn.addEventListener('click', function() {
            localStorage.setItem('cookieConsent', 'rejected');
            localStorage.setItem('cookieConsentDate', new Date().toISOString());
            cookieModal.classList.remove('show');
            hideCookieModal();
        });
    }

    // Cerrar modal
    if (closeCookieBtn) {
        closeCookieBtn.addEventListener('click', function() {
            // No guardar nada si se cierra sin aceptar/rechazar
            closeCookieModal();
        });
    }

    // Cerrar modal cuando se hace clic fuera
    cookieModal.addEventListener('click', function(event) {
        if (event.target === cookieModal) {
            closeCookieModal();
        }
    });

    function hideCookieModal() {
        setTimeout(() => {
            cookieModal.style.display = 'none';
        }, 300);
    }

    function closeCookieModal() {
        cookieModal.classList.remove('show');
        setTimeout(() => {
            cookieModal.style.display = 'none';
        }, 300);
    }

    // Función para mostrar modal nuevamente (útil para botones de preferencias)
    window.resetCookieConsent = function() {
        localStorage.removeItem('cookieConsent');
        localStorage.removeItem('cookieConsentDate');
        cookieModal.style.display = 'flex';
        setTimeout(() => {
            cookieModal.classList.add('show');
        }, 100);
    };
});
