<div class="space-y-6 animate-fade-in-up">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="space-y-4">
            <div class="card-base p-4 border-l-4 border-success flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Clientes Atendidos (Mes)</p>
                    <h3 class="text-2xl font-bold text-gray-800">142</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-50 text-success flex items-center justify-center">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>

            <div class="card-base p-4 border-l-4 border-warning flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Tickets Pendientes</p>
                    <h3 class="text-2xl font-bold text-gray-800">18</h3>
                    <p class="text-xs text-warning font-medium">Requieren atención</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-50 text-warning flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="card-base p-4 border-l-4 border-primary flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">En Visita Técnica (N3)</p>
                    <h3 class="text-2xl font-bold text-gray-800">5</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 lg:col-span-2">
            <h3 class="font-bold text-gray-800 text-lg mb-2">Estado de la Base de Clientes</h3>
            <div class="h-64 w-full">
                <canvas id="clientesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">

        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Directorio de Clientes</h3>
                <p class="text-xs text-gray-500">Gestión y asignación de incidencias</p>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" placeholder="Buscar por DNI o Nombre..." class="input-prored pl-9 py-2 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
                <button class="btn-secondary px-3"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Cliente / Razón Social</th>
                        <th class="px-6 py-4">Identificación (DNI/RUC)</th>
                        <th class="px-6 py-4 text-center">N° Servicios</th>
                        <th class="px-6 py-4 text-center">Tickets Activos</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="clientesTableBody">
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-center bg-gray-50/30">
            <nav class="flex gap-1">
                <button
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm"><i
                        class="fas fa-chevron-left"></i></button>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white text-sm font-bold shadow-md">1</button>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm">2</button>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm"><i
                        class="fas fa-chevron-right"></i></button>
            </nav>
        </div>
    </div>
</div>

<div id="dynamicModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all scale-95"
        id="modalPanel">

        <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-primary text-white rounded-t-xl">
            <h3 class="text-lg font-bold" id="modalTitle">Título Modal</h3>
            <button onclick="closeDynamicModal()"
                class="text-white/70 hover:text-white hover:bg-white/10 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar bg-gray-50/50 flex-grow" id="modalContent">
        </div>

        <div class="px-6 py-4 bg-white border-t border-gray-200 rounded-b-xl flex justify-end gap-3" id="modalFooter">
            <button onclick="closeDynamicModal()" class="btn-outline">Cerrar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- MOCK DATA ---
    // Simulamos una respuesta de base de datos con clientes y sus relaciones
    const dbClients = [
        {
            id: 1,
            nombre: "Juan Pérez",
            dni: "12345678",
            servicios: [
                { id: 101, direccion: "Av. Giraldez 123", plan: "Fibra 100", ip: "192.168.1.10" }
            ],
            telefonos: ["999888777", "987654321"],
            tickets: [] // Sin tickets activos -> Mostrará botón "Asignar"
        },
        {
            id: 2,
            nombre: "ShopNear S.R.L.",
            dni: "20601234567",
            servicios: [
                { id: 201, direccion: "Jr. Junin 550 (Oficina)", plan: "Fibra Pyme 300", ip: "10.20.30.50" },
                { id: 202, direccion: "Jr. Junin 552 (Almacén)", plan: "Fibra 50", ip: "10.20.30.51" }
            ],
            telefonos: ["912345678 (Gerencia)", "966554433 (Soporte)"],
            tickets: [ // Tiene tickets -> Mostrará botón "Ver"
                { id: 501, fecha: "10/02/2026", motivo: "Corte de fibra", estado: "RESUELTO", nivel: "Nivel 3 (Técnico)" },
                { id: 505, fecha: "12/02/2026", motivo: "Lentitud", estado: "PENDIENTE", nivel: "Nivel 2 (Soporte)" }
            ]
        },
        {
            id: 3,
            nombre: "Ana María García",
            dni: "45678901",
            servicios: [
                { id: 301, direccion: "Calle Real 890", plan: "Fibra Home 200", ip: "192.168.10.5" }
            ],
            telefonos: ["955112233"],
            tickets: [
                { id: 480, fecha: "01/02/2026", motivo: "Cambio clave Wifi", estado: "RESUELTO", nivel: "Nivel 2 (Soporte)" }
            ]
        }
    ];

    // --- RENDERIZAR TABLA PRINCIPAL ---
    function renderTable() {
        const tbody = document.getElementById('clientesTableBody');
        tbody.innerHTML = dbClients.map(c => {
            const hasActiveTickets = c.tickets.some(t => t.estado === 'PENDIENTE');
            const totalTickets = c.tickets.length;

            // Lógica del botón: Si tiene tickets (activos o históricos) -> VER. Si está limpio -> ASIGNAR.
            // Nota: Podrías cambiar la lógica a "si tiene tickets PENDIENTES muestra Ver, si no Asignar".
            // Aquí usaré: Si tiene historial = Ver, Si está vacío = Asignar.

            let actionBtn;
            if (totalTickets > 0) {
                actionBtn = `
                    <button onclick="openHistoryModal(${c.id})" class="btn-secondary px-3 py-1.5 text-xs shadow-none w-32 justify-center">
                        <i class="fas fa-eye mr-2"></i> Ver Tickets
                    </button>`;
            } else {
                actionBtn = `
                    <button onclick="openCreateModal(${c.id})" class="btn-primary px-3 py-1.5 text-xs shadow-none w-32 justify-center">
                        <i class="fas fa-plus-circle mr-2"></i> Asignar
                    </button>`;
            }

            const activeBadge = hasActiveTickets
                ? `<span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold animate-pulse">Atención</span>`
                : `<span class="bg-green-100 text-green-600 px-2 py-0.5 rounded text-xs font-bold">Limpio</span>`;

            return `
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                ${c.nombre.substring(0, 2).toUpperCase()}
                            </div>
                            <span class="font-medium text-gray-800">${c.nombre}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs">${c.dni}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 px-2 py-1 rounded font-bold text-gray-600">${c.servicios.length}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center">
                            <span class="font-bold text-lg ${hasActiveTickets ? 'text-red-500' : 'text-gray-400'}">${totalTickets}</span>
                            ${activeBadge}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        ${actionBtn}
                    </td>
                </tr>
            `;
        }).join('');
    }

    // --- MODAL: HISTORIAL DE TICKETS ---
    function openHistoryModal(id) {
        const client = dbClients.find(c => c.id === id);

        document.getElementById('modalTitle').innerHTML = `<i class="fas fa-history mr-2"></i> Historial de Tickets - ${client.nombre}`;

        let html = `
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm text-gray-500">Total registrados: <strong>${client.tickets.length}</strong></p>
                <button onclick="openCreateModal(${id})" class="text-primary text-xs font-bold hover:underline">+ Crear Nuevo Ticket</button>
            </div>
            <div class="space-y-3">`;

        html += client.tickets.map(t => {
            const statusClass = t.estado === 'RESUELTO'
                ? 'bg-green-100 text-green-700 border-green-200'
                : 'bg-orange-100 text-orange-700 border-orange-200';

            const icon = t.estado === 'RESUELTO' ? 'fa-check-circle' : 'fa-clock';

            return `
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-800">#${t.id}</span>
                                <span class="text-xs px-2 py-0.5 rounded border ${statusClass}">${t.estado}</span>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">${t.motivo}</p>
                            <p class="text-xs text-gray-400 mt-1">Resuelto en: <span class="font-bold text-primary">${t.nivel}</span></p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">${t.fecha}</span>
                    </div>
                </div>
            `;
        }).join('');

        html += `</div>`;

        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('modalFooter').innerHTML = `<button onclick="closeDynamicModal()" class="btn-outline">Cerrar</button>`;
        showModal();
    }

    // --- MODAL: ASIGNAR NUEVO TICKET (FORMULARIO) ---
    function openCreateModal(id) {
        const client = dbClients.find(c => c.id === id);

        document.getElementById('modalTitle').innerHTML = `<i class="fas fa-plus-circle mr-2"></i> Asignar Ticket - ${client.nombre}`;

        // Generar opciones de servicio
        const serviceOptions = client.servicios.map(s =>
            `<option value="${s.id}">${s.direccion} (${s.plan})</option>`
        ).join('');

        // Generar opciones de teléfono (simulando que se contactó desde uno de ellos)
        const phoneOptions = client.telefonos.map(p =>
            `<option value="${p}">${p}</option>`
        ).join('');

        const html = `
            <form id="createTicketForm" class="space-y-4">
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Servicio Afectado</label>
                    <div class="relative">
                        <select class="input-prored bg-white appearance-none" required>
                            ${serviceOptions}
                        </select>
                        <i class="fas fa-network-wired absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    ${client.servicios.length > 1 ? '<p class="text-[10px] text-blue-500 mt-1">* Cliente tiene múltiples servicios</p>' : ''}
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Contacto del Cliente</label>
                    <div class="flex gap-2">
                        <div class="relative w-full">
                            <select class="input-prored bg-white appearance-none" required>
                                <option value="" disabled selected>Seleccione número...</option>
                                ${phoneOptions}
                                <option value="otro">Otro número...</option>
                            </select>
                            <i class="fas fa-phone absolute right-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Número desde el cual el cliente reportó la incidencia.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Motivo del Ticket</label>
                    <textarea class="input-prored" rows="3" placeholder="Describa el problema detalladamente..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Asignar a Nivel</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="nivel" value="N2" class="peer sr-only" checked>
                            <div class="p-3 rounded-lg border border-gray-200 peer-checked:border-primary peer-checked:bg-blue-50 hover:bg-gray-50 transition-all text-center">
                                <i class="fas fa-headset text-xl text-gray-400 peer-checked:text-primary mb-1 block"></i>
                                <span class="text-sm font-bold text-gray-600 peer-checked:text-primary">Nivel 2</span>
                                <p class="text-[10px] text-gray-400">Soporte Remoto</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="nivel" value="N3" class="peer sr-only">
                            <div class="p-3 rounded-lg border border-gray-200 peer-checked:border-secondary peer-checked:bg-orange-50 hover:bg-gray-50 transition-all text-center">
                                <i class="fas fa-truck text-xl text-gray-400 peer-checked:text-secondary mb-1 block"></i>
                                <span class="text-sm font-bold text-gray-600 peer-checked:text-secondary">Nivel 3</span>
                                <p class="text-[10px] text-gray-400">Visita Técnica</p>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        `;

        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('modalFooter').innerHTML = `
            <button onclick="closeDynamicModal()" class="btn-outline">Cancelar</button>
            <button onclick="submitNewTicket()" class="btn-primary">Crear Ticket</button>
        `;
        showModal();
    }

    // --- FUNCIONES AUXILIARES ---
    function submitNewTicket() {
        alert("Ticket creado y asignado correctamente.");
        closeDynamicModal();
        // Aquí recargarías la tabla vía AJAX
    }

    const modal = document.getElementById('dynamicModal');
    const panel = document.getElementById('modalPanel');

    function showModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeDynamicModal() {
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // --- GRÁFICO (Chart.js) ---
    document.addEventListener('DOMContentLoaded', () => {
        renderTable();

        const ctx = document.getElementById('clientesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sin Incidencias', 'Ticket Pendiente', 'En Visita Técnica'],
                    datasets: [{
                        label: 'Clientes',
                        data: [120, 18, 5],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.2)', // Verde
                            'rgba(245, 158, 11, 0.2)', // Naranja
                            'rgba(0, 95, 162, 0.2)'    // Azul
                        ],
                        borderColor: [
                            '#10B981',
                            '#F59E0B',
                            '#005FA2'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Gráfico horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
</script>