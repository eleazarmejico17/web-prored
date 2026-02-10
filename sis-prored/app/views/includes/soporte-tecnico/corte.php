<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Cortes y Morosidad</h1>
            <p class="text-sm text-gray-500">Análisis de suspensiones y ejecución de cortes masivos.</p>
        </div>

        <div class="flex gap-2">
            <button onclick="toggleAnalytics()"
                class="btn-outline py-2 px-4 shadow-sm text-primary border-primary hover:bg-primary-light">
                <i class="fas fa-chart-pie mr-2"></i> Ocultar/Ver Estadísticas
            </button>
            <button onclick="exportTableToExcel('tablaCortes', 'Reporte_Corte_Servicios')"
                class="btn-secondary py-2 px-4 shadow-sm flex items-center gap-2">
                <i class="fas fa-file-excel text-green-600"></i> Exportar Lista
            </button>
        </div>
    </div>

    <div id="analytics-section" class="space-y-6 transition-all duration-500 overflow-hidden">

        <div class="card-base p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-lg">Historial de Suspensiones Ejecutadas</h3>

                <div class="bg-gray-100 p-1 rounded-lg flex gap-1">
                    <button onclick="updateChart('semana')" id="btn-sem"
                        class="px-4 py-1.5 text-xs font-bold rounded-md bg-white text-primary shadow-sm transition-all">Semana</button>
                    <button onclick="updateChart('mes')" id="btn-mes"
                        class="px-4 py-1.5 text-xs font-bold rounded-md text-gray-500 hover:text-gray-700 transition-all">Mes</button>
                    <button onclick="updateChart('anio')" id="btn-anio"
                        class="px-4 py-1.5 text-xs font-bold rounded-md text-gray-500 hover:text-gray-700 transition-all">Año</button>
                </div>
            </div>

            <div class="h-64 w-full">
                <canvas id="historyChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="card-base p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Cortes por Distrito (Mes Actual)</h3>
                <div class="h-48 w-full relative">
                    <canvas id="districtChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400">Total cortes mes actual: <span
                            class="font-bold text-gray-700">142</span></p>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="card-base p-4 border-l-4 border-red-500 bg-gradient-to-br from-white to-red-50">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                            #1</div>
                        <i class="fas fa-map-marker-alt text-red-200 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mt-2">El Tambo</h4>
                    <p class="text-3xl font-bold text-red-600 my-1">45%</p>
                    <p class="text-xs text-gray-500">de los cortes totales</p>
                </div>

                <div class="card-base p-4 border-l-4 border-orange-400">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                            #2</div>
                        <i class="fas fa-map-marker-alt text-orange-200 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mt-2">Chilca</h4>
                    <p class="text-3xl font-bold text-orange-500 my-1">30%</p>
                    <p class="text-xs text-gray-500">de los cortes totales</p>
                </div>

                <div class="card-base p-4 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                            #3</div>
                        <i class="fas fa-map-marker-alt text-blue-200 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mt-2">Huancayo</h4>
                    <p class="text-3xl font-bold text-blue-600 my-1">15%</p>
                    <p class="text-xs text-gray-500">de los cortes totales</p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 my-4"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <button onclick="switchFilter('critical')" id="tab-critical"
            class="card-base p-4 border-l-4 border-red-500 text-left transition-all ring-2 ring-offset-2 ring-transparent focus:outline-none relative group">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800">Corte Inmediato</h3>
                    <p class="text-xs text-gray-500">Mora > 2 días (Ejecutar Corte)</p>
                </div>
                <div class="bg-red-100 text-red-600 font-bold px-3 py-1 rounded-lg text-xl" id="count-critical">0</div>
            </div>
            <div id="ind-critical" class="absolute bottom-0 left-0 w-full h-1 bg-red-500 hidden"></div>
        </button>

        <button onclick="switchFilter('warning')" id="tab-warning"
            class="card-base p-4 border-l-4 border-orange-400 text-left transition-all ring-2 ring-offset-2 ring-transparent focus:outline-none relative group opacity-60 hover:opacity-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800">Alerta Preventiva</h3>
                    <p class="text-xs text-gray-500">Vence hoy o ayer (0-1 días)</p>
                </div>
                <div class="bg-orange-100 text-orange-600 font-bold px-3 py-1 rounded-lg text-xl" id="count-warning">0
                </div>
            </div>
            <div id="ind-warning" class="absolute bottom-0 left-0 w-full h-1 bg-orange-400 hidden"></div>
        </button>
    </div>

    <div class="card-base overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="check_all_main"
                    class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500 transition-colors cursor-pointer"
                    onchange="toggleSelectAll()">
                <label for="check_all_main"
                    class="text-sm font-bold text-gray-700 cursor-pointer select-none">Seleccionar Todo</label>
            </div>
            <span class="text-xs text-gray-400 italic" id="filter-label">Mostrando candidatos a corte</span>
        </div>

        <div class="overflow-x-auto">
            <table id="tablaCortes" class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 w-10"></th>
                        <th class="px-6 py-4">Cliente / Servicio</th>
                        <th class="px-6 py-4">IP / Winbox</th>
                        <th class="px-6 py-4">Deuda Total</th>
                        <th class="px-6 py-4">Vencimiento</th>
                        <th class="px-6 py-4 text-center">Días Mora</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="corteTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="action-bar"
    class="fixed bottom-0 left-0 md:left-64 right-0 bg-white border-t border-gray-200 p-4 shadow-2xl transform translate-y-full transition-transform duration-300 z-40 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs font-bold"><span id="selected-count">0</span>
            Seleccionados</div>
        <p class="text-sm text-gray-600 hidden md:block">Usuarios listos para procesar suspensión.</p>
    </div>
    <button onclick="openConfirmModal()"
        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-all flex items-center gap-2">
        <i class="fas fa-cut"></i><span>SUSPENDER SERVICIOS</span>
    </button>
</div>

<div id="confirmModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="confirmPanel">
        <div class="p-6 text-center">
            <div
                class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-exclamation-triangle"></i></div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Confirmar Corte Masivo</h3>
            <p class="text-sm text-gray-500 mb-6">Se suspenderá el servicio a los siguientes usuarios:</p>
            <div
                class="bg-gray-50 rounded-lg p-4 text-left max-h-48 overflow-y-auto custom-scrollbar mb-6 border border-gray-200">
                <ul id="affected-list" class="space-y-2 text-sm text-gray-700"></ul>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeConfirmModal()" class="btn-outline justify-center">Cancelar</button>
                <button onclick="executeSuspension()"
                    class="btn-primary bg-red-600 hover:bg-red-700 justify-center border-transparent">Confirmar y
                    Cortar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- DATOS SIMULADOS PARA GRÁFICOS ---
    const chartData = {
        semana: { labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'], data: [5, 12, 8, 15, 20, 10, 2] },
        mes: { labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'], data: [45, 60, 30, 80] },
        anio: { labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'], data: [120, 150, 100, 180, 200, 160] }
    };

    let historyChartInstance = null;

    // --- INICIALIZACIÓN GRÁFICOS ---
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Gráfico Histórico (Barras)
        const ctxHist = document.getElementById('historyChart');
        if (ctxHist) {
            historyChartInstance = new Chart(ctxHist, {
                type: 'bar',
                data: {
                    labels: chartData.semana.labels,
                    datasets: [{
                        label: 'Cortes Ejecutados',
                        data: chartData.semana.data,
                        backgroundColor: '#EF4444',
                        borderRadius: 4,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // 2. Gráfico Distritos (Dona)
        const ctxDist = document.getElementById('districtChart');
        if (ctxDist) {
            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: ['El Tambo', 'Chilca', 'Huancayo', 'Otros'],
                    datasets: [{
                        data: [45, 30, 15, 10],
                        backgroundColor: ['#EF4444', '#F97316', '#3B82F6', '#9CA3AF'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
        }

        // Cargar tabla (Lógica existente)
        updateCounts();
        renderTable();
    });

    // --- FUNCIÓN ACTUALIZAR GRÁFICO ---
    function updateChart(periodo) {
        // Actualizar datos
        historyChartInstance.data.labels = chartData[periodo].labels;
        historyChartInstance.data.datasets[0].data = chartData[periodo].data;
        historyChartInstance.update();

        // Actualizar estilos botones
        ['sem', 'mes', 'anio'].forEach(p => {
            const btn = document.getElementById(`btn-${p}`);
            if (p === periodo || (periodo === 'semana' && p === 'sem') || (periodo === 'anio' && p === 'anio')) { // Match simple
                // Reset todos a inactivo
                btn.className = "px-4 py-1.5 text-xs font-bold rounded-md text-gray-500 hover:text-gray-700 transition-all";
            }
        });

        // Activar el seleccionado (Hack visual rápido)
        const activeBtn = document.getElementById(`btn-${periodo === 'semana' ? 'sem' : periodo}`);
        activeBtn.className = "px-4 py-1.5 text-xs font-bold rounded-md bg-white text-primary shadow-sm transition-all";
    }

    function toggleAnalytics() {
        const section = document.getElementById('analytics-section');
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

    // --- LÓGICA DE TABLA Y GESTIÓN (IDÉNTICA AL ANTERIOR) ---
    const users = [
        { id: 101, nombre: "Juan Pérez", direccion: "Av. Giraldez 123", ip: "192.168.10.15", winbox: "RB-Pangoa", deuda: "S/ 89.90", vencimiento: "05/02/2026", dias_mora: 5, categoria: "critical" },
        { id: 102, nombre: "Bodega El Sol", direccion: "Jr. Junin 440", ip: "192.168.10.22", winbox: "RB-Pangoa", deuda: "S/ 150.00", vencimiento: "06/02/2026", dias_mora: 4, categoria: "critical" },
        { id: 103, nombre: "Carlos Ruiz", direccion: "Calle Real 890", ip: "192.168.20.5", winbox: "RB-Chilca", deuda: "S/ 70.00", vencimiento: "07/02/2026", dias_mora: 3, categoria: "critical" },
        { id: 104, nombre: "Empresa X SAC", direccion: "Parque Industrial", ip: "192.168.20.99", winbox: "RB-Chilca", deuda: "S/ 300.00", vencimiento: "03/02/2026", dias_mora: 7, categoria: "critical" },
        { id: 201, nombre: "Ana Maria", direccion: "Los Olivos 44", ip: "192.168.30.10", winbox: "RB-Huancayo", deuda: "S/ 60.00", vencimiento: "09/02/2026", dias_mora: 1, categoria: "warning" },
        { id: 202, nombre: "Luis Torres", direccion: "Av. Ferrocarril", ip: "192.168.30.12", winbox: "RB-Huancayo", deuda: "S/ 80.00", vencimiento: "10/02/2026", dias_mora: 0, categoria: "warning" },
        { id: 203, nombre: "Pedro Castillo", direccion: "Chota 123", ip: "192.168.30.15", winbox: "RB-Huancayo", deuda: "S/ 100.00", vencimiento: "10/02/2026", dias_mora: 0, categoria: "warning" }
    ];

    let currentCategory = 'critical';
    let selectedIds = new Set();

    function updateCounts() {
        document.getElementById('count-critical').innerText = users.filter(u => u.categoria === 'critical').length;
        document.getElementById('count-warning').innerText = users.filter(u => u.categoria === 'warning').length;
    }

    function switchFilter(category) {
        currentCategory = category;
        selectedIds.clear();
        updateActionBar();
        document.getElementById('check_all_main').checked = false;

        const tabCrit = document.getElementById('tab-critical');
        const tabWarn = document.getElementById('tab-warning');
        const indCrit = document.getElementById('ind-critical');
        const indWarn = document.getElementById('ind-warning');

        if (category === 'critical') {
            tabCrit.classList.remove('opacity-60');
            tabWarn.classList.add('opacity-60');
            indCrit.classList.remove('hidden');
            indWarn.classList.add('hidden');
            document.getElementById('filter-label').innerText = "Mostrando candidatos a corte (> 2 días mora)";
        } else {
            tabWarn.classList.remove('opacity-60');
            tabCrit.classList.add('opacity-60');
            indWarn.classList.remove('hidden');
            indCrit.classList.add('hidden');
            document.getElementById('filter-label').innerText = "Mostrando preventivos (Vence hoy o ayer)";
        }
        renderTable();
    }

    function renderTable() {
        const tbody = document.getElementById('corteTableBody');
        const filteredUsers = users.filter(u => u.categoria === currentCategory);

        if (filteredUsers.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-gray-400">No hay usuarios en esta categoría.</td></tr>`;
            return;
        }

        tbody.innerHTML = filteredUsers.map(u => {
            const isSelected = selectedIds.has(u.id);
            const moraColor = u.dias_mora > 2 ? 'text-red-600 bg-red-50' : 'text-orange-600 bg-orange-50';
            return `
                <tr class="hover:bg-gray-50 transition-colors ${isSelected ? 'bg-blue-50/50' : ''}">
                    <td class="px-6 py-4"><input type="checkbox" onchange="toggleUser(${u.id})" ${isSelected ? 'checked' : ''} class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer user-checkbox"></td>
                    <td class="px-6 py-4"><p class="font-bold text-gray-800">${u.nombre}</p><p class="text-xs text-gray-500">${u.direccion}</p></td>
                    <td class="px-6 py-4 font-mono text-xs"><p>${u.ip}</p><span class="text-gray-400">${u.winbox}</span></td>
                    <td class="px-6 py-4 font-bold text-gray-700">${u.deuda}</td>
                    <td class="px-6 py-4 text-xs">${u.vencimiento}</td>
                    <td class="px-6 py-4 text-center"><span class="px-2 py-1 rounded font-bold text-xs ${moraColor}">${u.dias_mora} días</span></td>
                    <td class="px-6 py-4 text-center"><span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">Activo (Mora)</span></td>
                </tr>
            `;
        }).join('');
    }

    function toggleUser(id) {
        if (selectedIds.has(id)) selectedIds.delete(id);
        else selectedIds.add(id);
        updateActionBar();
    }

    function toggleSelectAll() {
        const mainCheck = document.getElementById('check_all_main');
        const filteredUsers = users.filter(u => u.categoria === currentCategory);
        if (mainCheck.checked) filteredUsers.forEach(u => selectedIds.add(u.id));
        else filteredUsers.forEach(u => selectedIds.delete(u.id));
        renderTable();
        updateActionBar();
    }

    function updateActionBar() {
        const bar = document.getElementById('action-bar');
        const countSpan = document.getElementById('selected-count');
        countSpan.innerText = selectedIds.size;
        if (selectedIds.size > 0) bar.classList.remove('translate-y-full');
        else bar.classList.add('translate-y-full');
    }

    // Modales y Exportación (Funciones de soporte)
    const confirmModal = document.getElementById('confirmModal');
    const confirmPanel = document.getElementById('confirmPanel');

    function openConfirmModal() {
        const list = document.getElementById('affected-list');
        const selectedUsers = users.filter(u => selectedIds.has(u.id));
        list.innerHTML = selectedUsers.map(u => `<li class="flex justify-between items-center border-b border-gray-100 pb-1 last:border-0"><span>${u.nombre}</span><span class="font-mono text-xs text-red-500">${u.ip}</span></li>`).join('');
        confirmModal.classList.remove('hidden');
        setTimeout(() => { confirmModal.classList.remove('opacity-0'); confirmPanel.classList.remove('scale-95'); confirmPanel.classList.add('scale-100'); }, 10);
    }

    function closeConfirmModal() {
        confirmModal.classList.add('opacity-0');
        confirmPanel.classList.remove('scale-100');
        confirmPanel.classList.add('scale-95');
        setTimeout(() => confirmModal.classList.add('hidden'), 300);
    }

    function executeSuspension() {
        const btn = document.querySelector('#confirmPanel button.btn-primary');
        const originalText = btn.innerText;
        btn.innerText = "Procesando...";
        btn.disabled = true;
        setTimeout(() => {
            alert(`✅ Se han suspendido ${selectedIds.size} servicios correctamente.`);
            selectedIds.clear();
            closeConfirmModal();
            updateActionBar();
            btn.innerText = originalText;
            btn.disabled = false;
            document.getElementById('check_all_main').checked = false;
            renderTable();
        }, 1500);
    }

    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>