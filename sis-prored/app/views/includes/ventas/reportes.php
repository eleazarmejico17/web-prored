<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reportes y Estadísticas</h1>
            <p class="text-sm text-gray-500">Análisis visual del rendimiento del ISP.</p>
        </div>
        <button onclick="window.print()" class="btn-outline text-xs">
            <i class="fas fa-print mr-2"></i> Imprimir Reporte
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-chart-line text-primary mr-2"></i>
                    Flujo de Caja (Pagos)</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-01'); ?>"
                        onchange="updateChart('finance')">
                    <span class="text-gray-400">-</span>
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-t'); ?>"
                        onchange="updateChart('finance')">
                </div>
            </div>
            <div class="relative flex-grow p-4 group">
                <canvas id="financeChart"></canvas>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Ingresos_Egresos')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-users text-secondary mr-2"></i> Nuevas
                    Altas</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8"
                        value="<?php echo date('Y-01-01'); ?>">
                    <span class="text-gray-400">-</span>
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8"
                        value="<?php echo date('Y-12-31'); ?>">
                </div>
            </div>
            <div class="relative flex-grow p-4 group">
                <canvas id="growthChart"></canvas>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Nuevos_Clientes')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i
                        class="fas fa-file-invoice-dollar text-purple-600 mr-2"></i> Emisión de Comprobantes</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-01'); ?>">
                    <span class="text-gray-400">-</span>
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-t'); ?>">
                </div>
            </div>
            <div class="relative flex-grow p-4 group flex justify-center">
                <div class="w-2/3">
                    <canvas id="invoiceChart"></canvas>
                </div>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Reporte_Facturacion')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-bell text-yellow-500 mr-2"></i>
                    Notificaciones Enviadas</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8"
                        value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                    <span class="text-gray-400">-</span>
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="relative flex-grow p-4 group">
                <canvas id="notifChart"></canvas>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Reporte_Notificaciones')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-tags text-pink-500 mr-2"></i> Cargos
                    Adicionales (S/)</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-01'); ?>">
                    <span class="text-gray-400">-</span>
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-t'); ?>">
                </div>
            </div>
            <div class="relative flex-grow p-4 group">
                <canvas id="chargesChart"></canvas>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Reporte_Cargos_Extra')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-base p-0 overflow-hidden flex flex-col h-96">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm mb-2"><i
                        class="fas fa-network-wired text-indigo-500 mr-2"></i> Estado del Parque</h3>
                <div class="flex gap-2 items-center">
                    <input type="date" class="input-prored text-xs py-1 px-2 h-8" value="<?php echo date('Y-m-d'); ?>"
                        disabled title="Muestra estado actual">
                    <span class="text-gray-400 text-xs italic">Datos en tiempo real</span>
                </div>
            </div>
            <div class="relative flex-grow p-4 group flex justify-center">
                <div class="w-2/3">
                    <canvas id="statusChart"></canvas>
                </div>
                <div
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                    <button onclick="exportExcel('Estado_Servicios')"
                        class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Configuración Común
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    document.addEventListener('DOMContentLoaded', () => {

        // 1. FINANZAS (Line Chart)
        new Chart(document.getElementById('financeChart'), {
            type: 'line',
            data: {
                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                datasets: [
                    {
                        label: 'Pagos Recibidos',
                        data: [1200, 1900, 1500, 2200],
                        borderColor: '#10B981', // Success
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Deuda Acumulada',
                        data: [500, 400, 300, 600],
                        borderColor: '#EF4444', // Danger
                        borderDash: [5, 5],
                        tension: 0.4
                    }
                ]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        // 2. CRECIMIENTO (Bar Chart)
        new Chart(document.getElementById('growthChart'), {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Nuevos Clientes',
                    data: [12, 19, 15, 25, 22, 30],
                    backgroundColor: '#005FA2', // Primary
                    borderRadius: 4
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        // 3. FACTURACIÓN (Doughnut)
        new Chart(document.getElementById('invoiceChart'), {
            type: 'doughnut',
            data: {
                labels: ['Boletas', 'Facturas'],
                datasets: [{
                    data: [85, 15],
                    backgroundColor: ['#3B82F6', '#8B5CF6'],
                    borderWidth: 0
                }]
            },
            options: { cutout: '60%', plugins: { legend: { position: 'bottom' } } }
        });

        // 4. NOTIFICACIONES (Stacked Bar)
        new Chart(document.getElementById('notifChart'), {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
                datasets: [
                    {
                        label: 'WhatsApp',
                        data: [45, 50, 40, 60, 55, 30, 20],
                        backgroundColor: '#10B981'
                    },
                    {
                        label: 'SMS',
                        data: [10, 15, 8, 12, 10, 5, 2],
                        backgroundColor: '#6366F1'
                    }
                ]
            },
            options: {
                scales: { x: { stacked: true }, y: { stacked: true } },
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // 5. CARGOS ADICIONALES (Horizontal Bar)
        new Chart(document.getElementById('chargesChart'), {
            type: 'bar',
            data: {
                labels: ['Instalaciones', 'Materiales', 'Penalidades', 'Traslados'],
                datasets: [{
                    label: 'Monto Recaudado (S/)',
                    data: [1500, 800, 300, 450],
                    backgroundColor: ['#E58E21', '#F59E0B', '#EF4444', '#6B7280'],
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } }
            }
        });

        // 6. ESTADO SERVICIOS (Pie)
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Activos', 'Suspendidos', 'Cortados'],
                datasets: [{
                    data: [350, 45, 12],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    });

    // --- Lógica de Simulación ---

    function updateChart(chartId) {
        // Simulación: En un entorno real, esto haría un fetch AJAX con las fechas
        // Aquí solo mostramos un toast para indicar que el evento funciona
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
        toast.innerHTML = '<i class="fas fa-sync-alt fa-spin mr-2"></i> Actualizando datos por rango de fecha...';
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 2000);
    }

    function exportExcel(reportName) {
        // Simulación de descarga
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
        toast.innerHTML = `<i class="fas fa-file-excel mr-2"></i> Generando ${reportName}.xlsx...`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.innerHTML = `<i class="fas fa-check mr-2"></i> Descarga completada`;
            setTimeout(() => toast.remove(), 2000);
        }, 1500);
    }
</script>