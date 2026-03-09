// Script Global de Manejo de Cookies
(function() {
    'use strict';

    // Obtener la ruta correcta a politica-de-privacidad según la ubicación actual
    function getPoliciesLink() {
        const currentPath = window.location.pathname;
        // Si estamos en uno de los nuevos subdirectorios
        if (currentPath.includes('/cobertura') || 
            currentPath.includes('/internet-empresas') || 
            currentPath.includes('/nosotros') || 
            currentPath.includes('/realizar-pagos') || 
            currentPath.includes('/politica-de-privacidad') ||
            currentPath.includes('/public/')) {
            return '../politica-de-privacidad/';
        }
        // Si estamos en la raíz
        return 'politica-de-privacidad/';
    }

    // Crear HTML del modal si no existe
    function createCookieModal() {
        // Verificar si ya existe el modal
        if (document.getElementById('cookieModal')) {
            return;
        }

        const policiesLink = getPoliciesLink();

        const modalHTML = `
        <div id="cookieModal" class="cookie-modal">
            <div class="cookie-content">
                <div class="cookie-header">
                    <i class="fas fa-cookie-bite"></i>
                    <h3>Política de Cookies</h3>
                    <button id="closeCookieBtn" class="close-cookie-btn" aria-label="Cerrar">×</button>
                </div>
                <div class="cookie-body">
                    <p>
                        Utilizamos cookies y tecnologías similares para mejorar tu experiencia en nuestro sitio web. 
                        Las cookies nos ayudan a entender cómo utilizas nuestro sitio y nos permiten personalizar el contenido y los anuncios.
                    </p>
                    <p>
                        Al hacer clic en <strong>"Aceptar"</strong>, consientes el uso de todas las cookies. 
                        Puedes cambiar tus preferencias en cualquier momento.
                    </p>
                </div>
                <div class="cookie-footer">
                    <a href="${policiesLink}" class="cookie-link">Leer más sobre cookies</a>
                    <div class="cookie-buttons">
                        <button id="rejectCookieBtn" class="btn btn-outline btn-small">Rechazar</button>
                        <button id="acceptCookieBtn" class="btn btn-secondary btn-small">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
        `;

        // Insertar modal al final del body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    // Verificar si el usuario ya aceptó cookies
    function hasUserConsented() {
        return localStorage.getItem('cookieConsent') === 'accepted';
    }

    // Mostrar modal
    function showCookieModal() {
        const modal = document.getElementById('cookieModal');
        if (modal && !hasUserConsented()) {
            modal.classList.add('show');
        }
    }

    // Ocultar modal
    function hideCookieModal() {
        const modal = document.getElementById('cookieModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Manejar eventos del modal
    function setupCookieEvents() {
        const acceptBtn = document.getElementById('acceptCookieBtn');
        const rejectBtn = document.getElementById('rejectCookieBtn');
        const closeBtn = document.getElementById('closeCookieBtn');
        const modal = document.getElementById('cookieModal');

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function() {
                localStorage.setItem('cookieConsent', 'accepted');
                localStorage.setItem('cookieConsentDate', new Date().toISOString());
                hideCookieModal();
            });
        }

        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                localStorage.setItem('cookieConsent', 'rejected');
                localStorage.setItem('cookieConsentDate', new Date().toISOString());
                hideCookieModal();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                hideCookieModal();
            });
        }

        // Cerrar al hacer clic fuera del modal
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    hideCookieModal();
                }
            });
        }
    }

    // Función global para resetear consentimiento
    window.resetCookieConsent = function() {
        localStorage.removeItem('cookieConsent');
        localStorage.removeItem('cookieConsentDate');
        const modal = document.getElementById('cookieModal');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 100);
        }
    };

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            createCookieModal();
            setupCookieEvents();
            
            // Mostrar modal después de 1 segundo solo si no ha consentido
            if (!hasUserConsented()) {
                setTimeout(showCookieModal, 1000);
            }
        });
    } else {
        // Ejecutar inmediatamente si el DOM ya está listo
        createCookieModal();
        setupCookieEvents();
        
        if (!hasUserConsented()) {
            setTimeout(showCookieModal, 1000);
        }
    }
})();
