<?php
// --- MOCK DATA: Simulación de Base de Datos ---

// Datos para la Gráfica
$stats_reconexion = [
    'labels' => ['Diciembre', 'Enero', 'Febrero'],
    'data' => [45, 62, 28]
];

// Lista de Usuarios con Servicio Cortado/Suspendido (Datos enriquecidos)
$cortes_activos = [
    [
        'id_servicio' => 1005,
        'cliente' => 'Juan Pérez',
        'dni' => '12345678',
        'direccion' => 'Av. Giraldez 550, Huancayo',
        'plan' => 'Fibra 100 Mbps',
        'ip' => '10.20.30.40',
        'consumo' => '450 GB',
        'mensualidad' => 'S/ 89.90',
        'estado' => 'SUSPENDIDO',
        'fecha_corte' => '10/02/2026',
        'deuda_pendiente' => 0.00, // Listo para reconectar
        'telefonos' => [
            ['numero' => '987654321', 'tipo' => 'Principal (Titular)', 'wsp' => true],
            ['numero' => '064234567', 'tipo' => 'Casa', 'wsp' => false]
        ]
    ],
    [
        'id_servicio' => 2040,
        'cliente' => 'Bodega El Sol',
        'dni' => '20601234567',
        'direccion' => 'Jr. Puno 123, El Tambo',
        'plan' => 'Fibra Pyme 50',
        'ip' => '10.20.30.41',
        'consumo' => '120 GB',
        'mensualidad' => 'S/ 60.00',
        'estado' => 'CORTADO',
        'fecha_corte' => '01/02/2026',
        'deuda_pendiente' => 150.00, // Debe pagar
        'telefonos' => [
            ['numero' => '911222333', 'tipo' => 'Contacto Ventas', 'wsp' => true]
        ]
    ],
    [
        'id_servicio' => 3099,
        'cliente' => 'Carlos Ruiz',
        'dni' => '40506070',
        'direccion' => 'Calle Real 890, Chilca',
        'plan' => 'Fibra Home 200',
        'ip' => '10.20.30.42',
        'consumo' => '890 GB',
        'mensualidad' => 'S/ 120.00',
        'estado' => 'SUSPENDIDO',
        'fecha_corte' => '11/02/2026',
        'deuda_pendiente' => 0.00,
        'telefonos' => [
            ['numero' => '955443322', 'tipo' => 'Móvil Personal', 'wsp' => true],
            ['numero' => '999888777', 'tipo' => 'Esposa', 'wsp' => true]
        ]
    ]
];
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reconexión de Servicios</h1>
            <p class="text-sm text-gray-500">Gestión de reactivación y contacto con clientes en mora.</p>
        </div>

        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Buscar cliente..." class="input-prored pl-9 py-2 text-sm">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-base p-6 md:col-span-1 bg-gradient-to-br from-green-500 to-emerald-600 text-white">
            <h3 class="font-bold text-lg mb-1">Reconexiones Hoy</h3>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-bold">12</span>
                <span class="text-xs bg-white/20 px-2 py-1 rounded mb-1"><i class="fas fa-arrow-up"></i> +3</span>
            </div>
            <p class="text-xs text-green-100 mt-4 opacity-80">Eficiencia: 98%</p>
        </div>
        <div class="card-base p-6 md:col-span-3">
            <h3 class="font-bold text-gray-800 mb-4">Recuperación de Cartera (3 Meses)</h3>
            <div class="h-40 w-full"><canvas id="reconexionChart"></canvas></div>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Servicios Cortados</h3>
            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded font-bold"><?= count($cortes_activos) ?>
                Pendientes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Cliente / Servicio</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-center">Deuda</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php foreach ($cortes_activos as $c): ?>
                        <tr class="hover:bg-gray-50 transition-colors group" id="row-<?= $c['id_servicio'] ?>">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800"><?= $c['cliente'] ?></p>
                                <p class="text-xs text-gray-500"><?= $c['direccion'] ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($c['estado'] === 'SUSPENDIDO'): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">Suspendido</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">Corte
                                        Físico</span>
                                <?php endif; ?>
                                <p class="text-[10px] text-gray-400 mt-1">Desde: <?= $c['fecha_corte'] ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($c['deuda_pendiente'] > 0): ?>
                                    <span class="text-red-600 font-bold">S/
                                        <?= number_format($c['deuda_pendiente'], 2) ?></span>
                                <?php else: ?>
                                    <span class="text-green-600 font-bold"><i class="fas fa-check"></i> Pagado</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick='openDetailModal(<?= json_encode($c) ?>)'
                                        class="btn-secondary px-3 py-1.5 text-xs shadow-none"
                                        title="Ver información completa y contactar">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </button>

                                    <?php if ($c['deuda_pendiente'] == 0): ?>
                                        <button onclick="confirmarReconexion(<?= $c['id_servicio'] ?>, '<?= $c['cliente'] ?>')"
                                            class="btn-primary bg-green-600 hover:bg-green-700 px-3 py-1.5 text-xs shadow-none">
                                            <i class="fas fa-plug"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="detailModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="detailPanel">

        <div class="bg-primary px-6 py-4 rounded-t-xl text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg">Detalle del Servicio</h3>
                <p class="text-xs text-blue-100 opacity-80" id="m_header_id">#000</p>
            </div>
            <button onclick="closeModal('detailModal')" class="hover:text-gray-200"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2"><i class="fas fa-user mr-1"></i>
                            Titular</h4>
                        <p class="font-bold text-gray-800 text-lg leading-tight" id="m_cliente">--</p>
                        <p class="text-sm text-gray-600 mt-1">DNI/RUC: <span id="m_dni"
                                class="font-mono font-medium">--</span></p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2"><i
                                class="fas fa-network-wired mr-1"></i> Servicio</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Plan:</span>
                                <span class="font-bold text-primary" id="m_plan">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dirección:</span>
                                <span class="text-gray-800 text-right text-xs" id="m_direccion">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">IP Asignada:</span>
                                <span class="font-mono text-gray-800" id="m_ip">--</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                            <p class="text-[10px] text-blue-500 font-bold uppercase">Consumo Mes</p>
                            <p class="font-bold text-gray-800" id="m_consumo">--</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg text-center">
                            <p class="text-[10px] text-green-600 font-bold uppercase">Mensualidad</p>
                            <p class="font-bold text-gray-800" id="m_mensualidad">--</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col h-full">

                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Gestión de Contacto</h4>

                    <div
                        class="bg-white border-2 border-primary/10 rounded-xl p-5 shadow-sm flex-grow flex flex-col justify-between">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Seleccione número para
                                contactar:</label>

                            <div class="relative mb-6">
                                <select id="m_selector_tel"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold text-gray-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary appearance-none cursor-pointer transition-shadow"
                                    onchange="updateContactButtons()">
                                </select>
                                <div class="absolute left-3 top-3.5 text-gray-400"><i class="fas fa-phone"></i></div>
                                <div class="absolute right-3 top-3.5 text-gray-400 pointer-events-none"><i
                                        class="fas fa-chevron-down text-xs"></i></div>
                            </div>

                            <div class="grid grid-cols-2 gap-3" id="contact_buttons_container">
                                <a id="btn_wsp" href="#" target="_blank"
                                    class="flex flex-col items-center justify-center p-3 rounded-lg border border-green-200 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all group disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i
                                        class="fab fa-whatsapp text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold">WhatsApp</span>
                                </a>

                                <a id="btn_call" href="#"
                                    class="flex flex-col items-center justify-center p-3 rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all group">
                                    <i
                                        class="fas fa-phone-alt text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold">Llamar</span>
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-400 mb-2 text-center">Estado Financiero</p>
                            <div id="m_estado_deuda" class="text-center p-2 rounded-lg font-bold text-sm">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-between items-center border-t border-gray-200">
            <span class="text-xs text-gray-400 italic">Última sinc. con Winbox: Hace 5 min</span>
            <button onclick="closeModal('detailModal')" class="btn-outline">Cerrar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- GRÁFICA ---
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('reconexionChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($stats_reconexion['labels']) ?>,
                    datasets: [{
                        label: 'Reconexiones',
                        data: <?= json_encode($stats_reconexion['data']) ?>,
                        borderColor: '#10B981', backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderWidth: 2, tension: 0.4, fill: true, pointRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });
        }
    });

    // --- VARIABLES GLOBALES PARA MODAL ---
    let currentClientPhones = [];

    // --- ABRIR MODAL DETALLE ---
    function openDetailModal(data) {
        // 1. Llenar Información Básica
        document.getElementById('m_header_id').textContent = 'Servicio #' + data.id_servicio;
        document.getElementById('m_cliente').textContent = data.cliente;
        document.getElementById('m_dni').textContent = data.dni;
        document.getElementById('m_plan').textContent = data.plan;
        document.getElementById('m_direccion').textContent = data.direccion;
        document.getElementById('m_ip').textContent = data.ip;
        document.getElementById('m_consumo').textContent = data.consumo;
        document.getElementById('m_mensualidad').textContent = data.mensualidad;

        // 2. Llenar Estado Financiero
        const estadoDiv = document.getElementById('m_estado_deuda');
        if (data.deuda_pendiente > 0) {
            estadoDiv.className = "text-center p-2 rounded-lg font-bold text-sm bg-red-100 text-red-600";
            estadoDiv.innerHTML = `<i class="fas fa-times-circle mr-1"></i> Deuda: S/ ${parseFloat(data.deuda_pendiente).toFixed(2)}`;
        } else {
            estadoDiv.className = "text-center p-2 rounded-lg font-bold text-sm bg-green-100 text-green-600";
            estadoDiv.innerHTML = `<i class="fas fa-check-circle mr-1"></i> Al día (Listo para activar)`;
        }

        // 3. Llenar Selector de Teléfonos
        currentClientPhones = data.telefonos;
        const select = document.getElementById('m_selector_tel');
        select.innerHTML = ''; // Limpiar

        data.telefonos.forEach((tel, index) => {
            const option = document.createElement('option');
            option.value = index; // Usamos el índice para buscar en el array luego
            option.text = `${tel.numero} - ${tel.tipo}`;
            select.appendChild(option);
        });

        // 4. Actualizar Botones con el primer teléfono
        updateContactButtons();

        // 5. Mostrar Modal
        openModal('detailModal', 'detailPanel');
    }

    // --- ACTUALIZAR BOTONES DE CONTACTO ---
    function updateContactButtons() {
        const index = document.getElementById('m_selector_tel').value;
        const telData = currentClientPhones[index];
        const btnWsp = document.getElementById('btn_wsp');
        const btnCall = document.getElementById('btn_call');

        if (telData) {
            // Configurar WhatsApp
            if (telData.wsp) {
                btnWsp.href = `https://wa.me/51${telData.numero}?text=Hola, nos comunicamos de ProRed respecto a su servicio.`;
                btnWsp.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            } else {
                btnWsp.href = '#';
                btnWsp.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            }

            // Configurar Llamada
            btnCall.href = `tel:${telData.numero}`;
        }
    }

    // --- CONFIRMAR RECONEXIÓN (Igual que antes pero en tabla) ---
    function confirmarReconexion(id, nombre) {
        if (confirm(`¿Desea reactivar inmediatamente el servicio de ${nombre}?`)) {
            // Simulación
            const row = document.getElementById('row-' + id);
            if (row) {
                row.classList.add('bg-green-50');
                setTimeout(() => row.remove(), 800);
            }
            alert("✅ Servicio reactivado y notificado al cliente.");
        }
    }

    // --- UTILS MODAL ---
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

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const panel = modal.querySelector('div[id$="Panel"]');
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>