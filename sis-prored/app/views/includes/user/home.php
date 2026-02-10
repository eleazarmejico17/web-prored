<?php
// --- SIMULACIÓN DE DATOS (MOCK DATA) ---
// En producción, esto vendría de tu base de datos:
// SELECT * FROM servicio WHERE id_cliente = ?
$servicios = [
    [
        'id' => 1001,
        'plan' => 'Fibra Óptica 100 Mbps',
        'velocidad' => '100 Mbps / 100 Mbps',
        'direccion' => 'Av. Giraldez 550, Huancayo',
        'estado' => 'ACTIVO', // ACTIVO, SUSPENDIDO, CORTE
        'fecha_corte' => '05/03/2026',
        'mensualidad' => 89.90,
        'deuda_pendiente' => false, // Cambiar a true para simular deuda
        'consumo_gb' => 324.5, // GB consumidos este mes
        'dispositivos_tv' => [
            ['nombre' => 'Deco Sala', 'tipo' => '4K Box'],
            ['nombre' => 'Deco Dormitorio', 'tipo' => 'HD Box']
        ],
        'cargos_extra' => [
            ['concepto' => 'Alquiler Deco Adicional', 'monto' => 10.00],
            ['concepto' => 'IP Pública Estática', 'monto' => 15.00]
        ]
    ]
];

// Historial de pagos reciente
$ultimos_pagos = [
    ['fecha' => '05/02/2026', 'monto' => '114.90', 'metodo' => 'Yape', 'codigo' => '123456', 'estado' => 'VALIDADO'],
    ['fecha' => '05/01/2026', 'monto' => '114.90', 'metodo' => 'Plin', 'codigo' => '987654', 'estado' => 'VALIDADO'],
    ['fecha' => '05/12/2025', 'monto' => '114.90', 'metodo' => 'Transferencia BCP', 'codigo' => '456123', 'estado' => 'VALIDADO']
];

// Saludo según la hora
$hora = date('G');
$saludo = ($hora < 12) ? 'Buenos días' : (($hora < 18) ? 'Buenas tardes' : 'Buenas noches');
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <?= $saludo ?>,
                <?= explode(' ', $usuario_nombre)[0] ?> 👋
            </h1>
            <p class="text-sm text-gray-500">Aquí tienes el resumen de tus servicios y facturación.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.location.href='?pagina=ayuda'" class="btn-secondary px-4 py-2 text-sm shadow-sm">
                <i class="fas fa-life-ring mr-2"></i> Reportar Avería
            </button>
        </div>
    </div>

    <?php foreach ($servicios as $servicio): ?>
        <?php
        // Cálculos rápidos para la vista
        $total_extras = array_sum(array_column($servicio['cargos_extra'], 'monto'));
        $total_mes = $servicio['mensualidad'] + $total_extras;

        // Colores de estado
        $estado_bg = $servicio['estado'] === 'ACTIVO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
        $estado_icon = $servicio['estado'] === 'ACTIVO' ? 'fa-check-circle' : 'fa-exclamation-circle';
        ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="card-base overflow-hidden relative group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex gap-3 items-center">
                                <div class="bg-primary/10 p-3 rounded-lg text-primary">
                                    <i class="fas fa-wifi text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">
                                        <?= $servicio['plan'] ?>
                                    </h3>
                                    <p class="text-xs text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i>
                                        <?= $servicio['direccion'] ?>
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 <?= $estado_bg ?>">
                                <i class="fas <?= $estado_icon ?>"></i>
                                <?= $servicio['estado'] ?>
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-t border-gray-100">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Velocidad</p>
                                <p class="font-bold text-gray-700">
                                    <?= $servicio['velocidad'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Código Cliente</p>
                                <p class="font-mono text-gray-700">
                                    <?= $servicio['id'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Cierre de Ciclo</p>
                                <p class="text-gray-700">Día 05 de cada mes</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">IP Asignada</p>
                                <p class="font-mono text-gray-500 text-xs mt-0.5">Dinámica (CGNAT)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-base p-6 border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-secondary"></i> Facturación del Mes
                    </h3>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Mensualidad Base (
                                    <?= date('M Y') ?>)
                                </span>
                                <span class="font-medium">S/
                                    <?= number_format($servicio['mensualidad'], 2) ?>
                                </span>
                            </div>

                            <?php if (!empty($servicio['cargos_extra'])): ?>
                                <?php foreach ($servicio['cargos_extra'] as $cargo): ?>
                                    <div class="flex justify-between text-sm text-gray-500 pl-2 border-l-2 border-gray-200">
                                        <span>
                                            <?= $cargo['concepto'] ?>
                                        </span>
                                        <span>S/
                                            <?= number_format($cargo['monto'], 2) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="border-t border-gray-200 my-2"></div>

                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">Total a Pagar</p>
                                    <?php if ($servicio['deuda_pendiente']): ?>
                                        <p class="text-xs text-red-500 font-bold"><i
                                                class="fas fa-exclamation-triangle mr-1"></i> Vencido</p>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-400">Vence el
                                            <?= $servicio['fecha_corte'] ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <span class="text-2xl font-bold text-primary">S/
                                    <?= number_format($total_mes, 2) ?>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                            <button onclick="window.location.href='?pagina=pagos&action=reportar'"
                                class="btn-primary w-full shadow-lg">
                                <i class="fas fa-qrcode mr-2"></i> Pagar / Reportar Pago
                            </button>
                            <button onclick="alert('Descargando recibo PDF...')"
                                class="btn-outline w-full justify-center bg-white border border-gray-200 text-gray-600 hover:text-primary hover:border-primary">
                                <i class="fas fa-download mr-2"></i> Descargar Recibo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="card-base p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Consumo Mensual</h4>

                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                    Internet
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-blue-600">
                                    <?= $servicio['consumo_gb'] ?> GB
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-100">
                            <div style="width:65%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 animate-pulse">
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 text-center"><i class="fas fa-infinity mr-1"></i> Tienes datos
                            ilimitados</p>
                    </div>
                </div>

                <div class="card-base p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-3 flex justify-between items-center">
                        <span>TVs Registradas</span>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px]">
                            <?= count($servicio['dispositivos_tv']) ?> disp.
                        </span>
                    </h4>

                    <div class="space-y-3">
                        <?php foreach ($servicio['dispositivos_tv'] as $tv): ?>
                            <div class="flex items-center gap-3 p-2 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="w-8 h-8 rounded bg-white flex items-center justify-center text-primary shadow-sm">
                                    <i class="fas fa-tv text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700">
                                        <?= $tv['nombre'] ?>
                                    </p>
                                    <p class="text-[10px] text-gray-400">
                                        <?= $tv['tipo'] ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-base p-0 overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h4 class="text-xs font-bold text-gray-500 uppercase">Últimos Pagos</h4>
                        <a href="?pagina=pagos" class="text-[10px] text-primary hover:underline">Ver todos</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <?php foreach ($ultimos_pagos as $pago): ?>
                            <div class="p-3 flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="text-gray-300">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-700">
                                            <?= $pago['fecha'] ?>
                                        </p>
                                        <p class="text-[10px] text-gray-400">
                                            <?= $pago['metodo'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-700">S/
                                        <?= $pago['monto'] ?>
                                    </p>
                                    <span
                                        class="text-[9px] text-green-600 bg-green-50 px-1.5 py-0.5 rounded border border-green-100">
                                        <?= $pago['estado'] ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

    <div class="bg-gradient-to-r from-primary to-blue-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-10 translate-y-10">
            <i class="fas fa-tachometer-alt text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h3 class="font-bold text-xl mb-2">¿Necesitas más velocidad?</h3>
            <p class="text-blue-100 text-sm mb-4 max-w-md">Mejora tu plan hoy mismo y obtén un 50% de descuento en la
                instalación de un repetidor Mesh.</p>
            <button onclick="window.location.href='?pagina=ayuda'"
                class="bg-white text-primary px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-100 transition-colors shadow-md">
                Consultar Upgrade
            </button>
        </div>
    </div>

</div>