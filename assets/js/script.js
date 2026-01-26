// Menú móvil toggle
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');
const navOverlay = document.getElementById('navOverlay');

const toggleMobileMenu = () => {
    const isActive = navMenu.classList.toggle('active');
    if (isActive) {
        navOverlay.classList.add('active');
        document.body.classList.add('no-scroll');
    } else {
        navOverlay.classList.remove('active');
        document.body.classList.remove('no-scroll');
    }
};

menuToggle.addEventListener('click', toggleMobileMenu);

// cerrar menú al hacer click en el overlay
if (navOverlay) {
    navOverlay.addEventListener('click', () => {
        navMenu.classList.remove('active');
        navOverlay.classList.remove('active');
        document.body.classList.remove('no-scroll');
    });
}

// Cerrar menú al hacer click en un enlace
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        if (navOverlay) navOverlay.classList.remove('active');
        document.body.classList.remove('no-scroll');
    });
});

// Navbar scroll effect
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth scroll para enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#' || href === '#contacto' || href === '#inicio' || href === '#planes') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offsetTop = target.offsetTop - 70;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // 1. Remover clase 'active' de todos los botones
            tabBtns.forEach(b => b.classList.remove('active'));
            // 2. Añadir 'active' al botón clickeado
            this.classList.add('active');

            // 3. Ocultar todos los paneles de contenido
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // 4. Mostrar el panel correspondiente al data-tab
            const targetId = 'tab' + this.dataset.tab.charAt(0).toUpperCase() + this.dataset.tab.slice(1);
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});

// Animación de entrada para las cards
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '0';
            entry.target.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                entry.target.style.transition = 'all 0.6s ease-out';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }, 100);
            
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.card, .plan-card').forEach(card => {
    observer.observe(card);
});

// Sistema de animaciones mejorado al hacer scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.fade-in-element, .slide-in-left, .slide-in-right, .scale-in');
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Añadir delay escalonado para elementos múltiples
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 100);
                scrollObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
    });
    
    elements.forEach(element => {
        scrollObserver.observe(element);
    });
};

// Iniciar animaciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', animateOnScroll);

// Contador animado para las estadísticas
const animateCounters = () => {
    const counters = document.querySelectorAll('.fade-in-element div[style*="font-size: 3rem"]');
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const text = target.textContent;
                const number = parseInt(text);
                
                if (!isNaN(number)) {
                    let current = 0;
                    const increment = number / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= number) {
                            target.textContent = text;
                            clearInterval(timer);
                        } else {
                            target.textContent = Math.floor(current) + text.replace(/[0-9]/g, '');
                        }
                    }, 30);
                }
                
                counterObserver.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
};

setTimeout(animateCounters, 100);