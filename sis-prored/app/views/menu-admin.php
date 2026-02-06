<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | ProRed</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005FA2',         // Azul Corporativo
                        'primary-dark': '#004a80',
                        'primary-light': '#E6F2FA', // Fondo azul suave
                        secondary: '#E58E21',       // Naranja Corporativo
                        'secondary-light': '#FEF5E7', // Fondo naranja suave
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        surface: '#ffffff',
                        background: '#f3f4f6'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 15px -3px rgba(0, 95, 162, 0.1), 0 4px 6px -2px rgba(0, 95, 162, 0.05)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f3f4f6;
            color: #374151;
        }

        /* Scrollbar Corporativo */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 95, 162, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #005FA2;
        }

        /* Animaciones */
        .page-enter {
            animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Componentes UI */
        .btn-primary {
            @apply bg-primary text-white hover:bg-primary-dark shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center;
        }

        .btn-secondary {
            @apply bg-secondary text-white hover:bg-orange-600 shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center;
        }

        .card-base {
            @apply bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300;
        }

        .badge {
            @apply px-2 py-1 rounded text-xs font-bold;
        }
    </style>
</head>

<body class="bg-background font-sans text-gray-700 antialiased overflow-hidden">

    <div class="flex h-screen">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity"
            onclick="closeSidebar()"></div>

        <aside id="sidebar"
            class="bg-white shadow-xl flex flex-col w-64 fixed md:relative h-full z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex flex-col items-center justify-center">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-primary text-white p-2.5 rounded-lg shadow-lg shadow-primary/30 flex items-center justify-center">
                        <img src="assets/img/logo.ico" alt="ProRed Logo" class="w-6 h-6 object-contain">
                    </div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">Pro<span
                            class="text-secondary">Red</span></h1>
                </div>
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Administración</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold shadow-md">
                        <span id="userInitial">C</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 id="userName" class="font-semibold text-sm text-gray-800 truncate">Carlos Rodríguez</h3>
                        <p id="userRole" class="text-xs text-gray-500 truncate">Super Admin</p>
                    </div>
                </div>
                <button id="logoutBtn" onclick="alert('Cerrando sesión de administrador...')"
                    class="w-full py-2 px-4 border border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-500 hover:border-red-100 font-medium rounded-lg transition-colors flex items-center justify-center text-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <button id="sidebarToggle" class="md:hidden text-gray-500 hover:text-primary transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 id="pageTitle" class="text-xl font-bold text-gray-800">Home</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden md:flex relative">
                            <input type="text" placeholder="Buscar en logs, usuarios..."
                                class="pl-9 pr-4 py-1.5 rounded-full border border-gray-200 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>

                        <div class="relative">
                            <button id="notificationsBtn"
                                class="relative p-2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notificationCount"
                                    class="absolute top-1 right-1 bg-danger text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">3</span>
                            </button>
                            <div id="notificationsPanel"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                                <div class="p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-gray-800">Alertas del Sistema</h3>
                                    <p class="text-xs text-gray-500" id="notificationStatus">3 críticas</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar" id="notificationsList"></div>
                                <div class="p-3 text-center border-t border-gray-100">
                                    <button class="text-xs font-semibold text-primary hover:text-primary-dark">Ver logs
                                        completos</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol id="breadcrumbs" class="flex items-center space-x-2 text-xs text-gray-500"></ol>
                    </nav>
                </div>
            </header>

            <main id="mainContainer" class="flex-grow p-4 md:p-6 overflow-y-auto custom-scrollbar bg-background">
                <div id="dashboardContent" class="max-w-7xl mx-auto page-enter">
                </div>
            </main>
        </div>
    </div>

    <script>
        // --- CONFIGURACIÓN DEL ROL: ADMINISTRADOR ---
        const userData = {
            name: "Carlos Rodríguez",
            role: "Super Admin",
            initial: "C",
            // Menú actualizado según solicitud
            menu: [
                {
                    title: "Home",
                    icon: "fas fa-home",
                    page: "home",
                    submenu: []
                },
                {
                    title: "Gestión de Empleados",
                    icon: "fas fa-user-tie",
                    page: "empleados",
                    submenu: [
                        { title: "Lista de Personal", page: "lista-empleados" },
                        { title: "Asignación de Roles", page: "roles" },
                        { title: "Control de Asistencia", page: "asistencia" }
                    ]
                },
                {
                    title: "Configuración del Sistema",
                    icon: "fas fa-cogs",
                    page: "configuracion",
                    submenu: [
                        { title: "Parámetros Generales", page: "parametros" },
                        { title: "Tarifas y Planes", page: "tarifas" },
                        { title: "Integraciones API", page: "api" }
                    ]
                },
                {
                    title: "Reportes Ejecutivos",
                    icon: "fas fa-chart-bar",
                    page: "reportes",
                    submenu: []
                },
                {
                    title: "Auditoría y Logs",
                    icon: "fas fa-shield-alt",
                    page: "auditoria",
                    submenu: []
                },
                {
                    title: "Acceso a Todos los Módulos",
                    icon: "fas fa-th-large",
                    page: "modulos",
                    submenu: []
                },
                {
                    title: "Mi Perfil",
                    icon: "fas fa-user-circle",
                    page: "perfil",
                    submenu: []
                }
            ],
            notifications: [
                { id: 1, text: "CPU Server Principal > 90%", time: "Hace 5 min", read: false, type: 'danger' },
                { id: 2, text: "Intento fallido de login (IP 192.168.x.x)", time: "Hace 1 hora", read: false, type: 'warning' },
                { id: 3, text: "Backup diario completado", time: "Hace 4 horas", read: true, type: 'success' }
            ]
        };

        // Estado Global
        let currentPage = 'home';
        let sidebarVisible = false;

        // Elementos DOM
        const dom = {
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('overlay'),
            menu: document.getElementById('menu'),
            pageTitle: document.getElementById('pageTitle'),
            content: document.getElementById('dashboardContent'),
            notifPanel: document.getElementById('notificationsPanel')
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            initUserData();
            setupListeners();
            loadPage('home');
        });

        function initUserData() {
            document.getElementById('userName').textContent = userData.name;
            document.getElementById('userRole').textContent = userData.role;
            document.getElementById('userInitial').textContent = userData.initial;
            loadMenu(userData.menu);
            loadNotifications(userData.notifications);
        }

        // --- LÓGICA DEL MENÚ ---
        function loadMenu(menuItems) {
            dom.menu.innerHTML = '';

            menuItems.forEach(item => {
                const isActive = currentPage === item.page || (item.submenu && item.submenu.some(sub => sub.page === currentPage));
                const hasSubmenu = item.submenu && item.submenu.length > 0;

                const activeClasses = 'bg-primary-light text-primary font-semibold border-r-4 border-primary';
                const inactiveClasses = 'text-gray-600 hover:bg-gray-50 hover:text-primary border-r-4 border-transparent';

                const div = document.createElement('div');
                div.className = 'mb-1';
                div.innerHTML = `
                    <button 
                        class="w-full flex items-center justify-between p-3 rounded-l-lg transition-all duration-200 group ${isActive ? activeClasses : inactiveClasses}" 
                        onclick="${hasSubmenu ? `toggleSubmenu('${item.page}')` : `loadPage('${item.page}'); closeSidebar();`}"
                    >
                        <div class="flex items-center">
                            <div class="w-6 flex justify-center">
                                <i class="${item.icon} text-lg ${isActive ? 'text-primary' : 'text-gray-400 group-hover:text-primary'} transition-colors"></i>
                            </div>
                            <span class="ml-3 text-sm">${item.title}</span>
                        </div>
                        ${hasSubmenu ? `<i id="arrow-${item.page}" class="fas fa-chevron-down text-xs transition-transform duration-300 ${isActive ? 'rotate-180' : ''}"></i>` : ''}
                    </button>
                    
                    ${hasSubmenu ? `
                        <div id="submenu-${item.page}" class="ml-9 mt-1 space-y-1 overflow-hidden transition-all duration-300 ${isActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'}">
                            ${item.submenu.map(sub => {
                    const isSubActive = currentPage === sub.page;
                    return `
                                <button 
                                    class="w-full text-left p-2 pl-3 rounded-lg text-xs transition-colors border-l-2 ${isSubActive ? 'border-secondary text-primary font-medium bg-white shadow-sm' : 'border-gray-200 text-gray-500 hover:text-primary hover:bg-gray-50'}"
                                    onclick="event.stopPropagation(); loadPage('${sub.page}'); closeSidebar();"
                                >
                                    ${sub.title}
                                </button>
                                `
                }).join('')}
                        </div>
                    ` : ''}
                `;
                dom.menu.appendChild(div);
            });
        }

        function toggleSubmenu(id) {
            const submenu = document.getElementById(`submenu-${id}`);
            const arrow = document.getElementById(`arrow-${id}`);

            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0', 'opacity-0');
                submenu.classList.add('max-h-96', 'opacity-100');
                arrow.classList.add('rotate-180');
            } else {
                submenu.classList.add('max-h-0', 'opacity-0');
                submenu.classList.remove('max-h-96', 'opacity-100');
                arrow.classList.remove('rotate-180');
            }
        }

        // --- RENDERIZADO DE CONTENIDO (ADMINISTRADOR) ---
        function loadPage(page) {
            currentPage = page;
            updateBreadcrumbs(page);
            loadMenu(userData.menu);

            const flatMenu = userData.menu.flatMap(i => [i, ...(i.submenu || [])]);
            const currentItem = flatMenu.find(i => i.page === page);
            if (currentItem) dom.pageTitle.textContent = currentItem.title;

            loadPageContent(page);
        }

        function loadPageContent(page) {
            // Contenido temporal - se cargará dinámicamente desde includes/
            const html = `
                <div class="card-base p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-laptop-code text-4xl text-gray-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">${dom.pageTitle.textContent}</h2>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">El contenido se cargará dinámicamente desde la carpeta includes.</p>
                </div>
            `;

            dom.content.innerHTML = html;
        }

        // --- UTILIDADES ---
        function updateBreadcrumbs(page) {
            const crumbs = document.getElementById('breadcrumbs');
            let html = `<li><button class="hover:text-primary" onclick="loadPage('home')"><i class="fas fa-home"></i></button></li>`;

            const flatMenu = userData.menu.flatMap(i => {
                if (i.page === page) return [{ title: i.title }];
                if (i.submenu) {
                    const found = i.submenu.find(s => s.page === page);
                    if (found) return [{ title: i.title }, { title: found.title }];
                }
                return [];
            });

            if (flatMenu.length > 0 && page !== 'home') {
                flatMenu.forEach(item => {
                    html += `<li><span class="mx-2 text-gray-300">/</span></li><li>${item.title}</li>`;
                });
            }
            crumbs.innerHTML = html;
        }

        function loadNotifications(list) {
            const container = document.getElementById('notificationsList');
            container.innerHTML = '';
            list.forEach(n => {
                const item = document.createElement('div');
                item.className = `p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer flex gap-3 ${n.read ? 'opacity-60' : ''}`;

                let iconClass = 'text-primary bg-primary-light';
                let icon = 'info-circle';
                if (n.type === 'danger') { iconClass = 'text-danger bg-red-50'; icon = 'exclamation-circle'; }
                if (n.type === 'warning') { iconClass = 'text-warning bg-orange-50'; icon = 'shield-alt'; }
                if (n.type === 'success') { iconClass = 'text-success bg-green-50'; icon = 'check-circle'; }

                item.innerHTML = `
                    <div class="w-8 h-8 rounded-full ${iconClass} flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-${icon} text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-800 ${!n.read ? 'font-semibold' : ''}">${n.text}</p>
                        <p class="text-xs text-gray-400 mt-1">${n.time}</p>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        function setupListeners() {
            const toggle = () => {
                sidebarVisible = !sidebarVisible;
                if (sidebarVisible) {
                    dom.sidebar.classList.remove('-translate-x-full');
                    dom.overlay.classList.remove('hidden');
                    setTimeout(() => dom.overlay.classList.remove('opacity-0'), 10);
                } else {
                    dom.sidebar.classList.add('-translate-x-full');
                    dom.overlay.classList.add('opacity-0');
                    setTimeout(() => dom.overlay.classList.add('hidden'), 300);
                }
            };

            document.getElementById('sidebarToggle').addEventListener('click', toggle);
            window.closeSidebar = () => { if (sidebarVisible) toggle(); };

            document.getElementById('notificationsBtn').addEventListener('click', (e) => {
                e.stopPropagation();
                dom.notifPanel.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!dom.notifPanel.contains(e.target)) dom.notifPanel.classList.add('hidden');
            });
        }
    </script>
</body>

</html>