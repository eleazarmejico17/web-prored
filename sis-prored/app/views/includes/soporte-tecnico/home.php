<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard de Soporte Técnico</h1>
            <p class="text-sm text-gray-500">Gestión de incidentes Nivel 2 y escalamiento a visitas técnicas.</p>
        </div>
        <div class="flex gap-3">
            <span
                class="px-4 py-2 bg-white rounded-lg shadow-sm text-sm font-medium text-gray-600 border border-gray-200">
                <i class="far fa-calendar-alt mr-2 text-primary"></i>
                <?php echo date('d/m/Y'); ?>
            </span>
            <button onclick="alert('Funcionalidad: Crear ticket manual rápido')" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Nuevo Ticket
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-base p-6 border-l-4 border-primary relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tickets Hoy</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">24</h3>
                    <p class="text-xs text-green-500 mt-1 font-medium"><i class="fas fa-arrow-up mr-1"></i> +12% vs ayer
                    </p>
                </div>
                <div class="p-3 rounded-full bg-primary/10 text-primary">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-success relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Resueltos (Oficina)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">15</h3>
                    <p class="text-xs text-gray-500 mt-1">Solucionados remotamente</p>
                </div>
                <div class="p-3 rounded-full bg-green-50 text-success">
                    <i class="fas fa-headset text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-secondary relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Escalados (Visita)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">5</h3>
                    <p class="text-xs text-gray-500 mt-1">Pendientes de técnico</p>
                </div>
                <div class="p-3 rounded-full bg-orange-50 text-secondary">
                    <i class="fas fa-truck text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card-base lg:col-span-2 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-lg">Cola de Atención (Nivel 2)</h3>
                <div class="flex gap-2">
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded font-bold">2 Críticos</span>
                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded font-bold">5 Normales</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Ticket / Hora</th>
                            <th class="px-6 py-4">Cliente / Servicio</th>
                            <th class="px-6 py-4">Motivo / Diagnóstico N1</th>
                            <th class="px-6 py-4 text-center">Prioridad</th>
                            <th class="px-6 py-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="ticketsTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-base p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">Tipología de Incidentes</h3>
            <div class="h-64 w-full relative">
                <canvas id="incidentesChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-400">Datos de los últimos 7 días</p>
            </div>
        </div>
    </div>
</div>

<div id="atencionModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all scale-95"
        id="modalPanel">

        <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-primary text-white rounded-t-xl">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="fas fa-headset text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Atención de Ticket <span id="m_ticket_id"
                            class="opacity-80 font-mono">#000</span></h3>
                    <p class="text-xs text-blue-100" id="m_ticket_tiempo">Abierto hace: 45 min</p>
                </div>
            </div>
            <button onclick="closeModal()"
                class="text-white/70 hover:text-white hover:bg-white/10 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-grow overflow-y-auto custom-scrollbar p-6 bg-gray-50/50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3"><i class="far fa-user mr-1"></i>
                            Datos del Cliente</h4>
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold mr-3"
                                id="m_initials">--</div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg leading-tight" id="m_cliente">--</h3>
                                <p class="text-xs text-gray-500 font-mono mt-0.5" id="m_dni">DNI: --</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-600"><span class="font-semibold">Motivo:</span> <span
                                    id="m_motivo">--</span></p>
                            <div class="mt-2 text-xs bg-yellow-50 text-yellow-700 p-2 rounded border border-yellow-100">
                                <i class="fas fa-info-circle mr-1"></i> Nota Nivel 1: <span id="m_nota_n1"
                                    class="italic">--</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3"><i
                                class="fas fa-network-wired mr-1"></i> Servicio Afectado</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dirección:</span>
                                <span class="font-medium text-right max-w-[60%]" id="m_direccion">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Plan:</span>
                                <span class="font-medium text-primary" id="m_plan">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">IP / Nodo:</span>
                                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded" id="m_ip">--</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Estado:</span>
                                <span
                                    class="text-xs font-bold px-2 py-0.5 rounded bg-green-100 text-green-700">ACTIVO</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <button class="btn-outline text-xs py-1.5"><i class="fas fa-ping-pong-paddle mr-1"></i>
                                Ping</button>
                            <button class="btn-outline text-xs py-1.5"><i class="fas fa-history mr-1"></i> Logs</button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Gestión de Contacto</h4>
                        <div id="phones-container" class="space-y-3">
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Resolución del Ticket</h4>

                        <div class="grid grid-cols-2 gap-3 mb-4" id="action-buttons">
                            <button onclick="selectAction('L2')" id="btn-l2"
                                class="py-3 px-2 border-2 border-gray-200 rounded-lg hover:border-success hover:bg-green-50 hover:text-success transition-all text-sm font-semibold text-gray-500 flex flex-col items-center gap-1 group">
                                <i class="fas fa-check-circle text-lg group-hover:scale-110 transition-transform"></i>
                                Resolver (Nivel 2)
                            </button>
                            <button onclick="selectAction('L3')" id="btn-l3"
                                class="py-3 px-2 border-2 border-gray-200 rounded-lg hover:border-secondary hover:bg-orange-50 hover:text-secondary transition-all text-sm font-semibold text-gray-500 flex flex-col items-center gap-1 group">
                                <i class="fas fa-truck text-lg group-hover:scale-110 transition-transform"></i>
                                Escalar (Nivel 3)
                            </button>
                        </div>

                        <div id="resolution-area" class="hidden animate-fade-in-up">
                            <label class="block text-xs font-bold text-gray-700 mb-1" id="resolution-label">Diagnóstico
                                Final</label>

                            <div id="l3-alert"
                                class="hidden mb-2 bg-orange-50 border border-orange-200 text-orange-800 text-xs p-2 rounded flex items-start gap-2">
                                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                                <div>
                                    <span class="font-bold">Escalamiento a Visita Técnica:</span>
                                    <p>Se adjuntará automáticamente el número: <strong id="attached-phone">--</strong>
                                    </p>
                                </div>
                            </div>

                            <textarea id="resolution-text" rows="3" class="input-prored text-sm mb-3"
                                placeholder="Describa la solución o las instrucciones para el técnico..."></textarea>

                            <button onclick="submitTicket()" id="btn-submit"
                                class="w-full btn-primary py-2.5 shadow-lg">
                                Confirmar Acción
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- MOCK DATA ---
    const dbTickets = [
        {
            id: 4920,
            hora: "10:15 AM",
            cliente: "Empresa ShopNear S.R.L.",
            dni: "20601234567",
            direccion: "Jr. Junin 123, Chilca",
            plan: "Fibra Pyme 300",
            ip: "10.20.30.50",
            motivo: "Sin conexión a internet",
            nota_n1: "Cliente reporta luz roja en router. Reinicio no funcionó.",
            prioridad: "ALTA",
            telefonos: [
                { numero: "912345678", tipo: "MOVIL", origen: "Nivel 1", principal: true }, // Asignado por N1
                { numero: "064223344", tipo: "FIJO", origen: "Base Datos", principal: false },
                { numero: "987654321", tipo: "WHATSAPP", origen: "Base Datos", principal: false }
            ]
        },
        {
            id: 4918,
            hora: "09:45 AM",
            cliente: "María Fernanda Lopez",
            dni: "45678901",
            direccion: "Av. Giraldez 550, Huancayo",
            plan: "Fibra Home 100",
            ip: "192.168.15.10",
            motivo: "Lentitud en juegos",
            nota_n1: "Ping alto a servidores internacionales.",
            prioridad: "MEDIA",
            telefonos: [
                { numero: "955443322", tipo: "MOVIL", origen: "Base Datos", principal: true },
                { numero: "999000111", tipo: "MOVIL", origen: "Base Datos", principal: false }
            ]
        },
        {
            id: 4915,
            hora: "09:10 AM",
            cliente: "Carlos Huaman",
            dni: "70112233",
            direccion: "Calle Real 1040, El Tambo",
            plan: "Fibra Home 50",
            ip: "192.168.20.5",
            motivo: "Cambio de clave Wifi",
            nota_n1: "Cliente no recuerda su clave actual.",
            prioridad: "BAJA",
            telefonos: [
                { numero: "988776655", tipo: "WHATSAPP", origen: "Nivel 1", principal: true }
            ]
        }
    ];

    let currentTicket = null;
    let selectedActionType = null;

    // --- RENDERIZADO DE TABLA ---
    function renderTickets() {
        const tbody = document.getElementById('ticketsTableBody');
        tbody.innerHTML = dbTickets.map(t => {
            const badgeColor = t.prioridad === 'ALTA' ? 'bg-red-100 text-red-700 border-red-200' :
                t.prioridad === 'MEDIA' ? 'bg-orange-100 text-orange-700 border-orange-200' :
                    'bg-green-100 text-green-700 border-green-200';

            return `
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800">#${t.id}</span>
                        <p class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> ${t.hora}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                ${t.cliente.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">${t.cliente}</p>
                                <p class="text-[10px] text-gray-400 truncate max-w-[150px]">${t.direccion}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-700 font-medium">${t.motivo}</p>
                        <p class="text-xs text-gray-500 italic truncate max-w-xs">"${t.nota_n1}"</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border ${badgeColor}">
                            ${t.prioridad}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="openModal(${t.id})" class="btn-primary text-xs px-3 py-1.5 shadow-none">
                            Atender
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // --- LÓGICA DEL MODAL ---
    const modal = document.getElementById('atencionModal');
    const panel = document.getElementById('modalPanel');

    function openModal(id) {
        currentTicket = dbTickets.find(t => t.id === id);
        if (!currentTicket) return;

        // Reset UI
        document.getElementById('resolution-area').classList.add('hidden');
        document.getElementById('btn-l2').classList.remove('border-success', 'bg-green-50', 'text-success');
        document.getElementById('btn-l3').classList.remove('border-secondary', 'bg-orange-50', 'text-secondary');
        selectedActionType = null;

        // Llenar datos básicos
        document.getElementById('m_ticket_id').textContent = '#' + currentTicket.id;
        document.getElementById('m_cliente').textContent = currentTicket.cliente;
        document.getElementById('m_initials').textContent = currentTicket.cliente.substring(0, 2).toUpperCase();
        document.getElementById('m_dni').textContent = 'DNI/RUC: ' + currentTicket.dni;
        document.getElementById('m_motivo').textContent = currentTicket.motivo;
        document.getElementById('m_nota_n1').textContent = currentTicket.nota_n1;
        document.getElementById('m_direccion').textContent = currentTicket.direccion;
        document.getElementById('m_plan').textContent = currentTicket.plan;
        document.getElementById('m_ip').textContent = currentTicket.ip;

        // --- RENDERIZADO DE TELÉFONOS (REQUISITO CLAVE) ---
        const phonesContainer = document.getElementById('phones-container');
        phonesContainer.innerHTML = currentTicket.telefonos.map(tel => {
            // Estilos condicionales
            const containerClasses = tel.principal
                ? 'bg-white border-l-4 border-primary ring-1 ring-primary/20 shadow-md transform scale-105 z-10' // Resaltado (Principal)
                : 'bg-gray-50 border border-gray-200 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 hover:scale-100 hover:shadow-md transition-all duration-300'; // Secundario (Gris)

            const badge = tel.principal
                ? `<span class="bg-primary text-white text-[10px] px-1.5 py-0.5 rounded font-bold ml-2">PRINCIPAL (N1)</span>`
                : `<span class="bg-gray-200 text-gray-500 text-[10px] px-1.5 py-0.5 rounded ml-2">SECUNDARIO</span>`;

            const iconClass = tel.tipo === 'WHATSAPP' ? 'fab fa-whatsapp' : tel.tipo === 'MOVIL' ? 'fas fa-mobile-alt' : 'fas fa-phone-alt';

            return `
                <div class="p-3 rounded-lg flex justify-between items-center ${containerClasses}">
                    <div>
                        <div class="flex items-center">
                            <i class="${iconClass} text-gray-400 w-5"></i>
                            <span class="font-mono font-bold text-gray-700 text-lg">${tel.numero}</span>
                            ${badge}
                        </div>
                        <p class="text-[10px] text-gray-400 pl-5 uppercase">${tel.tipo} - ${tel.origen}</p>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-500 hover:text-white transition-colors flex items-center justify-center" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center" title="SMS">
                            <i class="fas fa-comment-alt"></i>
                        </button>
                        <button class="w-8 h-8 rounded-full bg-primary-light text-primary hover:bg-primary hover:text-white transition-colors flex items-center justify-center" title="Llamar">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        // Abrir Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // --- LÓGICA DE RESOLUCIÓN ---
    function selectAction(type) {
        selectedActionType = type;
        const area = document.getElementById('resolution-area');
        const label = document.getElementById('resolution-label');
        const btn = document.getElementById('btn-submit');
        const alertL3 = document.getElementById('l3-alert');
        const btnL2 = document.getElementById('btn-l2');
        const btnL3 = document.getElementById('btn-l3');

        area.classList.remove('hidden');

        // Reset estilos botones
        btnL2.className = "py-3 px-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-500 flex flex-col items-center gap-1 opacity-60";
        btnL3.className = "py-3 px-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-500 flex flex-col items-center gap-1 opacity-60";

        if (type === 'L2') {
            btnL2.className = "py-3 px-2 border-2 border-success bg-green-50 rounded-lg text-sm font-bold text-success flex flex-col items-center gap-1 shadow-md transform scale-105 transition-all";
            label.textContent = "Detalle de Solución (Nivel 2)";
            btn.textContent = "Cerrar Ticket como Resuelto";
            btn.className = "w-full bg-success text-white hover:bg-green-600 rounded-lg py-2.5 font-medium shadow-lg transition-colors";
            alertL3.classList.add('hidden');
        } else {
            btnL3.className = "py-3 px-2 border-2 border-secondary bg-orange-50 rounded-lg text-sm font-bold text-secondary flex flex-col items-center gap-1 shadow-md transform scale-105 transition-all";
            label.textContent = "Motivo de Escalamiento";
            btn.textContent = "Generar Visita Técnica";
            btn.className = "w-full bg-secondary text-white hover:bg-orange-600 rounded-lg py-2.5 font-medium shadow-lg transition-colors";

            // Lógica de "Arrastrar número"
            alertL3.classList.remove('hidden');
            const mainPhone = currentTicket.telefonos.find(t => t.principal) || currentTicket.telefonos[0];
            document.getElementById('attached-phone').textContent = mainPhone.numero;
        }
    }

    function submitTicket() {
        const txt = document.getElementById('resolution-text').value;
        if (txt.length < 5) {
            alert("Por favor ingrese un detalle válido.");
            return;
        }
        alert(`Ticket #${currentTicket.id} procesado correctamente como ${selectedActionType}.`);
        closeModal();
    }

    // --- CHART.JS CONFIG ---
    document.addEventListener('DOMContentLoaded', () => {
        renderTickets();

        const ctx = document.getElementById('incidentesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sin Internet (LOS)', 'Lentitud', 'Wifi/Clave', 'Equipos', 'Otros'],
                    datasets: [{
                        data: [40, 25, 15, 10, 10],
                        backgroundColor: [
                            '#EF4444', // Rojo (Critical)
                            '#F59E0B', // Naranja
                            '#3B82F6', // Azul
                            '#10B981', // Verde
                            '#9CA3AF'  // Gris
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }
    });
</script>