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
    const controllerUrl = '../controllers/VentasController.php';
    window.servicesData = [];
    window.plansData = [];
    window.internetOptions = [];
    window.tvOptions = [];

    // --- 1. CARGA DE DATOS ---
    document.addEventListener('DOMContentLoaded', () => {
        loadServices();
        loadPlans();
        loadOptions(); // Fetch Internet/TV options

        // Listener para búsqueda
        const filterInput = document.getElementById('filterInput');
        filterInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                loadServices(this.value);
            }
        });
    });

    async function loadOptions() {
        try {
            const res = await fetch(`${controllerUrl}?op=get_plan_options`);
            if (res.ok) {
                const data = await res.json();
                window.internetOptions = data.internet || [];
                window.tvOptions = data.tv || [];
            }
        } catch (e) { console.error("Error loading options", e); }
    }

    async function loadPlans() {
        // Container for plans
        const container = document.querySelector('.flex.overflow-x-auto'); // Adjust selector if needed
        // Keep the "New Plan" button
        const newPlanBtn = container.querySelector('div:last-child');

        // Show loading state? Or just append after fetch

        try {
            const response = await fetch(`${controllerUrl}?op=get_plans`);
            if (!response.ok) throw new Error('Error cargando planes');
            const data = await response.json();
            window.plansData = data;

            // Clear existing plans (except the "New Plan" button)
            // Strategy: Rebuild the container content
            let html = '';

            data.forEach(p => {
                const isDuo = p.id_internet && p.id_tv;
                const isTvOnly = !p.id_internet && p.id_tv;
                const isInternetOnly = p.id_internet && !p.id_tv;

                let cardClasses = 'bg-white border-gray-200 hover:border-gray-300';
                let icon = 'fa-wifi';
                let iconColor = 'text-primary';
                let priceColor = 'text-primary';
                let typeLabel = '';

                if (isDuo) {
                    cardClasses = 'bg-gradient-to-br from-white to-purple-50/50 border-purple-200 hover:border-purple-300 shadow-sm';
                    icon = 'fa-layer-group'; // Or fa-star
                    iconColor = 'text-purple-600';
                    priceColor = 'text-purple-700';
                    typeLabel = '<span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-bold ml-2">DUO</span>';
                } else if (isTvOnly) {
                    cardClasses = 'bg-gradient-to-br from-white to-pink-50/50 border-pink-200 hover:border-pink-300';
                    icon = 'fa-tv';
                    iconColor = 'text-pink-500';
                    priceColor = 'text-pink-600';
                    typeLabel = '<span class="text-[10px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded font-bold ml-2">TV</span>';
                } else {
                    // Internet Only (Default)
                    cardClasses = 'bg-gradient-to-br from-white to-blue-50/50 border-blue-200 hover:border-blue-300';
                    icon = 'fa-wifi';
                    iconColor = 'text-blue-500';
                    priceColor = 'text-blue-600';
                    typeLabel = '<span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold ml-2">NET</span>';
                }

                const isPopular = p.descripcion && p.descripcion.includes('Popular'); 
                const tag = isPopular ? '<span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-bold">POPULAR</span>' : '';

                html += `
                <div class="min-w-[260px] ${cardClasses} rounded-xl border p-5 hover:shadow-lg transition-all relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transform translate-x-1/4 -translate-y-1/4 transition-transform">
                        <i class="fas ${icon} text-9xl ${iconColor}"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-2">
                            <div class="p-2 rounded-lg bg-white/80 shadow-sm inline-flex">
                                <i class="fas ${icon} text-2xl ${iconColor}"></i>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                ${tag}
                                ${typeLabel}
                            </div>
                        </div>

                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-4 min-h-[3rem] line-clamp-2" title="${p.nombre}">${p.nombre}</h3>
                        
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-sm text-gray-400 font-medium">S/</span>
                            <span class="text-3xl font-extrabold ${priceColor}">${parseFloat(p.precio).toFixed(2)}</span>
                        </div>

                        <ul class="text-xs text-gray-500 space-y-2 mb-2">
                            ${p.velocidad_bajada ? `<li><i class="fas fa-download text-green-500 mr-2 w-4"></i> ${p.velocidad_bajada} Descarga</li>` : ''}
                            ${p.velocidad_subida ? `<li><i class="fas fa-upload text-blue-400 mr-2 w-4"></i> ${p.velocidad_subida} Subida</li>` : ''}
                            ${(isDuo || isTvOnly) ? `<li><i class="fas fa-tv text-pink-500 mr-2 w-4"></i> TV Incluida</li>` : ''}
                        </ul>
                    </div>
                </div>`;
            });

            // Re-add the "New Plan" button
            html += `
            <div onclick="openAddPlanModal()"
                class="min-w-[240px] bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-4 flex flex-col items-center justify-center text-gray-400 hover:border-primary hover:text-primary cursor-pointer transition-colors">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <span class="text-sm font-medium">Nuevo Plan Base</span>
            </div>`;

            container.innerHTML = html;

            // Also update the Select in Edit Modal
            updatePlanSelect(data);

        } catch (error) {
            console.error(error);
        }
    }

    function updatePlanSelect(plans) {
        const select = document.getElementById('selectPlanBase');
        let opts = '';
        plans.forEach(p => {
            opts += `<option value="${p.id_plan}">${p.nombre} (S/ ${parseFloat(p.precio).toFixed(2)})</option>`;
        });
        opts += '<option value="custom">-- PERSONALIZADO --</option>';
        select.innerHTML = opts;
    }

    async function loadServices(searchTerm = '') {
        const tbody = document.getElementById('servicesTableBody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin text-primary"></i> Cargando servicios...</td></tr>';

        try {
            const response = await fetch(`${controllerUrl}?op=search&search=${encodeURIComponent(searchTerm)}`);
            if (!response.ok) throw new Error('Error en respuesta del servidor');
            const data = await response.json();
            if (data.error) throw new Error(data.error);

            window.servicesData = data;
            renderServices(data);

        } catch (error) {
            console.error('Error:', error);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-500"><i class="fas fa-exclamation-circle"></i> Error: ${error.message}</td></tr>`;
        }
    }

    function renderServices(datos) {
        const tbody = document.getElementById('servicesTableBody');
        tbody.innerHTML = '';

        if (datos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No se encontraron servicios.</td></tr>';
            return;
        }

        datos.forEach(s => {
            const id = s.id_servicio;
            const cliente = s.nombre_cliente;
            const dni = s.dni;
            const plan = s.nombre_plan || 'Sin Plan';
            const precio = parseFloat(s.precio_plan || 0);
            const velocidad = s.velocidad_bajada;
            const direccion = s.direccion;
            const estado = s.estado;

            // Logic for Plan Tag
            let planTag = `<span class="font-medium text-gray-800">${plan}</span>
                           <div class="text-[10px] text-gray-400">S/ ${precio.toFixed(2)} - ${velocidad}Mb</div>`;

            // Logic Status Class
            let estadoClass = '';
            if (estado === 'ACTIVO') estadoClass = 'bg-green-100 text-green-700';
            else if (estado === 'EN_MORA') estadoClass = 'bg-orange-100 text-orange-700';
            else estadoClass = 'bg-red-100 text-red-700';

            const row = `
                <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">#${id}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800 text-sm">${cliente}</p>
                        <p class="text-xs text-gray-400">${dni}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">${planTag}</td>
                    <td class="px-6 py-4 text-xs text-gray-500 truncate max-w-xs">${direccion}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded text-[10px] font-bold ${estadoClass}">${estado}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openViewModal(${id})" class="w-8 h-8 rounded border border-gray-200 text-gray-500 hover:text-primary hover:border-primary transition-colors" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="openEditModal(${id})" class="w-8 h-8 rounded bg-primary text-white hover:bg-primary-dark shadow-sm transition-colors" title="Editar / Migrar">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // --- 3. MODALES ---

    // EDIT MODAL
    async function openEditModal(id) {
        // Fetch fresh details
        try {
            const res = await fetch(`${controllerUrl}?op=get_service_details&id=${id}`);
            if (!res.ok) throw new Error("Err");
            const servicio = await res.json();

            document.getElementById('modal_id_servicio').textContent = id;
            document.getElementById('modal_titular_actual').textContent = `${servicio.nombres} ${servicio.apellidos} (${servicio.dni})`;

            // Set Form Values
            document.getElementById('selectPlanBase').value = servicio.id_plan || 'custom';

            document.getElementById('checkCustom').checked = false;
            toggleCustomInputs(false);

            document.getElementById('inputSpeed').value = servicio.velocidad_bajada;
            document.getElementById('inputPrice').value = servicio.precio_plan;
            document.getElementById('inputIp').value = servicio.ip_asignada;

            switchTab('tabPlan');

            // Store current Editing ID on the modal for saving
            document.getElementById('editServiceModal').dataset.id = id;

            showModal('editServiceModal', 'modalPanelEdit');

        } catch (e) {
            alert("Error cargando detalles del servicio");
            console.error(e);
        }
    }

    async function saveChanges() {
        const modal = document.getElementById('editServiceModal');
        const id = modal.dataset.id;
        const id_plan = document.getElementById('selectPlanBase').value;
        const ip = document.getElementById('inputIp').value;

        // Basic validation
        if (!id || !id_plan) return;

        try {
            const res = await fetch(`${controllerUrl}?op=update_service`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_servicio: id,
                    id_plan: id_plan,
                    ip: ip
                })
            });
            const data = await res.json();
            if (data.success) {
                alert("Servicio actualizado correctamente");
                closeModal('editServiceModal');
                loadServices(); // Reload table
            } else {
                throw new Error(data.error || "Error al actualizar");
            }
        } catch (e) {
            alert(e.message);
        }
    }

    // VIEW MODAL (To be implemented fully, reuse Edit structure or create new)
    function openViewModal(id) {
        // For now same as edit but maybe read-only? 
        // User asked for "Ver" functionality. 
        // We can reuse openEditModal but disable inputs or just show them.
        openEditModal(id);
    }

    // ADD PLAN MODAL
    function openAddPlanModal() {
        // Assuming we have a modal for adding plans. 
        // Since the HTML for Add Plan Modal is not in the original file, 
        // I will dynamically create it or reuse/alert if not requested to add HTML.
        // The user asked to "agregar modales faltantes para agregas planes nuebos".
        // I will inject the HTML for this modal.

        let modal = document.getElementById('addPlanModal');
        if (!modal) {
            createAddPlanModalHtml();
            modal = document.getElementById('addPlanModal');
        }
        showModal('addPlanModal', 'modalPanelPlan');
    }

    function createAddPlanModalHtml() {
        // Generate Options for Selects
        let intOpts = '<option value="">-- Sin Internet --</option>';
        window.internetOptions.forEach(opt => {
            intOpts += `<option value="${opt.id_internet}" data-price="${opt.precio}" data-speed="${opt.velocidad}">${opt.velocidad} - S/ ${opt.precio}</option>`;
        });

        let tvOpts = '<option value="">-- Sin TV --</option>';
        window.tvOptions.forEach(opt => {
            tvOpts += `<option value="${opt.id_tv}" data-price="${opt.precio}">${opt.nombre} - S/ ${opt.precio}</option>`;
        });

        const html = `
        <div id="addPlanModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95" id="modalPanelPlan">
                <div class="bg-primary p-4 rounded-t-xl text-white flex justify-between items-center">
                    <h3 class="font-bold">Nuevo Plan Comercial</h3>
                    <button onclick="closeModal('addPlanModal')"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="label-prored">Nombre del Plan</label>
                        <input type="text" id="newPlanName" class="input-prored" placeholder="Ej. Combo Fibra + TV">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label-prored">Servicio Internet</label>
                            <select id="selectInternetNew" class="input-prored" onchange="calculateNewPlanPrice()">
                                ${intOpts}
                            </select>
                        </div>
                        <div>
                            <label class="label-prored">Servicio TV</label>
                            <select id="selectTvNew" class="input-prored" onchange="calculateNewPlanPrice()">
                                ${tvOpts}
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div>
                            <label class="label-prored text-xs">Velocidad (Bajada/Subida)</label>
                            <input type="text" id="newPlanSpeed" class="input-prored bg-white" placeholder="Ej. 100 Mbps" readonly>
                        </div>
                        <div>
                            <label class="label-prored text-xs">Precio Total (S/)</label>
                            <input type="number" id="newPlanPrice" class="input-prored bg-white font-bold text-primary" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-b-xl flex justify-end gap-2">
                    <button onclick="closeModal('addPlanModal')" class="btn-outline">Cancelar</button>
                    <button onclick="saveNewPlan()" class="btn-primary">Guardar Plan</button>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
    }

    function calculateNewPlanPrice() {
        const intSelect = document.getElementById('selectInternetNew');
        const tvSelect = document.getElementById('selectTvNew');

        const intOpt = intSelect.options[intSelect.selectedIndex];
        const tvOpt = tvSelect.options[tvSelect.selectedIndex];

        let price = 0;
        let speed = '';

        if (intSelect.value) {
            price += parseFloat(intOpt.dataset.price || 0);
            speed = intOpt.dataset.speed || '';
        }
        if (tvSelect.value) {
            price += parseFloat(tvOpt.dataset.price || 0);
        }

        document.getElementById('newPlanPrice').value = price.toFixed(2);

        // Auto-fill speed if not custom
        if (speed) document.getElementById('newPlanSpeed').value = speed;

        // Auto-suggest name if empty
        const nameInput = document.getElementById('newPlanName');
        if (!nameInput.value && (intSelect.value || tvSelect.value)) {
            let name = '';
            if (intSelect.value) name += `Internet ${speed.replace(' Mbps', '')}`;
            if (tvSelect.value) name += (name ? ' + ' : '') + tvOpt.text.split(' - ')[0];
            nameInput.placeholder = name; // Just hint
        }
    }

    async function saveNewPlan() {
        const nombre = document.getElementById('newPlanName').value;
        const speed = document.getElementById('newPlanSpeed').value; // This is a string now (e.g. "20 Mbps"), need to be careful if backend expects int. 
        // Backend expects 'velocidad_bajada' varchar, so string is fine.
        const price = document.getElementById('newPlanPrice').value;

        const id_internet = document.getElementById('selectInternetNew').value;
        const id_tv = document.getElementById('selectTvNew').value;

        if (!nombre || (!id_internet && !id_tv) || !price) {
            alert("Seleccione al menos un servicio y complete el nombre");
            return;
        }

        try {
            const res = await fetch(`${controllerUrl}?op=create_plan`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nombre: nombre,
                    velocidad_bajada: speed, // sending full string
                    velocidad_subida: speed, // assuming symmetric for now or same string
                    precio: price,
                    id_internet: id_internet,
                    id_tv: id_tv
                })
            });
            const data = await res.json();
            if (data.success) {
                alert("Plan creado exitosamente");
                closeModal('addPlanModal');
                loadPlans(); // Refresh catalogue
            } else {
                alert("Error al crear el plan");
            }
        } catch (e) {
            console.error(e);
            alert("Error de conexión");
        }
    }

    // UTILS
    function showModal(id, panelId) {
        const modal = document.getElementById(id);
        const panel = document.getElementById(panelId);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[id^="modalPanel"]');
        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Reuse existing Tab/Toggle functions...
    function switchTab(tabId) {
        document.getElementById('tabPlan').classList.add('hidden');
        document.getElementById('tabTitular').classList.add('hidden');
        document.getElementById('btnTabPlan').className = "flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors border-b-2 border-transparent";
        document.getElementById('btnTabTitular').className = "flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition-colors border-b-2 border-transparent";

        document.getElementById(tabId).classList.remove('hidden');
        const activeClass = "flex-1 py-3 text-sm font-medium focus:outline-none transition-colors text-primary border-b-2 border-primary bg-white";

        if (tabId === 'tabPlan') document.getElementById('btnTabPlan').className = activeClass;
        else document.getElementById('btnTitular').className = activeClass;
    }

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
        check.checked = isChecked;
        if (isChecked) inputs.classList.remove('opacity-50', 'pointer-events-none');
        else inputs.classList.add('opacity-50', 'pointer-events-none');
    }

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

    function confirmarBaja() {
        if (confirm("¿ESTÁ SEGURO?")) {
            alert("Solicitud procesada");
            closeModal('editServiceModal');
        }
    }
</script>