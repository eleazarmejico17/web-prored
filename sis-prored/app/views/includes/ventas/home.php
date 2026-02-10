<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard de Operaciones</h1>
            <p class="text-sm text-gray-500">Resumen de cobranza, instalaciones y validaciones pendientes.</p>
        </div>
        <div class="flex gap-3">
            <span class="px-4 py-2 bg-white rounded-lg shadow-sm text-sm font-medium text-gray-600 border border-gray-200">
                <i class="far fa-calendar-alt mr-2 text-primary"></i> <?php echo date('d/m/Y'); ?>
            </span>
            <button onclick="alert('Abrir Modal RF-V02: Registrar Pago Manual')" class="btn-primary">
                <i class="fas fa-cash-register"></i> Caja Rápida
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="card-base p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Crecimiento de Red</h3>
                    <p class="text-xs text-gray-500">Nuevos servicios activados este mes</p>
                </div>
                <div class="flex gap-2">
                    <span class="text-xs font-bold bg-blue-50 text-primary px-2 py-1 rounded border border-blue-100">
                        Meta: 50
                    </span>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="card-base p-6 border-l-4 border-warning relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-file-invoice-dollar text-6xl text-warning"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gestión de Pagos</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <h3 class="text-3xl font-bold text-gray-800">12</h3>
                        <span class="text-sm text-warning font-medium">Por Validar</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mb-4">Reportados vía App/Web</p>
                    <button class="w-full btn-secondary text-xs py-2 justify-center">
                        <i class="fas fa-check-double mr-2"></i> Validar Pagos (RF-V01)
                    </button>
                </div>
            </div>

            <div class="card-base p-6 border-l-4 border-danger">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cartera en Mora</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">S/ 4,250</h3>
                    </div>
                    <div class="p-2 rounded-full bg-red-50 text-danger animate-pulse">
                        <i class="fas fa-siren-on"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-3 text-xs">
                    <div class="bg-gray-50 p-2 rounded text-center">
                        <span class="block font-bold text-orange-500">8</span>
                        <span class="text-gray-400">Suspendidos</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded text-center">
                        <span class="block font-bold text-red-600">3</span>
                        <span class="text-gray-400">Corte Físico</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg mb-4">Directorio General de Servicios</h3>
            
            <form id="searchForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="label-prored">Documento (DNI/RUC)</label>
                    <div class="relative">
                        <input type="text" class="input-prored pl-9" placeholder="Buscar...">
                        <i class="fas fa-id-card absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <div class="md:col-span-4">
                    <label class="label-prored">Cliente / Razón Social</label>
                    <input type="text" class="input-prored" placeholder="Ej: Juan Pérez o ShopNear S.R.L.">
                </div>

                <div class="md:col-span-3">
                    <label class="label-prored">Estado del Servicio</label>
                    <select class="input-prored bg-white">
                        <option value="">Todos</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="SUSPENDIDO">Suspendido</option>
                        <option value="EN_MORA">En Mora</option>
                        <option value="CORTADO">Cortado</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="button" class="btn-primary w-full justify-center">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn-outline w-12 justify-center" title="Limpiar">
                        <i class="fas fa-eraser"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Cliente / Razón Social</th>
                        <th class="px-6 py-4">Plan / Velocidad</th>
                        <th class="px-6 py-4">Dirección (Servicio)</th>
                        <th class="px-6 py-4">Deuda</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                    RM
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Raúl Mendoza</p>
                                    <p class="text-xs text-gray-400">72889102</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 font-medium">Fibra Home 100</p>
                            <p class="text-xs text-gray-500">100/100 Mbps</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                            Av. Los Incas 450, Pangoa
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-success font-bold">S/ 0.00</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">
                                Activo
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openClientModal(1)" class="text-primary hover:text-primary-dark transition-colors">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs mr-3">
                                    SN
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">ShopNear S.R.L.</p>
                                    <p class="text-xs text-gray-400">20601234567</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 font-medium">Fibra Pyme 300</p>
                            <p class="text-xs text-gray-500">300/300 Mbps</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                            Jr. Junín 123, Chilca
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-danger font-bold">S/ 165.00</span>
                            <p class="text-[10px] text-danger">Incluye Mora</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning border border-warning/20">
                                En Mora
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openClientModal(2)" class="text-primary hover:text-primary-dark transition-colors">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 flex justify-center bg-gray-50/30">
            <nav class="flex gap-1">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm"><i class="fas fa-chevron-left"></i></button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white text-sm font-bold shadow-md">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm"><i class="fas fa-chevron-right"></i></button>
            </nav>
        </div>
    </div>
</div>

<div id="clientModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col transform transition-all scale-95" id="modalPanel">
        
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl">
                    <span id="m_initials">--</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800" id="m_nombre">Cargando...</h3>
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <span id="m_dni"><i class="far fa-id-card mr-1"></i> --</span>
                        <span class="h-4 w-px bg-gray-300"></span>
                        <span id="m_ubicacion"><i class="fas fa-map-marker-alt mr-1"></i> --</span>
                    </div>
                </div>
            </div>
            <button onclick="closeClientModal()" class="text-gray-400 hover:text-danger w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-grow bg-gray-50/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Detalles del Servicio</h4>
                    
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-primary/5 rounded-bl-full"></div>
                        <p class="text-xs text-gray-500 mb-1">Plan Contratado</p>
                        <h2 class="text-lg font-bold text-primary" id="m_plan">--</h2>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-bold text-gray-800" id="m_precio">S/ --</span>
                            <span class="text-xs text-gray-500">/ mes</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm space-y-3">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-sm text-gray-600">IP Asignada</span>
                            <span class="text-sm font-mono font-medium text-gray-800" id="m_ip">--</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-sm text-gray-600">Winbox / Nodo</span>
                            <span class="text-sm font-medium text-gray-800" id="m_winbox">--</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Estado</span>
                            <span id="m_estado_badge" class="text-xs font-bold px-2 py-0.5 rounded bg-gray-100">--</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3">Teléfonos de Contacto</p>
                        <div id="m_telefonos" class="space-y-2">
                            </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Facturación y Deudas</h4>
                    
                    <div id="m_alert_mora" class="hidden bg-red-50 border-l-4 border-danger p-3 rounded-r-md">
                        <div class="flex gap-2">
                            <i class="fas fa-exclamation-circle text-danger mt-1"></i>
                            <div>
                                <p class="text-sm font-bold text-danger">Servicio en Mora</p>
                                <p class="text-xs text-red-700">Debe: <span id="m_total_deuda">S/ 0.00</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-2">Periodo</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="m_tabla_deuda">
                                </tbody>
                        </table>
                    </div>

                    <button class="w-full btn-secondary text-sm py-2">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Generar Estado de Cuenta (PDF)
                    </button>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Últimos Eventos</h4>
                    
                    <div class="relative pl-4 border-l-2 border-gray-200 space-y-6" id="m_timeline">
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-primary border-2 border-white"></div>
                            <p class="text-xs text-gray-400 mb-0.5">Hoy, 10:30 AM</p>
                            <p class="text-sm font-bold text-gray-800">Pago Validado</p>
                            <p class="text-xs text-gray-600">Transferencia BCP - S/ 100.00</p>
                        </div>
                    </div>
                    
                    <button class="btn-outline w-full text-xs mt-4">Ver Historial Completo</button>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeClientModal()" class="btn-outline">Cerrar</button>
            <button class="btn-primary">
                <i class="fas fa-edit mr-1"></i> Editar Cliente
            </button>
        </div>
    </div>
</div>

<script>
    // Configuración Gráfico Ventas (KPI 1)
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('ventasChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                    datasets: [{
                        label: 'Instalaciones',
                        data: [8, 12, 5, 15], // Datos simulados de COUNT(servicio)
                        borderColor: '#005FA2',
                        backgroundColor: 'rgba(0, 95, 162, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });

    // Mock Data basado en las tablas: cliente, servicio, plan, deuda
    const dbMock = {
        1: {
            // Tabla: cliente
            nombres: "Raúl Mendoza", razon_social: null, dni: "72889102", 
            // Tabla: servicio + distrito
            direccion: "Av. Los Incas 450, Pangoa", ip: "192.168.10.45",
            // Tabla: plan
            plan: "Fibra Home 100", precio: "89.90",
            // Tabla: winbox
            winbox: "RB-Pangoa-Norte",
            estado: "ACTIVO",
            // Tabla: cliente_telefono
            telefonos: [
                {numero: "998877665", tipo: "WHATSAPP", principal: true},
                {numero: "064234567", tipo: "FIJO", principal: false}
            ],
            // Tabla: deuda + periodos
            deudas: [
                {periodo: "Feb 2026", total: "89.90", estado: "PENDIENTE"}
            ]
        },
        2: {
            nombres: null, razon_social: "ShopNear S.R.L.", dni: "20601234567",
            direccion: "Jr. Junín 123, Chilca", ip: "10.20.30.50",
            plan: "Fibra Pyme 300", precio: "150.00",
            winbox: "RB-Chilca-Empresas",
            estado: "EN_MORA",
            telefonos: [
                {numero: "912345678", tipo: "MOVIL", principal: true}
            ],
            deudas: [
                {periodo: "Ene 2026", total: "165.00", estado: "PENDIENTE"} // Incluye mora
            ]
        }
    };

    const modal = document.getElementById('clientModal');
    const panel = document.getElementById('modalPanel');

    function openClientModal(id) {
        const data = dbMock[id];
        if(!data) return;

        // Llenar Header
        const displayName = data.razon_social || data.nombres;
        document.getElementById('m_nombre').textContent = displayName;
        document.getElementById('m_initials').textContent = displayName.substring(0,2).toUpperCase();
        document.getElementById('m_dni').innerHTML = `<i class="far fa-id-card mr-1"></i> ${data.dni}`;
        document.getElementById('m_ubicacion').innerHTML = `<i class="fas fa-map-marker-alt mr-1"></i> ${data.direccion}`;

        // Llenar Datos Servicio
        document.getElementById('m_plan').textContent = data.plan;
        document.getElementById('m_precio').textContent = "S/ " + data.precio;
        document.getElementById('m_ip').textContent = data.ip;
        document.getElementById('m_winbox').textContent = data.winbox;
        
        // Badge Estado
        const badge = document.getElementById('m_estado_badge');
        badge.textContent = data.estado;
        badge.className = `text-xs font-bold px-2 py-0.5 rounded ${
            data.estado === 'ACTIVO' ? 'bg-green-100 text-green-700' : 
            data.estado === 'EN_MORA' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100'
        }`;

        // Llenar Teléfonos
        const telContainer = document.getElementById('m_telefonos');
        telContainer.innerHTML = data.telefonos.map(t => `
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="${t.tipo === 'WHATSAPP' ? 'fab fa-whatsapp text-green-500' : 'fas fa-phone text-gray-400'}"></i>
                <span>${t.numero}</span>
                ${t.principal ? '<span class="text-[10px] bg-blue-50 text-blue-600 px-1 rounded">Principal</span>' : ''}
            </div>
        `).join('');

        // Llenar Deudas
        const deudaContainer = document.getElementById('m_tabla_deuda');
        deudaContainer.innerHTML = data.deudas.map(d => `
            <tr>
                <td class="px-4 py-2 font-medium">${d.periodo}</td>
                <td class="px-4 py-2 text-right">S/ ${d.total}</td>
                <td class="px-4 py-2 text-center">
                    <span class="text-xs ${d.estado === 'PAGADO' ? 'text-green-600' : 'text-red-500 font-bold'}">${d.estado}</span>
                </td>
            </tr>
        `).join('');

        // Mostrar alerta mora si aplica
        if(data.estado === 'EN_MORA') {
            document.getElementById('m_alert_mora').classList.remove('hidden');
            document.getElementById('m_total_deuda').textContent = "S/ " + data.deudas[0].total; // Simplificado
        } else {
            document.getElementById('m_alert_mora').classList.add('hidden');
        }

        // Animación de entrada
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeClientModal() {
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>