// Código específico de index.html (tabs → tabs.js)

document.addEventListener('DOMContentLoaded', function () {
	// Animación de entrada para las cards (index.html)
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

	// Contador animado para las estadísticas (index.html)
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
});
