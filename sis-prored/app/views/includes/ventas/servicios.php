<div class="space-y-8 animate-fade-in-up">

    <div>
        <div class="flex justify-between items-end mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Catálogo y Servicios</h1>
                <p class="text-sm text-gray-500">Gestión de planes comerciales y parque de servicios activos.</p>
            </div>
            <button class="btn-outline text-xs">
                <i class="fas fa-cog mr-1"></i> Configurar Catálogo
            </button>
        </div>

        <div class="flex overflow-x-auto gap-4 pb-4 custom-scrollbar">
            <div
                class="min-w-[240px] bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20">
                    <i class="fas fa-wifi text-6xl text-primary"></i>
                </div>
                <h3 class="font-bold text-gray-800">Fibra Home 100</h3>
                <p class="text-2xl font-bold text-primary my-2">S/ 89.90</p>
                <ul class="text-xs text-gray-500 space-y-1 mb-3">
                    <li><i class="fas fa-download text-green-500 mr-1"></i> 100 Mbps Descarga</li>
                    <li><i class="fas fa-upload text-blue-500 mr-1"></i> 100 Mbps Subida</li>
                    <li><i class="fas fa-tv text-gray-400 mr-1"></i> Sin TV</li>
                </ul>
                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">ESTÁNDAR</span>
            </div>

            <div
                class="min-w-[240px] bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20">
                    <i class="fas fa-gamepad text-6xl text-secondary"></i>
                </div>
                <h3 class="font-bold text-gray-800">Fibra Gamer 200</h3>
                <p class="text-2xl font-bold text-secondary my-2">S/ 119.90</p>
                <ul class="text-xs text-gray-500 space-y-1 mb-3">
                    <li><i class="fas fa-download text-green-500 mr-1"></i> 200 Mbps Descarga</li>
                    <li><i class="fas fa-upload text-blue-500 mr-1"></i> 200 Mbps Subida</li>
                    <li><i class="fas fa-tv text-secondary mr-1"></i> TV Digital (80 ch)</li>
                </ul>
                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold">POPULAR</span>
            </div>

            <div
                class="min-w-[240px] bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-4 flex flex-col items-center justify-center text-gray-400 hover:border-primary hover:text-primary cursor-pointer transition-colors">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <span class="text-sm font-medium">Nuevo Plan Base</span>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        <div
            class="p-5 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-network-wired text-primary"></i> Servicios Instalados
            </h3>

            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" id="filterInput" class="input-prored pl-9 py-1.5 text-sm"
                        placeholder="Buscar por DNI, Cliente o ID...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ID Servicio</th>
                        <th class="px-6 py-4">Titular (DNI)</th>
                        <th class="px-6 py-4">Plan Actual</th>
                        <th class="px-6 py-4">Dirección</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Gestión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="servicesTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editServiceModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="modalPanelEdit">

        <div class="bg-primary p-5 rounded-t-xl flex justify-between items-center text-white">
            <div>
                <h3 class="font-bold text-lg">Gestión de Servicio #<span id="modal_id_servicio">000</span></h3>
                <p class="text-xs text-blue-100 opacity-90">Titular Actual: <span id="modal_titular_actual"
                        class="font-bold">--</span></p>
            </div>
            <button onclick="closeModal('editServiceModal')" class="hover:text-gray-200"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="flex border-b border-gray-200 bg-gray-50">
            <button onclick="switchTab('tabPlan')" id="btnTabPlan"
                class="flex-1 py-3 text-sm font-medium text-primary border-b-2 border-primary bg-white focus:outline-none transition-colors">
                <i class="fas fa-box-open mr-2"></i> Plan y Personalización
            </button>
            <button onclick="switchTab('tabTitular')" id="btnTabTitular"
                class="flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
                <i class="fas fa-exchange-alt mr-2"></i> Cambio de Titularidad
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-grow">

            <div id="tabPlan" class="space-y-6">
                <div>
                    <label class="label-prored">Plan Base (Catálogo)</label>
                    <select id="selectPlanBase" class="input-prored bg-white" onchange="checkCustomPlan()">
                        <option value="1">Fibra Home 100 (S/ 89.90)</option>
                        <option value="2">Fibra Gamer 200 (S/ 119.90)</option>
                        <option value="3">Fibra Pyme 300 (S/ 150.00)</option>
                        <option value="custom">-- PERSONALIZADO --</option>
                    </select>
                </div>

                <div class="bg-blue-50/50 rounded-lg p-4 border border-blue-100">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-700 text-sm"><i class="fas fa-sliders-h mr-2"></i> Ajustes del
                            Servicio</h4>
                        <div class="flex items-center">
                            <input type="checkbox" id="checkCustom"
                                class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary"
                                onchange="toggleCustomInputs()">
                            <label for="checkCustom" class="ml-2 text-xs text-gray-600 cursor-pointer">Sobreescribir
                                valores (Personalizar)</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 opacity-50 pointer-events-none transition-opacity"
                        id="customInputs">
                        <div>
                            <label class="label-prored text-xs">Velocidad (Mbps)</label>
                            <input type="number" id="inputSpeed" class="input-prored bg-white text-sm">
                        </div>
                        <div>
                            <label class="label-prored text-xs">Precio Mensual (S/)</label>
                            <input type="number" id="inputPrice"
                                class="input-prored bg-white text-sm font-bold text-gray-800">
                        </div>
                        <div>
                            <label class="label-prored text-xs">IP Asignada</label>
                            <input type="text" id="inputIp" class="input-prored bg-white text-sm font-mono">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button onclick="confirmarBaja()"
                        class="text-danger text-xs font-bold hover:underline flex items-center">
                        <i class="fas fa-trash-alt mr-1"></i> Dar de baja este servicio permanentemente
                    </button>
                </div>
            </div>

            <div id="tabTitular" class="hidden space-y-5">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 text-xs text-yellow-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    El cambio de titularidad transferirá todas las deudas futuras al nuevo cliente.
                </div>

                <div class="flex gap-4 mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="titularMode" value="existente" checked onchange="toggleTitularMode()"
                            class="text-primary focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Cliente Existente</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="titularMode" value="nuevo" onchange="toggleTitularMode()"
                            class="text-primary focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Registrar Nuevo Cliente</span>
                    </label>
                </div>

                <div id="modeExistente" class="animate-fade-in-up">
                    <label class="label-prored">Buscar Cliente en Base de Datos</label>
                    <div class="flex gap-2">
                        <div class="relative w-full">
                            <input type="text" class="input-prored pl-9" placeholder="Ingrese DNI o Apellido...">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                        </div>
                        <button class="btn-secondary px-4"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="mt-3 p-3 border border-green-200 bg-green-50 rounded-lg flex justify-between items-center hidden"
                        id="searchResultClient">
                        <div>
                            <p class="text-sm font-bold text-gray-800">Elena Torres</p>
                            <p class="text-xs text-gray-500">DNI: 12345678</p>
                        </div>
                        <button
                            class="text-xs bg-white border border-green-300 text-green-700 px-2 py-1 rounded font-bold">Seleccionado</button>
                    </div>
                </div>

                <div id="modeNuevo" class="hidden space-y-4 animate-fade-in-up">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label-prored">DNI / RUC *</label>
                            <input type="text" class="input-prored" required>
                        </div>
                        <div>
                            <label class="label-prored">Teléfono *</label>
                            <input type="text" class="input-prored" required>
                        </div>
                        <div>
                            <label class="label-prored">Nombres *</label>
                            <input type="text" class="input-prored" required>
                        </div>
                        <div>
                            <label class="label-prored">Apellidos *</label>
                            <input type="text" class="input-prored" required>
                        </div>
                        <div class="col-span-2">
                            <label class="label-prored">Email</label>
                            <input type="email" class="input-prored">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="p-5 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeModal('editServiceModal')" class="btn-outline">Cancelar</button>
            <button onclick="saveChanges()" class="btn-primary shadow-lg">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </div>
</div>

<script>
    // --- 1. DATOS SIMULADOS (Based on DB) ---
    const serviciosDB = [
        {
            id: 101, dni: "72889102", cliente: "Raúl Mendoza",
            plan: "Fibra Home 100", id_plan: 1, es_custom: false,
            direccion: "Av. Los Incas 450", estado: "ACTIVO",
            precio: 89.90, velocidad: 100, ip: "192.168.10.45"
        },
        {
            id: 102, dni: "20601234567", cliente: "ShopNear S.R.L.",
            plan: "Plan Corporativo", id_plan: null, es_custom: true, // Custom
            direccion: "Jr. Comercio 200", estado: "EN_MORA",
            precio: 250.00, velocidad: 500, ip: "10.20.30.50"
        },
        {
            id: 103, dni: "12345678", cliente: "Elena Torres",
            plan: "Fibra Gamer 200", id_plan: 2, es_custom: false,
            direccion: "Calle Real 123", estado: "SUSPENDIDO",
            precio: 119.90, velocidad: 200, ip: "192.168.10.60"
        }
    ];

    // --- 2. RENDERIZADO DE TABLA ---
    function renderServices() {
        const tbody = document.getElementById('servicesTableBody');
        tbody.innerHTML = '';

        serviciosDB.forEach(s => {
            // Lógica Etiqueta Plan
            let planTag = `<span class="font-medium text-gray-800">${s.plan}</span>`;
            if (s.es_custom) {
                planTag = `<div class="flex flex-col">
                                <span class="font-bold text-primary">PERSONALIZADO</span>
                                <span class="text-[10px] text-gray-400">S/ ${s.precio.toFixed(2)} - ${s.velocidad}Mb</span>
                           </div>`;
            }

            // Lógica Estado
            let estadoClass = '';
            if (s.estado === 'ACTIVO') estadoClass = 'bg-green-100 text-green-700';
            else if (s.estado === 'EN_MORA') estadoClass = 'bg-orange-100 text-orange-700';
            else estadoClass = 'bg-red-100 text-red-700';

            const row = `
                <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">#${s.id}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800 text-sm">${s.cliente}</p>
                        <p class="text-xs text-gray-400">${s.dni}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">${planTag}</td>
                    <td class="px-6 py-4 text-xs text-gray-500 truncate max-w-xs">${s.direccion}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded text-[10px] font-bold ${estadoClass}">${s.estado}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="w-8 h-8 rounded border border-gray-200 text-gray-500 hover:text-primary hover:border-primary transition-colors" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="openEditModal(${s.id})" class="w-8 h-8 rounded bg-primary text-white hover:bg-primary-dark shadow-sm transition-colors" title="Editar / Migrar">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    renderServices(); // Init

    // --- 3. GESTIÓN DEL MODAL ---

    // Abrir Modal
    function openEditModal(id) {
        const servicio = serviciosDB.find(s => s.id === id);

        // Llenar Header
        document.getElementById('modal_id_servicio').textContent = servicio.id;
        document.getElementById('modal_titular_actual').textContent = `${servicio.cliente} (${servicio.dni})`;

        // Llenar Tab Plan
        const select = document.getElementById('selectPlanBase');
        if (servicio.es_custom) {
            select.value = 'custom';
            document.getElementById('checkCustom').checked = true;
            toggleCustomInputs(true);
        } else {
            select.value = servicio.id_plan;
            document.getElementById('checkCustom').checked = false;
            toggleCustomInputs(false);
        }

        // Llenar Inputs Custom
        document.getElementById('inputSpeed').value = servicio.velocidad;
        document.getElementById('inputPrice').value = servicio.precio;
        document.getElementById('inputIp').value = servicio.ip;

        // Reset Tabs
        switchTab('tabPlan');

        // Mostrar
        const modal = document.getElementById('editServiceModal');
        const panel = document.getElementById('modalPanelEdit');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    // Tabs Logic
    function switchTab(tabId) {
        // Ocultar todos
        document.getElementById('tabPlan').classList.add('hidden');
        document.getElementById('tabTitular').classList.add('hidden');

        // Reset estilos botones
        const btnPlan = document.getElementById('btnTabPlan');
        const btnTitular = document.getElementById('btnTabTitular');

        btnPlan.className = "flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors border-b-2 border-transparent";
        btnTitular.className = "flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors border-b-2 border-transparent";

        // Mostrar seleccionado
        document.getElementById(tabId).classList.remove('hidden');

        // Activar estilo botón
        const activeClass = "text-primary border-b-2 border-primary bg-white";
        if (tabId === 'tabPlan') {
            btnPlan.className = `flex-1 py-3 text-sm font-medium focus:outline-none transition-colors ${activeClass}`;
        } else {
            btnTitular.className = `flex-1 py-3 text-sm font-medium focus:outline-none transition-colors ${activeClass}`;
        }
    }

    // Custom Plan Logic
    function checkCustomPlan() {
        const val = document.getElementById('selectPlanBase').value;
        if (val === 'custom') {
            document.getElementById('checkCustom').checked = true;
            toggleCustomInputs(true);
        }
    }

    function toggleCustomInputs(forceState = null) {
        const check = document.getElementById('checkCustom');
        const inputs = document.getElementById('customInputs');
        const isChecked = forceState !== null ? forceState : check.checked;

        check.checked = isChecked; // Sync UI

        if (isChecked) {
            inputs.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            inputs.classList.add('opacity-50', 'pointer-events-none');
            // Reset to standard values logic would go here
        }
    }

    // Toggle Titular Mode (Existente vs Nuevo)
    function toggleTitularMode() {
        const mode = document.querySelector('input[name="titularMode"]:checked').value;
        if (mode === 'existente') {
            document.getElementById('modeExistente').classList.remove('hidden');
            document.getElementById('modeNuevo').classList.add('hidden');
        } else {
            document.getElementById('modeExistente').classList.add('hidden');
            document.getElementById('modeNuevo').classList.remove('hidden');
        }
    }

    // Acciones Finales
    function saveChanges() {
        alert("Cambios guardados correctamente (Simulación).\n- Plan Actualizado\n- Titular Verificado");
        closeModal('editServiceModal');
    }

    function confirmarBaja() {
        if (confirm("¿ESTÁ SEGURO? Esta acción cortará el servicio y generará una orden de retiro de equipos.")) {
            alert("Servicio dado de baja.");
            closeModal('editServiceModal');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[class*="transform"]');

        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>