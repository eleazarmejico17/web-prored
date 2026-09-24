
// Código específico de planes/index.html

document.addEventListener('DOMContentLoaded', function () {
    // Tabs de planes → tabs.js

    // Animaciones de entrada al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in-element, .slide-in-left, .slide-in-right, .scale-in').forEach(el => {
        observer.observe(el);
    });

    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Cerrar todos los demás
            faqItems.forEach(i => i.classList.remove('active'));
            
            // Abrir el actual si no estaba activo
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Smooth scroll para enlaces internos (ej: #planes-prev)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
