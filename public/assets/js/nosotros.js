// Código específico de nosotros.html

document.addEventListener('DOMContentLoaded', function () {
    // Contador animado para estadísticas
    const animateCounters = () => {
        const counters = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const targetValue = parseInt(target.getAttribute('data-target'));
                    let current = 0;
                    const increment = targetValue / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= targetValue) {
                            target.textContent = targetValue + (targetValue === 10 ? '+' : '');
                            clearInterval(timer);
                        } else {
                            target.textContent = Math.floor(current);
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
});
