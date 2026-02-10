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
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">15</h3>
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
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">142</h3>
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
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">8</h3>
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

<script>
    // --- 1. DATOS SIMULADOS (MOCK BD) ---
    // hasAccess: true (Usuario existe en tabla usuario), false (Usuario es NULL)
    const clientesData = [
        {
            id: 1, dni: "72889102", nombre: "Raúl", apellido: "Mendoza",
            telefono: "998877665", plan: "Fibra Home 100",
            hasAccess: true, username: "72889102"
        },
        {
            id: 2, dni: "20601234567", nombre: "ShopNear S.R.L.", apellido: "",
            telefono: "912345678", plan: "Fibra Pyme 300",
            hasAccess: true, username: "20601234567"
        },
        {
            id: 3, dni: "45678901", nombre: "Ana", apellido: "García",
            telefono: "955443322", plan: "Fibra 50Mb",
            hasAccess: false, username: null
        },
        {
            id: 4, dni: "09876543", nombre: "Carlos", apellido: "Vargas",
            telefono: "966112233", plan: "Fibra Gamer 200",
            hasAccess: false, username: null
        }
    ];

    // --- 2. RENDERIZADO DE TABLA ---
    function renderTabla(datos) {
        const tbody = document.getElementById('tablaClientes');
        tbody.innerHTML = '';

        datos.forEach(c => {
            const nombreCompleto = c.apellido ? `${c.nombre} ${c.apellido}` : c.nombre;

            // Lógica de Estado de Acceso
            let accesoBadge = '';
            let accionBtn = '';

            if (c.hasAccess) {
                // Caso: TIENE Credenciales -> Botón Editar
                accesoBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i> Habilitado
                               </span>`;

                accionBtn = `<button onclick="openCredModal(${c.id}, true)" class="text-xs bg-white border border-gray-300 text-gray-600 hover:text-primary hover:border-primary px-3 py-1.5 rounded transition-colors shadow-sm flex items-center ml-auto gap-1">
                                <i class="fas fa-key text-orange-400"></i> Restablecer
                             </button>`;
            } else {
                // Caso: NO TIENE Credenciales -> Botón Asignar
                accesoBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                <i class="fas fa-times-circle mr-1"></i> Sin Acceso
                               </span>`;

                accionBtn = `<button onclick="openCredModal(${c.id}, false)" class="text-xs bg-primary text-white hover:bg-primary-dark px-3 py-1.5 rounded transition-colors shadow-md flex items-center ml-auto gap-1 animate-pulse">
                                <i class="fas fa-user-plus"></i> Crear Usuario
                             </button>`;
            }

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
                    <i class="fas fa-phone text-gray-400 mr-1"></i> ${c.telefono}
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-medium bg-blue-50 text-blue-600 px-2 py-1 rounded">${c.plan}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    ${accesoBadge}
                </td>
                <td class="px-6 py-4 text-right">
                    ${accionBtn}
                </td>
                <td class="px-6 py-4 text-right">
                    <button class="text-gray-400 hover:text-primary transition-colors p-1" title="Editar Info Cliente">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Inicializar tabla
    document.addEventListener('DOMContentLoaded', () => {
        renderTabla(clientesData);
    });

    // --- 3. FILTRADO ---
    function aplicarFiltros() {
        const dni = document.getElementById('filtroDni').value.toLowerCase();
        const apellido = document.getElementById('filtroApellido').value.toLowerCase();
        const nombre = document.getElementById('filtroNombre').value.toLowerCase();

        const filtrados = clientesData.filter(c => {
            const matchDni = c.dni.includes(dni);
            const matchApellido = c.apellido.toLowerCase().includes(apellido);
            const matchNombre = c.nombre.toLowerCase().includes(nombre);
            return matchDni && matchApellido && matchNombre;
        });

        renderTabla(filtrados);
    }

    // --- 4. GESTIÓN MODAL DE CREDENCIALES ---
    let currentClientId = null;

    function openCredModal(id, hasAccess) {
        currentClientId = id;
        const cliente = clientesData.find(c => c.id === id);

        // Elementos del DOM
        const modal = document.getElementById('credencialModal');
        const panel = document.getElementById('modalPanelCreds');
        const title = document.getElementById('modalTitle');
        const btnSubmit = modal.querySelector('button[type="submit"]');

        // Llenar datos cliente
        document.getElementById('credClientName').textContent = cliente.apellido ? `${cliente.nombre} ${cliente.apellido}` : cliente.nombre;
        document.getElementById('credClientDni').textContent = "DNI: " + cliente.dni;
        document.getElementById('credUser').value = cliente.dni; // Por defecto DNI
        document.getElementById('credPass').value = ""; // Limpiar pass

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

    function guardarCredenciales(e) {
        e.preventDefault();
        const pass = document.getElementById('credPass').value;
        const whatsapp = document.getElementById('sendWhatsapp').checked;

        if (pass.length < 4) {
            alert("La contraseña debe tener al menos 4 caracteres.");
            return;
        }

        // Simulación de guardado
        // Aquí iría el AJAX update a tabla usuario
        alert(`Credenciales guardadas con éxito.\n${whatsapp ? '📩 Se abrirá WhatsApp para enviar los datos.' : ''}`);

        closeModal('credencialModal');

        // Actualizar UI localmente (simulación)
        const cliente = clientesData.find(c => c.id === currentClientId);
        cliente.hasAccess = true; // Ahora tiene acceso
        renderTabla(clientesData);
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
</script>