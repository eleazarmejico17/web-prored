<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico | ProRed</title>

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

        /* Badges de estado para tickets */
        .badge-urgent {
            @apply bg-red-100 text-red-700 border border-red-200;
        }

        .badge-medium {
            @apply bg-orange-100 text-orange-700 border border-orange-200;
        }

        .badge-low {
            @apply bg-green-100 text-green-700 border border-green-200;
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
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Módulo de Soporte</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md">
                        <span id="userInitial">R</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 id="userName" class="font-semibold text-sm text-gray-800 truncate">Roberto Díaz</h3>
                        <p id="userRole" class="text-xs text-gray-500 truncate">Soporte Nivel 2</p>
                    </div>
                </div>
                <button id="logoutBtn" onclick="alert('Cerrando sesión de soporte...')"
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
                            <input type="text" placeholder="Buscar ticket #, IP, Cliente..."
                                class="pl-9 pr-4 py-1.5 rounded-full border border-gray-200 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>

                        <div class="relative">
                            <button id="notificationsBtn"
                                class="relative p-2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notificationCount"
                                    class="absolute top-1 right-1 bg-secondary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">4</span>
                            </button>
                            <div id="notificationsPanel"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                                <div class="p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-gray-800">Cola de Trabajo</h3>
                                    <p class="text-xs text-gray-500" id="notificationStatus">2 tickets críticos
                                        asignados</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar" id="notificationsList"></div>
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
        // --- CONFIGURACIÓN DEL ROL: SOPORTE TÉCNICO ---
        const userData = {
            name: "Roberto Díaz",
            role: "Soporte Técnico",
            initial: "R",
            // Menú configurado según tu solicitud
            menu: [
                {
                    title: "Home",
                    icon: "fas fa-home",
                    page: "home",
                    submenu: []
                },
                {
                    title: "Tickets",
                    icon: "fas fa-ticket-alt",
                    page: "tickets",
                    submenu: [
                        { title: "Mis Asignaciones", page: "mis-tickets" },
                        { title: "Cola General", page: "cola-tickets" },
                        { title: "Crear Ticket", page: "nuevo-ticket" }
                    ]
                },
                {
                    title: "Clientes",
                    icon: "fas fa-users",
                    page: "clientes",
                    submenu: [
                        { title: "Diagnóstico Rápido", page: "diagnostico" },
                        { title: "Equipos Instalados", page: "equipos" }
                    ]
                },
                {
                    title: "Reportes",
                    icon: "fas fa-chart-pie",
                    page: "reportes",
                    submenu: [
                        { title: "Rendimiento", page: "metricas" },
                        { title: "Incidencias por Zona", page: "mapa-calor" }
                    ]
                },
                {
                    title: "Mi Perfil",
                    icon: "fas fa-user-circle",
                    page: "perfil",
                    submenu: []
                }
            ],
            notifications: [
                { id: 1, text: "Ticket #4920: Sin conexión (Empresarial)", time: "Hace 5 min", read: false, type: 'critical' },
                { id: 2, text: "Ticket #4918: Lentitud zona Sur", time: "Hace 20 min", read: false, type: 'medium' },
                { id: 3, text: "Mantenimiento OLT-04 finalizado", time: "Hace 1 hora", read: true, type: 'success' }
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

        // --- RENDERIZADO DE CONTENIDO (SOPORTE) ---
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
        <div class="card-base p-6 border-l-4 border-${color}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">${title}</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">${val}</h3>
                </div>
                <div class="p-3 rounded-lg bg-${color}-light text-${color} bg-opacity-50">
                    <i class="${icon} text-xl"></i>
                </div>
            </div>
            ${subText ? `<p class="mt-4 text-xs text-gray-500">${subText}</p>` : ''}
        </div>
        `;

            switch (page) {
                case 'home':
                    html = `
            < div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" >
                ${ renderMetric('Mis Tickets', '5', 'fas fa-inbox', 'primary', '2 prioritarios') }
                            ${ renderMetric('Resueltos Hoy', '8', 'fas fa-check-double', 'success', 'Tiempo prom: 15m') }
                            ${ renderMetric('En Cola', '12', 'fas fa-layer-group', 'secondary', 'Área general') }
                            ${ renderMetric('Averías Masivas', '0', 'fas fa-broadcast-tower', 'danger', 'Red estable') }
                        </div >

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 card-base overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Tickets Prioritarios</h3>
                        <button class="text-primary text-xs font-bold hover:underline" onclick="loadPage('mis-tickets')">Ver todos</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-medium">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Cliente</th>
                                    <th class="px-6 py-3">Problema</th>
                                    <th class="px-6 py-3">Tiempo</th>
                                    <th class="px-6 py-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-800">#4920</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">Empresa Tech SAC</div>
                                        <div class="text-xs text-gray-500">Plan Corporativo</div>
                                    </td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-bold badge-urgent">Corte Total</span></td>
                                    <td class="px-6 py-4 text-xs text-red-600 font-bold">45 min</td>
                                    <td class="px-6 py-4"><button class="btn-primary py-1 px-3 text-xs">Atender</button></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-800">#4915</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">Familia Torres</div>
                                        <div class="text-xs text-gray-500">Residencial 100Mb</div>
                                    </td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-bold badge-medium">Lentitud</span></td>
                                    <td class="px-6 py-4 text-xs text-gray-500">2 horas</td>
                                    <td class="px-6 py-4"><button class="btn-secondary py-1 px-3 text-xs">Revisar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-base p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Diagnóstico Rápido</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Consultar Señal (ONU/Router)</label>
                            <div class="flex gap-2">
                                <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary" placeholder="MAC o Usuario...">
                                    <button class="bg-primary text-white rounded px-3 hover:bg-primary-dark transition"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Estado de la Red</h4>
                            <div class="flex items-center justify-between text-sm mb-2 p-2 bg-green-50 rounded border border-green-100">
                                <span class="text-green-800 font-medium"><i class="fas fa-server mr-2"></i> OLT Norte</span>
                                <span class="text-xs bg-white px-2 py-1 rounded border border-green-200 text-green-700 font-bold">ONLINE</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-2 p-2 bg-green-50 rounded border border-green-100">
                                <span class="text-green-800 font-medium"><i class="fas fa-server mr-2"></i> OLT Sur</span>
                                <span class="text-xs bg-white px-2 py-1 rounded border border-green-200 text-green-700 font-bold">ONLINE</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-2 p-2 bg-red-50 rounded border border-red-100">
                                <span class="text-red-800 font-medium"><i class="fas fa-wifi mr-2"></i> Nodo Centro</span>
                                <span class="text-xs bg-white px-2 py-1 rounded border border-red-200 text-red-700 font-bold">ALERTA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                    break;

                case 'tickets':
                    // Vista genérica para la sección de tickets
                    html = `
            < div class="card-base p-6" >
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-gray-800 text-lg">Gestión de Tickets</h3>
                                <div class="flex gap-2">
                                    <button class="btn-secondary text-xs"><i class="fas fa-filter mr-2"></i> Filtrar</button>
                                    <button class="btn-primary text-xs"><i class="fas fa-plus mr-2"></i> Nuevo Ticket</button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="font-bold text-gray-600 mb-3 flex justify-between">Pendientes <span class="bg-gray-200 px-2 rounded-full text-xs flex items-center">3</span></h4>
                                    <div class="space-y-3">
                                        <div class="bg-white p-3 rounded shadow-sm border-l-4 border-primary cursor-pointer hover:shadow-md transition">
                                            <div class="flex justify-between mb-1"><span class="text-xs font-bold text-gray-400">#4922</span> <span class="text-xs text-primary font-bold">Alta</span></div>
                                            <p class="text-sm font-medium text-gray-800">Cambio de Clave Wifi</p>
                                            <p class="text-xs text-gray-500 mt-1">Cliente: Juan Perez</p>
                                        </div>
                                         <div class="bg-white p-3 rounded shadow-sm border-l-4 border-danger cursor-pointer hover:shadow-md transition">
                                            <div class="flex justify-between mb-1"><span class="text-xs font-bold text-gray-400">#4920</span> <span class="text-xs text-danger font-bold">Crítica</span></div>
                                            <p class="text-sm font-medium text-gray-800">Corte de Fibra</p>
                                            <p class="text-xs text-gray-500 mt-1">Cliente: Tech SAC</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="font-bold text-primary mb-3 flex justify-between">En Proceso <span class="bg-blue-100 text-primary px-2 rounded-full text-xs flex items-center">1</span></h4>
                                    <div class="bg-white p-3 rounded shadow-sm border-l-4 border-warning cursor-pointer hover:shadow-md transition">
                                        <div class="flex justify-between mb-1"><span class="text-xs font-bold text-gray-400">#4918</span> <span class="text-xs text-warning font-bold">Media</span></div>
                                        <p class="text-sm font-medium text-gray-800">Revisión de Potencia</p>
                                        <p class="text-xs text-gray-500 mt-1">Técnico en camino</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="font-bold text-success mb-3 flex justify-between">Resueltos <span class="bg-green-100 text-success px-2 rounded-full text-xs flex items-center">8</span></h4>
                                    <div class="bg-white p-3 rounded shadow-sm border-l-4 border-success opacity-75">
                                        <div class="flex justify-between mb-1"><span class="text-xs font-bold text-gray-400">#4910</span> <span class="text-xs text-success font-bold">Ok</span></div>
                                        <p class="text-sm font-medium text-gray-800">Configuración Router</p>
                                        <p class="text-xs text-gray-500 mt-1">Hace 2 horas</p>
                                    </div>
                                </div>
                            </div>
                        </div >
            `;
                    break;

                case 'clientes':
                    html = `
            < div class="card-base p-8 text-center" >
                            <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search-location text-2xl text-primary"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">Buscador Técnico de Clientes</h2>
                            <p class="text-gray-500 mb-6">Ingresa el DNI o Código de Cliente para ver el estado de sus equipos.</p>
                            
                            <div class="max-w-xl mx-auto flex gap-2">
                                <input type="text" class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50" placeholder="Ej: 45882211 o C-1029">
                                <button class="btn-primary px-6">Buscar</button>
                            </div>
                        </div >
            `;
                    break;

                default:
                    // Plantilla genérica
                    html = `
            < div class="card-base p-12 text-center" >
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-tools text-4xl text-gray-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Sección: ${dom.pageTitle.textContent}</h2>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Módulo en construcción. Pronto podrás acceder a estas herramientas.</p>
                            <button class="btn-primary mx-auto px-6" onclick="loadPage('home')">
                                Volver al Home
                            </button>
                        </div >
            `;
            }

            dom.content.innerHTML = html;
        }

        // --- UTILIDADES ---
        function updateBreadcrumbs(page) {
            const crumbs = document.getElementById('breadcrumbs');
            let html = `< li > <button class="hover:text-primary" onclick="loadPage('home')"><i class="fas fa-home"></i></button></li > `;

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
                    html += `< li > <span class="mx-2 text-gray-300">/</span></li > <li>${item.title}</li>`;
                });
            }
            crumbs.innerHTML = html;
        }

        function loadNotifications(list) {
            const container = document.getElementById('notificationsList');
            container.innerHTML = '';
            list.forEach(n => {
                const item = document.createElement('div');
                item.className = `p - 4 border - b border - gray - 100 hover: bg - gray - 50 cursor - pointer flex gap - 3 ${ n.read ? 'opacity-60' : '' } `;

                let iconClass = 'text-primary bg-primary-light';
                let icon = 'info-circle';
                if (n.type === 'critical') { iconClass = 'text-danger bg-red-50'; icon = 'bomb'; }
                if (n.type === 'medium') { iconClass = 'text-warning bg-orange-50'; icon = 'exclamation-triangle'; }
                if (n.type === 'success') { iconClass = 'text-success bg-green-50'; icon = 'check-circle'; }

                item.innerHTML = `
            < div class="w-8 h-8 rounded-full ${iconClass} flex items-center justify-center flex-shrink-0" >
                <i class="fas fa-${icon} text-xs"></i>
                    </div >
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