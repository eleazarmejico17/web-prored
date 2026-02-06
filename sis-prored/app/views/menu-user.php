<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Cliente | ProRed</title>

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
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Portal de Clientes</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-secondary to-orange-600 flex items-center justify-center text-white font-bold shadow-md">
                        <span id="userInitial">J</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 id="userName" class="font-semibold text-sm text-gray-800 truncate">Juan Pérez</h3>
                        <p id="userRole" class="text-xs text-gray-500 truncate">Cliente Residencial</p>
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
                        <button
                            class="hidden md:flex items-center text-sm font-medium text-primary hover:text-primary-dark transition-colors bg-primary-light px-3 py-1.5 rounded-lg">
                            <i class="fas fa-headset mr-2"></i> Soporte
                        </button>

                        <div class="relative">
                            <button id="notificationsBtn"
                                class="relative p-2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notificationCount"
                                    class="absolute top-1 right-1 bg-danger text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">1</span>
                            </button>
                            <div id="notificationsPanel"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                                <div class="p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-gray-800">Tus Avisos</h3>
                                    <p class="text-xs text-gray-500" id="notificationStatus">1 aviso importante</p>
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
        // --- CONFIGURACIÓN DEL ROL: CLIENTE ---
        const userData = {
            name: "Juan Pérez",
            role: "Cliente Residencial",
            initial: "J",
            // Menú actualizado según solicitud
            menu: [
                {
                    title: "Home",
                    icon: "fas fa-home",
                    page: "home",
                    submenu: []
                },
                {
                    title: "Estado de Cuenta",
                    icon: "fas fa-file-invoice-dollar",
                    page: "estado-cuenta",
                    submenu: []
                },
                {
                    title: "Pagos",
                    icon: "fas fa-credit-card",
                    page: "pagos",
                    submenu: []
                },
                {
                    title: "Consumo de Internet",
                    icon: "fas fa-chart-line",
                    page: "consumo",
                    submenu: []
                },
                {
                    title: "Tickets de Soporte",
                    icon: "fas fa-ticket-alt",
                    page: "tickets",
                    submenu: []
                },
                {
                    title: "Mi Perfil",
                    icon: "fas fa-user-circle",
                    page: "perfil",
                    submenu: []
                },
                {
                    title: "Ayuda",
                    icon: "fas fa-question-circle",
                    page: "ayuda",
                    submenu: []
                },
                {
                    title: "Cerrar Sesión",
                    icon: "fas fa-sign-out-alt",
                    page: "logout",
                    submenu: []
                }
            ],
            notifications: [
                { id: 1, text: "Tu factura de Febrero ya está disponible", time: "Hace 2 horas", read: false, type: 'info' },
                { id: 2, text: "Mantenimiento programado: 20 Feb, 02:00 AM", time: "Ayer", read: true, type: 'warning' }
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

                // Color rojizo para Cerrar Sesión
                const isLogout = item.page === 'logout';
                const hoverClass = isLogout ? 'hover:bg-red-50 hover:text-red-500' : 'hover:bg-gray-50 hover:text-primary';

                const activeClasses = 'bg-primary-light text-primary font-semibold border-r-4 border-primary';
                const inactiveClasses = `text-gray-600 ${hoverClass} border-r-4 border-transparent`;

                const div = document.createElement('div');
                div.className = 'mb-1';

                // Lógica de click
                let clickAction = "";
                if (hasSubmenu) {
                    clickAction = `toggleSubmenu('${item.page}')`;
                } else if (isLogout) {
                    clickAction = "alert('Cerrando sesión...'); window.location.reload();";
                } else {
                    clickAction = `loadPage('${item.page}'); closeSidebar();`;
                }

                div.innerHTML = `
                    <button 
                        class="w-full flex items-center justify-between p-3 rounded-l-lg transition-all duration-200 group ${isActive ? activeClasses : inactiveClasses}" 
                        onclick="${clickAction}"
                    >
                        <div class="flex items-center">
                            <div class="w-6 flex justify-center">
                                <i class="${item.icon} text-lg ${isActive ? 'text-primary' : (isLogout ? 'text-red-400' : 'text-gray-400 group-hover:text-primary')} transition-colors"></i>
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

        // --- RENDERIZADO DE CONTENIDO (Páginas Mock) ---
        function loadPage(page) {
            currentPage = page;
            updateBreadcrumbs(page);
            loadMenu(userData.menu);

            // Actualizar título
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


            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-${color} rounded-full opacity-10 group-hover:scale-125 transition-transform"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">${title}</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">${val}</h3>
                </div>
                <div class="p-3 rounded-lg bg-${color}-light text-${color} bg-opacity-50">
                    <i class="${icon} text-xl"></i>
                </div>
            </div>
            ${
            footerText ? `
                    <div class="mt-4 flex items-center text-xs text-gray-500 relative z-10">
                        ${footerText}
                    </div>` : ''
        }
        </div >
            `;

            switch (page) {
                case 'home':
                    html = `
            < div class="mb-8" >
                            <h2 class="text-2xl font-bold text-gray-800">Hola, ${userData.name.split(' ')[0]}</h2>
                            <p class="text-gray-500">Bienvenido a tu panel de cliente de ProRed.</p>
                        </div >
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            ${renderMetric('Saldo Pendiente', 'S/ 89.90', 'fas fa-wallet', 'primary', 'Vence el 15 Feb')}
                            ${renderMetric('Plan Actual', '100 Mbps', 'fas fa-tachometer-alt', 'secondary', 'Fibra Óptica')}
                            ${renderMetric('Tickets Abiertos', '0', 'fas fa-ticket-alt', 'success', 'Todo en orden')}
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div class="card-base p-6 bg-gradient-to-r from-primary to-primary-dark text-white">
                                <h3 class="font-bold mb-2">Pagar Servicio</h3>
                                <p class="text-sm opacity-80 mb-4">Realiza tu pago de forma segura y evita cortes.</p>
                                <button onclick="loadPage('pagos')" class="bg-white text-primary px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-100 transition shadow-lg w-full md:w-auto">
                                    <i class="fas fa-credit-card mr-2"></i> Ir a Pagos
                                </button>
                             </div>
                             <div class="card-base p-6 flex flex-col justify-center">
                                <h3 class="font-bold text-gray-800 mb-2">Estado de la Red</h3>
                                <div class="flex items-center text-success font-bold text-lg">
                                    <i class="fas fa-check-circle mr-2 text-2xl"></i> Operativo
                                    <span class="text-gray-400 text-sm font-normal ml-auto">Último chequeo: 5 min</span>
                                </div>
                             </div>
                        </div>
        `;
                    break;

                case 'estado-cuenta':
                    html = `
            < div class="card-base p-6 max-w-4xl mx-auto" >
                            <div class="flex justify-between items-center mb-6 border-b pb-4">
                                <h3 class="text-xl font-bold text-gray-800">Estado de Cuenta</h3>
                                <button class="text-primary hover:text-primary-dark font-medium text-sm"><i class="fas fa-download mr-1"></i> Descargar PDF</button>
                            </div>
                            
                            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-8 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-orange-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-orange-700">
                                            Tienes un recibo pendiente de pago por <span class="font-bold">S/ 89.90</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Periodo</span>
                                        <span class="font-medium text-gray-800">Enero 2026</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Vencimiento</span>
                                        <span class="font-medium text-gray-800">15 Febrero 2026</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Subtotal</span>
                                        <span class="font-medium text-gray-800">S/ 76.19</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">IGV (18%)</span>
                                        <span class="font-medium text-gray-800">S/ 13.71</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-gray-100">
                                        <span class="font-bold text-gray-800">Total</span>
                                        <span class="font-bold text-primary text-lg">S/ 89.90</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="loadPage('pagos')" class="w-full btn-primary py-3">Pagar Ahora</button>
                        </div >
            `;
                    break;

                case 'pagos':
                    html = `
            < div class="card-base p-8 text-center max-w-2xl mx-auto" >
                            <div class="w-20 h-20 bg-secondary-light rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-credit-card text-3xl text-secondary"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Zona de Pagos</h2>
                            <p class="text-gray-500 mb-8">Selecciona tu método de pago preferido para cancelar tu deuda de <span class="font-bold text-gray-800">S/ 89.90</span>.</p>
                            
                            <div class="grid grid-cols-1 gap-4 mb-8 text-left">
                                <button class="p-4 border rounded-xl flex items-center hover:border-primary hover:bg-primary-light transition-colors group">
                                    <i class="fab fa-cc-visa text-2xl text-blue-600 mr-4"></i>
                                    <div>
                                        <p class="font-bold text-gray-800 group-hover:text-primary">Tarjeta de Crédito/Débito</p>
                                        <p class="text-xs text-gray-500">Visa, Mastercard, Amex</p>
                                    </div>
                                </button>
                                <button class="p-4 border rounded-xl flex items-center hover:border-primary hover:bg-primary-light transition-colors group">
                                    <i class="fas fa-qrcode text-2xl text-gray-600 mr-4"></i>
                                    <div>
                                        <p class="font-bold text-gray-800 group-hover:text-primary">Yape / Plin</p>
                                        <p class="text-xs text-gray-500">Escanea el código QR</p>
                                    </div>
                                </button>
                            </div>
                        </div >
            `;
                    break;

                case 'consumo':
                    html = `
            < div class="card-base p-6" >
                            <div class="flex justify-between items-end mb-6">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Tu Consumo de Datos</h3>
                                    <p class="text-sm text-gray-500">Ciclo: 01 Feb - 28 Feb</p>
                                </div>
                                <span class="bg-success text-white text-xs px-2 py-1 rounded font-bold">Ilimitado</span>
                            </div>
                            
                            <div class="relative pt-1 mb-8">
                                <div class="flex mb-2 items-center justify-between">
                                    <div><span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-primary bg-primary-light">Descarga Total</span></div>
                                    <div class="text-right"><span class="text-xs font-semibold inline-block text-primary">650 GB</span></div>
                                </div>
                                <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-gray-200">
                                    <div style="width:65%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary"></div>
                                </div>
                                <p class="text-xs text-center text-gray-500">Aunque tu plan es ilimitado, monitoreamos el uso para garantizar la calidad del servicio.</p>
                            </div>

                            <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide">Métricas en tiempo real</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-100">
                                    <i class="fas fa-tachometer-alt text-primary mb-2 text-2xl"></i>
                                    <p class="text-gray-500 text-xs uppercase font-bold">Velocidad</p>
                                    <p class="text-2xl font-bold text-gray-800">100 Mbps</p>
                                </div>
                                <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-100">
                                    <i class="fas fa-laptop-house text-secondary mb-2 text-2xl"></i>
                                    <p class="text-gray-500 text-xs uppercase font-bold">Dispositivos</p>
                                    <p class="text-2xl font-bold text-gray-800">5 Activos</p>
                                </div>
                            </div>
                        </div >
            `;
                    break;

                case 'tickets':
                    html = `
            < div class="card-base p-6" >
                             <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-gray-800 text-lg">Mis Tickets de Soporte</h3>
                                <button class="btn-primary text-xs px-3 py-2"><i class="fas fa-plus mr-2"></i> Nuevo Ticket</button>
                             </div>
                             
                             <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <div class="inline-block p-4 rounded-full bg-white shadow-sm mb-3">
                                    <i class="fas fa-clipboard-check text-4xl text-success"></i>
                                </div>
                                <h4 class="text-gray-800 font-bold">¡Todo limpio!</h4>
                                <p class="text-gray-500 text-sm mt-1">No tienes tickets de soporte pendientes en este momento.</p>
                             </div>
                        </div >
            `;
                    break;

                case 'perfil':
                    html = `
            < div class="card-base p-8 max-w-3xl mx-auto" >
                            <div class="flex items-center gap-6 mb-8">
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-4xl text-gray-400 font-bold border-4 border-white shadow-lg">
                                    ${userData.initial}
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">${userData.name}</h2>
                                    <p class="text-primary font-medium">${userData.role}</p>
                                    <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> Lima, Perú</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Correo Electrónico</p>
                                    <p class="text-gray-800 font-medium">juan.perez@email.com</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Teléfono</p>
                                    <p class="text-gray-800 font-medium">+51 987 654 321</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Código de Cliente</p>
                                    <p class="text-gray-800 font-medium">CL-882910</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Dirección de Instalación</p>
                                    <p class="text-gray-800 font-medium">Av. Siempre Viva 123, Dpto 402</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button class="text-primary font-medium hover:underline text-sm">Editar Información</button>
                            </div>
                        </div >
            `;
                    break;

                default:
                    // Plantilla genérica para Ayuda u otras páginas futuras
                    html = `
            < div class="card-base p-12 text-center" >
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-hard-hat text-4xl text-gray-400"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-3">${dom.pageTitle.textContent}</h2>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Estamos trabajando en esta sección para brindarte una mejor experiencia. ¡Vuelve pronto!</p>
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
                if (n.type === 'warning') { iconClass = 'text-warning bg-orange-50'; icon = 'exclamation-circle'; }

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