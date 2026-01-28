// Código específico de cobertura.html

// Verificador de cobertura y mapa interactivo
// (Extraído de cobertura.html)
document.addEventListener('DOMContentLoaded', function () {
    // Verificador de cobertura
    const form = document.getElementById('verificadorForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const distrito = document.getElementById('distrito').value;
            const direccion = document.getElementById('direccion').value;
            const resultado = document.getElementById('resultado');
            const distritosConCobertura = ['huancayo', 'el-tambo', 'chilca', 'concepcion', 'san-jeronimo', 'pilcomayo'];
            resultado.style.display = 'block';
            if (distritosConCobertura.includes(distrito)) {
                resultado.className = 'resultado-cobertura disponible';
                resultado.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-check-circle" style="font-size: 3rem;"></i>
                        <div>
                            <h3 style="margin-bottom: 10px; font-size: 1.5rem;">¡Tenemos cobertura en tu zona!</h3>
                            <p style="margin-bottom: 15px;">Dirección: ${direccion}</p>
                            <a href="index.html#contacto" class="btn btn-primary">Contratar Ahora</a>
                        </div>
                    </div>
                `;
            } else {
                resultado.className = 'resultado-cobertura no-disponible';
                resultado.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-times-circle" style="font-size: 3rem;"></i>
                        <div>
                            <h3 style="margin-bottom: 10px; font-size: 1.5rem;">Aún no llegamos a tu zona</h3>
                            <p style="margin-bottom: 15px;">Pero estamos expandiendo nuestra red. ¡Déjanos tus datos y te contactaremos pronto!</p>
                            <a href="tel:+51123456789" class="btn btn-primary" style="background: #dc3545;">Contactar</a>
                        </div>
                    </div>
                `;
            }
        });
    }

    // Mapa interactivo con Leaflet
    if (window.L && document.getElementById('map')) {
        const map = L.map('map').setView([-12.0658, -75.2127], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        const zonasCobertura = [
            { lat: -12.0658, lng: -75.2127, nombre: 'Huancayo Centro', color: '#005B9F' },
            { lat: -12.0456, lng: -75.2234, nombre: 'El Tambo', color: '#E58E21' },
            { lat: -12.0890, lng: -75.1845, nombre: 'Chilca', color: '#005B9F' },
            { lat: -11.9176, lng: -75.3147, nombre: 'Concepción', color: '#E58E21' },
            { lat: -12.0789, lng: -75.2567, nombre: 'San Jerónimo', color: '#005B9F' },
            { lat: -12.0345, lng: -75.2456, nombre: 'Pilcomayo', color: '#E58E21' }
        ];
        zonasCobertura.forEach(zona => {
            const circle = L.circle([zona.lat, zona.lng], {
                color: zona.color,
                fillColor: zona.color,
                fillOpacity: 0.3,
                radius: 1500
            }).addTo(map);
            const marker = L.marker([zona.lat, zona.lng]).addTo(map);
            marker.bindPopup(`<b>${zona.nombre}</b><br>Cobertura disponible`);
            circle.bindPopup(`<b>${zona.nombre}</b><br>Zona con cobertura ProRed`);
        });
    }

    // Botones consultar
    document.querySelectorAll('.btn-consultar').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const zonaNombre = this.closest('.zona-card').querySelector('.zona-nombre').textContent;
            alert(`Consultando información de cobertura para ${zonaNombre}. Pronto nos pondremos en contacto contigo.`);
        });
    });
});
