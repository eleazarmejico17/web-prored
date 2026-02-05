// Efecto de Partículas en Red - Hero Section
(function () {
  const canvas = document.getElementById("networkCanvas");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  const hero = document.querySelector(".hero");

  // Configuración
  const config = {
    particleCount: 50,
    particleSpeed: 0.3,
    connectionDistance: 150,
    particleSize: 2,
    particleColor: "rgba(255, 255, 255, 0.8)",
    lineColor: "rgba(0, 191, 255, 0.3)",
    lineWidth: 1,
  };

  let particles = [];
  let animationId;

  // Ajustar tamaño del canvas
  function resizeCanvas() {
    canvas.width = hero.offsetWidth;
    canvas.height = hero.offsetHeight;
  }

  // Clase Partícula
  class Particle {
    constructor() {
      this.x = Math.random() * canvas.width;
      this.y = Math.random() * canvas.height;
      this.vx = (Math.random() - 0.5) * config.particleSpeed;
      this.vy = (Math.random() - 0.5) * config.particleSpeed;
      this.size = config.particleSize;
    }

    update() {
      this.x += this.vx;
      this.y += this.vy;

      // Rebotar en los bordes
      if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
      if (this.y < 0 || this.y > canvas.height) this.vy *= -1;

      // Mantener dentro del canvas
      this.x = Math.max(0, Math.min(canvas.width, this.x));
      this.y = Math.max(0, Math.min(canvas.height, this.y));
    }

    draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
      ctx.fillStyle = config.particleColor;
      ctx.fill();
    }
  }

  // Crear partículas
  function createParticles() {
    particles = [];
    for (let i = 0; i < config.particleCount; i++) {
      particles.push(new Particle());
    }
  }

  // Dibujar conexiones entre partículas cercanas
  function drawConnections() {
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance < config.connectionDistance) {
          const opacity = 1 - distance / config.connectionDistance;
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0, 191, 255, ${opacity * 0.3})`;
          ctx.lineWidth = config.lineWidth;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
  }

  // Animar
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Actualizar y dibujar partículas
    particles.forEach((particle) => {
      particle.update();
      particle.draw();
    });

    // Dibujar conexiones
    drawConnections();

    animationId = requestAnimationFrame(animate);
  }

  // Inicializar
  function init() {
    resizeCanvas();
    createParticles();
    animate();
  }

  // Event listeners
  window.addEventListener("resize", () => {
    resizeCanvas();
    createParticles();
  });

  // Iniciar cuando el DOM esté listo
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Limpiar al salir
  window.addEventListener("beforeunload", () => {
    if (animationId) {
      cancelAnimationFrame(animationId);
    }
  });
})();
