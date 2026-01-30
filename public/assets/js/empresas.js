// Configuración de la secuencia bento
class BentoSlider {
    constructor() {
        this.items = [
            { element: document.querySelector('.item-1'), direction: 'left' },
            { element: document.querySelector('.item-2'), direction: 'left' },
            { element: document.querySelector('.item-3'), direction: 'left' },
            { element: document.querySelector('.item-4'), direction: 'right' },
            { element: document.querySelector('.item-5'), direction: 'right' }
        ];
        
        this.config = {
            duration: 1500,   
            transition: 400,  
            delay: 3000      
        };
        
        this.currentIndex = 0;
        this.isAnimating = false;
        this.init();
    }
    
    init() {
        // Inicializar: todas las imágenes ocultas
        this.items.forEach(item => {
            const imgFace = item.element.querySelector('.image-face');
            const textFace = item.element.querySelector('.text-face');
            
            if (imgFace && textFace) {
                // Items 1-3: imagen oculta a la izquierda
                if (item.direction === 'left') {
                    imgFace.classList.add('hidden-left');
                }
                // Items 4-5: imagen oculta a la derecha
                else {
                    imgFace.classList.add('hidden-right');
                }
            }
        });
        
        // Iniciar secuencia después de 1 segundo
        setTimeout(() => {
            this.startSequence();
        }, 1000);
    }
    
    startSequence() {
        // Animar el primer item inmediatamente
        this.animateItem(0);
        
        // Programar las siguientes animaciones
        for (let i = 1; i < this.items.length; i++) {
            setTimeout(() => {
                this.animateItem(i);
            }, this.config.delay * i);
        }
        
        // Ciclo infinito
        setTimeout(() => {
            this.restartSequence();
        }, this.config.delay * this.items.length);
    }
    
    restartSequence() {
        // Resetear todas las clases
        this.items.forEach(item => {
            const imgFace = item.element.querySelector('.image-face');
            const textFace = item.element.querySelector('.text-face');
            
            // Remover todas las clases de animación
            imgFace.classList.remove('visible-left', 'hidden-left', 'visible-right', 'hidden-right');
            textFace.classList.remove('hidden-left', 'hidden-right');
            textFace.classList.add('active');
            
            // Resetear a estado inicial
            if (item.direction === 'left') {
                imgFace.classList.add('hidden-left');
            } else {
                imgFace.classList.add('hidden-right');
            }
        });
        
        // Reiniciar secuencia
        this.currentIndex = 0;
        this.startSequence();
    }
    
    animateItem(index) {
        if (this.isAnimating || index >= this.items.length) return;
        
        this.isAnimating = true;
        this.currentIndex = index;
        const item = this.items[index];
        const imgFace = item.element.querySelector('.image-face');
        const textFace = item.element.querySelector('.text-face');
        
        if (!imgFace || !textFace) {
            this.isAnimating = false;
            return;
        }
        
        // Mostrar imagen
        setTimeout(() => {
            if (item.direction === 'left') {
                // Quitar clases anteriores
                imgFace.classList.remove('hidden-left');
                textFace.classList.remove('active');
                
                // Aplicar animación de entrada
                imgFace.classList.add('visible-left');
                textFace.classList.add('hidden-left');
            } else {
                // Quitar clases anteriores
                imgFace.classList.remove('hidden-right');
                textFace.classList.remove('active');
                
                // Aplicar animación de entrada
                imgFace.classList.add('visible-right');
                textFace.classList.add('hidden-right');
            }
        }, 100);
        
        // Ocultar imagen después de la duración configurada
        setTimeout(() => {
            if (item.direction === 'left') {
                // Revertir a texto
                imgFace.classList.remove('visible-left');
                imgFace.classList.add('hidden-left');
                textFace.classList.remove('hidden-left');
                textFace.classList.add('active');
            } else {
                // Revertir a texto
                imgFace.classList.remove('visible-right');
                imgFace.classList.add('hidden-right');
                textFace.classList.remove('hidden-right');
                textFace.classList.add('active');
            }
            
            this.isAnimating = false;
        }, this.config.duration + this.config.transition);
    }
}

// Función para animaciones al hacer scroll
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

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Iniciar slider bento si existe
    if (document.querySelector('.bento-item')) {
        new BentoSlider();
    }
    
    // Inicializar animaciones
    animateOnScroll();
    
    // Inicializar contadores después de un breve retraso
    setTimeout(animateCounters, 100);
});