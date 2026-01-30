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
        const map = L.map('map').setView([-12.0658, -75.2127], 11); // Ajusté el zoom a 11 para ver todas las zonas
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const zonasCobertura = [
            { lat: -11.9176, lng: -75.3147, nombre: 'Concepción', color: '#E58E21' },
            { lat: -11.9720885, lng: -75.2535674, nombre: 'Hualhuas', color: '#E58E21' },
            { lat: -11.9959765, lng: -75.2453951, nombre: 'San Agustín de Cajas', color: '#E58E21' },
            { lat: -11.9595235, lng: -75.258844, nombre: 'San Pedro de Saño', color: '#E58E21' },
            { lat: -11.8920309, lng: -75.3477767, nombre: 'Matahuasi', color: '#6c757d' },
            { lat: -11.8542782, lng: -75.3557927, nombre: 'Apata', color: '#6c757d' }
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
