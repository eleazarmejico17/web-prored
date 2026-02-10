<div class="space-y-6 animate-fade-in-up h-[calc(100vh-100px)] flex flex-col">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Comprobantes Electrónicos</h1>
            <p class="text-sm text-gray-500">Gestión de Boletas, Facturas y notificaciones de pago.</p>
        </div>

        <button onclick="alert('Iniciando envío masivo a pendientes...')" class="btn-outline text-xs">
            <i class="fas fa-paper-plane mr-2"></i> Notificar a todos los pendientes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-shrink-0">

        <div class="card-base p-5 border-l-4 border-primary relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Emitidos (Mes)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_total">145</h3>
                </div>
                <div class="p-3 rounded-lg bg-blue-50 text-primary">
                    <i class="fas fa-file-invoice text-2xl"></i>
                </div>
            </div>
            <p class="text-[10px] text-gray-400 mt-2">Boletas y Facturas generadas</p>
        </div>

        <div class="card-base p-5 border-l-4 border-success">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Notificados</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_enviados">120</h3>
                    <p class="text-[10px] text-success mt-1 font-bold">
                        <i class="fas fa-check-double mr-1"></i> 82% Cobertura
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-green-50 text-success">
                    <i class="fab fa-whatsapp text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-5 border-l-4 border-orange-400">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin Notificar</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="kpi_pendientes">25</h3>
                    <p class="text-[10px] text-orange-500 mt-1 font-bold animate-pulse">
                        <i class="fas fa-exclamation-circle mr-1"></i> Acción requerida
                    </p>
                </div>
                <div class="p-3 rounded-lg bg-orange-50 text-orange-500">
                    <i class="fas fa-bell-slash text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-base flex flex-col flex-grow overflow-hidden">

        <div
            class="p-5 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4 flex-shrink-0">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-gray-400"></i> Historial de Emisión
            </h3>

            <div class="flex gap-2 w-full md:w-auto">
                <select id="filterType" class="input-prored bg-white text-xs py-2 w-32" onchange="renderTable()">
                    <option value="all">Todos</option>
                    <option value="BOL">Boletas</option>
                    <option value="FAC">Facturas</option>
                </select>
                <select id="filterStatus" class="input-prored bg-white text-xs py-2 w-32" onchange="renderTable()">
                    <option value="all">Estado Envío</option>
                    <option value="PENDIENTE">Pendientes</option>
                    <option value="ENVIADO">Enviados</option>
                </select>
                <div class="relative w-full md:w-56">
                    <input type="text" id="searchInput" onkeyup="renderTable()" class="input-prored pl-8 py-2 text-xs"
                        placeholder="Buscar cliente o serie...">
                    <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-grow p-0">
            <table class="w-full text-left text-sm text-gray-600">
                <thead
                    class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4">Emisión</th>
                        <th class="px-6 py-4">Comprobante</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4 text-right">Importe Total</th>
                        <th class="px-6 py-4 text-center">Canal Notif.</th>
                        <th class="px-6 py-4 text-center">Estado Envío</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="comprobantesTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="sendModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95 flex flex-col"
        id="modalPanelSend">

        <div class="bg-gray-800 p-5 rounded-t-xl flex justify-between items-center text-white">
            <div>
                <h3 class="font-bold text-lg"><i class="fas fa-paper-plane mr-2"></i> Enviar Comprobante</h3>
                <p class="text-xs text-gray-400" id="modalSerie">Serie: B001-0000123</p>
            </div>
            <button onclick="closeModal('sendModal')" class="hover:text-gray-300"><i class="fas fa-times"></i></button>
        </div>

        <div class="p-6">

            <div class="flex gap-4 mb-6">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="channel" value="whatsapp" class="peer sr-only" checked
                        onchange="switchChannel()">
                    <div
                        class="p-3 rounded-lg border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all text-center">
                        <i class="fab fa-whatsapp text-2xl text-green-600 mb-1 block"></i>
                        <span class="text-xs font-bold text-gray-700">WhatsApp (PDF)</span>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="channel" value="sms" class="peer sr-only" onchange="switchChannel()">
                    <div
                        class="p-3 rounded-lg border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                        <i class="fas fa-sms text-2xl text-blue-600 mb-1 block"></i>
                        <span class="text-xs font-bold text-gray-700">SMS (TXT)</span>
                    </div>
                </label>
            </div>

            <div class="space-y-3">
                <p class="text-xs font-bold text-gray-500 uppercase">Vista Previa del Mensaje</p>

                <div id="previewWhatsapp"
                    class="bg-green-50 p-4 rounded-lg border border-green-100 text-sm text-gray-800 relative">
                    <div
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">
                        PDF Adjunto</div>
                    <p class="mb-2">Hola <strong id="previewClientName">Cliente</strong>,</p>
                    <p>Adjuntamos su comprobante electrónico <strong>#<span id="previewDocNum">B001-123</span></strong>
                        correspondiente al periodo actual.</p>
                    <div class="mt-3 flex items-center gap-2 bg-white p-2 rounded border border-gray-200">
                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                        <span class="text-xs font-mono text-gray-500">comprobante.pdf (150kb)</span>
                    </div>
                </div>

                <div id="previewSms"
                    class="hidden bg-gray-100 p-4 rounded-lg border border-gray-200 font-mono text-xs text-gray-800">
                    ProRed: Hola <span id="previewClientNameSMS">Cliente</span>. Hemos emitido su comprobante <span
                        id="previewDocNumSMS">B001</span> por S/ <span id="previewAmount">00.00</span>. Descarguelo
                    aqui: bit.ly/prored-123
                </div>
            </div>

            <div class="mt-6">
                <label class="label-prored">Enviar al número:</label>
                <select id="modalPhoneSelect" class="input-prored bg-white">
                </select>
            </div>

        </div>

        <div class="p-4 bg-gray-50 rounded-b-xl border-t border-gray-100 flex gap-3 justify-end">
            <button onclick="closeModal('sendModal')" class="btn-outline">Cancelar</button>
            <button onclick="confirmSend()" class="btn-primary shadow-lg" id="btnSendAction">
                <i class="fab fa-whatsapp mr-2"></i> Enviar Ahora
            </button>
        </div>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS (Mockup BD) ---
    // Tipos: BOL (Boleta), FAC (Factura)
    let comprobantesDB = [
        {
            id: 1, serie: "B001-0004521", fecha: "09/02/2026",
            cliente: "Raúl Mendoza", tipo: "BOL", monto: 89.90,
            estado_envio: "PENDIENTE", canal: null,
            telefonos: ["998877665", "966554433"]
        },
        {
            id: 2, serie: "F001-0000320", fecha: "09/02/2026",
            cliente: "ShopNear S.R.L.", tipo: "FAC", monto: 150.00,
            estado_envio: "ENVIADO", canal: "WHATSAPP",
            telefonos: ["912345678"]
        },
        {
            id: 3, serie: "B001-0004522", fecha: "09/02/2026",
            cliente: "Elena Torres", tipo: "BOL", monto: 70.00,
            estado_envio: "ENVIADO", canal: "SMS",
            telefonos: ["911223344"]
        },
        {
            id: 4, serie: "B001-0004523", fecha: "08/02/2026",
            cliente: "Jorge Luis", tipo: "BOL", monto: 89.90,
            estado_envio: "PENDIENTE", canal: null,
            telefonos: ["955443322"]
        }
    ];

    let selectedComprobante = null;

    // --- 2. RENDERIZADO Y LÓGICA DE TABLA ---
    function renderTable() {
        const tbody = document.getElementById('comprobantesTableBody');
        const filterType = document.getElementById('filterType').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const searchVal = document.getElementById('searchInput').value.toLowerCase();

        tbody.innerHTML = '';

        // Contadores para KPIs
        let total = 0, enviados = 0, pendientes = 0;

        const filteredData = comprobantesDB.filter(c => {
            const matchType = filterType === 'all' || c.tipo === filterType;
            const matchStatus = filterStatus === 'all' || c.estado_envio === filterStatus;
            const matchSearch = c.cliente.toLowerCase().includes(searchVal) || c.serie.toLowerCase().includes(searchVal);
            return matchType && matchStatus && matchSearch;
        });

        filteredData.forEach(c => {
            // Actualizar KPIs
            total++;
            if (c.estado_envio === 'ENVIADO') enviados++; else pendientes++;

            // Estilos
            const tipoBadge = c.tipo === 'BOL'
                ? '<span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded">BOLETA</span>'
                : '<span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded">FACTURA</span>';

            let statusBadge = '';
            if (c.estado_envio === 'ENVIADO') {
                statusBadge = `<span class="inline-flex items-center text-xs text-success font-bold"><i class="fas fa-check-double mr-1"></i> Enviado</span>`;
            } else {
                statusBadge = `<span class="inline-flex items-center text-xs text-orange-500 font-bold bg-orange-50 px-2 py-1 rounded-full animate-pulse"><i class="fas fa-clock mr-1"></i> Pendiente</span>`;
            }

            let canalIcon = '<span class="text-gray-300">-</span>';
            if (c.canal === 'WHATSAPP') canalIcon = '<i class="fab fa-whatsapp text-green-500 text-lg" title="Por WhatsApp"></i>';
            if (c.canal === 'SMS') canalIcon = '<i class="fas fa-sms text-blue-500 text-lg" title="Por SMS"></i>';

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 border-b border-gray-50 transition-colors">
                    <td class="px-6 py-4 text-xs text-gray-500 font-mono">${c.fecha}</td>
                    <td class="px-6 py-4 font-bold text-gray-800 text-sm">${c.serie}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 text-sm">${c.cliente}</span>
                            <span>${tipoBadge}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-mono font-bold text-gray-700">S/ ${c.monto.toFixed(2)}</td>
                    <td class="px-6 py-4 text-center">${canalIcon}</td>
                    <td class="px-6 py-4 text-center">${statusBadge}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="downloadPDF('${c.serie}')" class="w-8 h-8 rounded border border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-500 transition-colors" title="Descargar PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button onclick="openSendModal(${c.id})" class="w-8 h-8 rounded bg-primary text-white hover:bg-primary-dark shadow-sm transition-colors" title="Enviar Notificación">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-gray-400">No se encontraron comprobantes.</td></tr>`;
        }

        updateKPIs(total, enviados, pendientes);
    }

    function updateKPIs(t, e, p) {
        document.getElementById('kpi_total').textContent = t;
        document.getElementById('kpi_enviados').textContent = e;
        document.getElementById('kpi_pendientes').textContent = p;
    }

    // Inicializar
    renderTable();

    // --- 3. LÓGICA DE MODAL (ENVIAR) ---

    function openSendModal(id) {
        selectedComprobante = comprobantesDB.find(c => c.id === id);

        // Llenar datos header
        document.getElementById('modalSerie').textContent = "Serie: " + selectedComprobante.serie;

        // Llenar previews
        document.getElementById('previewClientName').textContent = selectedComprobante.cliente;
        document.getElementById('previewClientNameSMS').textContent = selectedComprobante.cliente;
        document.getElementById('previewDocNum').textContent = selectedComprobante.serie;
        document.getElementById('previewDocNumSMS').textContent = selectedComprobante.serie;
        document.getElementById('previewAmount').textContent = selectedComprobante.monto.toFixed(2);

        // Llenar Select de Teléfonos
        const select = document.getElementById('modalPhoneSelect');
        select.innerHTML = '';
        selectedComprobante.telefonos.forEach(tel => {
            const opt = document.createElement('option');
            opt.value = tel;
            opt.text = tel;
            select.appendChild(opt);
        });

        // Reset canal a WhatsApp
        document.querySelector('input[value="whatsapp"]').checked = true;
        switchChannel();

        // Mostrar Modal
        const modal = document.getElementById('sendModal');
        const panel = document.getElementById('modalPanelSend');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function switchChannel() {
        const channel = document.querySelector('input[name="channel"]:checked').value;
        const btn = document.getElementById('btnSendAction');

        if (channel === 'whatsapp') {
            document.getElementById('previewWhatsapp').classList.remove('hidden');
            document.getElementById('previewSms').classList.add('hidden');
            btn.innerHTML = '<i class="fab fa-whatsapp mr-2"></i> Enviar PDF por WhatsApp';
            btn.className = "btn-primary shadow-lg bg-green-600 hover:bg-green-700 text-white";
        } else {
            document.getElementById('previewWhatsapp').classList.add('hidden');
            document.getElementById('previewSms').classList.remove('hidden');
            btn.innerHTML = '<i class="fas fa-sms mr-2"></i> Enviar SMS (Texto)';
            btn.className = "btn-primary shadow-lg bg-blue-600 hover:bg-blue-700 text-white";
        }
    }

    function confirmSend() {
        const channel = document.querySelector('input[name="channel"]:checked').value;
        const phone = document.getElementById('modalPhoneSelect').value;

        // Simulación de envío
        closeModal('sendModal');

        // Actualizar datos locales
        selectedComprobante.estado_envio = "ENVIADO";
        selectedComprobante.canal = channel.toUpperCase();
        renderTable();

        // Mensaje Toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce flex items-center gap-2';

        if (channel === 'whatsapp') {
            // Abrir link real de WhatsApp (Simulación)
            // window.open(`https://wa.me/51${phone}?text=...`); 
            toast.innerHTML = '<i class="fab fa-whatsapp text-green-400 text-xl"></i> PDF Enviado por WhatsApp correctamente.';
        } else {
            toast.innerHTML = '<i class="fas fa-sms text-blue-400 text-xl"></i> SMS enviado correctamente.';
        }

        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // --- UTILS ---
    function downloadPDF(serie) {
        alert("Generando y descargando PDF para: " + serie);
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