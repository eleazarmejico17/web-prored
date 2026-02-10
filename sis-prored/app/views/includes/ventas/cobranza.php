<div class="space-y-6 animate-fade-in-up h-[calc(100vh-100px)] flex flex-col">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Cobranza</h1>
            <p class="text-sm text-gray-500">Monitor de pagos y gestión de morosidad.</p>
        </div>

        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-right">
            <p class="text-[10px] text-gray-400 uppercase font-bold">Meta Diaria</p>
            <p class="font-bold text-gray-600 text-sm">S/ 2,000.00</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-shrink-0">

        <div class="card-base p-4 border-l-4 border-blue-500 relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pagos (Semana)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">128</h3>
                    <p class="text-[10px] text-blue-600 mt-1 font-medium"><i class="fas fa-arrow-up"></i> 12% vs semana
                        anterior</p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50 text-blue-500 group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-4 border-l-4 border-yellow-400 relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Próximos a Vencer</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">45</h3>
                    <p class="text-[10px] text-yellow-600 mt-1 font-medium">Vencen en los próximos 3 días</p>
                </div>
                <div class="p-3 rounded-lg bg-yellow-50 text-yellow-500 group-hover:bg-yellow-100 transition-colors">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-base p-4 border-l-4 border-red-500 relative overflow-hidden group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pagos Vencidos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">12</h3>
                    <p class="text-[10px] text-red-600 mt-1 font-medium">Cartera en riesgo de corte</p>
                </div>
                <div
                    class="p-3 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 transition-colors animate-pulse">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-grow overflow-hidden">

        <div class="card-base flex flex-col h-full overflow-hidden border-t-4 border-success">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center flex-shrink-0">
                <div>
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-check-circle text-success"></i> Pagos Recibidos Hoy
                    </h3>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase">Total Hoy</p>
                    <p class="font-bold text-success text-lg">S/ 1,240.00</p>
                </div>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-grow p-0">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 bg-gray-50">Hora</th>
                            <th class="px-4 py-3 bg-gray-50">Cliente</th>
                            <th class="px-4 py-3 bg-gray-50">Método</th>
                            <th class="px-4 py-3 bg-gray-50 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="pagosHoyList">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-base flex flex-col h-full overflow-hidden border-t-4 border-primary">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex flex-col gap-2 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-primary"></i> Gestión de Cartera
                    </h3>
                    <div class="flex gap-2 text-[10px] text-gray-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span>
                            Vencido</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                            Hoy</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Próx</span>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" id="filtroCartera" onkeyup="filtrarCartera()"
                        class="input-prored py-1.5 pl-8 text-xs" placeholder="Filtrar por nombre...">
                    <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
                </div>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-grow p-4 space-y-3" id="carteraList">
            </div>
        </div>
    </div>
</div>

<div id="contactModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95 flex flex-col"
        id="modalPanelContact">

        <div class="bg-gray-800 p-5 rounded-t-xl flex justify-between items-center text-white">
            <div>
                <h3 class="font-bold text-lg" id="modalClientName">Cliente Nombre</h3>
                <p class="text-xs text-gray-400">Deuda Total: <span class="text-white font-bold text-sm"
                        id="modalDebt">S/ 0.00</span></p>
            </div>
            <button onclick="closeModal('contactModal')" class="hover:text-gray-300"><i
                    class="fas fa-times"></i></button>
        </div>

        <div id="statusAlert" class="bg-yellow-50 border-b border-yellow-100 p-3 text-center">
        </div>

        <div class="flex border-b border-gray-200 bg-gray-50">
            <button onclick="switchTab('tabNotificar')" id="btnTabNotificar"
                class="flex-1 py-3 text-sm font-medium text-primary border-b-2 border-primary bg-white focus:outline-none transition-colors">
                <i class="fas fa-robot mr-2"></i> Notificar
            </button>
            <button onclick="switchTab('tabManual')" id="btnTabManual"
                class="flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
                <i class="fas fa-user mr-2"></i> Contactar
            </button>
        </div>

        <div class="p-6">

            <div id="tabNotificar" class="space-y-4">
                <div class="bg-blue-50 p-3 rounded text-xs text-blue-800 border border-blue-100">
                    <span class="font-bold block mb-1"><i class="fas fa-quote-left mr-1"></i> Mensaje Automático:</span>
                    <span id="previewMessage" class="italic">Cargando plantilla...</span>
                </div>

                <div id="phonesListTemplate" class="space-y-3">
                </div>
            </div>

            <div id="tabManual" class="hidden space-y-4">
                <p class="text-xs text-gray-500 mb-2">Abre la aplicación sin mensaje predeterminado para gestión manual.
                </p>
                <div id="phonesListManual" class="space-y-3">
                </div>
            </div>

        </div>

        <div class="p-4 bg-gray-50 rounded-b-xl border-t border-gray-100 text-center">
            <button onclick="closeModal('contactModal')" class="btn-outline w-full justify-center">Cerrar
                Ventana</button>
        </div>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS ---

    // Pagos de Hoy
    const pagosHoy = [
        { hora: "08:30 AM", cliente: "Bodega El Sol", metodo: "Yape", monto: 60.00 },
        { hora: "09:15 AM", cliente: "Juan Pérez", metodo: "Efectivo", monto: 89.90 },
        { hora: "10:45 AM", cliente: "María López", metodo: "Transferencia", monto: 120.00 },
        { hora: "11:20 AM", cliente: "Carlos Ruiz", metodo: "Plin", monto: 50.00 },
        { hora: "12:10 PM", cliente: "Ana Torres", metodo: "Efectivo", monto: 70.00 },
        { hora: "01:05 PM", cliente: "Hotel Central", metodo: "Transferencia", monto: 250.00 }
    ];

    // Cartera de Cobranza (dias_retraso: negativo = falta para vencer, positivo = vencido)
    const carteraDB = [
        {
            id: 1, cliente: "Raúl Mendoza", servicio: "Av. Los Incas 450",
            deuda: 89.90, vencimiento: "2026-02-10", dias_retraso: -1,
            telefonos: [{ num: "998877665", tipo: "Móvil" }]
        },
        {
            id: 2, cliente: "Elena Torres", servicio: "Calle Real 123",
            deuda: 70.00, vencimiento: "2026-02-09", dias_retraso: 0,
            telefonos: [{ num: "911223344", tipo: "WhatsApp" }]
        },
        {
            id: 3, cliente: "ShopNear S.R.L.", servicio: "Jr. Comercio 200",
            deuda: 150.00, vencimiento: "2026-02-07", dias_retraso: 2,
            telefonos: [{ num: "912345678", tipo: "Móvil" }, { num: "987654321", tipo: "Oficina" }]
        },
        {
            id: 4, cliente: "Jorge Luis", servicio: "Psje. Los Pinos",
            deuda: 89.90, vencimiento: "2026-02-05", dias_retraso: 4,
            telefonos: [{ num: "955443322", tipo: "Móvil" }]
        }
    ];

    // --- 2. RENDERIZADO INICIAL ---

    // Render Pagos Hoy
    const tbodyPagos = document.getElementById('pagosHoyList');
    pagosHoy.forEach(p => {
        tbodyPagos.innerHTML += `
            <tr class="hover:bg-gray-50 border-b border-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-500">${p.hora}</td>
                <td class="px-4 py-3 font-medium text-gray-800">${p.cliente}</td>
                <td class="px-4 py-3 text-xs"><span class="bg-gray-100 px-2 py-1 rounded border border-gray-200">${p.metodo}</span></td>
                <td class="px-4 py-3 text-right font-bold text-success">S/ ${p.monto.toFixed(2)}</td>
            </tr>
        `;
    });

    // Render Cartera
    function renderCartera(lista) {
        const container = document.getElementById('carteraList');
        container.innerHTML = '';

        lista.forEach(c => {
            // Lógica de Semáforo y Badges
            let borderClass, badgeClass, badgeText;

            if (c.dias_retraso >= 2) {
                // ROJO CRÍTICO (Corte)
                borderClass = 'border-l-4 border-red-500 bg-red-50/30';
                badgeClass = 'bg-red-100 text-red-700 border border-red-200';
                badgeText = `Vencido (${c.dias_retraso} días)`;
            } else if (c.dias_retraso > 0) {
                // ROJO SUAVE (Vencido reciente)
                borderClass = 'border-l-4 border-red-400';
                badgeClass = 'bg-red-50 text-red-600 border border-red-100';
                badgeText = `Vencido (${c.dias_retraso} días)`;
            } else if (c.dias_retraso === 0) {
                // AMARILLO (Hoy)
                borderClass = 'border-l-4 border-yellow-400';
                badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                badgeText = 'Vence Hoy';
            } else {
                // VERDE (Próximo)
                borderClass = 'border-l-4 border-green-500';
                badgeClass = 'bg-green-100 text-green-700 border border-green-200';
                badgeText = 'Al día';
            }

            container.innerHTML += `
                <div class="card-base p-4 ${borderClass} shadow-sm hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-primary transition-colors">${c.cliente}</h4>
                            <p class="text-xs text-gray-500 truncate w-40"><i class="fas fa-map-marker-alt mr-1"></i> ${c.servicio}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded ${badgeClass}">${badgeText}</span>
                    </div>
                    
                    <div class="flex justify-between items-end mt-3 border-t border-gray-100 pt-3">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Total a Pagar</p>
                            <p class="text-lg font-bold text-gray-800">S/ ${c.deuda.toFixed(2)}</p>
                            <p class="text-[10px] text-gray-500">Vence: ${c.vencimiento}</p>
                        </div>
                        <button onclick="openContactModal(${c.id})" class="btn-primary py-1.5 px-3 text-xs shadow-sm flex items-center gap-1">
                            <i class="fas fa-comment-dots"></i> Contactar
                        </button>
                    </div>
                </div>
            `;
        });
    }
    renderCartera(carteraDB);

    // Filtro JS Simple
    function filtrarCartera() {
        const query = document.getElementById('filtroCartera').value.toLowerCase();
        const filtrado = carteraDB.filter(c => c.cliente.toLowerCase().includes(query));
        renderCartera(filtrado);
    }

    // --- 3. LÓGICA DEL MODAL ---

    let currentClient = null;

    function openContactModal(id) {
        currentClient = carteraDB.find(c => c.id === id);

        // Llenar datos básicos
        document.getElementById('modalClientName').textContent = currentClient.cliente;
        document.getElementById('modalDebt').textContent = "S/ " + currentClient.deuda.toFixed(2);

        // CONFIGURAR ALERTA Y MENSAJE SEGÚN DÍAS DE RETRASO
        const alertBox = document.getElementById('statusAlert');
        const previewMsg = document.getElementById('previewMessage');
        let messageText = "";

        if (currentClient.dias_retraso >= 2) {
            // MODO CORTE
            alertBox.className = "bg-red-100 border-b border-red-200 p-3 text-center";
            alertBox.innerHTML = '<p class="text-sm font-bold text-red-800 animate-pulse"><i class="fas fa-cut mr-1"></i> AVISO DE CORTE DE SERVICIO</p>';

            messageText = `Estimado ${currentClient.cliente}, su servicio presenta una deuda vencida de S/ ${currentClient.deuda}. Por favor regularice su pago hoy para evitar el corte del servicio.`;

        } else if (currentClient.dias_retraso >= 0) {
            // MODO VENCIDO HOY / AYER
            alertBox.className = "bg-yellow-100 border-b border-yellow-200 p-3 text-center";
            alertBox.innerHTML = '<p class="text-sm font-bold text-yellow-800"><i class="fas fa-exclamation-circle mr-1"></i> Pago Vencido o Vence Hoy</p>';

            messageText = `Hola ${currentClient.cliente}, recordamos que su recibo de internet vence hoy. Monto: S/ ${currentClient.deuda}. Gracias por su preferencia.`;

        } else {
            // MODO RECORDATORIO
            alertBox.className = "bg-green-100 border-b border-green-200 p-3 text-center";
            alertBox.innerHTML = '<p class="text-sm font-bold text-green-800"><i class="fas fa-calendar-check mr-1"></i> Recordatorio de Pago</p>';

            messageText = `Hola ${currentClient.cliente}, su próximo recibo vence el ${currentClient.vencimiento}. Puede realizar el pago por nuestros canales digitales.`;
        }

        previewMsg.textContent = messageText;
        const encodedMsg = encodeURIComponent(messageText);

        // GENERAR LISTAS DE TELÉFONOS
        const listTemplate = document.getElementById('phonesListTemplate');
        const listManual = document.getElementById('phonesListManual');
        listTemplate.innerHTML = '';
        listManual.innerHTML = '';

        currentClient.telefonos.forEach(tel => {
            const cleanNum = tel.num.replace(/\D/g, '');

            // FILA TAB NOTIFICAR (Con Mensaje)
            listTemplate.innerHTML += `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">${tel.num}</p>
                        <p class="text-[10px] text-gray-500 bg-gray-100 inline-block px-1 rounded">${tel.tipo}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="tel:${cleanNum}" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all" title="Llamar">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </a>
                        <a href="sms:${cleanNum}?body=${encodedMsg}" class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all" title="Enviar SMS">
                            <i class="fas fa-sms text-xs"></i>
                        </a>
                        <a href="https://wa.me/51${cleanNum}?text=${encodedMsg}" target="_blank" class="w-8 h-8 rounded-full bg-green-50 text-green-600 border border-green-200 flex items-center justify-center hover:bg-green-600 hover:text-white transition-all" title="Enviar WhatsApp">
                            <i class="fab fa-whatsapp text-sm"></i>
                        </a>
                    </div>
                </div>
            `;

            // FILA TAB MANUAL (Sin Mensaje)
            listManual.innerHTML += `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">${tel.num}</p>
                        <p class="text-[10px] text-gray-500 bg-gray-100 inline-block px-1 rounded">${tel.tipo}</p>
                    </div>
                    <div class="flex gap-2">
                         <a href="tel:${cleanNum}" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all" title="Llamar">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </a>
                        <a href="sms:${cleanNum}" class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all" title="Abrir SMS">
                            <i class="fas fa-sms text-xs"></i>
                        </a>
                        <a href="https://wa.me/51${cleanNum}" target="_blank" class="w-8 h-8 rounded-full bg-green-50 text-green-600 border border-green-200 flex items-center justify-center hover:bg-green-600 hover:text-white transition-all" title="Abrir Chat">
                            <i class="fab fa-whatsapp text-sm"></i>
                        </a>
                    </div>
                </div>
            `;
        });

        // Abrir Modal
        switchTab('tabNotificar'); // Reset tab
        const modal = document.getElementById('contactModal');
        const panel = document.getElementById('modalPanelContact');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function switchTab(tabId) {
        document.getElementById('tabNotificar').classList.add('hidden');
        document.getElementById('tabManual').classList.add('hidden');

        // Reset botones
        const btnNot = document.getElementById('btnTabNotificar');
        const btnMan = document.getElementById('btnTabManual');

        // Estilo Inactivo
        const inactiveClass = "flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors bg-gray-50";
        btnNot.className = inactiveClass;
        btnMan.className = inactiveClass;

        // Activar seleccionado
        document.getElementById(tabId).classList.remove('hidden');

        // Estilo Activo
        const activeClass = "flex-1 py-3 text-sm font-medium text-primary border-b-2 border-primary bg-white transition-colors";
        if (tabId === 'tabNotificar') btnNot.className = activeClass;
        else btnMan.className = activeClass;
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