<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mesa de Ayuda (Helpdesk)</h1>
            <p class="text-sm text-gray-500">Gestión de reportes, reclamos y derivación a técnica.</p>
        </div>
        <button onclick="openNewReportModal()" class="btn-primary shadow-lg hover:shadow-xl transition-all">
            <i class="fas fa-headset mr-2"></i> Nuevo Reporte
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card-base p-5 border-l-4 border-primary relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reportes Nuevos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_nuevos">8</h3>
                    <p class="text-[10px] text-primary mt-1 font-bold">
                        <i class="fas fa-inbox mr-1"></i> Bandeja de entrada
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50 text-primary">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-5 border-l-4 border-warning">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Derivados a Técnica</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_asignados">15</h3>
                    <p class="text-[10px] text-warning mt-1 font-bold">
                        <i class="fas fa-hard-hat mr-1"></i> Semana Actual
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-yellow-50 text-warning">
                    <i class="fas fa-tools text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-5 border-l-4 border-success">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Atendidos Nivel 1</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_resueltos">42</h3>
                    <p class="text-[10px] text-success mt-1 font-bold">
                        <i class="fas fa-check-circle mr-1"></i> Solucionados sin visita
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-green-50 text-success">
                    <i class="fas fa-hands-helping text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">

        <div
            class="p-5 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-alt text-gray-500"></i> Reportes Pendientes
            </h3>

            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" id="searchInput" onkeyup="renderTable()" class="input-prored pl-9 py-2 text-xs"
                        placeholder="Buscar cliente o ticket...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ID Reporte</th>
                        <th class="px-6 py-4">Cliente / Servicio</th>
                        <th class="px-6 py-4">Motivo Inicial</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4 text-center">Tiempo Esp.</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="ticketsTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="newReportModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="modalPanelNew">

        <div class="bg-primary p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i> Nuevo Reporte de Incidente</h3>
            <button onclick="closeModal('newReportModal')" class="hover:text-gray-200"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6 space-y-5 overflow-y-auto">

            <div>
                <label class="label-prored">1. Buscar Titular del Servicio</label>
                <div class="relative">
                    <input type="text" id="clientSearchInput" class="input-prored pl-9"
                        placeholder="Ingrese DNI, RUC o Apellido..." autocomplete="off">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <div id="clientSearchResults"
                    class="hidden mt-2 border border-gray-200 rounded-lg max-h-40 overflow-y-auto shadow-sm">
                </div>
            </div>

            <div id="serviceSelectionSection" class="hidden space-y-3">
                <label class="label-prored">2. Seleccione el Servicio Afectado</label>
                <div id="servicesListRadio" class="space-y-2">
                </div>
            </div>

            <div id="problemDetailSection" class="hidden space-y-4 pt-4 border-t border-gray-100">
                <div>
                    <label class="label-prored">3. Motivo del Reporte</label>
                    <select id="newIssueType" class="input-prored bg-white">
                        <option value="Lentitud">Lentitud en el servicio</option>
                        <option value="Corte Total">Corte total (Sin señal)</option>
                        <option value="Intermitencia">Intermitencia / Cortes constantes</option>
                        <option value="Cambio Clave">Solicitud cambio de clave WiFi</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
                <div>
                    <label class="label-prored">Descripción inicial del cliente</label>
                    <textarea id="newDescription" class="input-prored" rows="2"
                        placeholder="Ej: Dice que desde ayer está lento en las noches..."></textarea>
                </div>
            </div>

        </div>

        <div class="p-4 bg-gray-50 rounded-b-xl border-t border-gray-100 flex justify-end gap-3">
            <button onclick="closeModal('newReportModal')" class="btn-outline">Cancelar</button>
            <button onclick="submitNewReport()" class="btn-primary shadow-lg" id="btnSubmitNew" disabled>
                <i class="fas fa-save mr-2"></i> Registrar Reporte
            </button>
        </div>
    </div>
</div>

<div id="assignTicketModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="modalPanelAssign">

        <div class="bg-warning p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg text-yellow-900"><i class="fas fa-tools mr-2"></i> Asignar Ticket a Técnica
            </h3>
            <button onclick="closeModal('assignTicketModal')" class="text-yellow-900 hover:text-white"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6 space-y-4">
            <div class="bg-yellow-50 p-3 rounded text-xs text-yellow-800 border border-yellow-200 mb-4">
                <p>Estás derivando el reporte <strong>#<span id="assignReportId">000</span></strong> al área técnica. El
                    estado cambiará a <strong>ASIGNADO</strong>.</p>
            </div>

            <div>
                <label class="label-prored">Número de Contacto (Para el técnico)</label>
                <select id="assignContactNum" class="input-prored bg-white">
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-prored">Tipo de Problema</label>
                    <select id="assignProblemType" class="input-prored bg-white">
                        <option value="Corte Fibra">Corte de Fibra (LOS)</option>
                        <option value="Router Desconfigurado">Router Desconfigurado</option>
                        <option value="Atenuacion Alta">Atenuación Alta</option>
                        <option value="Cambio Equipo">Cambio de Equipo</option>
                    </select>
                </div>
                <div>
                    <label class="label-prored">Urgencia</label>
                    <select id="assignUrgency" class="input-prored bg-white">
                        <option value="BAJO">Baja (24-48h)</option>
                        <option value="MEDIO" selected>Media (24h)</option>
                        <option value="ALTO">Alta (Inmediato)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label-prored">Notas para el Técnico</label>
                <textarea id="assignNotes" class="input-prored" rows="3"
                    placeholder="Detalles técnicos, horario preferido del cliente, referencias..."></textarea>
            </div>
        </div>

        <div class="p-4 bg-gray-50 rounded-b-xl border-t border-gray-100 flex justify-end gap-3">
            <button onclick="closeModal('assignTicketModal')" class="btn-outline">Cancelar</button>
            <button onclick="confirmAssignTicket()"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all">
                <i class="fas fa-paper-plane mr-2"></i> Generar Ticket
            </button>
        </div>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS ---
    // Estados: ABIERTO (Reporte), ASIGNADO (Ticket Técnico), CERRADO (Resuelto)
    let ticketsDB = [
        {
            id: 2045, cliente: "Raúl Mendoza", servicio: "Av. Los Incas 450",
            problema: "Lentitud", fecha: "Hace 20 min", estado: "ABIERTO",
            telefonos: ["998877665", "966554433"]
        },
        {
            id: 2044, cliente: "Bodega El Sol", servicio: "Jr. Comercio 200",
            problema: "Corte Total", fecha: "Hace 1 hora", estado: "ABIERTO",
            telefonos: ["912345678"]
        },
        {
            id: 2042, cliente: "Elena Torres", servicio: "Calle Real 123",
            problema: "Intermitencia", fecha: "Hace 3 horas", estado: "ABIERTO",
            telefonos: ["911223344"]
        }
    ];

    const clientesSearchDB = [
        {
            id: 101, nombre: "Juan Perez", dni: "12345678",
            servicios: [
                { id: 1, dir: "Av. Siempre Viva 123", plan: "Fibra 50Mb" }
            ],
            telefonos: ["999888777"]
        },
        {
            id: 102, nombre: "Maria Gomez", dni: "87654321",
            servicios: [
                { id: 2, dir: "Calle Falsa 123", plan: "Fibra 100Mb" },
                { id: 3, dir: "Jr. Comercio 500 (Tienda)", plan: "Fibra Pyme" }
            ],
            telefonos: ["911222333"]
        }
    ];

    let currentAssignId = null;

    // --- 2. RENDERIZADO TABLA ---
    function renderTable() {
        const tbody = document.getElementById('ticketsTableBody');
        const search = document.getElementById('searchInput').value.toLowerCase();
        tbody.innerHTML = '';

        const filtered = ticketsDB.filter(t =>
            t.estado === 'ABIERTO' &&
            (t.cliente.toLowerCase().includes(search) || t.id.toString().includes(search))
        );

        filtered.forEach(t => {
            // Dropdown de contacto rápido
            const contactBtn = `
                <div class="relative group inline-block">
                    <button class="w-8 h-8 rounded border border-blue-200 text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-phone-alt"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl hidden group-hover:block z-50">
                        ${t.telefonos.map(num => `
                            <a href="tel:${num}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i class="fas fa-mobile-alt"></i> ${num}
                            </a>
                            <a href="https://wa.me/51${num}" target="_blank" class="block px-4 py-2 text-xs text-green-600 hover:bg-green-50 flex items-center gap-2">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 border-b border-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">#${t.id}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800 text-sm">${t.cliente}</p>
                        <p class="text-[10px] text-gray-400 truncate w-40"><i class="fas fa-map-marker-alt mr-1"></i> ${t.servicio}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="bg-red-50 text-red-600 px-2 py-1 rounded text-xs font-bold border border-red-100">${t.problema}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        ${contactBtn}
                    </td>
                    <td class="px-6 py-4 text-center text-xs text-gray-500">
                        <i class="far fa-clock mr-1"></i> ${t.fecha}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="markAsAttended(${t.id})" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded text-xs font-bold transition-colors" title="Marcar como Atendido (Cerrar)">
                                <i class="fas fa-check mr-1"></i> Atendido
                            </button>
                            <button onclick="openAssignModal(${t.id})" class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded text-xs font-bold transition-colors" title="Asignar Ticket Técnico">
                                <i class="fas fa-tools mr-1"></i> Ticket
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-gray-400">No hay reportes pendientes.</td></tr>`;
        }

        // Update KPI (Simple count)
        document.getElementById('kpi_nuevos').textContent = filtered.length;
    }

    renderTable();

    // --- 3. ACCIONES DE TABLA ---

    // Opción A: Marcar como Atendido (Cerrar)
    function markAsAttended(id) {
        if (confirm("¿El problema fue resuelto por Ventas/Atención? El reporte se marcará como CERRADO.")) {
            ticketsDB = ticketsDB.filter(t => t.id !== id);
            renderTable();

            // Toast
            showToast("Reporte cerrado exitosamente.", "success");

            // Simular contador KPI
            let resolved = parseInt(document.getElementById('kpi_resueltos').innerText);
            document.getElementById('kpi_resueltos').innerText = resolved + 1;
        }
    }

    // Opción B: Asignar Ticket (Modal)
    function openAssignModal(id) {
        currentAssignId = id;
        const ticket = ticketsDB.find(t => t.id === id);

        document.getElementById('assignReportId').textContent = ticket.id;

        // Llenar teléfonos
        const select = document.getElementById('assignContactNum');
        select.innerHTML = '';
        ticket.telefonos.forEach(num => {
            const opt = document.createElement('option');
            opt.value = num;
            opt.text = num;
            select.appendChild(opt);
        });

        // Abrir Modal
        openModal('assignTicketModal', 'modalPanelAssign');
    }

    function confirmAssignTicket() {
        // Lógica de guardado en BD (Simulado)
        // UPDATE ticket SET estado = 'ASIGNADO', ...

        ticketsDB = ticketsDB.filter(t => t.id !== currentAssignId); // Remover de pendientes
        renderTable();

        closeModal('assignTicketModal');
        showToast("Ticket asignado al Área Técnica.", "warning");

        // Update KPI
        let assigned = parseInt(document.getElementById('kpi_asignados').innerText);
        document.getElementById('kpi_asignados').innerText = assigned + 1;
    }


    // --- 4. NUEVO REPORTE (MODAL) ---

    // Buscador
    const searchInput = document.getElementById('clientSearchInput');
    const resultsDiv = document.getElementById('clientSearchResults');

    searchInput.addEventListener('keyup', (e) => {
        const val = e.target.value.toLowerCase();
        resultsDiv.innerHTML = '';

        if (val.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }

        const matches = clientesSearchDB.filter(c => c.nombre.toLowerCase().includes(val) || c.dni.includes(val));

        if (matches.length > 0) {
            resultsDiv.classList.remove('hidden');
            matches.forEach(c => {
                const div = document.createElement('div');
                div.className = "p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0";
                div.innerHTML = `
                    <p class="font-bold text-gray-800 text-sm">${c.nombre}</p>
                    <p class="text-xs text-gray-500">DNI: ${c.dni}</p>
                `;
                div.onclick = () => selectClientForReport(c);
                resultsDiv.appendChild(div);
            });
        } else {
            resultsDiv.classList.add('hidden');
        }
    });

    function selectClientForReport(client) {
        // Ocultar buscador y mostrar servicios
        searchInput.value = client.nombre;
        resultsDiv.classList.add('hidden');

        const list = document.getElementById('servicesListRadio');
        list.innerHTML = '';

        client.servicios.forEach((s, idx) => {
            const div = document.createElement('div');
            div.className = "flex items-center p-3 border border-gray-200 rounded-lg";
            div.innerHTML = `
                <input type="radio" name="selectedService" id="srv_${s.id}" value="${s.id}" ${idx === 0 ? 'checked' : ''} class="text-primary focus:ring-primary h-4 w-4">
                <label for="srv_${s.id}" class="ml-3 block text-sm font-medium text-gray-700 w-full cursor-pointer">
                    <span class="block font-bold">${s.dir}</span>
                    <span class="text-xs text-gray-500">${s.plan}</span>
                </label>
            `;
            list.appendChild(div);
        });

        document.getElementById('serviceSelectionSection').classList.remove('hidden');
        document.getElementById('problemDetailSection').classList.remove('hidden');
        document.getElementById('btnSubmitNew').disabled = false;
    }

    function submitNewReport() {
        // Simular insert
        const newTicket = {
            id: Math.floor(Math.random() * 1000) + 2000,
            cliente: document.getElementById('clientSearchInput').value,
            servicio: "Servicio Seleccionado...",
            problema: document.getElementById('newIssueType').value,
            fecha: "Ahora mismo",
            estado: "ABIERTO",
            telefonos: ["999000111"] // Simulado del cliente seleccionado
        };

        ticketsDB.unshift(newTicket);
        renderTable();
        closeModal('newReportModal');
        showToast("Nuevo reporte registrado.", "success");

        // Reset forms
        document.getElementById('clientSearchInput').value = '';
        document.getElementById('serviceSelectionSection').classList.add('hidden');
        document.getElementById('problemDetailSection').classList.add('hidden');
    }


    // --- UTILS ---
    function openNewReportModal() {
        openModal('newReportModal', 'modalPanelNew');
    }

    function openModal(modalId, panelId) {
        const modal = document.getElementById(modalId);
        const panel = document.getElementById(panelId);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[class*="transform"]');
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function showToast(msg, type) {
        const bg = type === 'success' ? 'bg-green-600' : 'bg-yellow-600';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-info-circle';

        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce flex items-center gap-2`;
        toast.innerHTML = `<i class="fas ${icon}"></i> ${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>