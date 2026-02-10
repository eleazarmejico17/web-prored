<div class="space-y-6 animate-fade-in-up h-[calc(100vh-100px)] flex flex-col">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Caja y Facturación</h1>
            <p class="text-sm text-gray-500">Gestión de operaciones diarias.</p>
        </div>

        <div class="bg-white px-5 py-2.5 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-success text-lg">
                <i class="fas fa-cash-register"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Recaudado Hoy</p>
                <p class="text-xl font-bold text-gray-800">S/ 1,240.00</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-grow overflow-hidden">

        <div class="card-base p-6 border-t-4 border-primary flex flex-col h-full overflow-hidden">
            <div class="flex-shrink-0">
                <h2 class="text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                    <i class="fas fa-search text-primary"></i> Caja Rápida
                </h2>
                <p class="text-xs text-gray-500 mb-4">Buscar cliente para cobro presencial.</p>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-4">
                            <label class="label-prored text-xs">Criterio</label>
                            <select id="searchType" class="input-prored bg-gray-50 text-sm py-2">
                                <option value="dni">DNI / RUC</option>
                                <option value="apellido">Apellidos</option>
                            </select>
                        </div>
                        <div class="col-span-8">
                            <label class="label-prored text-xs">Dato del Cliente</label>
                            <div class="relative">
                                <input type="text" id="searchInput" class="input-prored pl-9 text-sm py-2"
                                    placeholder="Ingrese dato..."
                                    onkeypress="if(event.key === 'Enter') buscarCliente()">
                                <i class="fas fa-keyboard absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <button onclick="buscarCliente()" class="w-full btn-primary py-2 text-sm shadow-md">
                        <i class="fas fa-search mr-2"></i> Buscar Servicios
                    </button>
                </div>
            </div>

            <div id="searchResults" class="hidden flex-col flex-grow overflow-hidden border-t border-gray-100 pt-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex-shrink-0">Resultados
                    Encontrados</h3>
                <div id="resultsList" class="space-y-3 overflow-y-auto custom-scrollbar pr-2 pb-2">
                </div>
            </div>
        </div>

        <div class="card-base flex flex-col h-full overflow-hidden border-t-4 border-warning">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center flex-shrink-0">
                <div>
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-warning"></i> Pagos Web/App
                    </h3>
                    <p class="text-xs text-gray-500">Pendientes de validación bancaria.</p>
                </div>
                <span class="bg-warning text-white text-xs font-bold px-2 py-1 rounded-full">3 pendientes</span>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-grow p-0">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 bg-gray-50">Cliente / Fecha</th>
                            <th class="px-4 py-3 bg-gray-50">Método / Monto</th>
                            <th class="px-4 py-3 bg-gray-50 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-800 text-sm">María López</p>
                                <p class="text-xs text-gray-400"><i class="far fa-clock mr-1"></i> Hace 20 min</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold">Yape</span>
                                    <span class="font-bold text-gray-800">S/ 89.90</span>
                                </div>
                                <a href="#" onclick="alert('Mostrando comprobante...')"
                                    class="text-xs text-primary hover:underline flex items-center mt-1">
                                    <i class="fas fa-paperclip mr-1"></i> Ver Comprobante
                                </a>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="w-8 h-8 rounded-full bg-green-50 text-success hover:bg-success hover:text-white transition-all flex items-center justify-center"
                                        title="Aprobar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button
                                        class="w-8 h-8 rounded-full bg-red-50 text-danger hover:bg-danger hover:text-white transition-all flex items-center justify-center"
                                        title="Rechazar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-800 text-sm">Jorge Ramírez</p>
                                <p class="text-xs text-gray-400"><i class="far fa-clock mr-1"></i> Hace 45 min</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold">Transf.</span>
                                    <span class="font-bold text-gray-800">S/ 120.00</span>
                                </div>
                                <a href="#" class="text-xs text-primary hover:underline flex items-center mt-1">
                                    <i class="fas fa-paperclip mr-1"></i> Ver Comprobante
                                </a>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="w-8 h-8 rounded-full bg-green-50 text-success hover:bg-success hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button
                                        class="w-8 h-8 rounded-full bg-red-50 text-danger hover:bg-danger hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-800 text-sm">Bodega "El Sol"</p>
                                <p class="text-xs text-gray-400"><i class="far fa-clock mr-1"></i> Hace 1h</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold">Yape</span>
                                    <span class="font-bold text-gray-800">S/ 60.00</span>
                                </div>
                                <a href="#" class="text-xs text-primary hover:underline flex items-center mt-1">
                                    <i class="fas fa-paperclip mr-1"></i> Ver Comprobante
                                </a>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="w-8 h-8 rounded-full bg-green-50 text-success hover:bg-success hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button
                                        class="w-8 h-8 rounded-full bg-red-50 text-danger hover:bg-danger hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<div id="serviceDetailModal"
    class="fixed inset-0 bg-black/60 z-40 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="modalPanelDetail">

        <div class="bg-primary p-6 rounded-t-xl flex justify-between items-start">
            <div class="text-white">
                <h3 class="text-xl font-bold" id="modal_cliente">Nombre Cliente</h3>
                <p class="text-primary-light text-sm opacity-90 flex items-center gap-1">
                    <i class="fas fa-map-marker-alt text-xs"></i> <span id="modal_direccion">Dirección del
                        Servicio</span>
                </p>
            </div>
            <button onclick="closeModal('serviceDetailModal')"
                class="text-white hover:text-gray-200 bg-white/10 hover:bg-white/20 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">

            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total a Pagar</p>
                    <p class="text-3xl font-bold text-gray-800" id="modal_monto">S/ 0.00</p>
                    <div id="modal_estado_container" class="mt-1">
                        <span class="text-xs font-bold text-white px-2 py-0.5 rounded bg-danger"
                            id="modal_estado">PENDIENTE</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Vencimiento</p>
                    <p class="font-medium text-gray-700 text-lg" id="modal_vencimiento">--/--/----</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-address-book text-secondary"></i> Medios de Contacto
                </h4>
                <p class="text-xs text-gray-500 mb-3">Selecciona un número para habilitar las opciones de comunicación:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="phonesList">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <button id="btnCall" disabled
                    class="flex flex-col items-center justify-center p-3 rounded-lg border border-gray-200 text-gray-400 bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 group">
                    <i class="fas fa-phone-alt text-xl mb-1 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium">Llamar</span>
                </button>

                <button id="btnWhatsapp" disabled
                    class="flex flex-col items-center justify-center p-3 rounded-lg border border-gray-200 text-gray-400 bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:bg-green-50 hover:text-green-600 hover:border-green-200 group">
                    <i class="fab fa-whatsapp text-xl mb-1 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium">WhatsApp</span>
                </button>

                <button id="btnSms" disabled
                    class="flex flex-col items-center justify-center p-3 rounded-lg border border-gray-200 text-gray-400 bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:bg-purple-50 hover:text-purple-600 hover:border-purple-200 group">
                    <i class="fas fa-sms text-xl mb-1 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium">SMS</span>
                </button>
            </div>

        </div>

        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl" id="modalFooterAction">
        </div>
    </div>
</div>

<div id="paymentFormModal"
    class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95"
        id="modalPanelPayment">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <h3 class="font-bold text-gray-800">Registrar Pago</h3>
            <button onclick="closeModal('paymentFormModal')" class="text-gray-400 hover:text-danger"><i
                    class="fas fa-times"></i></button>
        </div>

        <form onsubmit="processPayment(event)" class="p-6 space-y-4">
            <div>
                <label class="label-prored">Método de Pago</label>
                <select class="input-prored bg-white" required>
                    <option value="EFECTIVO">Efectivo</option>
                    <option value="POS">Tarjeta (POS)</option>
                    <option value="YAPE">Yape / Plin</option>
                    <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-prored">Monto (S/)</label>
                    <input type="number" id="payAmount" class="input-prored font-bold text-gray-800 text-lg" readonly>
                </div>
                <div>
                    <label class="label-prored">N° Operación (Opc.)</label>
                    <input type="text" class="input-prored" placeholder="xxxx">
                </div>
            </div>

            <div>
                <label class="label-prored">Concepto</label>
                <input type="text" class="input-prored bg-gray-100 text-sm" value="Mensualidad Internet - Feb 2026"
                    readonly>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full btn-success py-3 text-white font-bold rounded-lg shadow-md hover:shadow-lg hover:bg-green-600 transition-all transform active:scale-95">
                    <i class="fas fa-check-circle mr-2"></i> Confirmar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS (MOCKUP BD PRORED) ---
    const clientesDB = [
        {
            id: 1, dni: "72889102", nombre: "Raúl Mendoza",
            servicios: [
                {
                    id_servicio: 101,
                    direccion: "Av. Los Incas 450, Pangoa",
                    plan: "Fibra Home 100",
                    deuda: 89.90,
                    vencimiento: "28/02/2026",
                    telefonos: [
                        { id: 1, numero: "998877665", tipo: "Móvil", principal: true },
                        { id: 2, numero: "966554433", tipo: "WhatsApp", principal: false }
                    ]
                },
                {
                    id_servicio: 102,
                    direccion: "Jr. Comercio 200 (Negocio)",
                    plan: "Fibra Pyme 50",
                    deuda: 60.00,
                    vencimiento: "15/02/2026",
                    telefonos: [
                        { id: 1, numero: "998877665", tipo: "Móvil", principal: true }
                    ]
                }
            ]
        },
        {
            id: 2, dni: "12345678", nombre: "Elena Torres",
            servicios: [
                {
                    id_servicio: 201,
                    direccion: "Calle Real 123, Chilca",
                    plan: "Fibra 50Mb",
                    deuda: 70.00,
                    vencimiento: "28/02/2026",
                    telefonos: [
                        { id: 3, numero: "911223344", tipo: "Móvil", principal: true }
                    ]
                }
            ]
        }
    ];

    let servicioSeleccionado = null;

    // --- 2. BUSCADOR ---
    function buscarCliente() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const resultsContainer = document.getElementById('searchResults');
        const list = document.getElementById('resultsList');

        list.innerHTML = '';

        // Simulamos búsqueda "en tiempo real"
        if (query.length < 1) {
            resultsContainer.classList.add('hidden');
            return;
        }

        const resultados = clientesDB.filter(c =>
            c.dni.includes(query) || c.nombre.toLowerCase().includes(query)
        );

        resultsContainer.classList.remove('hidden');

        if (resultados.length === 0) {
            list.innerHTML = `
                <div class="text-center py-6">
                    <i class="fas fa-user-slash text-gray-300 text-3xl mb-2"></i>
                    <p class="text-sm text-gray-500">No se encontraron clientes.</p>
                </div>`;
            return;
        }

        resultados.forEach(cliente => {
            cliente.servicios.forEach(serv => {
                const card = document.createElement('div');
                card.className = "card-base p-4 border border-gray-200 hover:border-primary hover:shadow-md transition-all cursor-pointer group bg-white";
                card.onclick = () => openServiceModal(cliente, serv);

                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800 group-hover:text-primary transition-colors">${cliente.nombre}</h4>
                            <p class="text-[10px] text-gray-500 font-mono bg-gray-100 inline-block px-1 rounded mt-0.5">${cliente.dni}</p>
                        </div>
                        <span class="bg-blue-50 text-primary text-[10px] font-bold px-1.5 py-0.5 rounded border border-blue-100">#${serv.id_servicio}</span>
                    </div>
                    <div class="text-xs text-gray-600 mb-2 truncate">
                        <i class="fas fa-map-marker-alt text-secondary mr-1"></i> ${serv.direccion}
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 mt-2">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">${serv.plan}</span>
                        <span class="font-bold text-gray-800 text-sm">S/ ${serv.deuda.toFixed(2)}</span>
                    </div>
                `;
                list.appendChild(card);
            });
        });
    }

    // --- 3. MODAL DETALLE Y LÓGICA DE TELÉFONOS ---
    function openServiceModal(cliente, servicio) {
        servicioSeleccionado = servicio;

        // Resetear visuales del modal
        const modal = document.getElementById('serviceDetailModal');
        const panel = document.getElementById('modalPanelDetail');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);

        // Llenar datos
        document.getElementById('modal_cliente').textContent = cliente.nombre;
        document.getElementById('modal_direccion').textContent = servicio.direccion;
        document.getElementById('modal_monto').textContent = "S/ " + servicio.deuda.toFixed(2);
        document.getElementById('modal_vencimiento').textContent = servicio.vencimiento;

        resetModalState();

        // Generar lista de teléfonos (Radio Buttons)
        const phonesContainer = document.getElementById('phonesList');
        phonesContainer.innerHTML = '';

        servicio.telefonos.forEach((tel, index) => {
            const div = document.createElement('div');
            const uniqueId = `phone_${servicio.id_servicio}_${index}`;

            div.className = "relative flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50/50 cursor-pointer transition-colors";
            div.innerHTML = `
                <div class="flex items-center h-5">
                    <input id="${uniqueId}" name="phoneSelection" type="radio" value="${tel.numero}" 
                        class="focus:ring-primary h-4 w-4 text-primary border-gray-300"
                        onchange="enableContactButtons(this.value)">
                </div>
                <label for="${uniqueId}" class="ml-3 flex flex-col cursor-pointer w-full">
                    <span class="block text-sm font-medium text-gray-900 flex justify-between">
                        ${tel.numero}
                        ${tel.principal ? '<span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 rounded">Principal</span>' : ''}
                    </span>
                    <span class="block text-xs text-gray-500">${tel.tipo}</span>
                </label>
            `;
            phonesContainer.appendChild(div);
        });

        disableContactButtons();
    }

    function disableContactButtons() {
        ['btnCall', 'btnWhatsapp', 'btnSms'].forEach(id => {
            const btn = document.getElementById(id);
            btn.disabled = true;
            btn.removeAttribute('onclick');
            // Estilos disabled
            btn.className = "flex flex-col items-center justify-center p-3 rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed transition-all";
        });
    }

    function enableContactButtons(numero) {
        // Estilos activos base
        const baseClass = "flex flex-col items-center justify-center p-3 rounded-lg border transition-all transform active:scale-95 shadow-sm group cursor-pointer";

        // Call
        const btnCall = document.getElementById('btnCall');
        btnCall.disabled = false;
        btnCall.onclick = () => window.location.href = `tel:${numero}`;
        btnCall.className = `${baseClass} border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:shadow-md`;

        // WhatsApp
        const btnWa = document.getElementById('btnWhatsapp');
        btnWa.disabled = false;
        // Limpiar número (quitar espacios o guiones si hubiera)
        const cleanNum = numero.replace(/\D/g, '');
        btnWa.onclick = () => window.open(`https://wa.me/51${cleanNum}`, '_blank');
        btnWa.className = `${baseClass} border-green-200 bg-green-50 text-green-600 hover:bg-green-100 hover:shadow-md`;

        // SMS
        const btnSms = document.getElementById('btnSms');
        btnSms.disabled = false;
        btnSms.onclick = () => window.location.href = `sms:${numero}`;
        btnSms.className = `${baseClass} border-purple-200 bg-purple-50 text-purple-600 hover:bg-purple-100 hover:shadow-md`;
    }

    // --- 4. FLUJO DE PAGO ---

    function openPaymentForm() {
        // Cerrar detalle temporalmente (opcional, o superponer)
        // document.getElementById('serviceDetailModal').classList.add('opacity-0'); // visual hack

        const modalPay = document.getElementById('paymentFormModal');
        const panelPay = document.getElementById('modalPanelPayment');

        // Llenar datos
        document.getElementById('payAmount').value = servicioSeleccionado.deuda.toFixed(2);

        modalPay.classList.remove('hidden');
        setTimeout(() => {
            modalPay.classList.remove('opacity-0');
            panelPay.classList.remove('scale-95');
            panelPay.classList.add('scale-100');
        }, 10);
    }

    function processPayment(e) {
        e.preventDefault();

        // Cerrar modal pago
        closeModal('paymentFormModal');

        // Actualizar estado en Modal Detalle
        changeModalToPaidState();

        // Mostrar alerta (Simulación)
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
        toast.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i> Pago registrado con éxito';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function changeModalToPaidState() {
        // Visualmente pagado
        document.getElementById('modal_monto').textContent = "S/ 0.00";
        document.getElementById('modal_monto').classList.add('text-success');

        const badge = document.getElementById('modal_estado');
        badge.textContent = "PAGADO";
        badge.className = "text-xs font-bold text-white px-2 py-0.5 rounded bg-success";

        // Cambiar Botón Footer a Rojo (PDF)
        const footer = document.getElementById('modalFooterAction');
        footer.innerHTML = `
            <button onclick="generarPDF()" class="w-full bg-white border-2 border-danger text-danger hover:bg-danger hover:text-white shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-3 rounded-lg font-bold flex items-center justify-center text-lg gap-2">
                <i class="fas fa-file-pdf"></i> Generar Boleta de Venta
            </button>
        `;
    }

    function resetModalState() {
        // Restaurar estado PENDIENTE
        document.getElementById('modal_monto').classList.remove('text-success');
        const badge = document.getElementById('modal_estado');
        badge.textContent = "PENDIENTE";
        badge.className = "text-xs font-bold text-white px-2 py-0.5 rounded bg-danger";

        // Restaurar Botón Azul
        const footer = document.getElementById('modalFooterAction');
        footer.innerHTML = `
            <button onclick="openPaymentForm()" class="w-full btn-primary py-3 text-lg shadow-lg font-bold tracking-wide">
                <i class="fas fa-money-bill-wave mr-2"></i> Registrar Pago
            </button>
        `;
    }

    function generarPDF() {
        alert("Simulando descarga de PDF...");
    }

    // Utilidad Cierre Modal
    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[class*="transform"]'); // Buscar el panel interno

        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>