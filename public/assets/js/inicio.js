// Código específico de index.html

// Tabs de planes (index.html)
document.addEventListener('DOMContentLoaded', function () {
	const tabBtns = document.querySelectorAll('.tab-btn');
	const tabPanes = document.querySelectorAll('.tab-pane');
	if (tabBtns.length && tabPanes.length) {
		tabBtns.forEach(btn => {
			btn.addEventListener('click', function () {
				tabBtns.forEach(b => b.classList.remove('active'));
				this.classList.add('active');
				tabPanes.forEach(pane => pane.classList.remove('active'));
				const targetId = 'tab' + this.dataset.tab.charAt(0).toUpperCase() + this.dataset.tab.slice(1);
				const targetPane = document.getElementById(targetId);
				if (targetPane) {
					targetPane.classList.add('active');
				}
			});
		});
	}

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
