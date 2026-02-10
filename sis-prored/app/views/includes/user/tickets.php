<?php
// --- MOCK DATA ---

// Servicios del Cliente
$servicios = [
    ['id' => 1001, 'plan' => 'Fibra 100 Mbps', 'direccion' => 'Av. Giraldez 550'],
    ['id' => 1002, 'plan' => 'Fibra 50 Mbps', 'direccion' => 'Jr. Puno 123']
];

// Teléfonos Registrados (Perfil)
$telefonos = [
    ['id' => 1, 'numero' => '987654321', 'tipo' => 'Principal'],
    ['id' => 2, 'numero' => '912345678', 'tipo' => 'Casa']
];

// Historial de Tickets
$tickets = [
    [
        'id' => 'TCK-4920',
        'servicio' => 'Fibra 100 Mbps - Av. Giraldez 550',
        'asunto' => 'Lentitud en las noches',
        'fecha' => '10/02/2026',
        'estado' => 'ABIERTO', // ABIERTO, EN_PROCESO, RESUELTO, CERRADO
        'ultima_act' => 'Hace 2 horas'
    ],
    [
        'id' => 'TCK-4850',
        'servicio' => 'Fibra 50 Mbps - Jr. Puno 123',
        'asunto' => 'Cambio de clave WiFi',
        'fecha' => '15/01/2026',
        'estado' => 'CERRADO',
        'ultima_act' => '16/01/2026'
    ],
    [
        'id' => 'TCK-3200',
        'servicio' => 'Fibra 100 Mbps - Av. Giraldez 550',
        'asunto' => 'Sin servicio (Corte de luz zona)',
        'fecha' => '20/11/2025',
        'estado' => 'RESUELTO',
        'ultima_act' => '20/11/2025'
    ]
];
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Soporte Técnico</h1>
            <p class="text-sm text-gray-500">Gestiona tus incidencias y reporta problemas con tu servicio.</p>
        </div>
        <button onclick="openTicketModal()"
            class="btn-primary shadow-lg hover:shadow-xl transition-all transform active:scale-95 px-6 py-2.5">
            <i class="fas fa-plus-circle mr-2"></i> Crear Nuevo Ticket
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card-base p-4 border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Tickets Abiertos</p>
                <p class="text-2xl font-bold text-gray-800">1</p>
            </div>
            <div class="bg-blue-50 text-blue-500 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
        <div class="card-base p-4 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Solucionados (Mes)</p>
                <p class="text-2xl font-bold text-gray-800">2</p>
            </div>
            <div class="bg-green-50 text-green-500 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="card-base p-4 border-l-4 border-gray-300 flex items-center justify-between bg-gray-50 opacity-75">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Tiempo Promedio</p>
                <p class="text-xl font-bold text-gray-600">4h 15m</p>
            </div>
            <div
                class="bg-white text-gray-400 w-10 h-10 rounded-full flex items-center justify-center border border-gray-200">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Historial de Incidencias</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Ticket / Fecha</th>
                        <th class="px-6 py-4">Servicio Afectado</th>
                        <th class="px-6 py-4">Problema</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php foreach ($tickets as $t): ?>
                        <?php
                        // DEFINICIÓN DE COLORES E ICONOS (COMPATIBLE CON PHP < 8.0)
                        $colores = [
                            'ABIERTO' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'EN_PROCESO' => 'bg-orange-100 text-orange-700 border-orange-200',
                            'RESUELTO' => 'bg-green-100 text-green-700 border-green-200',
                            'CERRADO' => 'bg-gray-100 text-gray-600 border-gray-200'
                        ];

                        $iconos = [
                            'ABIERTO' => 'fa-envelope-open-text',
                            'EN_PROCESO' => 'fa-tools',
                            'RESUELTO' => 'fa-check',
                            'CERRADO' => 'fa-archive'
                        ];

                        $badgeColor = isset($colores[$t['estado']]) ? $colores[$t['estado']] : 'bg-gray-100';
                        $icon = isset($iconos[$t['estado']]) ? $iconos[$t['estado']] : 'fa-circle';
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-bold text-primary block"><?php echo $t['id']; ?></span>
                                <span class="text-xs text-gray-400"><?php echo $t['fecha']; ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-800 font-medium"><?php echo $t['servicio']; ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="truncate max-w-xs"><?php echo $t['asunto']; ?></p>
                                <p class="text-[10px] text-gray-400">Act: <?php echo $t['ultima_act']; ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badgeColor; ?>">
                                    <i class="fas <?php echo $icon; ?> mr-1.5"></i> <?php echo $t['estado']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    class="text-gray-400 hover:text-primary transition-colors p-2 rounded-full hover:bg-blue-50"
                                    title="Ver Conversación">
                                    <i class="fas fa-comments"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="ticketModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="modalPanel">

        <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-primary text-white rounded-t-xl">
            <h3 class="font-bold text-lg"><i class="fas fa-headset mr-2"></i> Nuevo Reporte</h3>
            <button onclick="closeTicketModal()" class="text-white/70 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form onsubmit="submitTicket(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scrollbar">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Servicio Afectado</label>
                <div class="relative">
                    <select class="input-prored bg-gray-50 appearance-none" required>
                        <option value="">Seleccione el servicio...</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['plan']; ?> -
                                <?php echo $s['direccion']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipo de Problema</label>
                    <select class="input-prored bg-gray-50" required>
                        <option>Internet Lento</option>
                        <option>Sin Conexión (LOS)</option>
                        <option>Intermitencia</option>
                        <option>Problema Wifi</option>
                        <option>Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ocurre Desde</label>
                    <input type="datetime-local" class="input-prored" value="<?php echo date('Y-m-d\TH:i'); ?>"
                        required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detalle del Problema</label>
                <textarea class="input-prored h-24 resize-none"
                    placeholder="Describa brevemente qué sucede (ej: las luces del router están rojas...)"
                    required></textarea>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <label class="block text-xs font-bold text-primary uppercase mb-3">
                    <i class="fas fa-phone-alt mr-1"></i> ¿A qué número te contactamos?
                </label>

                <div class="space-y-2">
                    <?php foreach ($telefonos as $tel): ?>
                        <label
                            class="flex items-center p-2 bg-white rounded border border-gray-200 cursor-pointer hover:border-primary transition-colors">
                            <input type="radio" name="contacto" value="<?php echo $tel['numero']; ?>"
                                class="text-primary focus:ring-primary h-4 w-4" checked onclick="toggleNewNumber(false)">
                            <span class="ml-3 text-sm font-medium text-gray-700"><?php echo $tel['numero']; ?> <span
                                    class="text-xs text-gray-400">(<?php echo $tel['tipo']; ?>)</span></span>
                        </label>
                    <?php endforeach; ?>

                    <label
                        class="flex items-center p-2 bg-white rounded border border-gray-200 cursor-pointer hover:border-primary transition-colors">
                        <input type="radio" name="contacto" value="new" class="text-primary focus:ring-primary h-4 w-4"
                            onclick="toggleNewNumber(true)">
                        <span class="ml-3 text-sm font-medium text-gray-700">Otro número (Temporal)</span>
                    </label>

                    <div id="newNumberInput" class="hidden mt-2 pl-7 animate-fade-in-up">
                        <input type="tel" placeholder="Ingrese el número de contacto..."
                            class="input-prored bg-white border-blue-300 focus:border-blue-500 placeholder-gray-400">
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="button" onclick="closeTicketModal()"
                    class="flex-1 btn-outline justify-center">Cancelar</button>
                <button type="submit" class="flex-1 btn-primary justify-center shadow-lg">Enviar Ticket</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('ticketModal');
    const panel = document.getElementById('modalPanel');

    function openTicketModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeTicketModal() {
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Lógica para mostrar/ocultar input de nuevo número
    function toggleNewNumber(show) {
        const inputDiv = document.getElementById('newNumberInput');
        const inputField = inputDiv.querySelector('input');

        if (show) {
            inputDiv.classList.remove('hidden');
            inputField.required = true;
            inputField.focus();
        } else {
            inputDiv.classList.add('hidden');
            inputField.required = false;
        }
    }

    function submitTicket(e) {
        e.preventDefault();

        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        btn.disabled = true;

        setTimeout(() => {
            alert('✅ Ticket creado exitosamente.\n\nUn técnico revisará tu caso y te contactará al número indicado.');
            closeTicketModal();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1500);
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeTicketModal();
    });
</script>