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
                        primary: '#005FA2',
                        'primary-dark': '#004a80',
                        'primary-light': '#E6F2FA',
                        secondary: '#E58E21',
                        'secondary-light': '#FEF5E7',
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

        .btn-primary {
            @apply bg-primary text-white hover:bg-primary-dark shadow-md hover:shadow-lg transition-all transform active:scale-95 px-4 py-2 rounded-lg font-medium flex items-center justify-center;
        }

        .card-base {
            @apply bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300;
        }
    </style>
</head>

<body class="bg-background font-sans text-gray-700 antialiased overflow-hidden">
    <?php
    // --- CONFIGURACIÓN PHP ---
    $pagina = $_GET['pagina'] ?? 'home';
    $usuario_nombre = "Juan Pérez";
    $usuario_rol = "Cliente Residencial";
    $usuario_inicial = "J";

    // Definición del menú para cliente
    $menu = [
        'home' => [
            'icon' => 'fa-home',
            'texto' => 'Home',
            'submenu' => []
        ],
        'estado-cuenta' => [
            'icon' => 'fa-file-invoice-dollar',
            'texto' => 'Estado de Cuenta',
            'submenu' => []
        ],
        'pagos' => [
            'icon' => 'fa-credit-card',
            'texto' => 'Pagos',
            'submenu' => []
        ],
        'consumo' => [
            'icon' => 'fa-chart-line',
            'texto' => 'Consumo de Internet',
            'submenu' => []
        ],
        'tickets' => [
            'icon' => 'fa-ticket-alt',
            'texto' => 'Tickets de Soporte',
            'submenu' => []
        ],
        'perfil' => [
            'icon' => 'fa-user-circle',
            'texto' => 'Mi Perfil',
            'submenu' => []
        ],
        'ayuda' => [
            'icon' => 'fa-question-circle',
            'texto' => 'Ayuda',
            'submenu' => []
        ]
    ];

    // Función para determinar clase activa del menú
    function activo($key, $paginaActual)
    {
        return $key === $paginaActual ? 'bg-primary-light text-primary font-semibold border-r-4 border-primary'
            : 'text-gray-600 hover:bg-gray-50 hover:text-primary border-r-4 border-transparent';
    }

    // Determinar la ruta del dashboard
    $rutaDashboard = "includes/user/{$pagina}.php";
    if (!file_exists($rutaDashboard)) {
        $rutaDashboard = "includes/construccion.php";
    }

    // Título e icono de la página actual
    $titulo = $menu[$pagina]['texto'] ?? 'Home';
    $icono = $menu[$pagina]['icon'] ?? 'fa-home';
    ?>

    <div class="flex h-screen">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity"
            onclick="closeSidebar()"></div>

        <aside id="sidebar"
            class="bg-white shadow-xl flex flex-col w-64 fixed md:relative h-full z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex flex-col items-center justify-center">
                <div class="flex items-center gap-3">
                    <div class="bg-primary text-white p-2.5 rounded-lg shadow-lg shadow-primary/30">
                        <i class="fas fa-wifi text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">Pro<span
                            class="text-secondary">Red</span></h1>
                </div>
                <p class="text-xs text-gray-400 mt-2 font-medium tracking-wide uppercase">Portal de Clientes</p>
            </div>

            <nav id="menu" class="flex-grow p-4 overflow-y-auto custom-scrollbar space-y-1">
                <?php foreach ($menu as $key => $item): ?>
                    <div class="mb-1">
                        <a href="?pagina=<?= $key ?>"
                            class="w-full flex items-center p-3 rounded-l-lg transition-all duration-200 group <?= activo($key, $pagina) ?>"
                            onclick="closeSidebar()">
                            <div class="flex items-center">
                                <div class="w-6 flex justify-center">
                                    <i
                                        class="fas <?= $item['icon'] ?> text-lg <?= $key === $pagina ? 'text-primary' : 'text-gray-400 group-hover:text-primary' ?> transition-colors"></i>
                                </div>
                                <span class="ml-3 text-sm"><?= $item['texto'] ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

                <!-- Botón de Cerrar Sesión separado -->
                <div class="mt-8 pt-4 border-t border-gray-100">
                    <button onclick="alert('Cerrando sesión...'); window.location.href='logout.php';"
                        class="w-full flex items-center p-3 rounded-l-lg transition-all duration-200 group text-gray-600 hover:bg-red-50 hover:text-red-500">
                        <div class="flex items-center">
                            <div class="w-6 flex justify-center">
                                <i
                                    class="fas fa-sign-out-alt text-lg text-gray-400 group-hover:text-red-500 transition-colors"></i>
                            </div>
                            <span class="ml-3 text-sm">Cerrar Sesión</span>
                        </div>
                    </button>
                </div>
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-secondary to-orange-600 flex items-center justify-center text-white font-bold shadow-md">
                        <span><?= $usuario_inicial ?></span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <h3 class="font-semibold text-sm text-gray-800 truncate"><?= $usuario_nombre ?></h3>
                        <p class="text-xs text-gray-500 truncate"><?= $usuario_rol ?></p>
                    </div>
                </div>
                <button onclick="alert('Cerrando sesión...'); window.location.href='logout.php';"
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
                        <h2 id="pageTitle" class="text-xl font-bold text-gray-800">
                            <i class="fas <?= $icono ?> mr-2 text-primary"></i><?= $titulo ?>
                        </h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="?pagina=ayuda"
                            class="hidden md:flex items-center text-sm font-medium text-primary hover:text-primary-dark transition-colors bg-primary-light px-3 py-1.5 rounded-lg">
                            <i class="fas fa-headset mr-2"></i> Soporte
                        </a>

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
                                    <p class="text-xs text-gray-500">1 aviso importante</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                    <div
                                        class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer flex gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-primary-light text-primary flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-info-circle text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-semibold">Tu factura de Febrero ya está
                                                disponible</p>
                                            <p class="text-xs text-gray-400 mt-1">Hace 2 horas</p>
                                        </div>
                                    </div>
                                    <div
                                        class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer flex gap-3 opacity-60">
                                        <div
                                            class="w-8 h-8 rounded-full bg-orange-50 text-warning flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800">Mantenimiento programado: 20 Feb, 02:00 AM
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Ayer</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol id="breadcrumbs" class="flex items-center space-x-2 text-xs text-gray-500">
                            <li><a href="?pagina=home" class="hover:text-primary"><i class="fas fa-home"></i></a></li>
                            <?php if ($pagina !== 'home'): ?>
                                <li><span class="mx-2 text-gray-300">/</span></li>
                                <li><?= $titulo ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
            </header>

            <main id="mainContainer" class="flex-grow p-4 md:p-6 overflow-y-auto custom-scrollbar bg-background">
                <div id="dashboardContent" class="max-w-7xl mx-auto page-enter">
                    <?php
                    if (file_exists($rutaDashboard)) {
                        include $rutaDashboard;
                    } else {
                        echo '<div class="card-base p-12 text-center">';
                        echo '<div class="w-24 h-24 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6">';
                        echo '<i class="fas fa-exclamation-triangle text-4xl text-primary"></i>';
                        echo '</div>';
                        echo '<h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard no encontrado</h2>';
                        echo '<p class="text-gray-500 mb-8 max-w-md mx-auto">El archivo <code class="bg-gray-100 px-2 py-1 rounded">' . htmlspecialchars($rutaDashboard) . '</code> no existe.</p>';
                        echo '<a href="?pagina=home" class="btn-primary mx-auto px-6 inline-block">';
                        echo '<i class="fas fa-arrow-left mr-2"></i> Volver al Home';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        let sidebarVisible = false;
        const dom = {
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('overlay'),
            notifPanel: document.getElementById('notificationsPanel')
        };

        // Función para cerrar sidebar en móvil
        window.closeSidebar = function () {
            if (window.innerWidth < 768 && sidebarVisible) {
                toggleSidebar();
            }
        };

        // Toggle sidebar
        function toggleSidebar() {
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
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar toggle
            document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);

            // Notificaciones
            document.getElementById('notificationsBtn').addEventListener('click', function (e) {
                e.stopPropagation();
                dom.notifPanel.classList.toggle('hidden');
            });

            // Cerrar notificaciones al hacer clic fuera
            document.addEventListener('click', function (e) {
                if (!dom.notifPanel.contains(e.target) && e.target.id !== 'notificationsBtn') {
                    dom.notifPanel.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>