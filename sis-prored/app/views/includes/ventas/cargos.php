<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cargos Adicionales</h1>
            <p class="text-sm text-gray-500">Gestión de cobros extra aplicables a futuras mensualidades.</p>
        </div>
        <button onclick="openNewChargeModal()" class="btn-primary shadow-lg hover:shadow-xl transition-all">
            <i class="fas fa-plus-circle mr-2"></i> Agregar Nuevo Cargo
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card-base p-6 border-l-4 border-primary relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Cargos (Mes)</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2" id="kpi_total">S/ 0.00</h3>
                    <p class="text-[10px] text-gray-400 mt-1">Acumulado generado</p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50 text-primary">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-success">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cargos Cobrados</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2" id="kpi_cobrados">S/ 0.00</h3>
                    <p class="text-[10px] text-success mt-1 font-bold">
                        <i class="fas fa-check-circle mr-1"></i> Procesados
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-green-50 text-success">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-warning">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendiente de Cobro</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2" id="kpi_pendientes">S/ 0.00</h3>
                    <p class="text-[10px] text-warning mt-1 font-bold">
                        <i class="fas fa-clock mr-1"></i> En próximas facturas
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-orange-50 text-warning animate-pulse">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">

        <div
            class="p-5 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-ul text-secondary"></i> Historial de Cargos
            </h3>

            <div class="flex gap-2 w-full md:w-auto">
                <select id="filterPeriodo" class="input-prored bg-white text-xs py-2 w-40" onchange="renderTable()">
                    <option value="all">Todos los Periodos</option>
                    <option value="Feb 2026" selected>Feb 2026</option>
                    <option value="Mar 2026">Mar 2026</option>
                </select>
                <div class="relative w-full md:w-56">
                    <input type="text" id="searchInput" onkeyup="renderTable()" class="input-prored pl-8 py-2 text-xs"
                        placeholder="Buscar cliente...">
                    <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Fecha Reg.</th>
                        <th class="px-6 py-4">Cliente / Servicio</th>
                        <th class="px-6 py-4">Concepto</th>
                        <th class="px-6 py-4">Periodo a Aplicar</th>
                        <th class="px-6 py-4 text-right">Monto</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="chargesTableBody">
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-center bg-gray-50/30">
            <button class="text-xs text-primary hover:underline">Ver historial completo de años anteriores</button>
        </div>
    </div>

</div>

<div id="newChargeModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="modalPanelCharge">

        <div class="bg-primary p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i> Registrar Cargo Extra</h3>
            <button onclick="closeModal('newChargeModal')" class="hover:text-gray-200"><i
                    class="fas fa-times"></i></button>
        </div>

        <form onsubmit="saveCharge(event)" class="p-6 space-y-5">

            <div>
                <label class="label-prored">1. Buscar Servicio / Cliente</label>
                <div class="relative">
                    <input type="text" id="clientSearch" class="input-prored pl-9"
                        placeholder="Ingrese DNI o Apellido..." autocomplete="off">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    <div id="clientResults"
                        class="absolute w-full bg-white shadow-xl border border-gray-100 rounded-lg mt-1 z-10 hidden max-h-40 overflow-y-auto">
                    </div>
                </div>
                <input type="hidden" id="selectedServiceId">
                <p class="text-[10px] text-gray-400 mt-1" id="selectedClientText">Ningún cliente seleccionado</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-prored">2. Periodo a Aplicar</label>
                    <select id="chargePeriod" class="input-prored bg-white" required>
                        <option value="">Seleccione...</option>
                        <option value="Feb 2026">Febrero 2026 (Actual)</option>
                        <option value="Mar 2026">Marzo 2026 (Próximo)</option>
                        <option value="Abr 2026">Abril 2026</option>
                    </select>
                </div>
                <div>
                    <label class="label-prored">3. Monto (S/)</label>
                    <input type="number" step="0.01" id="chargeAmount" class="input-prored font-bold text-gray-800"
                        placeholder="0.00" required>
                </div>
            </div>

            <div>
                <label class="label-prored">4. Concepto / Descripción</label>
                <div class="relative">
                    <input type="text" id="chargeConcept" list="conceptosList" class="input-prored pl-9"
                        placeholder="Ej: Instalación de Punto Adicional" required>
                    <i class="fas fa-tag absolute left-3 top-3 text-gray-400 text-xs"></i>
                </div>
                <datalist id="conceptosList">
                    <option value="Instalación Punto Adicional">
                    <option value="Reposición de Equipo">
                    <option value="Penalidad por Retraso">
                    <option value="Visita Técnica Extra">
                    <option value="Materiales: Cable UTP">
                </datalist>
            </div>

            <div class="bg-yellow-50 p-3 rounded text-xs text-yellow-800 border border-yellow-200 flex gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <p>Este cargo se sumará automáticamente a la deuda del periodo seleccionado y aparecerá en el estado de
                    cuenta del cliente.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('newChargeModal')"
                    class="btn-outline w-full justify-center">Cancelar</button>
                <button type="submit" class="btn-primary w-full justify-center shadow-md">
                    <i class="fas fa-save mr-2"></i> Guardar Cargo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS (Based on cargo_adicional table) ---
    // Estados: PENDIENTE, APLICADO (Cobrado/Facturado)
    let cargosDB = [
        {
            id: 1, fecha: "05/02/2026", cliente: "Raúl Mendoza", servicio: "Av. Los Incas 450",
            concepto: "Materiales: Cable UTP (15m)", periodo: "Feb 2026", monto: 33.00, estado: "PENDIENTE"
        },
        {
            id: 2, fecha: "01/02/2026", cliente: "ShopNear S.R.L.", servicio: "Jr. Comercio 200",
            concepto: "IP Pública Estática", periodo: "Feb 2026", monto: 50.00, estado: "APLICADO"
        },
        {
            id: 3, fecha: "08/02/2026", cliente: "Elena Torres", servicio: "Calle Real 123",
            concepto: "Visita Técnica (Fuera de horario)", periodo: "Mar 2026", monto: 20.00, estado: "PENDIENTE"
        },
        {
            id: 4, fecha: "15/01/2026", cliente: "Jorge Luis", servicio: "Psje. Los Pinos",
            concepto: "Reposición de Router", periodo: "Feb 2026", monto: 120.00, estado: "APLICADO"
        }
    ];

    // Clientes para el buscador
    const clientesSearchDB = [
        { id: 101, nombre: "Raúl Mendoza", dir: "Av. Los Incas 450" },
        { id: 102, nombre: "Elena Torres", dir: "Calle Real 123" },
        { id: 103, nombre: "ShopNear S.R.L.", dir: "Jr. Comercio 200" }
    ];

    // --- 2. RENDERIZADO Y LÓGICA DE TABLA ---
    function renderTable() {
        const tbody = document.getElementById('chargesTableBody');
        const filterPeriodo = document.getElementById('filterPeriodo').value;
        const searchVal = document.getElementById('searchInput').value.toLowerCase();

        tbody.innerHTML = '';

        let totalGenerado = 0;
        let totalCobrado = 0;
        let totalPendiente = 0;

        // Filtrado
        const filteredData = cargosDB.filter(c => {
            const matchPeriod = filterPeriodo === 'all' || c.periodo === filterPeriodo;
            const matchSearch = c.cliente.toLowerCase().includes(searchVal) || c.concepto.toLowerCase().includes(searchVal);
            return matchPeriod && matchSearch;
        });

        filteredData.forEach(c => {
            // Cálculos para KPIs (Solo de lo visible o global según lógica de negocio, aquí global del mes filtrado)
            totalGenerado += c.monto;
            if (c.estado === 'APLICADO') totalCobrado += c.monto;
            else totalPendiente += c.monto;

            // Render Fila
            const badgeClass = c.estado === 'APLICADO'
                ? 'bg-green-100 text-green-700 border-green-200'
                : 'bg-yellow-100 text-yellow-800 border-yellow-200';

            const badgeIcon = c.estado === 'APLICADO' ? 'fa-check' : 'fa-clock';

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 border-b border-gray-50 transition-colors">
                    <td class="px-6 py-4 text-xs text-gray-500 font-mono">${c.fecha}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800 text-sm">${c.cliente}</p>
                        <p class="text-[10px] text-gray-400 truncate w-32">${c.servicio}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">${c.concepto}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-primary px-2 py-1 rounded text-xs font-bold border border-blue-100">${c.periodo}</span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-gray-800">S/ ${c.monto.toFixed(2)}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border ${badgeClass}">
                            <i class="fas ${badgeIcon} mr-1"></i> ${c.estado}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        ${c.estado === 'PENDIENTE' ? `
                        <button onclick="deleteCharge(${c.id})" class="text-gray-400 hover:text-danger transition-colors p-1" title="Eliminar Cargo">
                            <i class="fas fa-trash-alt"></i>
                        </button>` : `<span class="text-gray-300 text-xs"><i class="fas fa-lock"></i></span>`}
                    </td>
                </tr>
            `;
        });

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-gray-400">No se encontraron cargos.</td></tr>`;
        }

        // Actualizar KPIs
        updateKPIs(totalGenerado, totalCobrado, totalPendiente);
    }

    function updateKPIs(gen, cob, pen) {
        // Animación simple de números
        document.getElementById('kpi_total').textContent = "S/ " + gen.toFixed(2);
        document.getElementById('kpi_cobrados').textContent = "S/ " + cob.toFixed(2);
        document.getElementById('kpi_pendientes').textContent = "S/ " + pen.toFixed(2);
    }

    // Inicializar
    renderTable();

    // --- 3. LÓGICA DE MODAL (AGREGAR CARGO) ---

    // Buscador de Clientes (Simulado)
    const clientInput = document.getElementById('clientSearch');
    const resultsBox = document.getElementById('clientResults');

    clientInput.addEventListener('keyup', (e) => {
        const val = e.target.value.toLowerCase();
        resultsBox.innerHTML = '';

        if (val.length < 2) {
            resultsBox.classList.add('hidden');
            return;
        }

        const matches = clientesSearchDB.filter(c => c.nombre.toLowerCase().includes(val));

        if (matches.length > 0) {
            resultsBox.classList.remove('hidden');
            matches.forEach(c => {
                const div = document.createElement('div');
                div.className = "p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-0";
                div.innerHTML = `
                    <p class="text-sm font-bold text-gray-800">${c.nombre}</p>
                    <p class="text-xs text-gray-500">${c.dir}</p>
                `;
                div.onclick = () => selectClient(c);
                resultsBox.appendChild(div);
            });
        } else {
            resultsBox.classList.add('hidden');
        }
    });

    function selectClient(c) {
        clientInput.value = c.nombre;
        document.getElementById('selectedServiceId').value = c.id;
        document.getElementById('selectedClientText').innerHTML = `<span class="text-success font-bold"><i class="fas fa-check"></i> Seleccionado:</span> ${c.nombre} (${c.dir})`;
        resultsBox.classList.add('hidden');
    }

    // Guardar Cargo
    function saveCharge(e) {
        e.preventDefault();

        if (!document.getElementById('selectedServiceId').value) {
            alert("Por favor seleccione un cliente de la lista.");
            return;
        }

        const newCharge = {
            id: Date.now(), // Fake ID
            fecha: new Date().toLocaleDateString('es-PE'),
            cliente: document.getElementById('clientSearch').value,
            servicio: "Dirección del Cliente...", // Simplificado
            concepto: document.getElementById('chargeConcept').value,
            periodo: document.getElementById('chargePeriod').value,
            monto: parseFloat(document.getElementById('chargeAmount').value),
            estado: "PENDIENTE"
        };

        cargosDB.unshift(newCharge); // Agregar al inicio
        renderTable();

        // Feedback Visual
        closeModal('newChargeModal');
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
        toast.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i> Cargo agregado correctamente';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);

        // Reset Form
        e.target.reset();
        document.getElementById('selectedClientText').textContent = "Ningún cliente seleccionado";
        document.getElementById('selectedServiceId').value = "";
    }

    function deleteCharge(id) {
        if (confirm("¿Estás seguro de eliminar este cargo pendiente?")) {
            cargosDB = cargosDB.filter(c => c.id !== id);
            renderTable();
        }
    }

    // --- UTILS MODAL ---
    function openNewChargeModal() {
        const modal = document.getElementById('newChargeModal');
        const panel = document.getElementById('modalPanelCharge');
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
</script>