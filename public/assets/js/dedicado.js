// Menú móvil
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');

menuToggle.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});

const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
    });
});

// Navbar scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Formulario empresarial
document.getElementById('formEmpresarial').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Aquí se integraría con el backend
    alert('Gracias por su interés. Un asesor empresarial se contactará con usted en las próximas 24 horas.');
    this.reset();
});

// Animaciones de scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.fade-in-element, .slide-in-left, .slide-in-right');
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 50);
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

document.addEventListener('DOMContentLoaded', animateOnScroll);

// Contador animado para estadísticas SLA
const animateCounters = () => {
    const counters = document.querySelectorAll('.counter');
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const targetValue = parseFloat(target.getAttribute('data-target'));
                let current = 0;
                const increment = targetValue / 50;
                const isDecimal = targetValue % 1 !== 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= targetValue) {
                        target.textContent = isDecimal ? targetValue.toFixed(1) : targetValue;
                        clearInterval(timer);
                    } else {
                        target.textContent = isDecimal ? current.toFixed(1) : Math.floor(current);
                    }
                }, 30);
                
                counterObserver.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
};

setTimeout(animateCounters, 100);

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            const target = document.querySelector(href);
            const offsetTop = target.offsetTop - 70;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});