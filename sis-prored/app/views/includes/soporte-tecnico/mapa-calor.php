<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mapa de Calor de Incidencias</h1>
            <p class="text-sm text-gray-500">Visualización geoespacial de reportes y zonas críticas.</p>
        </div>

        <div class="bg-white p-1.5 rounded-lg shadow-sm border border-gray-200 flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase px-2">Periodo:</span>
            <select class="text-sm border-none focus:ring-0 bg-transparent text-gray-600 font-medium cursor-pointer">
                <option>Últimos 7 días</option>
                <option>Este Mes</option>
                <option>Mes Anterior</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card-base p-0 overflow-hidden border-l-4 border-danger relative group">
            <div class="p-6 relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-red-100 text-danger text-xs font-bold px-2 py-1 rounded uppercase">
                        #1 Mayor Incidencia
                    </div>
                    <i
                        class="fas fa-map-marker-alt text-danger/20 text-4xl absolute right-4 top-4 group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">El Tambo</h3>
                <div class="flex items-end gap-2 mt-2">
                    <span class="text-4xl font-bold text-danger">42</span>
                    <span class="text-sm text-gray-500 mb-1">Tickets</span>
                </div>
                <p class="text-xs text-red-600 mt-2 font-medium">
                    <i class="fas fa-arrow-up mr-1"></i> 15% vs semana anterior
                </p>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-white"></div>
        </div>

        <div class="card-base p-0 overflow-hidden border-l-4 border-warning relative group">
            <div class="p-6 relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-orange-100 text-warning text-xs font-bold px-2 py-1 rounded uppercase">
                        #2 Zona Riesgo
                    </div>
                    <i
                        class="fas fa-map-marker-alt text-warning/20 text-4xl absolute right-4 top-4 group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Chilca</h3>
                <div class="flex items-end gap-2 mt-2">
                    <span class="text-4xl font-bold text-warning">28</span>
                    <span class="text-sm text-gray-500 mb-1">Tickets</span>
                </div>
                <p class="text-xs text-orange-600 mt-2 font-medium">
                    <i class="fas fa-minus mr-1"></i> Estable
                </p>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-white"></div>
        </div>

        <div class="card-base p-0 overflow-hidden border-l-4 border-primary relative group">
            <div class="p-6 relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-blue-100 text-primary text-xs font-bold px-2 py-1 rounded uppercase">
                        #3 Zona Media
                    </div>
                    <i
                        class="fas fa-map-marker-alt text-primary/20 text-4xl absolute right-4 top-4 group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Huancayo</h3>
                <div class="flex items-end gap-2 mt-2">
                    <span class="text-4xl font-bold text-primary">15</span>
                    <span class="text-sm text-gray-500 mb-1">Tickets</span>
                </div>
                <p class="text-xs text-green-600 mt-2 font-medium">
                    <i class="fas fa-arrow-down mr-1"></i> -5% vs semana anterior
                </p>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-white"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="card-base p-1 lg:col-span-3 h-[600px] relative shadow-lg">
            <div id="map" class="w-full h-full rounded-lg z-0"></div>

            <div
                class="absolute bottom-6 right-6 bg-white/90 backdrop-blur-sm p-3 rounded-lg shadow-lg border border-gray-200 z-[400] text-xs">
                <h4 class="font-bold text-gray-700 mb-2">Densidad de Fallas</h4>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-3 h-3 rounded-full bg-red-600 shadow-sm"></span> Crítico (Corte Masivo)
                </div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-3 h-3 rounded-full bg-orange-500 shadow-sm"></span> Alto (Lentitud/Intermitencia)
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm"></span> Bajo (Tickets aislados)
                </div>
            </div>
        </div>

        <div class="space-y-4 lg:col-span-1">
            <h3 class="font-bold text-gray-800 text-sm uppercase">Nodos Afectados</h3>

            <div class="space-y-3 h-[600px] overflow-y-auto custom-scrollbar pr-2">

                <div class="bg-white p-3 rounded-lg border border-red-100 shadow-sm cursor-pointer hover:shadow-md transition-all hover:border-red-300"
                    onclick="flyToNode(-12.065, -75.210)">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Nodo El Tambo - A1</h4>
                            <p class="text-xs text-gray-500">Sector: Av. Mariscal Castilla</p>
                        </div>
                        <span class="bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded">15 Tck</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-red-500 h-1.5 rounded-full" style="width: 85%"></div>
                    </div>
                    <p class="text-[10px] text-red-500 mt-1 text-right">85% Capacidad Tickets</p>
                </div>

                <div class="bg-white p-3 rounded-lg border border-orange-100 shadow-sm cursor-pointer hover:shadow-md transition-all hover:border-orange-300"
                    onclick="flyToNode(-12.075, -75.220)">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Nodo Chilca - Sur</h4>
                            <p class="text-xs text-gray-500">Sector: Parque de los Héroes</p>
                        </div>
                        <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-1.5 py-0.5 rounded">8
                            Tck</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-orange-400 h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                    <p class="text-[10px] text-orange-500 mt-1 text-right">45% Capacidad Tickets</p>
                </div>

                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition-all"
                    onclick="flyToNode(-12.055, -75.205)">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Nodo Centro</h4>
                            <p class="text-xs text-gray-500">Sector: Plaza Constitución</p>
                        </div>
                        <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-1.5 py-0.5 rounded">3 Tck</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-blue-400 h-1.5 rounded-full" style="width: 15%"></div>
                    </div>
                    <p class="text-[10px] text-blue-500 mt-1 text-right">Estable</p>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.heat/0.2.0/leaflet-heat.js"></script>

<script>
    // 1. Inicializar el mapa centrado en Huancayo (Lat, Lng)
    // Coordenadas aprox: -12.068132, -75.210085
    const map = L.map('map').setView([-12.068, -75.210], 14);

    // 2. Cargar capa de OpenStreetMap (Gratis)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // 3. Función auxiliar para simular extracción de coordenadas desde un link de Google Maps
    function extraerCoordsDeLink(link) {
        // Simulación: Si tu base de datos tiene "https://maps.google.com/?q=-12.065,-75.210"
        // Esta función extraería los números. Aquí retornamos valores aleatorios cercanos al centro para la demo.
        const latBase = -12.068;
        const lngBase = -75.210;
        // Variación aleatoria
        return [
            latBase + (Math.random() - 0.5) * 0.04,
            lngBase + (Math.random() - 0.5) * 0.04,
            Math.random() // Intensidad (0.0 a 1.0)
        ];
    }

    // 4. Generar Datos Simulados para el Mapa de Calor
    // Formato leaflet.heat: [lat, lng, intensidad]
    let heatData = [];

    // Generar zona caliente en "El Tambo" (Norte)
    for (let i = 0; i < 45; i++) {
        heatData.push([-12.055 + (Math.random() * 0.01), -75.205 + (Math.random() * 0.01), 0.8]);
    }
    // Generar zona media en "Chilca" (Sur)
    for (let i = 0; i < 30; i++) {
        heatData.push([-12.080 + (Math.random() * 0.01), -75.220 + (Math.random() * 0.01), 0.5]);
    }
    // Puntos dispersos
    for (let i = 0; i < 20; i++) {
        heatData.push(extraerCoordsDeLink("link_simulado_db"));
    }

    // 5. Agregar capa de Calor
    const heat = L.heatLayer(heatData, {
        radius: 25,
        blur: 15,
        maxZoom: 17,
        gradient: { 0.4: 'blue', 0.65: 'lime', 1: 'red' }
    }).addTo(map);

    // 6. Agregar marcadores específicos para los Nodos Críticos
    const nodoTambo = L.marker([-12.065, -75.210]).addTo(map).bindPopup('<b>Nodo El Tambo</b><br>15 Tickets Activos');
    const nodoChilca = L.marker([-12.075, -75.220]).addTo(map).bindPopup('<b>Nodo Chilca</b><br>8 Tickets Activos');
    const nodoCentro = L.marker([-12.055, -75.205]).addTo(map).bindPopup('<b>Nodo Centro</b><br>Operativo');

    // 7. Función para volar al nodo al hacer clic en el panel lateral
    window.flyToNode = function (lat, lng) {
        map.flyTo([lat, lng], 16, {
            animate: true,
            duration: 1.5
        });
    };

    // Ajustar mapa si cambia el tamaño de la ventana
    window.addEventListener('resize', function () {
        map.invalidateSize();
    });
</script>