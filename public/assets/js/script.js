// Menú móvil toggle
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');
const navOverlay = document.getElementById('navOverlay');
// Menú móvil, overlay y navbar scroll reutilizable para todas las páginas
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');
    const navbar = document.getElementById('navbar');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function () {
            const isActive = navMenu.classList.toggle('active');
            if (navOverlay) {
                if (isActive) {
                    navOverlay.classList.add('active');
                    document.body.classList.add('no-scroll');
                } else {
                    navOverlay.classList.remove('active');
                    document.body.classList.remove('no-scroll');
                }
            }
        });
    }

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
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

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

    // Animaciones de scroll reutilizables
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.fade-in-element, .slide-in-left, .slide-in-right, .scale-in');
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
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
    animateOnScroll();
});