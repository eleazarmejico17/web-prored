<?php
// Gestión de Usuarios - Frontend
// Se conecta vía AJAX a app/controllers/UsuarioController.php
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
            <p class="text-sm text-gray-500">Administración de perfiles, roles y accesos al sistema.</p>
        </div>
        <button onclick="openEditModal()"
            class="btn-primary shadow-lg hover:shadow-xl transition-all transform active:scale-95 px-6">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
        </button>
        <button onclick="openAssignModal()"
            class="btn-secondary shadow-lg hover:shadow-xl transition-all transform active:scale-95 px-6 ml-2">
            <i class="fas fa-id-card mr-2"></i> Asignar Credenciales
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <div onclick="filterUsers('CLIENTE')" id="card-CLIENTE"
            class="card-base p-4 border-l-4 border-primary cursor-pointer hover:shadow-lg transition-all transform hover:-translate-y-1 group relative overflow-hidden bg-blue-50/50">
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Clientes</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="count-CLIENTE">0</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-blue-100 text-primary flex items-center justify-center text-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div onclick="filterUsers('VENTAS')" id="card-VENTAS"
            class="card-base p-4 border-l-4 border-green-500 cursor-pointer hover:shadow-lg transition-all transform hover:-translate-y-1 group relative overflow-hidden">
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ventas</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="count-VENTAS">0</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <div onclick="filterUsers('SOPORTE')" id="card-SOPORTE"
            class="card-base p-4 border-l-4 border-orange-500 cursor-pointer hover:shadow-lg transition-all transform hover:-translate-y-1 group relative overflow-hidden">
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Soporte Técnico</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="count-SOPORTE">0</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i class="fas fa-headset"></i>
                </div>
            </div>
        </div>

        <div onclick="filterUsers('TECNICO')" id="card-TECNICO"
            class="card-base p-4 border-l-4 border-gray-500 cursor-pointer hover:shadow-lg transition-all transform hover:-translate-y-1 group relative overflow-hidden">
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Técnicos Campo</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="count-TECNICO">0</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-lg group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-base overflow-hidden min-h-[400px]">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800" id="tableTitle">Listado de Usuarios</h3>
            <div class="relative w-64">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Buscar en esta lista..."
                    class="input-prored pl-8 py-1.5 text-xs">
                <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-white text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Identificación</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="userTableBody">
                </tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-10 text-gray-400">
            <i class="fas fa-users-slash text-4xl mb-3"></i>
            <p>No se encontraron usuarios en este rol.</p>
        </div>
    </div>
</div>

<div id="assignModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="assignPanel">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Asignar Credenciales a Cliente</h3>
                <p class="text-xs text-gray-500">Seleccione un cliente para crearle un usuario</p>
            </div>
            <button onclick="closeModal('assignModal')" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar">
            <div class="mb-4">
                <input type="text" id="searchClientInput" onkeyup="searchClientList()"
                    placeholder="Buscar por nombre o DNI..." class="input-prored bg-gray-50 w-full pl-10">
                <i class="fas fa-search absolute left-9 top-[6.5rem] text-gray-400"></i>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">DNI / RUC</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="clientTableBody">
                        <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>

            <div id="emptyClients" class="hidden text-center py-8 text-gray-400">
                <i class="fas fa-check-circle text-3xl mb-2 text-green-200"></i>
                <p>No hay clientes pendientes de usuario.</p>
            </div>
        </div>
    </div>
</div>

<div id="viewModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-95" id="viewPanel">

        <div class="relative h-24 bg-gradient-to-r from-primary to-blue-600 rounded-t-xl overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-20">
                <i class="fas fa-network-wired text-8xl absolute -top-4 -right-4 text-white"></i>
            </div>
            <button onclick="closeModal('viewModal')"
                class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/20 rounded-full w-8 h-8 flex items-center justify-center"><i
                    class="fas fa-times"></i></button>

            <div class="absolute -bottom-10 left-6">
                <div class="w-20 h-20 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-3xl font-bold text-primary"
                    id="v_avatar">
                </div>
            </div>
        </div>

        <div class="pt-12 px-6 pb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800" id="v_nombre">--</h2>
                    <p class="text-sm text-gray-500" id="v_email">--</p>
                </div>
                <button id="btnSwitchEdit" onclick="" class="btn-secondary text-xs px-3 py-1.5 shadow-sm">
                    <i class="fas fa-pencil-alt mr-1"></i> Editar este usuario
                </button>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Rol Asignado</span>
                    <span class="font-bold text-primary" id="v_rol">--</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Estado Cuenta</span>
                    <span id="v_estado">--</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">DNI / RUC</span>
                    <p class="text-gray-700 font-mono" id="v_dni">--</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Teléfono</span>
                    <p class="text-gray-700" id="v_telefono">--</p>
                </div>
                <div class="col-span-2">
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Dirección</span>
                    <p class="text-gray-700" id="v_direccion">--</p>
                </div>
                <div class="col-span-2 border-t border-gray-100 pt-2 mt-2">
                    <span class="text-xs text-gray-400">Registrado el: <span id="v_fecha">--</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 flex flex-col max-h-[90vh]"
        id="editPanel">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <h3 class="font-bold text-gray-800 text-lg" id="modalTitle">Nuevo Usuario</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="userForm" class="space-y-4">
                <input type="hidden" id="e_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="label-prored">Nombre Completo / Razón Social</label>
                        <input type="text" id="e_nombre" class="input-prored" required>
                    </div>

                    <div>
                        <label class="label-prored">DNI / RUC</label>
                        <input type="text" id="e_dni" class="input-prored font-mono" required>
                    </div>

                    <div>
                        <label class="label-prored">Teléfono / Celular</label>
                        <input type="tel" id="e_telefono" class="input-prored">
                    </div>

                    <div class="col-span-2">
                        <label class="label-prored">Dirección</label>
                        <input type="text" id="e_direccion" class="input-prored">
                    </div>

                    <div class="col-span-2 border-t border-gray-100 pt-4 mt-2">
                        <h4 class="text-xs font-bold text-primary uppercase mb-3">Configuración de Cuenta</h4>
                    </div>

                    <div>
                        <label class="label-prored">Correo Electrónico (Usuario)</label>
                        <input type="email" id="e_email" class="input-prored" required>
                    </div>

                    <div>
                        <label class="label-prored">Rol de Sistema</label>
                        <select id="e_rol" class="input-prored bg-white" required>
                            <option value="CLIENTE">Cliente</option>
                            <option value="VENTAS">Ventas</option>
                            <option value="SOPORTE">Soporte Técnico</option>
                            <option value="TECNICO">Técnico de Campo</option>
                            <option value="ADMIN">Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="label-prored">Contraseña</label>
                        <input type="password" id="e_password" class="input-prored"
                            placeholder="Dejar en blanco para no cambiar">
                    </div>

                    <div>
                        <label class="label-prored">Estado</label>
                        <select id="e_estado" class="input-prored bg-white">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo / Bloqueado</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeModal('editModal')" class="btn-outline">Cancelar</button>
            <button onclick="saveUser()" class="btn-primary">Guardar Cambios</button>
        </div>
    </div>
</div>

<script>
    let dbUsers = [];
    let currentFilter = 'CLIENTE'; // Default: Mostrar clientes
    const controllerUrl = 'http://localhost/web-prored/sis-prored/app/controllers/UsuarioController.php';

    // --- INICIALIZACIÓN ---
    document.addEventListener('DOMContentLoaded', () => {
        fetchUsers();
    });

    // --- CRUD AJAX ---
    async function fetchUsers() {
        try {
            const response = await fetch(`${controllerUrl}?op=listar`);
            const data = await response.json();

            if (data.error) {
                alert('Error al cargar: ' + data.error);
                return;
            }
            dbUsers = data;

            // Recalcular conteos
            updateCounts();

            // Renderizar vista actual
            filterUsers(currentFilter);

        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    function updateCounts() {
        const counts = {};
        dbUsers.forEach(u => {
            const r = u.rol.toUpperCase(); // Rol viene de DB
            // Mapeamos roles de DB a tarjetas si difieren en nombre exacto
            // DB: admin, soporte_tecnico, tecnico_campo, ventas, user
            // Frontend Cards: CLIENTE (user?), VENTAS, SOPORTE (soporte_tecnico?), TECNICO (tecnico_campo?)

            // Mapeo normalizado
            let key = normalizeRole(r);
            counts[key] = (counts[key] || 0) + 1;
        });

        document.getElementById('count-CLIENTE').innerText = counts['CLIENTE'] || 0;
        document.getElementById('count-VENTAS').innerText = counts['VENTAS'] || 0;
        document.getElementById('count-SOPORTE').innerText = counts['SOPORTE'] || 0;
        document.getElementById('count-TECNICO').innerText = counts['TECNICO'] || 0;
    }

    function normalizeRole(dbRole) {
        dbRole = dbRole.toUpperCase();
        if (dbRole === 'USER' || dbRole === 'CLIENTE') return 'CLIENTE';
        if (dbRole === 'SOPORTE_TECNICO' || dbRole === 'SOPORTE') return 'SOPORTE';
        if (dbRole === 'TECNICO_CAMPO' || dbRole === 'TECNICO') return 'TECNICO';
        return dbRole; // VENTAS, ADMIN
    }

    // --- FILTRADO Y RENDERIZADO ---
    function filterUsers(role) {
        currentFilter = role;

        // 1. Actualizar estilos de Cards
        document.querySelectorAll('[id^="card-"]').forEach(card => {
            card.classList.remove('bg-blue-50/50', 'ring-2', 'ring-primary', 'ring-offset-2');
            card.classList.add('opacity-70');
        });
        const activeCard = document.getElementById(`card-${role}`);
        if (activeCard) {
            activeCard.classList.remove('opacity-70');
            activeCard.classList.add('bg-blue-50/50', 'ring-2', 'ring-primary', 'ring-offset-2');
        }

        // 2. Filtrar Datos
        const filtered = dbUsers.filter(u => normalizeRole(u.rol) === role);
        const tbody = document.getElementById('userTableBody');
        const empty = document.getElementById('emptyState');

        // Actualizar título
        const titles = { 'CLIENTE': 'Clientes Registrados', 'VENTAS': 'Personal de Ventas', 'SOPORTE': 'Personal de Soporte', 'TECNICO': 'Técnicos de Campo' };
        document.getElementById('tableTitle').innerText = titles[role] || 'Usuarios';

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        // 3. Renderizar Tabla
        tbody.innerHTML = filtered.map(u => {
            const statusBadge = u.activo == 1
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"><span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span>Activo</span>'
                : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"><span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1.5"></span>Inactivo</span>';

            return `
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-9 w-9 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold mr-3">
                                ${u.nombre.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${u.nombre}</div>
                                <div class="text-xs text-gray-500">${u.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">${u.dni || '-'}</td>
                    <td class="px-6 py-4 text-gray-600">${u.telefono || '-'}</td>
                    <td class="px-6 py-4 text-center">${statusBadge}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openViewModal(${u.id_usuario})" class="text-gray-400 hover:text-blue-600 p-1 transition-colors" title="Ver Detalle">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="openEditModal(${u.id_usuario})" class="text-gray-400 hover:text-green-600 p-1 transition-colors" title="Editar / Modificar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button onclick="deleteUser(${u.id_usuario})" class="text-gray-400 hover:text-red-600 p-1 transition-colors" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // --- MODALES ---

    // 1. Modal VER
    function openViewModal(id) {
        const user = dbUsers.find(u => u.id_usuario == id);
        if (!user) return;

        // Llenar datos
        document.getElementById('v_avatar').innerText = user.nombre.substring(0, 2).toUpperCase();
        document.getElementById('v_nombre').innerText = user.nombre;
        document.getElementById('v_email').innerText = user.email;
        document.getElementById('v_dni').innerText = user.dni || '--';
        document.getElementById('v_telefono').innerText = user.telefono || '--';
        document.getElementById('v_direccion').innerText = user.direccion || '--';
        document.getElementById('v_rol').innerText = user.rol;
        document.getElementById('v_fecha').innerText = user.created_at || '--';

        const st = user.activo == 1
            ? '<span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded">ACTIVO</span>'
            : '<span class="text-red-600 font-bold text-xs bg-red-50 px-2 py-1 rounded">INACTIVO</span>';
        document.getElementById('v_estado').innerHTML = st;

        // Configurar botón "Editar este usuario"
        document.getElementById('btnSwitchEdit').onclick = function () {
            closeModal('viewModal');
            setTimeout(() => openEditModal(id), 300);
        };

        openModal('viewModal', 'viewPanel');
    }

    // 2. Modal EDITAR / CREAR
    function openEditModal(id = null) {
        const form = document.getElementById('userForm');
        form.reset();

        if (id) {
            // Modo Edición
            const user = dbUsers.find(u => u.id_usuario == id);
            document.getElementById('modalTitle').innerText = "Editar Usuario";
            document.getElementById('e_id').value = user.id_usuario;
            document.getElementById('e_nombre').value = user.nombre;
            document.getElementById('e_dni').value = user.dni;
            document.getElementById('e_telefono').value = user.telefono;
            document.getElementById('e_direccion').value = user.direccion;
            document.getElementById('e_email').value = user.email;

            // Mapeo Inverso para el Select
            // El Backend espera el NOMBRE del rol o ID. El select tiene values como CLIENTE, VENTAS, etc.
            // En BD: admin, soporte_tecnico, tecnico_campo, ventas, user
            let selectRol = normalizeRole(user.rol);
            // Si el user es 'admin', normalize devuelve 'CLIENTE' (por default) o undefined?
            // Revisa normalizeRole:
            // admin -> no machea nada -> devuelve admin.
            // Asi que necesitamos añadir 'ADMIN' al select values o al normalize.

            document.getElementById('e_rol').value = selectRol;

            document.getElementById('e_estado').value = user.activo;
        } else {
            // Modo Crear
            document.getElementById('modalTitle').innerText = "Nuevo Usuario";
            document.getElementById('e_id').value = "";
            document.getElementById('e_rol').value = currentFilter;
            document.getElementById('e_estado').value = "1";
        }

        openModal('editModal', 'editPanel');
    }

    // 3. Modal ASIGNAR (Listar Clientes)
    async function openAssignModal() {
        openModal('assignModal', 'assignPanel');
        await fetchPendingClients();
    }

    async function fetchPendingClients() {
        const tbody = document.getElementById('clientTableBody');
        const empty = document.getElementById('emptyClients');
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4">Cargando...</td></tr>';

        try {
            const response = await fetch(`${controllerUrl}?op=listarClientesSinUsuario`);
            const data = await response.json();

            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }

            if (data.length === 0) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            // Guardar para búsqueda
            window.pendingClients = data;
            renderClientList(data);

        } catch (e) {
            console.error(e);
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-red-500">Error de conexión</td></tr>';
        }
    }

    function renderClientList(clients) {
        const tbody = document.getElementById('clientTableBody');
        tbody.innerHTML = clients.map(c => `
            <tr class="hover:bg-blue-50 transition-colors cursor-pointer" onclick='selectClient(${JSON.stringify(c)})'>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">${c.nombre_completo}</div>
                    <div class="text-xs text-gray-500">${c.email || 'Sin email'}</div>
                </td>
                <td class="px-4 py-3 font-mono text-xs">${c.dni || '-'}</td>
                <td class="px-4 py-3 text-right">
                    <button class="btn-sm btn-primary">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function searchClientList() {
        const term = document.getElementById('searchClientInput').value.toLowerCase();
        if (!window.pendingClients) return;

        const filtered = window.pendingClients.filter(c =>
            c.nombre_completo.toLowerCase().includes(term) ||
            (c.dni && c.dni.includes(term))
        );
        renderClientList(filtered);
    }

    function selectClient(client) {
        closeModal('assignModal');
        // Abrir modal de edición pre-llenado
        setTimeout(() => {
            const form = document.getElementById('userForm');
            form.reset();

            document.getElementById('modalTitle').innerText = "Crear Usuario para Cliente";
            document.getElementById('e_id').value = ""; // Nuevo

            // Pre-fill hidden client ID (necesitamos input hidden extra o usar e_id con logica especial? Mejor input hidden)
            // No existe input hidden para id_cliente, agreguemoslo dinamicamente o al form HTML.
            // Vamos a agregar el campo hidden al form si no existe.
            let hiddenClient = document.getElementById('e_id_cliente');
            if (!hiddenClient) {
                hiddenClient = document.createElement('input');
                hiddenClient.type = 'hidden';
                hiddenClient.id = 'e_id_cliente';
                document.getElementById('userForm').appendChild(hiddenClient);
            }
            hiddenClient.value = client.id_cliente;

            document.getElementById('e_nombre').value = client.nombre_completo;
            document.getElementById('e_dni').value = client.dni || '';
            document.getElementById('e_email').value = client.email || '';
            document.getElementById('e_telefono').value = client.telefono || '';
            document.getElementById('e_direccion').value = client.direccion || '';

            document.getElementById('e_rol').value = 'CLIENTE';
            document.getElementById('e_estado').value = '1';

            // Bloquear edición de datos sensibles traídos del cliente si se desea, o dejarlos editables.
            // Dejamos editables para corregir al momento de crear usuario.

            openModal('editModal', 'editPanel');
        }, 300);
    }

    async function saveUser() {
        const id = document.getElementById('e_id').value;
        // Check for client ID
        const hiddenClient = document.getElementById('e_id_cliente');
        const id_cliente = hiddenClient ? hiddenClient.value : '';

        const nombre = document.getElementById('e_nombre').value;
        const dni = document.getElementById('e_dni').value;
        const email = document.getElementById('e_email').value;
        const rol = document.getElementById('e_rol').value;
        const telefono = document.getElementById('e_telefono').value;
        const direccion = document.getElementById('e_direccion').value;
        const estado = document.getElementById('e_estado').value;
        const password = document.getElementById('e_password').value;

        // Validación simple
        if (!nombre || !email || !dni) {
            alert("Completa los campos obligatorios (*)");
            return;
        }

        const formData = new FormData();
        formData.append('id_usuario', id);
        if (id_cliente) formData.append('id_cliente', id_cliente); // Add to form data
        formData.append('nombre', nombre);
        formData.append('dni', dni);
        formData.append('email', email);
        formData.append('rol', rol); // Envía CLIENTE, VENTAS... El controller debe mapearlo
        formData.append('telefono', telefono);
        formData.append('direccion', direccion);
        formData.append('estado', estado);
        formData.append('password', password);

        try {
            const response = await fetch(`${controllerUrl}?op=guardar`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.status) {
                alert("✅ " + data.msg);
                closeModal('editModal');
                fetchUsers(); // Recargar tabla
            } else {
                alert("❌ Error: " + data.msg);
            }
        } catch (e) {
            console.error(e);
            alert("Error de red al guardar");
        }
    }

    async function deleteUser(id) {
        if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
            const formData = new FormData();
            formData.append('id_usuario', id);

            try {
                const response = await fetch(`${controllerUrl}?op=eliminar`, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status) {
                    alert("🗑️ " + data.msg);
                    fetchUsers();
                } else {
                    alert("⚠️ " + data.msg);
                }
            } catch (e) {
                console.error(e);
                alert("Error al eliminar");
            }
        }
    }

    // --- UTILS ---
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

    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const tbody = document.getElementById('userTableBody');
        const tr = tbody.getElementsByTagName('tr');

        for (let i = 0; i < tr.length; i++) {
            const tdName = tr[i].getElementsByTagName("td")[0]; // Columna Nombre
            const tdDni = tr[i].getElementsByTagName("td")[1];  // Columna DNI
            if (tdName || tdDni) {
                const txtName = tdName.textContent || tdName.innerText;
                const txtDni = tdDni.textContent || tdDni.innerText;
                if (txtName.toLowerCase().indexOf(filter) > -1 || txtDni.indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>