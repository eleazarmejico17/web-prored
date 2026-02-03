<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Pagos de Internet - Empleado</title>
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
    <div class="flex min-h-screen">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
        
        <aside id="sidebar" class="bg-white shadow-xl flex flex-col w-64 fixed md:relative h-screen z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-center">
                    <div class="bg-primary p-3 rounded-lg">
                        <i class="fas fa-wifi text-white text-2xl"></i>
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-primary">Pro<span class="text-secondary">Red</span></h1>
                </div>
                <p class="text-gray-500 text-sm text-center mt-2">Panel del Empleado</p>
            </div>
            
            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar">
                <!-- Menú específico para empleados -->
            </nav>
            
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                        <span id="userInitial">M</span>
                    </div>
                    <div class="ml-3">
                        <h3 id="userName" class="font-medium text-gray-800">María González</h3>
                        <p id="userRole" class="text-xs text-gray-500">Empleado</p>
                    </div>
                </div>
                <button id="logoutBtn" class="w-full py-2 px-4 bg-secondary hover:bg-orange-600 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Cerrar sesión
                </button>
            </div>
        </aside>
        
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between p-4">
                    <button id="sidebarToggle" class="md:hidden text-primary hover:text-primary-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex items-center">
                        <h2 id="pageTitle" class="text-2xl font-bold text-primary ml-2 md:ml-0">Dashboard</h2>
                    </div>
                    
                    <div class="relative">
                        <button id="notificationsBtn" class="relative text-gray-600 hover:text-primary focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationCount" class="absolute -top-2 -right-2 bg-secondary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">2</span>
                        </button>
                        
                        <div id="notificationsPanel" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-lg text-primary">Notificaciones</h3>
                                <p class="text-sm text-gray-500" id="notificationStatus">Tiene 2 nuevas notificaciones</p>
                            </div>
                            <div class="max-h-64 overflow-y-auto custom-scrollbar" id="notificationsList"></div>
                            <div class="p-3 border-t border-gray-200 text-center">
                                <a href="#" class="text-primary hover:text-primary-dark font-medium text-sm">Ver todas las notificaciones</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 pb-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol id="breadcrumbs" class="flex items-center space-x-2 text-sm"></ol>
                    </nav>
                </div>
            </header>
            
            <main class="flex-grow p-4 md:p-6 overflow-y-auto">
                <div id="dashboardContent" class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Bienvenido al Panel del Empleado</h3>
                    <p class="text-gray-700 mb-4">Gestión de clientes y pagos del sistema.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-primary-light p-5 rounded-lg border-l-4 border-primary">
                            <div class="flex items-center">
                                <div class="bg-primary p-3 rounded-lg">
                                    <i class="fas fa-cash-register text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-primary">Gestión de Pagos</h4>
                                    <p class="text-gray-600 text-sm">Registre y administre pagos</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-secondary-light p-5 rounded-lg border-l-4 border-secondary">
                            <div class="flex items-center">
                                <div class="bg-secondary p-3 rounded-lg">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-secondary">Clientes</h4>
                                    <p class="text-gray-600 text-sm">Administre la cartera de clientes</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-primary-light p-5 rounded-lg border-l-4 border-primary">
                            <div class="flex items-center">
                                <div class="bg-primary p-3 rounded-lg">
                                    <i class="fas fa-concierge-bell text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-primary">Servicios</h4>
                                    <p class="text-gray-600 text-sm">Gestione activación y suspensiones</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Datos específicos para empleado
        const userData = {
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
        };

        let currentPage = 'dashboard';
        let sidebarVisible = false;
        let notificationsVisible = false;
        
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');
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
        
        function init() {
            loadUserData();
            setupEventListeners();
            loadPage(currentPage);
        }
        
        function setupEventListeners() {
            sidebarToggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', closeSidebar);
            notificationsBtn.addEventListener('click', toggleNotifications);
            
            document.addEventListener('click', (e) => {
                if (!notificationsBtn.contains(e.target) && !notificationsPanel.contains(e.target)) {
                    closeNotifications();
                }
            });
            
            logoutBtn.addEventListener('click', () => {
                alert('Función de cierre de sesión implementada.');
            });
        }
        
        function loadUserData() {
            userName.textContent = userData.name;
            userRole.textContent = userData.role;
            userInitial.textContent = userData.initial;
            loadMenu(userData.menu);
            loadNotifications(userData.notifications);
        }
        
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
                
                const mainButton = menuItem.querySelector('button');
                mainButton.addEventListener('click', () => {
                    if (hasSubmenu) {
                        const submenu = menuItem.querySelector('.submenu');
                        const chevron = menuItem.querySelector('.fa-chevron-down');
                        submenu.classList.toggle('hidden');
                        chevron.classList.toggle('rotate-180');
                    } else {
                        loadPage(item.page);
                        closeSidebar();
                    }
                });
                
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
        
        function loadNotifications(notifications) {
            notificationsList.innerHTML = '';
            const unreadCount = notifications.filter(n => !n.read).length;
            notificationCount.textContent = unreadCount;
            notificationStatus.textContent = `Tiene ${unreadCount} nuevas notificación${unreadCount !== 1 ? 'es' : ''}`;
            
            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="p-4 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                        <p>No hay notificaciones</p>
                    </div>
                `;
                return;
            }
            
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
                    notification.read = true;
                    loadNotifications(userData.notifications);
                });
                
                notificationsList.appendChild(notificationElement);
            });
        }
        
        function loadPage(page) {
            currentPage = page;
            updatePageTitle(page);
            updateBreadcrumbs(page);
            markActiveMenuItem(page);
            loadPageContent(page);
        }
        
        function updatePageTitle(page) {
            let pageTitleText = 'Dashboard';
            
            for (const item of userData.menu) {
                if (item.page === page) {
                    pageTitleText = item.title;
                    break;
                }
                
                for (const subitem of item.submenu) {
                    if (subitem.page === page) {
                        pageTitleText = `${item.title} - ${subitem.title}`;
                        break;
                    }
                }
            }
            
            pageTitle.textContent = pageTitleText;
        }
        
        function updateBreadcrumbs(page) {
            let breadcrumbsHTML = `
                <li>
                    <button class="text-primary hover:text-primary-dark" data-page="dashboard">
                        <i class="fas fa-home"></i>
                    </button>
                </li>
                <li>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </li>
            `;
            
            for (const item of userData.menu) {
                if (item.page === page) {
                    breadcrumbsHTML += `<li class="text-gray-800 font-medium">${item.title}</li>`;
                    break;
                }
                
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
            
            breadcrumbs.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', () => {
                    loadPage(button.dataset.page);
                });
            });
        }
        
        function markActiveMenuItem(page) {
            document.querySelectorAll('#menu button').forEach(button => {
                button.classList.remove('bg-primary-light', 'text-primary', 'font-medium');
                button.classList.add('text-gray-700');
            });
            
            const activeButton = document.querySelector(`#menu button[data-page="${page}"]`);
            if (activeButton) {
                activeButton.classList.add('bg-primary-light', 'text-primary', 'font-medium');
                activeButton.classList.remove('text-gray-700');
            }
            
            for (const item of userData.menu) {
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
        
        function loadPageContent(page) {
            let contentHTML = '';
            
            switch(page) {
                case 'dashboard':
                    contentHTML = `
                        <h3 class="text-2xl font-bold text-primary mb-4">Dashboard del Empleado</h3>
                        <p class="text-gray-700 mb-6">Panel de gestión para administrar clientes, pagos y servicios.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-primary-light p-5 rounded-xl border-l-4 border-primary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-primary text-lg">Pagos del Mes</h4>
                                        <p class="text-3xl font-bold mt-2 text-primary">89</p>
                                    </div>
                                    <div class="bg-primary p-4 rounded-lg">
                                        <i class="fas fa-credit-card text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">Pagos procesados este mes</p>
                            </div>
                            
                            <div class="bg-secondary-light p-5 rounded-xl border-l-4 border-secondary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-secondary text-lg">Clientes Activos</h4>
                                        <p class="text-3xl font-bold mt-2 text-secondary">624</p>
                                    </div>
                                    <div class="bg-secondary p-4 rounded-lg">
                                        <i class="fas fa-users text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">Clientes con servicio activo</p>
                            </div>
                            
                            <div class="bg-primary-light p-5 rounded-xl border-l-4 border-primary">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-primary text-lg">Pagos Pendientes</h4>
                                        <p class="text-3xl font-bold mt-2 text-primary">23</p>
                                    </div>
                                    <div class="bg-primary p-4 rounded-lg">
                                        <i class="fas fa-clock text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">Clientes con pagos atrasados</p>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="text-xl font-bold text-primary mb-4">Acciones Rápidas</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button class="bg-primary text-white p-4 rounded-lg flex items-center justify-between hover:bg-primary-dark transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-plus-circle text-xl mr-3"></i>
                                        <span>Registrar Nuevo Pago</span>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <button class="bg-secondary text-white p-4 rounded-lg flex items-center justify-between hover:bg-orange-600 transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-plus text-xl mr-3"></i>
                                        <span>Agregar Nuevo Cliente</span>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    break;
                    
                default:
                    contentHTML = `
                        <h3 class="text-2xl font-bold text-primary mb-4">${pageTitle.textContent}</h3>
                        <p class="text-gray-700 mb-6">Esta es una vista previa de la página <strong>"${page}"</strong>.</p>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-primary mb-3">Funcionalidades de esta página:</h4>
                            <ul class="list-disc pl-5 text-gray-700 space-y-2">
                                <li>Gestión de clientes y pagos</li>
                                <li>Registro de transacciones</li>
                                <li>Administración de servicios</li>
                                <li>Generación de reportes operativos</li>
                            </ul>
                        </div>
                    `;
            }
            
            dashboardContent.innerHTML = contentHTML;
        }
        
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
        
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>