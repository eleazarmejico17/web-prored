<?php
// --- MOCK DATA: Simulación de Base de Datos ---

// Estado actual
$estado_actual = [
    'deuda_total' => 0.00,
    'proximo_vencimiento' => '05/03/2026',
    'estado_servicio' => 'AL_DIA'
];

// Historial Extendido para la Gráfica (6 meses)
// En producción, esto vendría de una consulta SQL ordenada por fecha ASC
$historial_facturacion = [
    [
        'id' => 'INV-2025-010',
        'periodo' => 'Octubre 2025',
        'fecha_vencimiento' => '05/10/2025',
        'estado' => 'PAGADO',
        'total' => 89.90,
        'retraso_dias' => 0,
        'items' => [['concepto' => 'Plan Fibra 100 Mbps', 'monto' => 89.90]]
    ],
    [
        'id' => 'INV-2025-011',
        'periodo' => 'Noviembre 2025',
        'fecha_vencimiento' => '05/11/2025',
        'estado' => 'PAGADO',
        'total' => 89.90,
        'retraso_dias' => 0,
        'items' => [['concepto' => 'Plan Fibra 100 Mbps', 'monto' => 89.90]]
    ],
    [
        'id' => 'INV-2025-012',
        'periodo' => 'Diciembre 2025',
        'fecha_vencimiento' => '05/12/2025',
        'estado' => 'PAGADO',
        'total' => 89.90,
        'retraso_dias' => 0,
        'items' => [['concepto' => 'Plan Fibra 100 Mbps', 'monto' => 89.90]]
    ],
    [
        'id' => 'INV-2026-001',
        'periodo' => 'Enero 2026',
        'fecha_vencimiento' => '05/01/2026',
        'estado' => 'PAGADO',
        'total' => 119.90,
        'retraso_dias' => 0, // Con extra
        'items' => [['concepto' => 'Plan Fibra 100 Mbps', 'monto' => 89.90], ['concepto' => 'Instalación', 'monto' => 30.00]]
    ],
    [
        'id' => 'INV-2026-002',
        'periodo' => 'Febrero 2026',
        'fecha_vencimiento' => '05/02/2026',
        'estado' => 'PAGADO',
        'total' => 95.00,
        'retraso_dias' => 3, // Con mora
        'items' => [['concepto' => 'Plan Fibra 100 Mbps', 'monto' => 89.90], ['concepto' => 'Mora', 'monto' => 5.10]]
    ]
];

// Invertimos el array para la tabla (más reciente primero), pero usamos el original para la gráfica
$tabla_historial = array_reverse($historial_facturacion);
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div
            class="card-base p-6 bg-gradient-to-br from-white to-blue-50 border-t-4 <?= $estado_actual['deuda_total'] > 0 ? 'border-danger' : 'border-success' ?> flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-2">Tu Estado Actual</h2>
                <?php if ($estado_actual['deuda_total'] > 0): ?>
                    <p class="text-sm text-gray-600 mb-4">Tienes un saldo pendiente. Realiza tu pago para evitar cortes.</p>
                    <div class="text-center py-4 bg-white rounded-xl border border-red-100 shadow-sm mb-4">
                        <p class="text-xs text-gray-400 uppercase font-bold">Total a Pagar</p>
                        <p class="text-4xl font-bold text-danger">S/ <?= number_format($estado_actual['deuda_total'], 2) ?>
                        </p>
                    </div>
                    <button onclick="window.location.href='?pagina=pagos&action=pagar'"
                        class="btn-primary w-full py-3 shadow-lg animate-pulse justify-center">
                        <i class="fas fa-credit-card mr-2"></i> Pagar Ahora
                    </button>
                <?php else: ?>
                    <p class="text-sm text-gray-600 mb-6">¡Excelente! No tienes deudas pendientes por el momento.</p>
                    <div class="flex flex-col items-center justify-center flex-grow py-4">
                        <div
                            class="w-20 h-20 bg-green-100 text-success rounded-full flex items-center justify-center text-4xl mb-4 shadow-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Estás al día</p>
                        <p class="text-xs text-gray-500">Próx. vencimiento: <?= $estado_actual['proximo_vencimiento'] ?></p>
                    </div>
                    <button class="btn-secondary w-full justify-center opacity-50 cursor-not-allowed" disabled>
                        No hay deuda
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-base p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-lg">Evolución de Pagos (Últimos 6 meses)</h3>
                <div class="flex gap-2 text-xs">
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-success"></span> Puntual
                    </div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-warning"></span> Con
                        Retraso</div>
                </div>
            </div>
            <div class="h-64 w-full relative">
                <canvas id="pagosChart"></canvas>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="bg-white p-1 rounded-lg border border-gray-200 inline-flex shadow-sm">
            <button
                class="px-4 py-1.5 text-xs font-bold rounded bg-primary text-white shadow-sm transition-all">2026</button>
            <button
                class="px-4 py-1.5 text-xs font-bold rounded text-gray-500 hover:bg-gray-50 transition-all">2025</button>
        </div>

        <button onclick="alert('Generando reporte PDF completo...')"
            class="text-primary hover:text-primary-dark text-sm font-medium flex items-center gap-2 transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-primary">
            <i class="fas fa-file-download"></i> Descargar Historial Completo
        </button>
    </div>

    <div class="card-base overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-6 py-4">Periodo</th>
                        <th class="px-6 py-4">Vencimiento</th>
                        <th class="px-6 py-4">Detalle</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Recibo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php foreach ($tabla_historial as $factura): ?>
                        <?php
                        $tiene_extras = count($factura['items']) > 1;
                        $es_tardio = $factura['retraso_dias'] > 0;

                        if ($factura['estado'] === 'PAGADO') {
                            if ($es_tardio) {
                                $estado_badge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-orange-100 text-orange-600 border border-orange-200"><i class="fas fa-clock"></i> Pagado (+' . $factura['retraso_dias'] . 'd)</span>';
                            } else {
                                $estado_badge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-600 border border-green-200"><i class="fas fa-check"></i> Puntual</span>';
                            }
                        } else {
                            $estado_badge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-600 border border-red-200"><i class="fas fa-exclamation"></i> Pendiente</span>';
                        }
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800"><?= $factura['periodo'] ?></p>
                                <p class="text-xs text-gray-400 font-mono mt-0.5"><?= $factura['id'] ?></p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= $factura['fecha_vencimiento'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($tiene_extras): ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-50 text-blue-600 text-xs font-medium border border-blue-100"
                                        title="Ver detalle para desglosar">
                                        <i class="fas fa-plus-circle"></i> Incluye Extras
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">Mensualidad Base</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?= $estado_badge ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-800">S/ <?= number_format($factura['total'], 2) ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openInvoiceModal(<?= json_encode($factura) ?>)'
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-primary hover:text-white text-gray-500 transition-colors"
                                        title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="downloadPDF('<?= $factura['id'] ?>')"
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white text-gray-500 transition-colors"
                                        title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="invoiceModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95" id="modalPanel">
        <div class="relative bg-primary text-white p-6 rounded-t-xl overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10"><i class="fas fa-file-invoice-dollar text-6xl"></i></div>
            <h3 class="font-bold text-lg relative z-10">Detalle de Facturación</h3>
            <p class="text-sm text-blue-100 relative z-10" id="m_periodo">--</p>
            <button onclick="closeInvoiceModal()" class="absolute top-4 right-4 text-white/70 hover:text-white z-20"><i
                    class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                <div class="text-xs text-gray-500">
                    <p class="uppercase font-bold">N° Recibo</p>
                    <p class="font-mono text-gray-800" id="m_id">--</p>
                </div>
                <div class="text-xs text-gray-500 text-right">
                    <p class="uppercase font-bold">Vence</p>
                    <p class="font-medium text-gray-800" id="m_fecha">--</p>
                </div>
            </div>
            <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Conceptos Facturados</h4>
            <div id="m_items_container" class="space-y-2 mb-6"></div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-700">Total Facturado</span>
                <span class="font-bold text-xl text-primary" id="m_total">S/ --</span>
            </div>
        </div>
        <div class="p-4 bg-gray-50 rounded-b-xl border-t border-gray-100 text-center">
            <button onclick="alert('Descargando...')" class="text-sm text-primary font-bold hover:underline"><i
                    class="fas fa-download mr-1"></i> Descargar Comprobante</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- DATOS PARA LA GRÁFICA (Pasados desde PHP) ---
    const historialData = <?= json_encode($historial_facturacion) ?>;

    // Preparar arrays para Chart.js
    const labels = historialData.map(h => h.periodo);
    const dataMontos = historialData.map(h => h.total);

    // Lógica de colores (Verde si es puntual, Naranja si es tardío/mora)
    const backgroundColors = historialData.map(h => h.retraso_dias > 0 ? '#F59E0B' : '#10B981');
    const borderColors = historialData.map(h => h.retraso_dias > 0 ? '#D97706' : '#059669');

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('pagosChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monto Pagado (S/)',
                        data: dataMontos,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += 'S/ ' + context.parsed.y.toFixed(2);
                                    }
                                    return label;
                                },
                                afterLabel: function (context) {
                                    // Agregar info de retraso en el tooltip
                                    const item = historialData[context.dataIndex];
                                    if (item.retraso_dias > 0) return `⚠️ Retraso de ${item.retraso_dias} días`;
                                    return '✅ Pago Puntual';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: { callback: function (value) { return 'S/ ' + value; } }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });

    // --- LÓGICA MODAL (Igual que antes) ---
    const modal = document.getElementById('invoiceModal');
    const panel = document.getElementById('modalPanel');

    function openInvoiceModal(factura) {
        document.getElementById('m_periodo').textContent = factura.periodo;
        document.getElementById('m_id').textContent = factura.id;
        document.getElementById('m_fecha').textContent = factura.fecha_vencimiento;
        document.getElementById('m_total').textContent = 'S/ ' + parseFloat(factura.total).toFixed(2);

        const container = document.getElementById('m_items_container');
        container.innerHTML = factura.items.map(item => `
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">${item.concepto}</span>
                <span class="font-medium text-gray-800">S/ ${parseFloat(item.monto).toFixed(2)}</span>
            </div>
        `).join('');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeInvoiceModal() {
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function downloadPDF(id) { alert(`Descargando recibo: ${id}.pdf`); }

    modal.addEventListener('click', (e) => { if (e.target === modal) closeInvoiceModal(); });
</script>