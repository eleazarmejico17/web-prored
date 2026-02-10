<div class="space-y-6 animate-fade-in-up">

    <div
        class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rendimiento Nivel 2</h1>
            <p class="text-sm text-gray-500">Métricas de resolución y escalamiento a visitas técnicas.</p>
        </div>

        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Desde</label>
                <input type="date" class="input-prored py-1.5 text-sm w-36" value="<?php echo date('Y-m-01'); ?>">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Hasta</label>
                <input type="date" class="input-prored py-1.5 text-sm w-36" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <button class="btn-secondary py-2 px-3 shadow-none h-[34px] mt-auto">
                <i class="fas fa-filter"></i>
            </button>

            <div class="w-px h-8 bg-gray-200 mx-1 hidden md:block"></div>

            <button onclick="exportTableToExcel('tablaRendimiento', 'Reporte_Rendimiento_N2')"
                class="bg-green-600 text-white hover:bg-green-700 shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center h-[34px]">
                <i class="fas fa-file-excel mr-2"></i> Exportar Excel
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card-base p-6 border-l-4 border-primary relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Asignados (N2)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">145</h3>
                    <p class="text-xs text-gray-500 mt-1">Tickets recibidos en el periodo</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 text-primary flex items-center justify-center text-xl">
                    <i class="fas fa-inbox"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-success relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Resueltos en Nivel 2</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">112</h3>
                    <p class="text-xs text-green-600 mt-1 font-bold">
                        <i class="fas fa-check mr-1"></i> 77.2% Eficiencia
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-50 text-success flex items-center justify-center text-xl">
                    <i class="fas fa-headset"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-6 border-l-4 border-orange-500 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Re-asignados a Nivel 3</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">33</h3>
                    <p class="text-xs text-orange-600 mt-1 font-bold">
                        <i class="fas fa-arrow-up mr-1"></i> 22.8% Escalados
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="card-base p-6 lg:col-span-3">
            <h3 class="font-bold text-gray-800 text-lg mb-4">Evolución de Resolución vs. Escalamiento (Últimos 7 días)
            </h3>
            <div class="h-64 w-full">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <div class="card-base lg:col-span-3 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-lg">Detalle de Gestión Nivel 2</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="tablaRendimiento" class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Ticket</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Agente N2</th>
                            <th class="px-6 py-4">Problema</th>
                            <th class="px-6 py-4 text-center">Duración</th>
                            <th class="px-6 py-4 text-center">Estado Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="metricsTableBody">
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 flex justify-end bg-gray-50/30">
                <span class="text-xs text-gray-400 self-center mr-4">Mostrando 10 de 145 registros</span>
                <nav class="flex gap-1">
                    <button
                        class="px-3 py-1 rounded border border-gray-300 text-gray-500 text-xs hover:bg-gray-100">Anterior</button>
                    <button
                        class="px-3 py-1 rounded border border-gray-300 text-gray-500 text-xs hover:bg-gray-100">Siguiente</button>
                </nav>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- MOCK DATA PARA LA TABLA ---
    const dbMetrics = [
        { id: 4920, fecha: "2026-02-10", cliente: "ShopNear S.R.L.", agente: "Roberto Díaz", problema: "Sin internet (LOS)", duracion: "45 min", estado: "ESCALADO_N3" },
        { id: 4919, fecha: "2026-02-10", cliente: "Juan Pérez", agente: "Roberto Díaz", problema: "Cambio Clave Wifi", duracion: "12 min", estado: "RESUELTO_N2" },
        { id: 4918, fecha: "2026-02-10", cliente: "Maria Lopez", agente: "Ana Ruiz", problema: "Lentitud", duracion: "30 min", estado: "RESUELTO_N2" },
        { id: 4915, fecha: "2026-02-09", cliente: "Carlos Huaman", agente: "Roberto Díaz", problema: "Router desconfigurado", duracion: "1h 10m", estado: "ESCALADO_N3" },
        { id: 4910, fecha: "2026-02-09", cliente: "Hotel Los Andes", agente: "Ana Ruiz", problema: "Intermitencia", duracion: "25 min", estado: "RESUELTO_N2" },
        { id: 4905, fecha: "2026-02-08", cliente: "Pedro Castillo", agente: "Roberto Díaz", problema: "Cable cortado", duracion: "10 min", estado: "ESCALADO_N3" },
        { id: 4901, fecha: "2026-02-08", cliente: "Luisa Lane", agente: "Ana Ruiz", problema: "No conecta TV", duracion: "15 min", estado: "RESUELTO_N2" },
    ];

    // --- RENDERIZADO DE TABLA ---
    function renderMetricsTable() {
        const tbody = document.getElementById('metricsTableBody');
        tbody.innerHTML = dbMetrics.map(t => {
            let badge;
            if (t.estado === 'RESUELTO_N2') {
                badge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                            <i class="fas fa-check mr-1"></i> Resuelto N2
                         </span>`;
            } else {
                badge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                            <i class="fas fa-arrow-up mr-1"></i> Escalado N3
                         </span>`;
            }

            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-700">#${t.id}</td>
                    <td class="px-6 py-4 text-xs font-mono">${t.fecha}</td>
                    <td class="px-6 py-4">${t.cliente}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                ${t.agente.substring(0, 2).toUpperCase()}
                            </div>
                            <span>${t.agente}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">${t.problema}</td>
                    <td class="px-6 py-4 text-center font-mono text-xs">${t.duracion}</td>
                    <td class="px-6 py-4 text-center">${badge}</td>
                </tr>
            `;
        }).join('');
    }

    // --- FUNCIÓN EXPORTAR EXCEL (FRONTEND) ---
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Nombre del archivo
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Crear link de descarga
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Crear link href
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setear nombre
            downloadLink.download = filename;

            // Trigger descarga
            downloadLink.click();
        }
    }

    // --- GRÁFICO (Chart.js) ---
    document.addEventListener('DOMContentLoaded', () => {
        renderMetricsTable();

        const ctx = document.getElementById('performanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['04 Feb', '05 Feb', '06 Feb', '07 Feb', '08 Feb', '09 Feb', '10 Feb'],
                    datasets: [
                        {
                            label: 'Resueltos N2',
                            data: [12, 19, 15, 17, 10, 20, 15],
                            backgroundColor: '#10B981', // Verde Success
                            borderRadius: 4
                        },
                        {
                            label: 'Escalados a N3',
                            data: [3, 5, 2, 4, 8, 3, 5],
                            backgroundColor: '#F59E0B', // Naranja Warning
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        }
    });
</script>