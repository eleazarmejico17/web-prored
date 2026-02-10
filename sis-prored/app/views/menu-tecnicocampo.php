<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico de Campo | ProRed</title>

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

        /* Estilos específicos para móvil/campo */
        .status-dot {
            @apply w-3 h-3 rounded-full inline-block mr-2;
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
                    <div class="bg-primary text-white p-2.5 rounded-lg shadow-lg shadow-primary/30">
                        <i class="fas fa-tools text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">Pro<span
                            class="text-secondary">Red</span></h1>
                </div>
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Técnico de Campo</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold shadow-md">
                        <span id="userInitial">P</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 id="userName" class="font-semibold text-sm text-gray-800 truncate">Pedro Castillo</h3>
                        <p id="userRole" class="text-xs text-gray-500 truncate">Técnico de Fibra</p>
                    </div>
                </div>
                <button id="logoutBtn" onclick="alert('Finalizando turno...')"
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
                        <div
                            class="hidden md:flex items-center bg-green-50 text-success px-3 py-1 rounded-full border border-green-200 cursor-pointer hover:bg-green-100 transition">
                            <span class="w-2 h-2 rounded-full bg-success mr-2 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase">En Ruta</span>
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
                                    <h3 class="font-bold text-gray-800">Despacho</h3>
                                    <p class="text-xs text-gray-500" id="notificationStatus">1 visita agregada a tu ruta
                                    </p>
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
        // --- CONFIGURACIÓN DEL ROL: TÉCNICO DE CAMPO ---
        const userData = {
            name: "Pedro Castillo",
            role: "Técnico de Campo",
            initial: "P",
            // Menú estructurado según tus requerimientos
            menu: [
                {
                    title: "Home",
                    icon: "fas fa-home",
                    page: "home",
                    submenu: []
                },
                {
                    title: "Mis Visitas",
                    icon: "fas fa-map-marked-alt",
                    page: "visitas",
                    submenu: [
                        { title: "Agenda del Día", page: "agenda" },
                        { title: "Actualizar Estado", page: "actualizar-estado" },
                        { title: "Completar Reporte de Visita", page: "completar-reporte" }
                    ]
                },
                {
                    title: "Materiales",
                    icon: "fas fa-box-open",
                    page: "materiales",
                    submenu: [
                        { title: "Mi Inventario", page: "inventario" },
                        { title: "Reportar Materiales", page: "reportar-materiales" }
                    ]
                },
                {
                    title: "Historial",
                    icon: "fas fa-history",
                    page: "historial",
                    submenu: []
                },
                {
                    title: "Reportes",
                    icon: "fas fa-chart-line",
                    page: "reportes",
                    submenu: []
                },
                {
                    title: "Mi Perfil",
                    icon: "fas fa-user-cog",
                    page: "perfil",
                    submenu: []
                }
            ],
            notifications: [
                { id: 1, text: "Nueva instalación asignada: 14:00 hrs", time: "Hace 10 min", read: false, type: 'info' },
                { id: 2, text: "Stock bajo de conectores de fibra", time: "Hace 2 horas", read: false, type: 'warning' }
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

        // --- RENDERIZADO DE CONTENIDO (TÉCNICO DE CAMPO) ---
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
            let html = '';

            // Helper para Cards de Resumen
            const renderMetric = (title, val, icon, color, subText) => `
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            ${renderMetric('Visitas Hoy', '4', 'fas fa-clipboard-list', 'primary', '2 Completadas')}
                            ${renderMetric('Pendientes', '2', 'fas fa-clock', 'warning', 'Próxima a las 14:00')}
                            ${renderMetric('Materiales', 'OK', 'fas fa-box', 'success', 'Stock suficiente')}
                            ${renderMetric('Rendimiento', '98%', 'fas fa-tachometer-alt', 'secondary', 'Semana actual')}
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2 card-base p-6 border border-primary/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4 opacity-10">
                                    <i class="fas fa-map-marker-alt text-9xl text-primary"></i>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="bg-primary text-white text-xs px-2 py-1 rounded font-bold animate-pulse">EN CURSO</span>
                                        <span class="text-gray-500 text-sm">Ticket #4921</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-1">Instalación Fibra 100Mb</h3>
                                    <p class="text-lg text-gray-600 mb-4"><i class="fas fa-user mr-2"></i> Cliente: Familia Rodríguez</p>
                                    
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                                        <p class="text-sm text-gray-700 mb-1"><i class="fas fa-map-pin text-danger mr-2"></i> Av. Los Álamos 452, Urb. San Felipe</p>
                                        <p class="text-sm text-gray-500"><i class="fas fa-phone mr-2"></i> +51 998 887 776</p>
                                    </div>

                                    <div class="flex flex-wrap gap-3">
                                        <button class="btn-primary" onclick="loadPage('actualizar-estado')">
                                            <i class="fas fa-check-circle mr-2"></i> Llegué al Sitio
                                        </button>
                                        <button class="btn-secondary" onclick="window.open('https://maps.google.com', '_blank')">
                                            <i class="fas fa-directions mr-2"></i> Ver Mapa
                                        </button>
                                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                                            <i class="fas fa-phone mr-2"></i> Llamar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-base p-6">
                                <h3 class="font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
                                <div class="grid grid-cols-1 gap-3">
                                    <button onclick="loadPage('reportar-materiales')" class="p-3 bg-gray-50 hover:bg-orange-50 border border-gray-200 hover:border-orange-200 rounded-lg flex items-center transition group">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-secondary shadow-sm group-hover:scale-110 transition">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="text-sm font-bold text-gray-700 group-hover:text-secondary">Pedir Material</p>
                                            <p class="text-xs text-gray-500">Router, Cable, Conectores</p>
                                        </div>
                                    </button>
                                    
                                    <button onclick="loadPage('completar-reporte')" class="p-3 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg flex items-center transition group">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary shadow-sm group-hover:scale-110 transition">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="text-sm font-bold text-gray-700 group-hover:text-primary">Cerrar Orden</p>
                                            <p class="text-xs text-gray-500">Finalizar trabajo actual</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    break;

                case 'visitas':
                case 'agenda':
                    html = `
                        <div class="card-base p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-gray-800 text-lg">Agenda del Día</h3>
                                <span class="bg-primary-light text-primary text-xs font-bold px-3 py-1 rounded-full">4 Asignadas</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="border border-gray-200 rounded-lg p-4 opacity-75 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold text-success border border-success px-2 py-0.5 rounded mb-2 inline-block">COMPLETADO</span>
                                            <h4 class="font-bold text-gray-700">Mantenimiento Correctivo</h4>
                                            <p class="text-sm text-gray-500">09:00 AM - Cliente: Bodega "El Chino"</p>
                                        </div>
                                        <button class="text-gray-400 hover:text-primary"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>

                                <div class="border-l-4 border-primary bg-white rounded-r-lg shadow-md p-4 transform scale-105">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold text-white bg-primary px-2 py-0.5 rounded mb-2 inline-block animate-pulse">EN CURSO</span>
                                            <h4 class="font-bold text-gray-800 text-lg">Instalación Fibra 100Mb</h4>
                                            <p class="text-sm text-gray-600 font-medium">11:30 AM - Cliente: Familia Rodríguez</p>
                                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-danger"></i> Av. Los Álamos 452</p>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <button class="btn-primary text-xs py-1" onclick="loadPage('actualizar-estado')">Actualizar</button>
                                            <button class="btn-secondary text-xs py-1" onclick="loadPage('completar-reporte')">Finalizar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded mb-2 inline-block">PENDIENTE</span>
                                            <h4 class="font-bold text-gray-700">Cambio de Router</h4>
                                            <p class="text-sm text-gray-500">14:00 PM - Cliente: CyberCafe Zone</p>
                                        </div>
                                        <button class="text-primary hover:text-primary-dark font-medium text-xs">Iniciar Ruta</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;

                case 'actualizar-estado':
                    html = `
                         <div class="card-base p-6 max-w-2xl mx-auto">
                            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                                <i class="fas fa-sync-alt text-secondary mr-2"></i> Actualizar Estado de Visita
                            </h3>
                            <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                                <p class="text-sm text-gray-700"><strong>Orden #4921:</strong> Instalación Fibra</p>
                                <p class="text-xs text-gray-500">Cliente: Familia Rodríguez</p>
                            </div>

                            <form onsubmit="event.preventDefault(); alert('Estado actualizado'); loadPage('agenda');">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nuevo Estado</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" class="peer sr-only">
                                                <div class="p-3 border rounded-lg text-center peer-checked:bg-primary peer-checked:text-white hover:bg-gray-50 transition">
                                                    <i class="fas fa-car mb-1"></i><br>En Ruta
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" class="peer sr-only" checked>
                                                <div class="p-3 border rounded-lg text-center peer-checked:bg-primary peer-checked:text-white hover:bg-gray-50 transition">
                                                    <i class="fas fa-map-pin mb-1"></i><br>En Sitio
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" class="peer sr-only">
                                                <div class="p-3 border rounded-lg text-center peer-checked:bg-primary peer-checked:text-white hover:bg-gray-50 transition">
                                                    <i class="fas fa-tools mb-1"></i><br>Trabajando
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" class="peer sr-only">
                                                <div class="p-3 border rounded-lg text-center peer-checked:bg-success peer-checked:text-white hover:bg-gray-50 transition">
                                                    <i class="fas fa-check mb-1"></i><br>Finalizado
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas Rápidas (Opcional)</label>
                                        <textarea class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:border-primary" rows="2" placeholder="Ej: Cliente demoró en abrir..."></textarea>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <button class="w-full btn-primary py-3">Guardar Cambio</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    `;
                    break;

                case 'reportar-materiales':
                case 'materiales':
                    html = `
                         <div class="card-base p-6">
                            <h3 class="font-bold text-gray-800 text-lg mb-6">Mis Materiales</h3>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center p-3 border-b border-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center mr-3 text-gray-500">
                                            <i class="fas fa-wifi"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Router ONU Huawei</p>
                                            <p class="text-xs text-gray-500">Stock Crítico: 2</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xl font-bold text-gray-800">3</span>
                                        <span class="text-xs block text-warning font-bold">Bajo</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center p-3 border-b border-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center mr-3 text-gray-500">
                                            <i class="fas fa-network-wired"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Cable Drop (mts)</p>
                                            <p class="text-xs text-gray-500">Bobina #402</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xl font-bold text-gray-800">150</span>
                                        <span class="text-xs block text-success font-bold">OK</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-100 text-center">
                                <h4 class="font-bold text-secondary mb-2">Solicitar Reposición</h4>
                                <p class="text-xs text-gray-600 mb-4">Envía una solicitud al almacén para reponer stock.</p>
                                <button class="btn-secondary w-full text-sm">Crear Solicitud de Material</button>
                            </div>
                        </div>
                    `;
                    break;

                case 'completar-reporte':
                    html = `
                         <div class="card-base p-6 max-w-2xl mx-auto">
                            <div class="flex items-center gap-3 mb-6 border-b pb-4">
                                <div class="w-10 h-10 bg-success rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">Reporte de Cierre</h3>
                                    <p class="text-xs text-gray-500">Orden #4921</p>
                                </div>
                            </div>

                            <form onsubmit="event.preventDefault(); alert('Orden cerrada exitosamente'); loadPage('home');">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Materiales Usados</label>
                                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded mb-2">
                                            <span class="text-sm">Router ONU</span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="w-6 h-6 bg-white border rounded text-gray-600">-</button>
                                                <span class="font-bold">1</span>
                                                <button type="button" class="w-6 h-6 bg-white border rounded text-gray-600">+</button>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                            <span class="text-sm">Cable Drop (mts)</span>
                                            <input type="number" class="w-20 p-1 border rounded text-center text-sm" value="45">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Evidencia (Fotos)</label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 cursor-pointer">
                                            <i class="fas fa-camera text-2xl text-gray-400 mb-2"></i>
                                            <p class="text-xs text-gray-500">Toca para subir foto de la instalación</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Observaciones</label>
                                        <textarea class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3" placeholder="Potencia final: -19dBm..."></textarea>
                                    </div>

                                    <button class="w-full btn-primary py-3 font-bold mt-4">
                                        <i class="fas fa-check mr-2"></i> Finalizar Visita
                                    </button>
                                </div>
                            </form>
                        </div>
                    `;
                    break;

                default:
                    // Plantilla genérica
                    html = `
                        <div class="card-base p-12 text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-hard-hat text-4xl text-gray-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Sección: ${dom.pageTitle.textContent}</h2>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Funcionalidad en desarrollo para la App de Técnicos.</p>
                            <button class="btn-primary mx-auto px-6" onclick="loadPage('home')">
                                Volver al Home
                            </button>
                        </div>
                    `;
            }

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
                if (n.type === 'warning') { iconClass = 'text-warning bg-orange-50'; icon = 'exclamation-triangle'; }
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