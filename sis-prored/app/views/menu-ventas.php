<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Ventas | ProRed</title>

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

        /* Componentes UI Estandarizados */
        .btn-primary {
            @apply bg-primary text-white hover:bg-primary-dark shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center;
        }

        .btn-secondary {
            @apply bg-secondary text-white hover:bg-orange-600 shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center;
        }

        .card-base {
            @apply bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300;
        }

        .input-prored {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all;
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
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Módulo de Ventas</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white font-bold shadow-md">
                        <span id="userInitial">M</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 id="userName" class="font-semibold text-sm text-gray-800 truncate">María González</h3>
                        <p id="userRole" class="text-xs text-gray-500 truncate">Ejecutiva de Ventas</p>
                    </div>
                </div>
                <button id="logoutBtn" onclick="alert('Cerrando sesión...')"
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
                            <input type="text" placeholder="Buscar cliente, contrato..."
                                class="pl-9 pr-4 py-1.5 rounded-full border border-gray-200 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>

                        <div class="relative">
                            <button id="notificationsBtn"
                                class="relative p-2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notificationCount"
                                    class="absolute top-1 right-1 bg-secondary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">2</span>
                            </button>
                            <div id="notificationsPanel"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                                <div class="p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-gray-800">Novedades</h3>
                                    <p class="text-xs text-gray-500" id="notificationStatus">2 ventas nuevas hoy</p>
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
        // --- CONFIGURACIÓN DEL ROL: VENTAS ---
        const userData = {
            name: "María González",
            role: "Ejecutiva de Ventas",
            initial: "M",
            // Menú actualizado según solicitud del usuario
            menu: [
                {
                    title: "Home",
                    icon: "fas fa-home",
                    page: "home",
                    submenu: []
                },
                {
                    title: "Clientes",
                    icon: "fas fa-users",
                    page: "clientes",
                    submenu: [
                        { title: "Directorio de Clientes", page: "lista-clientes" },
                        { title: "Nuevo Cliente", page: "nuevo-cliente" }
                    ]
                },
                {
                    title: "Servicios",
                    icon: "fas fa-network-wired",
                    page: "servicios",
                    submenu: [
                        { title: "Catálogo de Planes", page: "catalogo" },
                        { title: "Verificar Cobertura", page: "cobertura" }
                    ]
                },
                {
                    title: "Pagos",
                    icon: "fas fa-money-bill-wave",
                    page: "pagos",
                    submenu: []
                },
                {
                    title: "Cobranza y Morosidad",
                    icon: "fas fa-user-clock",
                    page: "cobranza",
                    submenu: []
                },
                {
                    title: "Cargos Adicionales",
                    icon: "fas fa-file-invoice-dollar",
                    page: "cargos",
                    submenu: []
                },
                {
                    title: "Cambios de Titularidad",
                    icon: "fas fa-exchange-alt",
                    page: "titularidad",
                    submenu: []
                },
                {
                    title: "Comprobantes",
                    icon: "fas fa-receipt",
                    page: "comprobantes",
                    submenu: []
                },
                {
                    title: "Reportes",
                    icon: "fas fa-chart-pie",
                    page: "reportes",
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
                { id: 1, text: "Venta aprobada: Cliente #9021", time: "Hace 10 minutos", read: false, type: 'success' },
                { id: 2, text: "Meta mensual al 85%", time: "Hace 2 horas", read: true, type: 'info' }
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

        // --- RENDERIZADO DE CONTENIDO (VENTAS) ---
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
            ${footerText ? `
                    <div class="mt-4 flex items-center text-xs text-gray-500">
                        ${footerText}
                    </div>` : ''}
        </div>
        `;

            switch (page) {
                case 'home':
                    html = `
            < div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" >
                ${ renderMetric('Ventas Hoy', 'S/ 1,500.00', 'fas fa-chart-line', 'success', '<span class="text-success font-bold"><i class="fas fa-arrow-up mr-1"></i> 12% vs Ayer</span>') }
                            ${ renderMetric('Morosidad', '15 Clientes', 'fas fa-user-clock', 'danger', '<span class="text-gray-500">Gestión pendiente</span>') }
                            ${ renderMetric('Instalaciones', '5 Pendientes', 'fas fa-tools', 'primary', '<span class="text-primary font-bold">Programadas para hoy</span>') }
                        </div >

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 card-base overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Últimos Contratos</h3>
                        <button onclick="loadPage('nuevo-cliente')" class="btn-primary text-xs py-1 px-3"><i class="fas fa-plus mr-1"></i> Nuevo</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-medium">
                                <tr>
                                    <th class="px-6 py-3">Cliente</th>
                                    <th class="px-6 py-3">Plan</th>
                                    <th class="px-6 py-3">Estado</th>
                                    <th class="px-6 py-3 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">Jorge Ramírez</td>
                                    <td class="px-6 py-4 text-gray-600">Fibra 100Mb</td>
                                    <td class="px-6 py-4"><span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">Activo</span></td>
                                    <td class="px-6 py-4 text-right"><button class="text-primary hover:text-primary-dark"><i class="fas fa-eye"></i></button></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">Elena Torres</td>
                                    <td class="px-6 py-4 text-gray-600">Fibra 50Mb</td>
                                    <td class="px-6 py-4"><span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-bold">Pendiente</span></td>
                                    <td class="px-6 py-4 text-right"><button class="text-primary hover:text-primary-dark"><i class="fas fa-eye"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-base p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Accesos Rápidos</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="loadPage('pagos')" class="p-4 bg-gray-50 rounded-lg hover:bg-primary-light hover:text-primary transition-colors text-center border border-gray-100">
                            <i class="fas fa-cash-register text-2xl mb-2 text-gray-400"></i>
                            <p class="text-xs font-bold">Caja Rápida</p>
                        </button>
                        <button onclick="loadPage('cobertura')" class="p-4 bg-gray-50 rounded-lg hover:bg-primary-light hover:text-primary transition-colors text-center border border-gray-100">
                            <i class="fas fa-map-marker-alt text-2xl mb-2 text-gray-400"></i>
                            <p class="text-xs font-bold">Cobertura</p>
                        </button>
                        <button onclick="loadPage('cargos')" class="p-4 bg-gray-50 rounded-lg hover:bg-primary-light hover:text-primary transition-colors text-center border border-gray-100">
                            <i class="fas fa-file-invoice text-2xl mb-2 text-gray-400"></i>
                            <p class="text-xs font-bold">Cargo Extra</p>
                        </button>
                        <button onclick="loadPage('titularidad')" class="p-4 bg-gray-50 rounded-lg hover:bg-primary-light hover:text-primary transition-colors text-center border border-gray-100">
                            <i class="fas fa-users-cog text-2xl mb-2 text-gray-400"></i>
                            <p class="text-xs font-bold">Titularidad</p>
                        </button>
                    </div>
                </div>
            </div>
        `;
                    break;

                case 'pagos':
                    html = `
            < div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto" >
                            <div class="card-base p-6">
                                <h3 class="font-bold text-lg text-gray-800 mb-6 border-l-4 border-primary pl-3">Registrar Pago de Cliente</h3>
                                <form onsubmit="event.preventDefault(); alert('Pago procesado (Demo)');">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Cliente</label>
                                            <div class="flex gap-2">
                                                <input type="text" class="input-prored" placeholder="DNI o Apellido">
                                                <button type="button" class="btn-secondary px-3"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Concepto</label>
                                            <select class="input-prored bg-white">
                                                <option>Mensualidad</option>
                                                <option>Instalación</option>
                                                <option>Reconexión</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Monto (S/)</label>
                                                <input type="number" class="input-prored font-bold text-gray-800" value="0.00">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Método</label>
                                                <select class="input-prored bg-white">
                                                    <option>Efectivo</option>
                                                    <option>Yape/Plin</option>
                                                    <option>POS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="pt-4">
                                            <button type="submit" class="w-full btn-primary py-3 text-lg">
                                                <i class="fas fa-check-circle mr-2"></i> Procesar Pago
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                             <div class="card-base p-6 bg-gray-50 border-dashed border-2 border-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
                                    <h4 class="text-gray-500 font-medium">Historial Reciente</h4>
                                    <p class="text-xs text-gray-400 mt-2">Aquí aparecerán los últimos pagos procesados en tu sesión.</p>
                                </div>
                            </div>
                        </div >
            `;
                    break;

                case 'cobranza':
                    html = `
            < div class="card-base p-6" >
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-user-clock text-danger"></i> Gestión de Morosidad
                            </h3>
                            <div class="bg-red-50 border-l-4 border-danger p-4 mb-6">
                                <p class="text-sm text-red-700">Tienes <strong>3 clientes</strong> con más de 2 meses de deuda pendientes de contacto.</p>
                            </div>
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-100 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-2">Cliente</th>
                                        <th class="px-4 py-2">Deuda</th>
                                        <th class="px-4 py-2">Meses</th>
                                        <th class="px-4 py-2">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="px-4 py-3">Juan Quispe</td>
                                        <td class="px-4 py-3 font-bold text-danger">S/ 140.00</td>
                                        <td class="px-4 py-3">2</td>
                                        <td class="px-4 py-3"><button class="text-primary hover:underline">Contactar</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div >
            `;
                    break;

                default:
                    // Plantilla genérica para las nuevas páginas
                    html = `
            < div class="card-base p-12 text-center" >
                            <div class="w-24 h-24 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                                <i class="fas fa-tools text-4xl text-primary"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Módulo: ${dom.pageTitle.textContent}</h2>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Esta funcionalidad está en desarrollo. Pronto podrás gestionar ${dom.pageTitle.textContent.toLowerCase()} desde aquí.</p>
                            <button class="btn-primary mx-auto px-6" onclick="loadPage('home')">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al Home
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
                if (n.type === 'danger') { iconClass = 'text-danger bg-red-50'; icon = 'exclamation-triangle'; }
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
            // Sidebar
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

            // Notificaciones
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