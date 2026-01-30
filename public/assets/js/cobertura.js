// Código para el formulario simplificado con geocodificación inversa
document.addEventListener('DOMContentLoaded', function () {
    let marcadorMapa = null;
    let mapa = null;
    let markerIcon = null;
    
    // Validación de celular en tiempo real
    const celularInput = document.getElementById('celular');
    if (celularInput) {
        celularInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
            
            // Validar que empiece con 9
            if (this.value.length > 0 && this.value[0] !== '9') {
                this.setCustomValidity('El celular debe comenzar con 9');
                this.style.borderColor = '#dc3545';
            } else {
                this.setCustomValidity('');
                this.style.borderColor = '#e0e0e0';
            }
        });
    }
    
    // Validación de documento (solo números, opcional)
    const documentoInput = document.getElementById('documento');
    if (documentoInput) {
        documentoInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 12);
        });
    }
    
    // Inicializar mapa
    if (window.L && document.getElementById('map')) {
        // Centro aproximado de Junín
        mapa = L.map('map').setView([-11.9176, -75.3147], 13);
        
        // Capa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);
        
        // Crear icono personalizado
        markerIcon = L.divIcon({
            className: 'custom-div-icon',
            html: '<div class="custom-marker"><i class="fas fa-map-pin"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });
        
        // Evento para marcar ubicación en el mapa
        mapa.on('click', async function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Guardar coordenadas en campos ocultos
            document.getElementById('latitud').value = lat.toFixed(6);
            document.getElementById('longitud').value = lng.toFixed(6);
            
            // Mostrar información de ubicación
            mostrarInformacionUbicacion(lat, lng);
            
            // Actualizar marcador en el mapa
            actualizarMarcador(lat, lng);
            
            // Obtener dirección desde coordenadas (geocodificación inversa)
            await obtenerDireccionDesdeCoordenadas(lat, lng);
        });
        
        // Agregar control de búsqueda
        agregarControlBusqueda();
    }
    
    // Botón para buscar dirección manualmente
    document.getElementById('btnBuscarDireccion')?.addEventListener('click', function() {
        const direccion = prompt('Ingresa tu dirección manualmente:');
        if (direccion && direccion.trim() !== '') {
            document.getElementById('direccion').value = direccion.trim();
        }
    });
    
    // Botón para limpiar ubicación
    document.getElementById('btnLimpiarUbicacion')?.addEventListener('click', function() {
        limpiarUbicacion();
    });
    
    // Envío del formulario
    const formulario = document.getElementById('solicitudForm');
    if (formulario) {
        formulario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validarFormulario()) {
                return;
            }
            
            const datos = obtenerDatosFormulario();
            enviarPorWhatsApp(datos);
        });
    }
    
    // ===== FUNCIONES AUXILIARES =====
    
    function mostrarInformacionUbicacion(lat, lng) {
        const ubicacionInfo = document.getElementById('ubicacionInfo');
        const coordenadasTexto = document.getElementById('coordenadasTexto');
        
        coordenadasTexto.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
        ubicacionInfo.style.display = 'flex';
    }
    
    function actualizarMarcador(lat, lng) {
        // Eliminar marcador anterior si existe
        if (marcadorMapa) {
            mapa.removeLayer(marcadorMapa);
        }
        
        // Agregar nuevo marcador
        marcadorMapa = L.marker([lat, lng], { icon: markerIcon })
            .addTo(mapa)
            .bindPopup('Tu ubicación seleccionada')
            .openPopup();
        
        // Centrar mapa en la ubicación seleccionada
        mapa.setView([lat, lng], 16);
    }
    
    async function obtenerDireccionDesdeCoordenadas(lat, lng) {
        try {
            // Mostrar indicador de carga
            const direccionInput = document.getElementById('direccion');
            direccionInput.value = 'Obteniendo dirección...';
            direccionInput.style.color = '#666';
            
            // Usar Nominatim API (OpenStreetMap) - geocodificación inversa
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
            );
            
            if (!response.ok) {
                throw new Error('Error en la respuesta de la API');
            }
            
            const data = await response.json();
            
            if (data && data.display_name) {
                // Formatear la dirección
                let direccionFormateada = data.display_name;
                
                // Extraer partes importantes de la dirección
                if (data.address) {
                    const { road, suburb, city, town, village, county, state } = data.address;
                    
                    // Crear dirección más legible
                    const partes = [];
                    if (road) partes.push(road);
                    if (suburb) partes.push(suburb);
                    if (town || city || village) partes.push(town || city || village);
                    if (county && county !== (town || city || village)) partes.push(county);
                    
                    if (partes.length > 0) {
                        direccionFormateada = partes.join(', ');
                    }
                }
                
                direccionInput.value = direccionFormateada;
                direccionInput.style.color = '#000';
            } else {
                direccionInput.value = 'Dirección no encontrada. Por favor, ingrésala manualmente.';
                direccionInput.style.color = '#dc3545';
            }
        } catch (error) {
            console.error('Error en geocodificación inversa:', error);
            const direccionInput = document.getElementById('direccion');
            direccionInput.value = 'Error al obtener dirección. Por favor, ingrésala manualmente.';
            direccionInput.style.color = '#dc3545';
        }
    }
    
    function agregarControlBusqueda() {
        // Crear contenedor para la búsqueda
        const searchContainer = L.DomUtil.create('div', 'busqueda-container');
        
        // Crear input de búsqueda
        const searchBox = L.DomUtil.create('input', '', searchContainer);
        searchBox.id = 'searchBox';
        searchBox.placeholder = 'Buscar dirección o lugar...';
        searchBox.style.cssText = `
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-size: 14px;
        `;
        
        // Agregar al mapa
        mapa.getContainer().appendChild(searchContainer);
        
        // Evento de búsqueda
        let timeoutId;
        searchBox.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                if (this.value.trim().length > 2) {
                    buscarDireccion(this.value);
                }
            }, 500);
        });
    }
    
    async function buscarDireccion(query) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=pe`
            );
            
            const results = await response.json();
            
            if (results && results.length > 0) {
                const primerResultado = results[0];
                const lat = parseFloat(primerResultado.lat);
                const lng = parseFloat(primerResultado.lon);
                
                // Actualizar coordenadas
                document.getElementById('latitud').value = lat.toFixed(6);
                document.getElementById('longitud').value = lng.toFixed(6);
                
                // Actualizar marcador
                actualizarMarcador(lat, lng);
                
                // Mostrar información
                mostrarInformacionUbicacion(lat, lng);
                
                // Completar dirección
                document.getElementById('direccion').value = primerResultado.display_name;
                document.getElementById('direccion').style.color = '#000';
            }
        } catch (error) {
            console.error('Error en búsqueda de dirección:', error);
        }
    }
    
    function limpiarUbicacion() {
        // Limpiar campos
        document.getElementById('latitud').value = '';
        document.getElementById('longitud').value = '';
        document.getElementById('direccion').value = '';
        document.getElementById('coordenadasTexto').textContent = 'Ubicación no marcada';
        document.getElementById('ubicacionInfo').style.display = 'none';
        
        // Eliminar marcador del mapa
        if (marcadorMapa) {
            mapa.removeLayer(marcadorMapa);
            marcadorMapa = null;
        }
    }
    
    function validarFormulario() {
        const celular = document.getElementById('celular').value;
        const direccion = document.getElementById('direccion').value;
        const latitud = document.getElementById('latitud').value;
        
        // Validar celular (9 dígitos, empieza con 9)
        const regexCelular = /^9\d{8}$/;
        if (!regexCelular.test(celular)) {
            alert('Por favor, ingresa un número de celular válido de 9 dígitos que comience con 9.');
            return false;
        }
        
        // Validar que se haya marcado una ubicación
        if (!latitud || !direccion.trim()) {
            alert('Por favor, marca tu ubicación en el mapa haciendo clic en él.');
            return false;
        }
        
        return true;
    }
    
    function obtenerDatosFormulario() {
        return {
            documento: document.getElementById('documento').value || 'No especificado',
            celular: document.getElementById('celular').value,
            direccion: document.getElementById('direccion').value,
            coordenadas: `${document.getElementById('latitud').value}, ${document.getElementById('longitud').value}`,
            fecha: new Date().toLocaleString('es-PE')
        };
    }
    
    function enviarPorWhatsApp(datos) {
        // Formatear mensaje para WhatsApp
        const mensaje = `*NUEVA SOLICITUD DE COBERTURA - PRORED*%0A%0A` +
                       `*📱 Datos del Cliente:*%0A` +
                       `• Celular: ${datos.celular}%0A` +
                       `• Documento: ${datos.documento}%0A` +
                       `• Fecha: ${datos.fecha}%0A%0A` +
                       `*📍 Ubicación Solicitada:*%0A` +
                       `${datos.direccion}%0A` +
                       `• Coordenadas: ${datos.coordenadas}%0A%0A` +
                       `*🗺️ Enlace Google Maps:*%0A` +
                       `https://maps.google.com/?q=${datos.coordenadas}%0A%0A` +
                       `_Solicitud generada desde la web de ProRed_`;
        
        // Número de WhatsApp de ProRed (usando el mismo de tu código)
        const numeroWhatsApp = '51991445527';
        
        // Crear enlace de WhatsApp
        const enlaceWhatsApp = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${mensaje}`;
        
        // Abrir WhatsApp en nueva pestaña
        window.open(enlaceWhatsApp, '_blank');
        
        // Mostrar confirmación
        mostrarConfirmacion();
    }
    
    function mostrarConfirmacion() {
        const boton = document.getElementById('btnEnviarWhatsApp');
        const textoOriginal = boton.innerHTML;
        
        boton.innerHTML = '<i class="fas fa-check"></i> ¡Enviado!';
        boton.style.background = '#28a745';
        boton.disabled = true;
        
        // Resetear después de 3 segundos
        setTimeout(() => {
            boton.innerHTML = textoOriginal;
            boton.style.background = '';
            boton.disabled = false;
            
            // Opcional: limpiar el formulario
            formulario.reset();
            limpiarUbicacion();
        }, 3000);
    }
});