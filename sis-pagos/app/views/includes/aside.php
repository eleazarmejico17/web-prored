<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Pagos de Internet</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Fuente de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animaciones personalizadas */
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .animate-slide-out {
            animation: slideOut 0.3s ease-out;
        }
        
        /* Scroll personalizado */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #005FA2;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #004a80;
        }
    </style>
    
    <script>
        // Configuración de colores de Tailwind personalizados
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005FA2',
                        secondary: '#E58E21',
                        'primary-light': '#E6F2FA',
                        'secondary-light': '#FEF5E7'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Contenedor principal -->
    <div class="flex min-h-screen">
        <!-- Overlay para móviles -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white shadow-xl flex flex-col w-64 fixed md:relative h-screen z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <!-- Logo y título del sistema -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-center">
                    <div class="bg-primary p-3 rounded-lg">
                        <i class="fas fa-wifi text-white text-2xl"></i>
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-primary">NetPay<span class="text-secondary">System</span></h1>
                </div>
                <p class="text-gray-500 text-sm text-center mt-2">Gestión de pagos de internet</p>
            </div>
            
            <!-- Selector de tipo de usuario -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col space-y-2">
                    <label class="text-gray-600 text-sm font-medium">Seleccionar perfil:</label>
                    <select id="userType" class="bg-primary-light border border-primary rounded-lg py-2 px-3 text-primary font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="user">Usuario</option>
                        <option value="employee">Empleado</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>
            
            <!-- Menú de navegación dinámico -->
            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar">
                <!-- El menú se carga dinámicamente según el tipo de usuario -->
            </nav>
            
            <!-- Información del usuario y cierre de sesión -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                        <span id="userInitial">U</span>
                    </div>
                    <div class="ml-3">
                        <h3 id="userName" class="font-medium text-gray-800">Usuario Ejemplo</h3>
                        <p id="userRole" class="text-xs text-gray-500">Cliente</p>
                    </div>
                </div>
                <button id="logoutBtn" class="w-full py-2 px-4 bg-secondary hover:bg-orange-600 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Cerrar sesión
                </button>
            </div>
        </aside>
        
        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between p-4">
                    <!-- Botón para mostrar/ocultar sidebar en móviles -->
                    <button id="sidebarToggle" class="md:hidden text-primary hover:text-primary-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Título dinámico de la página -->
                    <div class="flex items-center">
                        <h2 id="pageTitle" class="text-2xl font-bold text-primary ml-2 md:ml-0">Dashboard</h2>
                    </div>
                    
                    <!-- Notificaciones -->
                    <div class="relative">
                        <button id="notificationsBtn" class="relative text-gray-600 hover:text-primary focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationCount" class="absolute -top-2 -right-2 bg-secondary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- Panel de notificaciones -->
                        <div id="notificationsPanel" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-lg text-primary">Notificaciones</h3>
                                <p class="text-sm text-gray-500" id="notificationStatus">Tiene 3 nuevas notificaciones</p>
                            </div>
                            <div class="max-h-64 overflow-y-auto custom-scrollbar" id="notificationsList">
                                <!-- Las notificaciones se cargarán aquí -->
                            </div>
                            <div class="p-3 border-t border-gray-200 text-center">
                                <a href="#" class="text-primary hover:text-primary-dark font-medium text-sm">Ver todas las notificaciones</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Breadcrumbs -->
                <div class="px-4 pb-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol id="breadcrumbs" class="flex items-center space-x-2 text-sm">
                            <!-- Los breadcrumbs se cargarán dinámicamente -->
                        </ol>
                    </nav>
                </div>
            </header>
            
            <!-- Contenido del dashboard (se cargará con includes) -->
            <main class="flex-grow p-4 md:p-6 overflow-y-auto">
                <div id="dashboardContent" class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Bienvenido al Sistema de Gestión de Pagos</h3>
                    <p class="text-gray-700 mb-4">Seleccione una opción del menú lateral para comenzar. El contenido se cargará dinámicamente según la opción seleccionada.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-primary-light p-5 rounded-lg border-l-4 border-primary">
                            <div class="flex items-center">
                                <div class="bg-primary p-3 rounded-lg">
                                    <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-primary">Pagos</h4>
                                    <p class="text-gray-600 text-sm">Gestione sus pagos de servicio de internet</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-secondary-light p-5 rounded-lg border-l-4 border-secondary">
                            <div class="flex items-center">
                                <div class="bg-secondary p-3 rounded-lg">
                                    <i class="fas fa-user-check text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-secondary">Perfil</h4>
                                    <p class="text-gray-600 text-sm">Actualice su información personal</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-primary-light p-5 rounded-lg border-l-4 border-primary">
                            <div class="flex items-center">
                                <div class="bg-primary p-3 rounded-lg">
                                    <i class="fas fa-history text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-primary">Historial</h4>
                                    <p class="text-gray-600 text-sm">Revise su historial de transacciones</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-bold text-primary mb-2">Instrucciones:</h4>
                        <ul class="list-disc pl-5 text-gray-700 space-y-1">
                            <li>Use el selector en el sidebar para cambiar entre los perfiles de usuario, empleado y administrador.</li>
                            <li>Cada perfil tiene opciones de menú diferentes según sus permisos.</li>
                            <li>Haga clic en el icono de campana para ver las notificaciones del sistema.</li>
                            <li>En dispositivos móviles, use el botón de menú para mostrar/ocultar el sidebar.</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Datos para cada tipo de usuario
        const userData = {
            user: {
                name: "Juan Pérez",
                role: "Cliente",
                initial: "J",
                menu: [
                    { 
                        title: "Dashboard", 
                        icon: "fas fa-home", 
                        page: "dashboard", 
                        submenu: [] 
                    },
                    { 
                        title: "Mis Pagos", 
                        icon: "fas fa-credit-card", 
                        page: "pagos", 
                        submenu: [
                            { title: "Realizar Pago", page: "realizar-pago" },
                            { title: "Historial de Pagos", page: "historial-pagos" },
                            { title: "Facturas", page: "facturas" }
                        ] 
                    },
                    { 
                        title: "Mi Servicio", 
                        icon: "fas fa-wifi", 
                        page: "servicio", 
                        submenu: [
                            { title: "Estado del Servicio", page: "estado-servicio" },
                            { title: "Planes", page: "planes" },
                            { title: "Soporte Técnico", page: "soporte" }
                        ] 
                    },
                    { 
                        title: "Mi Perfil", 
                        icon: "fas fa-user", 
                        page: "perfil", 
                        submenu: [] 
                    },
                    { 
                        title: "Notificaciones", 
                        icon: "fas fa-bell", 
                        page: "notificaciones", 
                        submenu: [] 
                    }
                ],
                notifications: [
                    { id: 1, text: "Su pago de diciembre ha sido procesado", time: "Hace 2 horas", read: false },
                    { id: 2, text: "Recordatorio: Pago pendiente para enero", time: "Ayer", read: false },
                    { id: 3, text: "Actualización de términos de servicio", time: "Hace 3 días", read: true },
                    { id: 4, text: "Mantenimiento programado para el sábado", time: "Hace 1 semana", read: true }
                ]
            },
            employee: {
                name: "María González",
                role: "Empleado",
                initial: "M",
                menu: [
                    { 
                        title: "Dashboard", 
                        icon: "fas fa-home", 
                        page: "dashboard", 
                        submenu: [] 
                    },
                    { 
                        title: "Gestión de Pagos", 
                        icon: "fas fa-cash-register", 
                        page: "gestion-pagos", 
                        submenu: [
                            { title: "Registrar Pago", page: "registrar-pago" },
                            { title: "Pagos Pendientes", page: "pagos-pendientes" },
                            { title: "Reportes de Pagos", page: "reportes-pagos" }
                        ] 
                    },
                    { 
                        title: "Clientes", 
                        icon: "fas fa-users", 
                        page: "clientes", 
                        submenu: [
                            { title: "Lista de Clientes", page: "lista-clientes" },
                            { title: "Nuevo Cliente", page: "nuevo-cliente" },
                            { title: "Clientes Morosos", page: "clientes-morosos" }
                        ] 
                    },
                    { 
                        title: "Servicios", 
                        icon: "fas fa-concierge-bell", 
                        page: "servicios", 
                        submenu: [
                            { title: "Activar Servicio", page: "activar-servicio" },
                            { title: "Suspender Servicio", page: "suspender-servicio" },
                            { title: "Cambio de Plan", page: "cambio-plan" }
                        ] 
                    },
                    { 
                        title: "Reportes", 
                        icon: "fas fa-chart-bar", 
                        page: "reportes", 
                        submenu: [] 
                    }
                ],
                notifications: [
                    { id: 1, text: "Nuevo pago registrado por Juan Pérez", time: "Hace 30 minutos", read: false },
                    { id: 2, text: "3 clientes con pagos pendientes", time: "Hoy", read: false },
                    { id: 3, text: "Reunión de equipo a las 3 PM", time: "Hace 1 día", read: true },
                    { id: 4, text: "Actualización del sistema completada", time: "Hace 2 días", read: true },
                    { id: 5, text: "Recordatorio: Envío de reporte mensual", time: "Hace 4 días", read: true }
                ]
            },
            admin: {
                name: "Carlos Rodríguez",
                role: "Administrador",
                initial: "C",
                menu: [
                    { 
                        title: "Dashboard", 
                        icon: "fas fa-home", 
                        page: "dashboard", 
                        submenu: [] 
                    },
                    { 
                        title: "Administración", 
                        icon: "fas fa-cogs", 
                        page: "administracion", 
                        submenu: [
                            { title: "Usuarios del Sistema", page: "usuarios-sistema" },
                            { title: "Roles y Permisos", page: "roles-permisos" },
                            { title: "Configuración General", page: "configuracion" }
                        ] 
                    },
                    { 
                        title: "Finanzas", 
                        icon: "fas fa-chart-line", 
                        page: "finanzas", 
                        submenu: [
                            { title: "Estado Financiero", page: "estado-financiero" },
                            { title: "Ingresos y Egresos", page: "ingresos-egresos" },
                            { title: "Proyecciones", page: "proyecciones" }
                        ] 
                    },
                    { 
                        title: "Empleados", 
                        icon: "fas fa-user-tie", 
                        page: "empleados", 
                        submenu: [
                            { title: "Gestión de Empleados", page: "gestion-empleados" },
                            { title: "Asignación de Turnos", page: "asignacion-turnos" },
                            { title: "Rendimiento", page: "rendimiento-empleados" }
                        ] 
                    },
                    { 
                        title: "Auditoría", 
                        icon: "fas fa-clipboard-check", 
                        page: "auditoria", 
                        submenu: [] 
                    },
                    { 
                        title: "Soporte Avanzado", 
                        icon: "fas fa-headset", 
                        page: "soporte-avanzado", 
                        submenu: [] 
                    }
                ],
                notifications: [
                    { id: 1, text: "Alerta: Intento de acceso no autorizado", time: "Hace 1 hora", read: false },
                    { id: 2, text: "Reporte financiero del mes disponible", time: "Hoy", read: false },
                    { id: 3, text: "3 empleados con evaluaciones pendientes", time: "Hace 2 días", read: false },
                    { id: 4, text: "Actualización crítica de seguridad disponible", time: "Hace 3 días", read: true },
                    { id: 5, text: "Revisión de políticas de la empresa", time: "Hace 5 días", read: true },
                    { id: 6, text: "Backup del sistema completado", time: "Hace 1 semana", read: true }
                ]
            }
        };

        // Estado actual de la aplicación
        let currentUserType = 'user';
        let currentPage = 'dashboard';
        let sidebarVisible = false;
        let notificationsVisible = false;
        
        // Elementos del DOM
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');
        const userTypeSelect = document.getElementById('userType');
        const menuContainer = document.getElementById('menu');
        const userName = document.getElementById('userName');
        const userRole = document.getElementById('userRole');
        const userInitial = document.getElementById('userInitial');
        const pageTitle = document.getElementById('pageTitle');
        const breadcrumbs = document.getElementById('breadcrumbs');
        const notificationsBtn = document.getElementById('notificationsBtn');
        const notificationsPanel = document.getElementById('notificationsPanel');
        const notificationsList = document.getElementById('notificationsList');
        const notificationCount = document.getElementById('notificationCount');
        const notificationStatus = document.getElementById('notificationStatus');
        const dashboardContent = document.getElementById('dashboardContent');
        const logoutBtn = document.getElementById('logoutBtn');
        
        // Inicializar la aplicación
        function init() {
            // Cargar datos del usuario por defecto
            loadUserData(currentUserType);
            
            // Configurar eventos
            setupEventListeners();
            
            // Cargar la página actual
            loadPage(currentPage);
        }
        
        // Configurar event listeners
        function setupEventListeners() {
            // Toggle del sidebar en móviles
            sidebarToggle.addEventListener('click', toggleSidebar);
            
            // Cerrar sidebar al hacer clic en el overlay
            overlay.addEventListener('click', closeSidebar);
            
            // Cambiar tipo de usuario
            userTypeSelect.addEventListener('change', (e) => {
                currentUserType = e.target.value;
                loadUserData(currentUserType);
            });
            
            // Toggle de notificaciones
            notificationsBtn.addEventListener('click', toggleNotifications);
            
            // Cerrar notificaciones al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (!notificationsBtn.contains(e.target) && !notificationsPanel.contains(e.target)) {
                    closeNotifications();
                }
            });
            
            // Cerrar sesión
            logoutBtn.addEventListener('click', () => {
                alert('Función de cierre de sesión implementada. En una aplicación real, esto redirigiría al login.');
            });
        }
        
        // Cargar datos del usuario según el tipo
        function loadUserData(userType) {
            const data = userData[userType];
            
            // Actualizar información del usuario
            userName.textContent = data.name;
            userRole.textContent = data.role;
            userInitial.textContent = data.initial;
            
            // Cargar menú
            loadMenu(data.menu);
            
            // Cargar notificaciones
            loadNotifications(data.notifications);
            
            // Si estamos en una página que no existe en el nuevo menú, volver al dashboard
            const menuItems = data.menu.flatMap(item => 
                [item.page, ...item.submenu.map(sub => sub.page)]
            );
            
            if (!menuItems.includes(currentPage)) {
                currentPage = 'dashboard';
                loadPage(currentPage);
            }
        }
        
        // Cargar el menú en el sidebar
        function loadMenu(menuItems) {
            menuContainer.innerHTML = '';
            
            menuItems.forEach(item => {
                const menuItem = document.createElement('div');
                menuItem.className = 'mb-2';
                
                const hasSubmenu = item.submenu && item.submenu.length > 0;
                
                menuItem.innerHTML = `
                    <button class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-primary-light text-gray-700 hover:text-primary transition duration-200 ${currentPage === item.page ? 'bg-primary-light text-primary font-medium' : ''}" data-page="${item.page}">
                        <div class="flex items-center">
                            <i class="${item.icon} mr-3"></i>
                            <span>${item.title}</span>
                        </div>
                        ${hasSubmenu ? '<i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>' : ''}
                    </button>
                    ${hasSubmenu ? `
                        <div class="submenu ml-8 mt-1 hidden">
                            ${item.submenu.map(sub => `
                                <button class="w-full flex items-center p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-primary transition duration-200 text-sm ${currentPage === sub.page ? 'bg-gray-100 text-primary font-medium' : ''}" data-page="${sub.page}">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    <span>${sub.title}</span>
                                </button>
                            `).join('')}
                        </div>
                    ` : ''}
                `;
                
                menuContainer.appendChild(menuItem);
                
                // Agregar evento para el item principal del menú
                const mainButton = menuItem.querySelector('button');
                mainButton.addEventListener('click', () => {
                    if (hasSubmenu) {
                        // Toggle del submenú
                        const submenu = menuItem.querySelector('.submenu');
                        const chevron = menuItem.querySelector('.fa-chevron-down');
                        
                        submenu.classList.toggle('hidden');
                        chevron.classList.toggle('rotate-180');
                    } else {
                        // Navegar a la página
                        loadPage(item.page);
                        closeSidebar();
                    }
                });
                
                // Agregar eventos para los subitems
                if (hasSubmenu) {
                    const subButtons = menuItem.querySelectorAll('.submenu button');
                    subButtons.forEach(button => {
                        button.addEventListener('click', (e) => {
                            e.stopPropagation();
                            loadPage(button.dataset.page);
                            closeSidebar();
                        });
                    });
                }
            });
        }
        
        // Cargar notificaciones
        function loadNotifications(notifications) {
            notificationsList.innerHTML = '';
            
            // Contar notificaciones no leídas
            const unreadCount = notifications.filter(n => !n.read).length;
            notificationCount.textContent = unreadCount;
            notificationStatus.textContent = `Tiene ${unreadCount} nuevas notificación${unreadCount !== 1 ? 'es' : ''}`;
            
            // Si no hay notificaciones
            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="p-4 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                        <p>No hay notificaciones</p>
                    </div>
                `;
                return;
            }
            
            // Mostrar notificaciones
            notifications.forEach(notification => {
                const notificationElement = document.createElement('div');
                notificationElement.className = `p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer ${notification.read ? '' : 'bg-primary-light'}`;
                notificationElement.innerHTML = `
                    <div class="flex justify-between">
                        <p class="text-gray-800 ${notification.read ? '' : 'font-medium'}">${notification.text}</p>
                        ${!notification.read ? '<span class="h-2 w-2 bg-secondary rounded-full ml-2"></span>' : ''}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">${notification.time}</p>
                `;
                
                notificationElement.addEventListener('click', () => {
                    // Marcar como leída (en una aplicación real, esto enviaría una petición al servidor)
                    notification.read = true;
                    loadNotifications(userData[currentUserType].notifications);
                });
                
                notificationsList.appendChild(notificationElement);
            });
        }
        
        // Cargar una página específica
        function loadPage(page) {
            currentPage = page;
            
            // Actualizar título de la página
            updatePageTitle(page);
            
            // Actualizar breadcrumbs
            updateBreadcrumbs(page);
            
            // Marcar la página activa en el menú
            markActiveMenuItem(page);
            
            // Cargar contenido de la página (simulado)
            loadPageContent(page);
        }
        
        // Actualizar título de la página
        function updatePageTitle(page) {
            // Buscar el título en el menú actual
            const menu = userData[currentUserType].menu;
            let pageTitleText = 'Dashboard';
            
            // Buscar en items principales
            for (const item of menu) {
                if (item.page === page) {
                    pageTitleText = item.title;
                    break;
                }
                
                // Buscar en subitems
                for (const subitem of item.submenu) {
                    if (subitem.page === page) {
                        pageTitleText = `${item.title} - ${subitem.title}`;
                        break;
                    }
                }
            }
            
            pageTitle.textContent = pageTitleText;
        }
        
        // Actualizar breadcrumbs
        function updateBreadcrumbs(page) {
            const menu = userData[currentUserType].menu;
            let breadcrumbsHTML = '';
            
            // Siempre incluir Dashboard como primer breadcrumb
            breadcrumbsHTML = `
                <li>
                    <button class="text-primary hover:text-primary-dark" data-page="dashboard">
                        <i class="fas fa-home"></i>
                    </button>
                </li>
                <li>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </li>
            `;
            
            // Buscar la página en el menú
            for (const item of menu) {
                if (item.page === page) {
                    breadcrumbsHTML += `<li class="text-gray-800 font-medium">${item.title}</li>`;
                    break;
                }
                
                // Buscar en subitems
                for (const subitem of item.submenu) {
                    if (subitem.page === page) {
                        breadcrumbsHTML += `
                            <li>
                                <button class="text-primary hover:text-primary-dark" data-page="${item.page}">${item.title}</button>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </li>
                            <li class="text-gray-800 font-medium">${subitem.title}</li>
                        `;
                        break;
                    }
                }
            }
            
            breadcrumbs.innerHTML = breadcrumbsHTML;
            
            // Agregar eventos a los breadcrumbs clicables
            breadcrumbs.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', () => {
                    loadPage(button.dataset.page);
                });
            });
        }
        
        // Marcar el item activo en el menú
        function markActiveMenuItem(page) {
            // Quitar la clase activa de todos los items
            document.querySelectorAll('#menu button').forEach(button => {
                button.classList.remove('bg-primary-light', 'text-primary', 'font-medium');
                button.classList.add('text-gray-700');
            });
            
            // Agregar la clase activa al item correspondiente
            const activeButton = document.querySelector(`#menu button[data-page="${page}"]`);
            if (activeButton) {
                activeButton.classList.add('bg-primary-light', 'text-primary', 'font-medium');
                activeButton.classList.remove('text-gray-700');
            }
            
            // También marcar el item principal si es un subitem
            const menu = userData[currentUserType].menu;
            for (const item of menu) {
                for (const subitem of item.submenu) {
                    if (subitem.page === page) {
                        const parentButton = document.querySelector(`#menu button[data-page="${item.page}"]`);
                        if (parentButton) {
                            parentButton.classList.add('bg-primary-light', 'text-primary', 'font-medium');
                            parentButton.classList.remove('text-gray-700');
                        }
                        break;
                    }
                }
            }
        }
        
        // Cargar contenido de la página (simulado)
        function loadPageContent(page) {
            let contentHTML = '';
            
            switch(page) {
                case 'dashboard':
                    contentHTML = `
                        <h3 class="text-2xl font-bold text-primary mb-4">Dashboard de ${userData[currentUserType].role}</h3>
                        <p class="text-gray-700 mb-6">Bienvenido al sistema de gestión de pagos de servicio de internet. Desde aquí puede acceder a todas las funcionalidades disponibles para su perfil.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-primary-light p-5 rounded-xl border-l-4 border-primary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-primary text-lg">Pagos del Mes</h4>
                                        <p class="text-3xl font-bold mt-2 text-primary">${currentUserType === 'admin' ? '142' : currentUserType === 'employee' ? '89' : '1'}</p>
                                    </div>
                                    <div class="bg-primary p-4 rounded-lg">
                                        <i class="fas fa-credit-card text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">${currentUserType === 'user' ? 'Su pago está al día' : 'Pagos procesados este mes'}</p>
                            </div>
                            
                            <div class="bg-secondary-light p-5 rounded-xl border-l-4 border-secondary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-secondary text-lg">${currentUserType === 'user' ? 'Estado del Servicio' : 'Clientes Activos'}</h4>
                                        <p class="text-3xl font-bold mt-2 text-secondary">${currentUserType === 'admin' ? '1,248' : currentUserType === 'employee' ? '624' : 'Activo'}</p>
                                    </div>
                                    <div class="bg-secondary p-4 rounded-lg">
                                        <i class="fas fa-${currentUserType === 'user' ? 'wifi' : 'users'} text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">${currentUserType === 'user' ? 'Conectado a la red principal' : 'Clientes con servicio activo'}</p>
                            </div>
                            
                            <div class="bg-primary-light p-5 rounded-xl border-l-4 border-primary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-primary text-lg">${currentUserType === 'admin' ? 'Ingresos Mensuales' : currentUserType === 'employee' ? 'Pagos Pendientes' : 'Próximo Pago'}</h4>
                                        <p class="text-3xl font-bold mt-2 text-primary">${currentUserType === 'admin' ? '$42,580' : currentUserType === 'employee' ? '23' : '15 Ene'}</p>
                                    </div>
                                    <div class="bg-primary p-4 rounded-lg">
                                        <i class="fas fa-${currentUserType === 'admin' ? 'dollar-sign' : currentUserType === 'employee' ? 'clock' : 'calendar-alt'} text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">${currentUserType === 'user' ? 'Fecha de vencimiento' : currentUserType === 'employee' ? 'Clientes con pagos atrasados' : 'Total ingresos este mes'}</p>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="text-xl font-bold text-primary mb-4">Actividad Reciente</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700">Esta es una simulación del contenido que se cargaría para la página "${page}". En una aplicación real, aquí se mostrarían datos dinámicos según el perfil del usuario y la página seleccionada.</p>
                                <p class="text-gray-700 mt-2">Para ver el contenido completo de cada sección, seleccione las diferentes opciones del menú lateral.</p>
                            </div>
                        </div>
                    `;
                    break;
                    
                default:
                    contentHTML = `
                        <h3 class="text-2xl font-bold text-primary mb-4">${pageTitle.textContent}</h3>
                        <p class="text-gray-700 mb-6">Esta es una vista previa de la página <strong>"${page}"</strong>. En una aplicación real, aquí se cargaría el contenido específico para esta sección.</p>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-primary mb-3">Funcionalidades de esta página:</h4>
                            <ul class="list-disc pl-5 text-gray-700 space-y-2">
                                <li>Contenido dinámico según el perfil del usuario (${currentUserType})</li>
                                <li>Datos específicos para la gestión de pagos de internet</li>
                                <li>Interfaz optimizada para ${currentUserType === 'user' ? 'clientes' : currentUserType === 'employee' ? 'empleados' : 'administradores'}</li>
                                <li>Integración con base de datos para información en tiempo real</li>
                            </ul>
                            
                            <div class="mt-6 p-4 bg-primary-light rounded-lg">
                                <p class="text-primary font-medium"><i class="fas fa-info-circle mr-2"></i> Esta es una demostración estática. En una implementación completa, el contenido se cargaría dinámicamente usando PHP includes, AJAX o un framework frontend.</p>
                            </div>
                        </div>
                    `;
            }
            
            dashboardContent.innerHTML = contentHTML;
        }
        
        // Mostrar/ocultar sidebar en móviles
        function toggleSidebar() {
            if (sidebarVisible) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
        
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0', 'animate-slide-in');
            overlay.classList.remove('hidden');
            sidebarVisible = true;
        }
        
        function closeSidebar() {
            sidebar.classList.remove('translate-x-0', 'animate-slide-in');
            sidebar.classList.add('-translate-x-full', 'animate-slide-out');
            overlay.classList.add('hidden');
            sidebarVisible = false;
        }
        
        // Mostrar/ocultar notificaciones
        function toggleNotifications() {
            if (notificationsVisible) {
                closeNotifications();
            } else {
                openNotifications();
            }
        }
        
        function openNotifications() {
            notificationsPanel.classList.remove('hidden');
            notificationsVisible = true;
        }
        
        function closeNotifications() {
            notificationsPanel.classList.add('hidden');
            notificationsVisible = false;
        }
        
        // Inicializar la aplicación cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>