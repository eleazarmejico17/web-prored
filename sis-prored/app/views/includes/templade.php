<?php
/**
 * UI KIT MAESTRO - PRORED
 * Este archivo contiene todos los bloques de construcción para los dashboards.
 * Úsalo como referencia para copiar y pegar componentes.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI Kit & Componentes | ProRed System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005FA2',         // Azul Corporativo
                        'primary-dark': '#004a80',
                        'primary-light': '#E6F2FA', // Fondo azul suave
                        secondary: '#E58E21',       // Naranja Corporativo
                        'secondary-light': '#FEF5E7', // Fondo naranja suave
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        info: '#3B82F6',
                        surface: '#ffffff',
                        background: '#f3f4f6'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 15px -3px rgba(0, 95, 162, 0.1), 0 4px 6px -2px rgba(0, 95, 162, 0.05)',
                        'glow': '0 0 15px rgba(0, 95, 162, 0.3)'
                    }
                }
            }
        }
    </script>

    <style>
        /* Estilos Base */
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; color: #374151; }
        
        /* Scrollbar Corporativo */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #005FA2; }

        /* Clases de Utilidad Personalizadas */
        .card-base {
            @apply bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300 border border-gray-100;
        }
        
        .input-prored {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm;
        }

        .label-prored {
            @apply block text-sm font-medium text-gray-700 mb-1.5;
        }

        .btn {
            @apply px-4 py-2 rounded-lg font-medium transition-all transform active:scale-95 flex items-center justify-center gap-2;
        }
        
        .btn-primary { @apply btn bg-primary text-white hover:bg-primary-dark shadow-md hover:shadow-lg shadow-primary/30; }
        .btn-secondary { @apply btn bg-secondary text-white hover:bg-orange-600 shadow-md hover:shadow-lg shadow-secondary/30; }
        .btn-outline { @apply btn border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-primary hover:border-primary; }
        .btn-danger { @apply btn bg-danger text-white hover:bg-red-600 shadow-md; }

        /* Toggle Switch Custom CSS */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #005FA2;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #005FA2;
        }
    </style>
</head>
<body class="p-8">

    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-primary mb-2">Pro<span class="text-secondary">Red</span> Design System</h1>
            <p class="text-gray-500">Componentes estandarizados para Admin, Empleados y Clientes.</p>
        </div>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">1. Tarjetas de Métricas (KPIs)</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div class="card-base p-6 border-l-4 border-primary">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ingresos Totales</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-2">$42,580</h3>
                        </div>
                        <div class="p-3 rounded-lg bg-primary-light text-primary">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="card-base p-6 border-l-4 border-secondary">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nuevos Clientes</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-2">142</h3>
                        </div>
                        <div class="p-3 rounded-lg bg-secondary-light text-secondary">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-success font-bold flex items-center bg-green-50 px-2 py-0.5 rounded">
                            <i class="fas fa-arrow-up mr-1"></i> 12.5%
                        </span>
                        <span class="text-gray-400 ml-2">vs. mes anterior</span>
                    </div>
                </div>

                <div class="card-base p-6 border-l-4 border-danger">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tickets Críticos</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-2">5</h3>
                        </div>
                        <div class="p-3 rounded-lg bg-red-50 text-danger animate-pulse">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-danger font-medium">
                        Requiere atención inmediata
                    </div>
                </div>

                <div class="card-base p-6">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-xs font-bold text-gray-400 uppercase">Capacidad de Red</p>
                        <span class="text-xs font-bold text-primary">78%</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">780 Mbps <span class="text-sm text-gray-400 font-normal">/ 1Gbps</span></h3>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full shadow-glow" style="width: 78%"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">2. Gráficos Visuales</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="card-base p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800">Tráfico de Red (Mbps)</h3>
                        <select class="text-xs border-gray-300 rounded-md text-gray-500 bg-gray-50 p-1">
                            <option>Últimas 24h</option>
                            <option>Semana</option>
                        </select>
                    </div>
                    <div class="h-64">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <div class="card-base p-6">
                    <h3 class="font-bold text-gray-800 mb-6">Distribución de Planes</h3>
                    <div class="h-64 flex justify-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">3. Tablas de Datos</h2>
            
            <div class="card-base overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-gray-50/50 gap-4">
                    <h3 class="font-bold text-gray-800 text-lg">Clientes Recientes</h3>
                    <div class="flex gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <input type="text" placeholder="Buscar..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-primary">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>
                        <button class="btn-primary py-1.5 px-3 text-sm"><i class="fas fa-filter"></i></button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                            <tr>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Plan</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Facturación</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                            JP
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Juan Pérez</p>
                                            <p class="text-xs text-gray-400">ID: #8921</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">Fibra 100Mb + TV</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">
                                        <span class="w-1.5 h-1.5 bg-success rounded-full mr-1.5"></span> Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium">S/ 89.90</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-primary transition-colors p-1"><i class="fas fa-eye"></i></button>
                                    <button class="text-gray-400 hover:text-secondary transition-colors p-1 ml-2"><i class="fas fa-edit"></i></button>
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
                                            <p class="text-xs text-gray-400">ID: #8922</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">Fibra Pyme 300Mb</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger border border-danger/20">
                                        <span class="w-1.5 h-1.5 bg-danger rounded-full mr-1.5"></span> Corte
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium">S/ 150.00</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-primary transition-colors p-1"><i class="fas fa-eye"></i></button>
                                    <button class="text-gray-400 hover:text-secondary transition-colors p-1 ml-2"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100 flex justify-between items-center">
                    <p class="text-xs text-gray-500">Mostrando 1-10 de 142</p>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50"><i class="fas fa-chevron-left text-xs"></i></button>
                        <button class="w-8 h-8 rounded bg-primary text-white flex items-center justify-center text-xs font-bold">1</button>
                        <button class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-xs">2</button>
                        <button class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50"><i class="fas fa-chevron-right text-xs"></i></button>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">4. Formularios y Controles</h2>
            
            <div class="card-base p-8">
                <form onsubmit="return false;" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="label-prored">Nombre Completo</label>
                        <div class="relative">
                            <input type="text" class="input-prored pl-10" placeholder="Ej: Juan Pérez">
                            <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="label-prored">Plan Seleccionado</label>
                        <div class="relative">
                            <select class="input-prored appearance-none bg-white">
                                <option>Seleccione una opción...</option>
                                <option>Plan Residencial 50Mb</option>
                                <option>Plan Gamer 200Mb</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-700">Estado del Servicio</h4>
                                <p class="text-xs text-gray-500">Activa o suspende el internet del cliente</p>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle" id="toggle1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300"/>
                                <label for="toggle1" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-700">Notificaciones SMS</h4>
                                <p class="text-xs text-gray-500">Enviar recordatorios de pago</p>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle2" id="toggle2" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300" checked/>
                                <label for="toggle2" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-prored">Contrato Firmado / Documentos</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 hover:border-primary transition-colors cursor-pointer group">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-primary transition-colors mb-3"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                                        <span>Subir un archivo</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">o arrastrar y soltar</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, PNG, JPG hasta 10MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex gap-3 justify-end mt-4">
                        <button class="btn-outline">Cancelar</button>
                        <button class="btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">5. Widgets ISP (Internet & TV)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-base p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-3 h-3 bg-success rounded-full animate-ping absolute top-0 left-0 opacity-75"></div>
                            <div class="w-3 h-3 bg-success rounded-full relative"></div>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Servidor Principal</p>
                            <p class="text-xs text-gray-500">Uptime: 99.9%</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-primary"><i class="fas fa-ellipsis-v"></i></button>
                </div>

                <div class="card-base p-6 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <span class="bg-secondary text-white text-xs font-bold px-2 py-1 rounded mb-2 inline-block">MÁS VENDIDO</span>
                        <h3 class="text-xl font-bold text-gray-800">Fibra Gamer</h3>
                        <div class="flex items-baseline my-2">
                            <span class="text-3xl font-bold text-primary">200</span>
                            <span class="text-sm text-gray-500 ml-1">Mbps</span>
                        </div>
                        <ul class="text-sm text-gray-600 space-y-2 mb-4">
                            <li class="flex items-center"><i class="fas fa-check text-success mr-2"></i> Fibra Simétrica</li>
                            <li class="flex items-center"><i class="fas fa-check text-success mr-2"></i> TV Digital incluida</li>
                        </ul>
                        <button class="btn-outline w-full text-sm">Ver Detalles</button>
                    </div>
                </div>

                <div class="card-base p-6 bg-red-50 border-danger border">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tower-broadcast text-danger text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-danger">Avería Zona Norte</h4>
                            <p class="text-xs text-gray-600 mt-1">Nodo 4 sin respuesta. Técnicos notificados.</p>
                            <button class="mt-3 text-xs bg-white text-danger border border-red-200 px-3 py-1 rounded hover:bg-danger hover:text-white transition-colors">
                                Ver Mapa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-secondary pl-3">6. Sistema de Modales</h2>
            
            <div class="flex gap-4">
                <button onclick="document.getElementById('modalDemo').classList.remove('hidden')" class="btn-primary">
                    Abrir Modal de Ejemplo
                </button>
            </div>

            <div id="modalDemo" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Confirmar Acción</h3>
                        <button onclick="document.getElementById('modalDemo').classList.add('hidden')" class="text-gray-400 hover:text-danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-warning/20 flex items-center justify-center text-warning flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <p class="text-gray-600 text-sm">¿Estás seguro que deseas suspender el servicio a este cliente? Esta acción generará un cargo de reconexión.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0 flex gap-3 justify-end">
                        <button onclick="document.getElementById('modalDemo').classList.add('hidden')" class="btn-outline">Cancelar</button>
                        <button class="btn-danger">Sí, suspender</button>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script>
        // Configuración de Gráficos con colores ProRed
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Line Chart
            const ctxLine = document.getElementById('lineChart');
            if(ctxLine) {
                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
                        datasets: [{
                            label: 'Tráfico (Mbps)',
                            data: [450, 520, 480, 600, 750, 800, 720],
                            borderColor: '#005FA2', // Azul Primary
                            backgroundColor: 'rgba(0, 95, 162, 0.1)',
                            borderWidth: 2,
                            tension: 0.4, // Curvas suaves
                            fill: true,
                            pointRadius: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#005FA2'
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

            // 2. Doughnut Chart
            const ctxDoughnut = document.getElementById('doughnutChart');
            if(ctxDoughnut) {
                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Residencial', 'Gamer', 'Pyme', 'TV Only'],
                        datasets: [{
                            data: [55, 25, 15, 5],
                            backgroundColor: [
                                '#005FA2', // Primary
                                '#E58E21', // Secondary
                                '#10B981', // Success
                                '#cbd5e1'  // Gray
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%', // Dona más delgada y moderna
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>