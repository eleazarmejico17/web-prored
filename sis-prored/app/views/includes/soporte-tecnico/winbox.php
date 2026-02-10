<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Concentradores (Winbox)</h1>
            <p class="text-sm text-gray-500">Administración de carga, límites de usuarios y migración de servicios.</p>
        </div>

        <button onclick="openModalWinbox()"
            class="btn-primary shadow-lg hover:shadow-xl transition-all transform active:scale-95">
            <i class="fas fa-server mr-2"></i> Agregar Nuevo Winbox
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="winbox-grid">
    </div>

</div>

<div id="modalWinbox"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95" id="panelWinbox">
        <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-primary text-white rounded-t-xl">
            <h3 class="font-bold" id="modalTitle">Nuevo Concentrador</h3>
            <button onclick="closeModalWinbox()" class="text-white/70 hover:text-white"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="label-prored">Nombre del Nodo (Identificador)</label>
                <input type="text" id="w_nombre" class="input-prored" placeholder="Ej: RB-Pangoa-Norte">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-prored">Dirección IP</label>
                    <input type="text" id="w_ip" class="input-prored" placeholder="192.168.x.x">
                </div>
                <div>
                    <label class="label-prored">Puerto API</label>
                    <input type="number" id="w_port" class="input-prored" value="8728">
                </div>
            </div>

            <div>
                <label class="label-prored flex justify-between">
                    <span>Límite de Usuarios (Capacidad)</span>
                    <i class="fas fa-info-circle text-primary" title="Define cuándo mostrar alertas de saturación"></i>
                </label>
                <input type="number" id="w_capacidad" class="input-prored" placeholder="Ej: 500">
                <p class="text-xs text-gray-400 mt-1">Se emitirá alerta al llegar al 90% de este valor.</p>
            </div>

            <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                <label class="text-xs font-bold text-red-600 uppercase block mb-1">Seguridad Requerida</label>
                <input type="password" id="w_admin_pass"
                    class="w-full px-3 py-2 border border-red-200 rounded text-sm focus:outline-none focus:border-red-500"
                    placeholder="Contraseña de Administrador del Sistema">
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-2">
            <button onclick="closeModalWinbox()" class="btn-outline">Cancelar</button>
            <button onclick="saveWinbox()" class="btn-primary">Guardar Cambios</button>
        </div>
    </div>
</div>

<div id="modalMigracion"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 h-[80vh] flex flex-col"
        id="panelMigracion">

        <div
            class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-800 to-gray-700 text-white rounded-t-xl flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg"><i class="fas fa-exchange-alt mr-2"></i> Asistente de Migración</h3>
                <p class="text-xs text-gray-300">Mover servicios entre concentradores</p>
            </div>
            <button onclick="closeModalMigracion()" class="text-white/70 hover:text-white"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-gray-50/50">

            <div
                class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="w-full">
                    <label class="text-xs font-bold text-gray-500 uppercase">Origen</label>
                    <div class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-server text-red-500"></i>
                        <span id="mig_origen_txt">--</span>
                    </div>
                </div>

                <div class="text-gray-400">
                    <i class="fas fa-arrow-right text-2xl hidden md:block"></i>
                    <i class="fas fa-arrow-down text-2xl md:hidden"></i>
                </div>

                <div class="w-full">
                    <label class="text-xs font-bold text-gray-500 uppercase">Destino</label>
                    <select id="mig_destino_select" class="input-prored py-1 font-bold text-gray-800">
                        <option value="">Seleccione destino...</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Migración</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo_migracion" value="total" class="peer sr-only"
                            onchange="toggleUserList(false)">
                        <div
                            class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-white transition-all text-center">
                            <i class="fas fa-users text-2xl mb-2 text-gray-400 peer-checked:text-red-500"></i>
                            <h4 class="font-bold text-sm">Migración Total</h4>
                            <p class="text-[10px] text-gray-500">Mover TODOS los usuarios.</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo_migracion" value="parcial" class="peer sr-only" checked
                            onchange="toggleUserList(true)">
                        <div
                            class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-primary peer-checked:bg-blue-50 hover:bg-white transition-all text-center">
                            <i class="fas fa-user-check text-2xl mb-2 text-gray-400 peer-checked:text-primary"></i>
                            <h4 class="font-bold text-sm">Migración Parcial</h4>
                            <p class="text-[10px] text-gray-500">Seleccionar usuarios específicos.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div id="lista_usuarios_container" class="animate-fade-in-up">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-bold text-gray-700">Seleccionar Usuarios</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="check_all" class="rounded text-primary focus:ring-primary"
                            onchange="toggleCheckAll()">
                        <label for="check_all" class="text-xs text-gray-600 cursor-pointer">Marcar todos</label>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg max-h-60 overflow-y-auto custom-scrollbar divide-y divide-gray-100"
                    id="user_list_body">
                </div>
            </div>

        </div>

        <div class="px-6 py-4 bg-white border-t border-gray-200 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeModalMigracion()" class="btn-outline">Cancelar</button>
            <button onclick="executeMigration()" class="btn-primary bg-gray-800 hover:bg-gray-900 border-transparent">
                <i class="fas fa-random mr-2"></i> Ejecutar Migración
            </button>
        </div>
    </div>
</div>

<script>
    // --- MOCK DATA ---
    let dbWinbox = [
        { id: 1, nombre: "RB-Pangoa-Norte", ip: "192.168.10.1", usuarios: 120, capacidad: 200, cpu: 15, ram: 30, online: true },
        { id: 2, nombre: "RB-Chilca-Empresas", ip: "192.168.20.1", usuarios: 450, capacidad: 500, cpu: 82, ram: 70, online: true },
        { id: 3, nombre: "RB-Huancayo-Centro", ip: "192.168.30.1", usuarios: 280, capacidad: 1000, cpu: 25, ram: 40, online: true },
        { id: 4, nombre: "RB-Backup-Sur", ip: "192.168.99.1", usuarios: 0, capacidad: 300, cpu: 2, ram: 5, online: false } // Inactivo
    ];

    // Usuarios Dummy para la migración
    const dbUsersDummy = [
        { id: 101, nombre: "Juan Pérez", plan: "50 Mbps" },
        { id: 102, nombre: "Bodega El Sol", plan: "100 Mbps" },
        { id: 103, nombre: "Cabina Internet Fast", plan: "300 Mbps" },
        { id: 104, nombre: "Maria Gonzales", plan: "20 Mbps" },
        { id: 105, nombre: "Colegio San Jose", plan: "500 Mbps" }
    ];

    // --- RENDERIZADO PRINCIPAL ---
    function renderWinboxes() {
        const grid = document.getElementById('winbox-grid');
        grid.innerHTML = dbWinbox.map(wb => {
            // Cálculos de Porcentaje
            const porcentaje = Math.round((wb.usuarios / wb.capacidad) * 100);

            // Lógica de Colores (Semáforo)
            let colorBarra = 'bg-success';
            let colorTexto = 'text-green-600';
            let alertIcon = '';

            if (porcentaje >= 90) {
                colorBarra = 'bg-danger';
                colorTexto = 'text-danger';
                alertIcon = `<div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg animate-bounce" title="¡Capacidad Crítica!"><i class="fas fa-exclamation"></i></div>`;
            } else if (porcentaje >= 70) {
                colorBarra = 'bg-warning';
                colorTexto = 'text-warning';
                alertIcon = `<div class="absolute -top-2 -right-2 bg-orange-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md" title="Capacidad Alta"><i class="fas fa-exclamation"></i></div>`;
            }

            // Estado del equipo
            const statusDot = wb.online
                ? '<span class="flex w-3 h-3 bg-green-500 rounded-full"></span>'
                : '<span class="flex w-3 h-3 bg-gray-400 rounded-full"></span>';

            // Botón Borrar (Deshabilitado si tiene usuarios)
            const deleteDisabled = wb.usuarios > 0;
            const deleteBtnClass = deleteDisabled
                ? 'text-gray-300 cursor-not-allowed'
                : 'text-gray-400 hover:text-red-500 cursor-pointer';

            return `
                <div class="card-base p-0 overflow-hidden relative group hover:shadow-xl transition-shadow border border-gray-100">
                    ${alertIcon}
                    
                    <div class="p-5 border-b border-gray-50 flex justify-between items-start">
                        <div class="flex gap-3">
                            <div class="mt-1">${statusDot}</div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg leading-tight">${wb.nombre}</h3>
                                <p class="text-xs text-gray-400 font-mono mt-1"><i class="fas fa-network-wired mr-1"></i>${wb.ip}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editWinbox(${wb.id})" class="text-gray-400 hover:text-primary transition-colors p-1"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteWinbox(${wb.id}, ${wb.usuarios})" class="${deleteBtnClass} p-1" ${deleteDisabled ? 'disabled title="No se puede borrar con usuarios activos"' : 'title="Eliminar Winbox"'}><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-gray-500">Usuarios Conectados</span>
                                <span class="${colorTexto}">${wb.usuarios} / ${wb.capacidad} (${porcentaje}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="${colorBarra} h-2.5 rounded-full transition-all duration-500" style="width: ${porcentaje}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div class="bg-gray-50 rounded-lg p-2 text-center border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold">CPU Load</p>
                                <p class="text-sm font-bold ${wb.cpu > 80 ? 'text-red-500' : 'text-gray-700'}">${wb.cpu}%</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2 text-center border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Memoria</p>
                                <p class="text-sm font-bold text-gray-700">${wb.ram} MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-4 border-t border-gray-100">
                        <button onclick="openModalMigracion(${wb.id})" class="w-full btn-secondary py-2 justify-center text-sm shadow-none border border-transparent hover:border-secondary-light">
                            <i class="fas fa-exchange-alt mr-2"></i> Migrar Usuarios
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // --- LÓGICA AGREGAR / EDITAR ---
    const modalWinbox = document.getElementById('modalWinbox');
    const panelWinbox = document.getElementById('panelWinbox');

    function openModalWinbox() {
        // Limpiar campos
        document.getElementById('modalTitle').innerText = "Nuevo Concentrador";
        document.getElementById('w_nombre').value = "";
        document.getElementById('w_ip').value = "";
        document.getElementById('w_capacidad').value = "";
        document.getElementById('w_admin_pass').value = "";

        modalWinbox.classList.remove('hidden');
        setTimeout(() => {
            modalWinbox.classList.remove('opacity-0');
            panelWinbox.classList.remove('scale-95');
            panelWinbox.classList.add('scale-100');
        }, 10);
    }

    function editWinbox(id) {
        const wb = dbWinbox.find(w => w.id === id);
        if (!wb) return;

        document.getElementById('modalTitle').innerText = "Editar Concentrador";
        document.getElementById('w_nombre').value = wb.nombre;
        document.getElementById('w_ip').value = wb.ip;
        document.getElementById('w_capacidad').value = wb.capacidad;
        document.getElementById('w_admin_pass').value = ""; // Siempre pedir pass

        modalWinbox.classList.remove('hidden');
        setTimeout(() => {
            modalWinbox.classList.remove('opacity-0');
            panelWinbox.classList.remove('scale-95');
            panelWinbox.classList.add('scale-100');
        }, 10);
    }

    function closeModalWinbox() {
        modalWinbox.classList.add('opacity-0');
        panelWinbox.classList.remove('scale-100');
        panelWinbox.classList.add('scale-95');
        setTimeout(() => modalWinbox.classList.add('hidden'), 300);
    }

    function saveWinbox() {
        const pass = document.getElementById('w_admin_pass').value;
        if (pass !== "admin123") { // Simulación de seguridad
            alert("⛔ Contraseña de administrador incorrecta.");
            return;
        }
        alert("✅ Winbox guardado correctamente.");
        closeModalWinbox();
        renderWinboxes(); // Refrescaría la vista
    }

    function deleteWinbox(id, usuarios) {
        if (usuarios > 0) {
            alert("⚠️ No se puede eliminar un Winbox con usuarios activos. Migre los usuarios primero.");
            return;
        }
        const pass = prompt("🔒 ACCIÓN CRÍTICA: Ingrese contraseña de Admin para eliminar:");
        if (pass === "admin123") {
            if (confirm("¿Seguro que desea eliminar este dispositivo?")) {
                dbWinbox = dbWinbox.filter(w => w.id !== id);
                renderWinboxes();
                alert("🗑️ Winbox eliminado.");
            }
        } else if (pass !== null) {
            alert("⛔ Contraseña incorrecta.");
        }
    }

    // --- LÓGICA MIGRACIÓN ---
    const modalMig = document.getElementById('modalMigracion');
    const panelMig = document.getElementById('panelMigracion');
    let currentSourceId = null;

    function openModalMigracion(sourceId) {
        currentSourceId = sourceId;
        const sourceWb = dbWinbox.find(w => w.id === sourceId);

        // Set UI Text
        document.getElementById('mig_origen_txt').innerText = sourceWb.nombre;

        // Llenar Select Destino (Excluyendo origen y full capacity si fuera real)
        const select = document.getElementById('mig_destino_select');
        select.innerHTML = '<option value="">Seleccione destino...</option>' +
            dbWinbox
                .filter(w => w.id !== sourceId && w.online)
                .map(w => `<option value="${w.id}">${w.nombre} (Disp: ${w.capacidad - w.usuarios})</option>`)
                .join('');

        // Llenar Lista Dummy de Usuarios
        renderUserList();

        modalMig.classList.remove('hidden');
        setTimeout(() => {
            modalMig.classList.remove('opacity-0');
            panelMig.classList.remove('scale-95');
            panelMig.classList.add('scale-100');
        }, 10);
    }

    function closeModalMigracion() {
        modalMig.classList.add('opacity-0');
        panelMig.classList.remove('scale-100');
        panelMig.classList.add('scale-95');
        setTimeout(() => modalMig.classList.add('hidden'), 300);
    }

    function toggleUserList(show) {
        const container = document.getElementById('lista_usuarios_container');
        if (show) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function renderUserList() {
        const listBody = document.getElementById('user_list_body');
        listBody.innerHTML = dbUsersDummy.map(u => `
            <div class="flex items-center p-3 hover:bg-gray-50">
                <input type="checkbox" name="users_mig[]" value="${u.id}" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mr-3">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700">${u.nombre}</p>
                    <p class="text-xs text-gray-400">Plan: ${u.plan}</p>
                </div>
            </div>
        `).join('');
    }

    function toggleCheckAll() {
        const checkAll = document.getElementById('check_all');
        const checkboxes = document.querySelectorAll('input[name="users_mig[]"]');
        checkboxes.forEach(cb => cb.checked = checkAll.checked);
    }

    function executeMigration() {
        const destino = document.getElementById('mig_destino_select').value;
        if (!destino) {
            alert("⚠️ Seleccione un Winbox de destino.");
            return;
        }

        // Simulación
        alert("🚀 Iniciando migración de servicios...\nEste proceso puede tomar unos minutos.");
        closeModalMigracion();
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', renderWinboxes);

</script>