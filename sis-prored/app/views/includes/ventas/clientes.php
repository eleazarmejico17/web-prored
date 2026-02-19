<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Directorio de Clientes</h1>
            <p class="text-sm text-gray-500">Administración de cuentas y accesos al portal de usuarios.</p>
        </div>
        <button onclick="openNewClientModal()" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Cliente
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card-base p-6 border-l-4 border-blue-500 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Clientes Nuevos (Mes)</p>
                    <h3 id="stat-nuevos" class="text-3xl font-bold text-gray-800 mt-2">-</h3>
                </div>
                <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                    <i class="fas fa-user-clock text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-blue-500 mt-2 font-medium">+12% vs mes anterior</p>
        </div>

        <div class="card-base p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Acceso Habilitado</p>
                    <h3 id="stat-habilitados" class="text-3xl font-bold text-gray-800 mt-2">-</h3>
                </div>
                <div class="p-3 rounded-lg bg-green-50 text-green-600">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Usuarios con login activo</p>
        </div>

        <div class="card-base p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin Acceso al Sistema</p>
                    <h3 id="stat-sin-acceso" class="text-3xl font-bold text-gray-800 mt-2">-</h3>
                </div>
                <div class="p-3 rounded-lg bg-orange-50 text-orange-500 animate-pulse">
                    <i class="fas fa-user-lock text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-orange-500 mt-2 font-medium">Requieren creación de usuario</p>
        </div>
    </div>

    <div class="card-base p-6">
        <h2 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-primary"></i> Filtros de Búsqueda
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="label-prored text-xs">DNI / RUC</label>
                <input type="text" id="filtroDni" class="input-prored text-sm" placeholder="Buscar documento...">
            </div>
            <div>
                <label class="label-prored text-xs">Apellidos</label>
                <input type="text" id="filtroApellido" class="input-prored text-sm" placeholder="Apellido paterno...">
            </div>
            <div>
                <label class="label-prored text-xs">Nombres</label>
                <input type="text" id="filtroNombre" class="input-prored text-sm" placeholder="Nombre...">
            </div>
            <div>
                <button onclick="aplicarFiltros()" class="btn-secondary w-full justify-center text-sm">
                    <i class="fas fa-search mr-2"></i> Buscar
                </button>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Cliente / DNI</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4">Plan Actual</th>
                        <th class="px-6 py-4 text-center">Acceso Sistema</th>
                        <th class="px-6 py-4 text-right">Credenciales</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="tablaClientes">
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-end bg-gray-50/30">
            <nav class="flex gap-1">
                <button
                    class="px-3 py-1 rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-xs">Anterior</button>
                <button class="px-3 py-1 rounded bg-primary text-white text-xs font-bold">1</button>
                <button
                    class="px-3 py-1 rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-xs">2</button>
                <button
                    class="px-3 py-1 rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-xs">Siguiente</button>
            </nav>
        </div>
    </div>

</div>

<div id="credencialModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95" id="modalPanelCreds">

        <div class="bg-primary p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg" id="modalTitle">Gestión de Acceso</h3>
            <button onclick="closeModal('credencialModal')" class="hover:text-gray-200"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6">
            <div class="mb-4 text-center">
                <div
                    class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-2 text-2xl">
                    <i class="fas fa-user-lock"></i>
                </div>
                <h4 class="font-bold text-gray-800" id="credClientName">Cliente Nombre</h4>
                <p class="text-xs text-gray-500" id="credClientDni">DNI: 00000000</p>
            </div>

            <form onsubmit="guardarCredenciales(event)" class="space-y-4">
                <input type="hidden" id="credClientId">

                <div>
                    <label class="label-prored">Usuario / Login</label>
                    <div class="relative">
                        <input type="text" id="credUser" class="input-prored pl-9 bg-gray-50" readonly>
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">* El usuario por defecto es el DNI/RUC.</p>
                </div>

                <div>
                    <label class="label-prored">Contraseña</label>
                    <div class="relative">
                        <input type="password" id="credPass" class="input-prored pl-9 pr-10"
                            placeholder="Nueva contraseña">
                        <i class="fas fa-key absolute left-3 top-3 text-gray-400 text-xs"></i>
                        <button type="button" onclick="togglePass()"
                            class="absolute right-3 top-3 text-gray-400 hover:text-primary">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="sendWhatsapp" checked class="rounded text-primary focus:ring-primary">
                    <label for="sendWhatsapp" class="text-xs text-gray-600">Enviar accesos por WhatsApp al
                        cliente</label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('credencialModal')"
                        class="btn-outline w-full justify-center">Cancelar</button>
                    <button type="submit" class="btn-primary w-full justify-center shadow-lg">
                        <i class="fas fa-save mr-2"></i> Guardar Acceso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL NUEVO/EDITAR CLIENTE -->
<div id="clientModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="modalPanelClient">
        <div class="bg-primary p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg" id="clientModalTitle">Nuevo Cliente</h3>
            <button onclick="closeModal('clientModal')" class="hover:text-gray-200"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="p-6">
            <form id="clientForm" onsubmit="saveClient(event)" class="space-y-4">
                <input type="hidden" id="clientId">

                <!-- TIPO DE CLIENTE -->
                <div>
                    <label class="label-prored">Tipo de Cliente</label>
                    <div class="flex gap-4 mt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="clientType" value="NATURAL" class="form-radio text-primary"
                                checked onchange="toggleClientFields()">
                            <span class="ml-2 text-gray-700">Persona Natural</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="clientType" value="EMPRESA" class="form-radio text-primary"
                                onchange="toggleClientFields()">
                            <span class="ml-2 text-gray-700">Empresa</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored" id="lblDoc">DNI *</label>
                        <input type="text" id="clientDni" class="input-prored" required maxlength="8"
                            placeholder="8 dígitos">
                    </div>
                    <div>
                        <label class="label-prored">Teléfono Contacto</label>
                        <input type="text" id="clientPhone" class="input-prored">
                    </div>
                </div>

                <!-- BLOCK NATURAL -->
                <div id="blockNatural" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Nombres *</label>
                        <input type="text" id="clientName" class="input-prored">
                    </div>
                    <div>
                        <label class="label-prored">Apellidos *</label>
                        <input type="text" id="clientLastname" class="input-prored">
                    </div>
                </div>

                <!-- BLOCK EMPRESA -->
                <div id="blockCompany" class="hidden">
                    <label class="label-prored">Razón Social *</label>
                    <input type="text" id="clientCompany" class="input-prored" placeholder="Nombre de la empresa">
                </div>

                <div>
                    <label class="label-prored">Correo Electrónico</label>
                    <input type="email" id="clientEmail" class="input-prored">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('clientModal')"
                        class="btn-outline w-full justify-center">Cancelar</button>
                    <button type="submit" class="btn-primary w-full justify-center shadow-lg">
                        <i class="fas fa-save mr-2"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DETALLE CLIENTE -->
<div id="viewClientModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95" id="modalPanelView">
        <div class="bg-gray-800 p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fas fa-id-card mr-2"></i> Ficha del Cliente</h3>
            <button onclick="closeModal('viewClientModal')" class="hover:text-gray-300"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="p-6" id="viewClientContent">
            <!-- Content loaded via JS -->
            <div class="text-center py-8"><i class="fas fa-spinner fa-spin text-4xl text-gray-300"></i></div>
        </div>
        <div class="bg-gray-50 p-4 rounded-b-xl flex justify-end">
            <button onclick="closeModal('viewClientModal')" class="btn-primary">Cerrar</button>
        </div>
    </div>
</div>

<script>
    // --- 1. FUNCIÓN DE CARGA DE DATOS ---
    async function loadClients(searchTerm = '') {
        const tbody = document.getElementById('tablaClientes');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin text-primary"></i> Cargando clientes...</td></tr>';

        try {
            const response = await fetch(`../controllers/VentasController.php?op=search_clients&search=${encodeURIComponent(searchTerm)}`);

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            renderTabla(data);

        } catch (error) {
            console.error('Error:', error);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-500"><i class="fas fa-exclamation-circle"></i> Error al cargar datos: ${error.message}</td></tr>`;
        }
    }

    // --- 2. RENDERIZADO DE TABLA ---
    function renderTabla(datos) {
        const tbody = document.getElementById('tablaClientes');
        tbody.innerHTML = '';

        if (datos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No se encontraron clientes.</td></tr>';
            return;
        }

        datos.forEach(c => {
            // Determinar nombre a mostrar
            let nombreCompleto = c.razon_social;
            if (!nombreCompleto) {
                nombreCompleto = `${c.nombres} ${c.apellidos}`.trim();
            }

            // Lógica de Estado de Acceso
            let accesoBadge = '';
            let accionBtn = '';

            // has_access viene como 1 o 0 de la BD
            const hasAccess = parseInt(c.has_access) > 0;

            if (hasAccess) {
                // Caso: TIENE Credenciales -> Botón Editar
                accesoBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i> Habilitado
                               </span>`;

                accionBtn = `<button onclick="openCredModal(${c.id_cliente}, '${c.dni}', '${nombreCompleto.replace(/'/g, "\\'")}', true)" class="text-xs bg-white border border-gray-300 text-gray-600 hover:text-primary hover:border-primary px-3 py-1.5 rounded transition-colors shadow-sm flex items-center ml-auto gap-1">
                                <i class="fas fa-key text-orange-400"></i> Restablecer
                             </button>`;
            } else {
                // Caso: NO TIENE Credenciales -> Botón Asignar
                accesoBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                <i class="fas fa-times-circle mr-1"></i> Sin Acceso
                               </span>`;

                accionBtn = `<button onclick="openCredModal(${c.id_cliente}, '${c.dni}', '${nombreCompleto.replace(/'/g, "\\'")}', false)" class="text-xs bg-primary text-white hover:bg-primary-dark px-3 py-1.5 rounded transition-colors shadow-md flex items-center ml-auto gap-1">
                                <i class="fas fa-user-plus"></i> Crear Usuario
                             </button>`;
            }

            const plan = c.plan || 'Sin Plan Activo';
            const telefono = c.telefono || '-';

            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50 transition-colors group border-b border-gray-50";
            tr.innerHTML = `
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                            ${nombreCompleto.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">${nombreCompleto}</p>
                            <p class="text-xs text-gray-400 font-mono">${c.dni}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    <i class="fas fa-phone text-gray-400 mr-1"></i> ${telefono}
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-medium bg-blue-50 text-blue-600 px-2 py-1 rounded">${plan}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    ${accesoBadge}
                </td>
                <td class="px-6 py-4 text-right">
                    ${accionBtn}
                </td>
                <td class="px-6 py-4 text-right">
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <button onclick="openViewModal(${c.id_cliente})" class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Ver Detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="openEditClientModal(${c.id_cliente})" class="text-gray-400 hover:text-primary transition-colors p-1" title="Editar Info Cliente">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Inicializar carga
    document.addEventListener('DOMContentLoaded', () => {
        loadClients();
        loadClientStats();
    });

    async function loadClientStats() {
        try {
            const response = await fetch(`../controllers/VentasController.php?op=get_client_stats`);
            if (!response.ok) throw new Error('Error loading stats');
            const data = await response.json();

            // Animate Numbers
            animateValue("stat-nuevos", 0, data.nuevos, 1000);
            animateValue("stat-habilitados", 0, data.con_acceso, 1000);
            animateValue("stat-sin-acceso", 0, data.sin_acceso, 1000);

        } catch (error) {
            console.error('Error stats:', error);
        }
    }

    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        if (!obj) return;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // --- 3. FILTRADO ---
    function aplicarFiltros() {
        // En este diseño simplificado por backend, usaremos un solo término de búsqueda
        // Podríamos concatenar o priorizar inputs. Por simplicidad, tomaremos el valor más largo
        // o concatenaremos.

        const dni = document.getElementById('filtroDni').value.trim();
        const apellido = document.getElementById('filtroApellido').value.trim();
        const nombre = document.getElementById('filtroNombre').value.trim();

        // Prioridad: Si hay DNI, buscar por DNI. Si no, Apellido > Nombre
        let searchTerm = dni;
        if (!searchTerm && apellido) searchTerm = apellido;
        if (!searchTerm && nombre) searchTerm = nombre;

        // Si queremos ser más flexibles, podríamos mandar todos los campos
        // y que el backend decida, pero searchClients() de VentasController
        // solo espera 'search'. Asi que mandamos el termino que haya.

        loadClients(searchTerm);
    }

    // Event listeners para 'Enter' en los inputs
    ['filtroDni', 'filtroApellido', 'filtroNombre'].forEach(id => {
        document.getElementById(id).addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                aplicarFiltros();
            }
        });
    });

    // --- 4. GESTIÓN MODAL DE CREDENCIALES ---
    let currentClientId = null;

    function openCredModal(id, dni, nombre, hasAccess) {
        currentClientId = id;

        // Elementos del DOM
        const modal = document.getElementById('credencialModal');
        const panel = document.getElementById('modalPanelCreds');
        const title = document.getElementById('modalTitle');
        const btnSubmit = modal.querySelector('button[type="submit"]');

        // Llenar datos cliente
        document.getElementById('credClientName').textContent = nombre;
        document.getElementById('credClientDni').textContent = "DNI: " + dni;
        document.getElementById('credUser').value = dni; // Por defecto DNI
        document.getElementById('credPass').value = ""; // Limpiar pass
        document.getElementById('credClientId').value = id;

        // Configurar Estado (Crear vs Editar)
        if (hasAccess) {
            title.textContent = "Restablecer Contraseña";
            btnSubmit.innerHTML = '<i class="fas fa-sync-alt mr-2"></i> Actualizar Clave';
            btnSubmit.className = "btn-secondary w-full justify-center shadow-lg"; // Naranja para editar
        } else {
            title.textContent = "Asignar Nuevas Credenciales";
            btnSubmit.innerHTML = '<i class="fas fa-save mr-2"></i> Crear Usuario';
            btnSubmit.className = "btn-primary w-full justify-center shadow-lg"; // Azul para crear
        }

        // Mostrar Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    async function guardarCredenciales(e) {
        e.preventDefault();
        const pass = document.getElementById('credPass').value;
        const whatsapp = document.getElementById('sendWhatsapp').checked;
        const idCliente = document.getElementById('credClientId').value;
        const dni = document.getElementById('credUser').value; // Usamos DNI como username

        if (pass.length < 4) {
            alert("La contraseña debe tener al menos 4 caracteres.");
            return;
        }

        // Aquí conectamos con UsuarioController para guardar
        // Reutilizamos la lógica que ya existía o creamos una petición ad-hoc
        // Dado que estamos en el módulo ventas, lo ideal es llamar a 
        // app/controllers/UsuarioController.php?op=guardar pero eso espera un POST form data

        const formData = new FormData();
        // Necesitamos saber si estamos creando o editando.
        // Simplificación: Asumimos 'CLIENTE' role (id_rol=5?? no, rol cliente es 4 segun insert.sql)
        // insert.sql: ('user', 'Cliente') id=4

        // El UsuarioController espera: id_usuario (vacío para crear), id_rol, nombre, email, password, activo, id_cliente

        // Problema: No tenemos el email ni el nombre completo separado facil aquí.
        // Pero el backend de UsuarioController::guardar espera esos datos.
        // Para no complicar, haremos una llamada simbiótica a VentasController si tuviera ese método,
        // o usaremos UsuarioController asumiendo que podemos enviar los datos mínimos.

        // Al NO tener un endpoint específico para "Asignar credencial simple", 
        // usaremos una alerta para indicar que esta funcionalidad requiere integración completa.
        // O mejor: implementamos un método rápido en VentasModel para setear password del cliente.

        alert("Funcionalidad de guardado en proceso de integración con el backend.");

        // TODO: Implementar endpoint real para guardar usuario desde ventas

        closeModal('credencialModal');
        loadClients(); // Recargar tabla
    }

    // --- UTILIDADES ---
    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[class*="transform"]');

        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function togglePass() {
        const input = document.getElementById('credPass');
        const icon = document.getElementById('eyeIcon');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // --- CLIENT MODAL LOGIC ---

    function toggleClientFields() {
        const type = document.querySelector('input[name="clientType"]:checked').value;
        const blockNatural = document.getElementById('blockNatural');
        const blockCompany = document.getElementById('blockCompany');
        const lblDoc = document.getElementById('lblDoc');
        const txtDni = document.getElementById('clientDni');

        if (type === 'NATURAL') {
            blockNatural.classList.remove('hidden');
            blockCompany.classList.add('hidden');
            lblDoc.textContent = 'DNI *';
            txtDni.maxLength = 8;
            txtDni.placeholder = '8 dígitos';
        } else {
            blockNatural.classList.add('hidden');
            blockCompany.classList.remove('hidden');
            lblDoc.textContent = 'RUC *';
            txtDni.maxLength = 11;
            txtDni.placeholder = '11 dígitos';
        }
    }

    function openNewClientModal() {
        document.getElementById('clientForm').reset();
        document.getElementById('clientId').value = '';
        document.getElementById('clientModalTitle').textContent = 'Nuevo Cliente';

        // Reset to Natural
        document.querySelector('input[name="clientType"][value="NATURAL"]').checked = true;
        toggleClientFields();

        openModal('clientModal', 'modalPanelClient');
    }

    async function openEditClientModal(id) {
        openModal('clientModal', 'modalPanelClient');
        document.getElementById('clientModalTitle').textContent = 'Editar Cliente';
        document.getElementById('clientForm').reset();

        try {
            const res = await fetch(`../controllers/VentasController.php?op=get_client_details&id=${id}`);
            if (!res.ok) throw new Error("Error en la petición al servidor");

            const data = await res.json();
            if (data.error) throw new Error(data.error);

            document.getElementById('clientId').value = data.id_cliente;
            document.getElementById('clientDni').value = data.dni;
            document.getElementById('clientPhone').value = data.telefono || '';
            document.getElementById('clientEmail').value = data.email || '';

            // Determine Type
            if (data.razon_social && data.razon_social.trim() !== '') {
                document.querySelector('input[name="clientType"][value="EMPRESA"]').checked = true;
                document.getElementById('clientCompany').value = data.razon_social;
                document.getElementById('clientName').value = '';
                document.getElementById('clientLastname').value = '';
            } else {
                document.querySelector('input[name="clientType"][value="NATURAL"]').checked = true;
                document.getElementById('clientName').value = data.nombres || '';
                document.getElementById('clientLastname').value = data.apellidos || '';
                document.getElementById('clientCompany').value = '';
            }
            toggleClientFields();

        } catch (e) {
            console.error(e);
            alert("Error cargando datos del cliente: " + e.message);
            closeModal('clientModal');
        }
    }

    async function saveClient(e) {
        e.preventDefault();

        const type = document.querySelector('input[name="clientType"]:checked').value;
        const dni = document.getElementById('clientDni').value.trim();

        let nombres = null;
        let apellidos = null;
        let razon_social = null;

        if (type === 'NATURAL') {
            if (dni.length !== 8) return alert("El DNI debe tener 8 dígitos");

            nombres = document.getElementById('clientName').value.trim();
            apellidos = document.getElementById('clientLastname').value.trim();

            if (!nombres || !apellidos) return alert("Nombres y Apellidos son obligatorios");

        } else {
            if (dni.length !== 11) return alert("El RUC debe tener 11 dígitos");

            razon_social = document.getElementById('clientCompany').value.trim();
            if (!razon_social) return alert("La Razón Social es obligatoria");uedes ca
        }

        const data = {
            id_cliente: document.getElementById('clientId').value,
            dni: dni,
            nombres: nombres,
            apellidos: apellidos,
            razon_social: razon_social,
            email: document.getElementById('clientEmail').value,
            telefono: document.getElementById('clientPhone').value
        };

        try {
            const res = await fetch(`../controllers/VentasController.php?op=save_client`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();

            if (result.success) {
                closeModal('clientModal');
                loadClients();
                loadClientStats(); // Refresh stats too
                // alert(result.message);
            } else {
                alert(result.error || "Error al guardar");
            }
        } catch (e) {
            console.error(e);
            alert("Error de conexión");
        }
    }

    // --- VIEW CLIENT MODAL ---
    async function openViewModal(id) {
        openModal('viewClientModal', 'modalPanelView');
        const content = document.getElementById('viewClientContent');
        content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-4xl text-primary"></i></div>';

        try {
            const res = await fetch(`../controllers/VentasController.php?op=get_client_details&id=${id}`);
            const data = await res.json();

            if (data.error) throw new Error(data.error);

            // Determine display name
            const displayName = data.razon_social || `${data.nombres} ${data.apellidos}`;

            content.innerHTML = `
                <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-16 h-16 rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold">
                        ${displayName.substring(0, 2).toUpperCase()}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">${displayName}</h2>
                        <p class="text-gray-500 font-mono"><i class="fas fa-id-card mr-2 text-gray-400"></i>${data.dni}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="px-3 py-1 rounded-full text-xs font-bold ${data.activo == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                            ${data.activo == 1 ? 'ACTIVO' : 'INACTIVO'}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Información de Contacto</h4>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-envelope w-5 text-center text-gray-400"></i>
                            <span class="text-gray-700">${data.email || 'No registrado'}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-phone w-5 text-center text-gray-400"></i>
                            <span class="text-gray-700">${data.telefono || 'No registrado'}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-map-marker-alt w-5 text-center text-gray-400"></i>
                            <span class="text-gray-700">Ubigeo: ${data.ubigeo || '-'}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Datos Adicionales</h4>
                        <p class="text-xs text-gray-500 mb-1">Registrado el:</p>
                        <p class="font-medium text-gray-800 mb-3">${data.creado_en || 'N/A'}</p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                             <p class="text-xs text-gray-500 italic">Historial de servicios y pagos próximamente visible aquí.</p>
                        </div>
                    </div>
                </div>
            `;

        } catch (e) {
            content.innerHTML = `<div class="text-red-500 text-center p-4">Error: ${e.message}</div>`;
        }
    }


    // --- HELPER FOR MODALS ---
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
</script>