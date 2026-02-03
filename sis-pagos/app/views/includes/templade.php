<?php
/**
 * Dashboard Template - Kit de Componentes UI/UX
 * Incluye todos los elementos reutilizables para los dashboards
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Template - Componentes UI</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animaciones personalizadas */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.3s ease-out;
        }
        
        .animate-pulse-custom {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
        
        /* Efectos de tarjetas */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Efecto de gradiente */
        .gradient-primary {
            background: linear-gradient(135deg, #005FA2 0%, #0077CC 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #E58E21 0%, #F9A825 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        }
        
        /* Loader personalizado */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #005FA2;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Sombra personalizada */
        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .shadow-custom-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Efecto de vidrio (glassmorphism) */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Tooltip personalizado */
        .tooltip {
            position: relative;
        }
        
        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        /* Badge animado */
        .badge-pulse {
            position: relative;
        }
        
        .badge-pulse::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            background: currentColor;
            animation: pulse 2s infinite;
            opacity: 0.3;
        }
        
        /* Tabla con hover */
        .table-hover tbody tr {
            transition: background-color 0.2s;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 95, 162, 0.05);
        }
        
        /* Input con focus personalizado */
        .input-focus:focus {
            border-color: #005FA2;
            box-shadow: 0 0 0 3px rgba(0, 95, 162, 0.1);
        }
        
        /* Botón con efecto ripple */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .ripple:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            20% {
                transform: scale(25, 25);
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }
    </style>
    
    <script>
        // Configuración de Tailwind personalizada
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005FA2',
                        secondary: '#E58E21',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        info: '#3B82F6',
                        dark: '#1F2937',
                        light: '#F9FAFB',
                        'primary-light': '#E6F2FA',
                        'secondary-light': '#FEF5E7',
                        'success-light': '#D1FAE5',
                        'warning-light': '#FEF3C7',
                        'danger-light': '#FEE2E2',
                        'info-light': '#DBEAFE'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-in-right': 'slideInRight 0.3s ease-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                        'pulse-custom': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 3s linear infinite',
                        'bounce-slow': 'bounce 2s infinite'
                    }
                }
            }
        }
        
        // Clases de utilidad CSS globales
        const utilityClasses = {
            // Colores de fondo
            bgPrimary: 'bg-primary',
            bgSecondary: 'bg-secondary',
            bgSuccess: 'bg-success',
            bgWarning: 'bg-warning',
            bgDanger: 'bg-danger',
            bgInfo: 'bg-info',
            
            // Colores de texto
            textPrimary: 'text-primary',
            textSecondary: 'text-secondary',
            textSuccess: 'text-success',
            textWarning: 'text-warning',
            textDanger: 'text-danger',
            textInfo: 'text-info',
            
            // Bordes
            borderPrimary: 'border-primary',
            borderSecondary: 'border-secondary',
            borderSuccess: 'border-success',
            borderWarning: 'border-warning',
            borderDanger: 'border-danger',
            borderInfo: 'border-info',
            
            // Tamaños de iconos
            iconSm: 'text-lg',
            iconMd: 'text-xl',
            iconLg: 'text-2xl',
            iconXl: 'text-3xl'
        };
    </script>
</head>
<body class="bg-gray-50">
    <!-- Contenedor principal -->
    <div class="container mx-auto px-4 py-8">
        
        <!-- Título de la página -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-primary mb-2">Kit de Componentes UI/UX</h1>
            <p class="text-gray-600">Plantilla completa con todos los elementos reutilizables para dashboards</p>
        </div>
        
        <!-- Sección: Tarjetas de Estadísticas -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Tarjetas de Estadísticas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tarjeta 1: Con icono -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6 card-hover border-t-4 border-primary">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-500 text-sm">Total de Clientes</p>
                            <h3 class="text-3xl font-bold text-primary">1,248</h3>
                        </div>
                        <div class="bg-primary-light p-3 rounded-lg">
                            <i class="fas fa-users text-primary text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="text-success flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 12.5%
                        </span>
                        <span class="text-gray-500 ml-2">Desde el mes pasado</span>
                    </div>
                </div>
                
                <!-- Tarjeta 2: Con gradiente -->
                <div class="gradient-secondary text-white rounded-xl shadow-custom-lg p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-white text-opacity-90 text-sm">Ingresos Mensuales</p>
                            <h3 class="text-3xl font-bold">$42,580</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-dollar-sign text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 8.3%
                        </span>
                        <span class="ml-2 text-white text-opacity-90">Crecimiento mensual</span>
                    </div>
                </div>
                
                <!-- Tarjeta 3: Con badge -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6 card-hover border-t-4 border-success relative">
                    <div class="absolute -top-2 -right-2">
                        <span class="bg-success text-white text-xs font-bold px-3 py-1 rounded-full">
                            +24 Nuevos
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-500 text-sm">Pagos Procesados</p>
                            <h3 class="text-3xl font-bold text-success">892</h3>
                        </div>
                        <div class="bg-success-light p-3 rounded-lg">
                            <i class="fas fa-credit-card text-success text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-check-circle text-success mr-1"></i> 98% completados
                    </div>
                </div>
                
                <!-- Tarjeta 4: Con contador animado -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6 card-hover border-t-4 border-warning">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-500 text-sm">Tickets de Soporte</p>
                            <h3 id="animatedCounter" class="text-3xl font-bold text-warning">0</h3>
                        </div>
                        <div class="bg-warning-light p-3 rounded-lg">
                            <i class="fas fa-headset text-warning text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-sm">
                        <span class="text-warning font-medium">12 pendientes</span>
                        <span class="text-gray-500 ml-2">de 24 totales</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Gráficos Estadísticos -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Gráficos Estadísticos</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico 1: Barras -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-primary">Ventas Mensuales</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-xs bg-primary-light text-primary rounded-lg">Mes</button>
                            <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg">Año</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                
                <!-- Gráfico 2: Circular -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-primary">Distribución de Clientes</h3>
                        <span class="text-sm text-gray-500">Total: 1,248</span>
                    </div>
                    <div class="h-64">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
                
                <!-- Gráfico 3: Líneas -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-primary">Crecimiento de Ingresos</h3>
                        <div class="flex items-center text-success">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span>+15.2% este trimestre</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Botones -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Botones y Estados</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-primary mb-4">Botones de Acción</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-3">Estilos de Botones</h4>
                        <div class="space-y-3">
                            <button class="w-full md:w-auto px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark transition duration-200">
                                <i class="fas fa-save mr-2"></i>Guardar Cambios
                            </button>
                            <button class="w-full md:w-auto px-6 py-3 bg-secondary text-white font-medium rounded-lg hover:bg-orange-600 transition duration-200">
                                <i class="fas fa-edit mr-2"></i>Editar
                            </button>
                            <button class="w-full md:w-auto px-6 py-3 bg-success text-white font-medium rounded-lg hover:bg-green-600 transition duration-200">
                                <i class="fas fa-check mr-2"></i>Aprobar
                            </button>
                            <button class="w-full md:w-auto px-6 py-3 bg-warning text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Advertencia
                            </button>
                            <button class="w-full md:w-auto px-6 py-3 bg-danger text-white font-medium rounded-lg hover:bg-red-600 transition duration-200">
                                <i class="fas fa-trash mr-2"></i>Eliminar
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-3">Tamaños y Estados</h4>
                        <div class="space-y-3">
                            <button class="px-2 py-1 text-sm bg-primary text-white rounded hover:bg-primary-dark transition">
                                Pequeño
                            </button>
                            <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                Normal
                            </button>
                            <button class="px-6 py-3 text-lg bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                Grande
                            </button>
                            
                            <div class="pt-4">
                                <button class="px-6 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                                    Deshabilitado
                                </button>
                            </div>
                            
                            <div class="pt-4">
                                <button class="px-6 py-3 bg-primary text-white rounded-lg ripple">
                                    Con Efecto Ripple
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-medium text-gray-700 mb-3">Botones Outline</h4>
                    <div class="flex flex-wrap gap-3">
                        <button class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary-light transition">
                            Primary
                        </button>
                        <button class="px-4 py-2 border border-secondary text-secondary rounded-lg hover:bg-secondary-light transition">
                            Secondary
                        </button>
                        <button class="px-4 py-2 border border-success text-success rounded-lg hover:bg-success-light transition">
                            Success
                        </button>
                        <button class="px-4 py-2 border border-warning text-warning rounded-lg hover:bg-warning-light transition">
                            Warning
                        </button>
                        <button class="px-4 py-2 border border-danger text-danger rounded-lg hover:bg-danger-light transition">
                            Danger
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Formularios -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Formularios y Entradas</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg p-6">
                <form id="sampleForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campo de texto -->
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">
                                Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="name" name="name" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light input-focus" 
                                       placeholder="Ingrese su nombre" required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Mínimo 3 caracteres</p>
                        </div>
                        
                        <!-- Campo de email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">
                                Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light input-focus" 
                                       placeholder="ejemplo@correo.com" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Select -->
                    <div>
                        <label for="plan" class="block text-gray-700 font-medium mb-2">Plan de Servicio</label>
                        <div class="relative">
                            <select id="plan" name="plan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light appearance-none bg-white">
                                <option value="">Seleccione un plan</option>
                                <option value="basic">Básico (10 Mbps)</option>
                                <option value="standard">Estándar (50 Mbps)</option>
                                <option value="premium">Premium (100 Mbps)</option>
                                <option value="enterprise">Empresarial (1 Gbps)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campo de texto con textarea -->
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Mensaje</label>
                        <textarea id="message" name="message" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light input-focus" 
                                  placeholder="Escriba su mensaje aquí..."></textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">Máximo 500 caracteres</p>
                            <p id="charCount" class="text-xs text-gray-500">0/500</p>
                        </div>
                    </div>
                    
                    <!-- Checkbox y Radio -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="font-medium text-gray-700 mb-3">Método de Notificación</p>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="notification" value="email" class="text-primary focus:ring-primary" checked>
                                    <span class="ml-2">Correo Electrónico</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="notification" value="sms" class="text-primary focus:ring-primary">
                                    <span class="ml-2">SMS</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="notification" value="both" class="text-primary focus:ring-primary">
                                    <span class="ml-2">Ambos</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <p class="font-medium text-gray-700 mb-3">Términos y Condiciones</p>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="terms" class="rounded text-primary focus:ring-primary">
                                    <span class="ml-2">Acepto los <a href="#" class="text-primary hover:underline">términos y condiciones</a></span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="newsletter" class="rounded text-primary focus:ring-primary" checked>
                                    <span class="ml-2">Suscribirme al boletín informativo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones del formulario -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark transition duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Formulario
                        </button>
                        <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-redo mr-2"></i>Limpiar Formulario
                        </button>
                        <button type="button" id="cancelBtn" class="px-6 py-3 border border-danger text-danger font-medium rounded-lg hover:bg-danger-light transition duration-200">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </section>
        
        <!-- Sección: Tablas -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Tablas de Datos</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-primary">Lista de Usuarios</h3>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Buscar usuario..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary-light">
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-hover">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="rounded text-primary focus:ring-primary">
                                        <span class="ml-2">Usuario</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Último Pago
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Fila 1 -->
                            <tr class="hover:bg-primary-light transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                                                JP
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Juan Pérez</div>
                                            <div class="text-sm text-gray-500">juan.perez@email.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-light text-success">
                                        <i class="fas fa-circle text-xs mr-1"></i> Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Premium 100Mbps
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    15/12/2023
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary hover:text-primary-dark mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-success hover:text-green-700 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-danger hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Fila 2 -->
                            <tr class="hover:bg-primary-light transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-secondary flex items-center justify-center text-white font-bold">
                                                MG
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">María González</div>
                                            <div class="text-sm text-gray-500">maria.g@email.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning-light text-warning">
                                        <i class="fas fa-circle text-xs mr-1"></i> Pendiente
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Estándar 50Mbps
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    10/12/2023
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary hover:text-primary-dark mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-success hover:text-green-700 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-danger hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Fila 3 -->
                            <tr class="hover:bg-primary-light transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-success flex items-center justify-center text-white font-bold">
                                                CR
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Carlos Rodríguez</div>
                                            <div class="text-sm text-gray-500">carlos.r@email.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-light text-danger">
                                        <i class="fas fa-circle text-xs mr-1"></i> Suspendido
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Básico 10Mbps
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    05/12/2023
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary hover:text-primary-dark mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-success hover:text-green-700 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-danger hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center">
                    <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                        Mostrando <span class="font-medium">1</span> a <span class="font-medium">3</span> de <span class="font-medium">24</span> resultados
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                            Anterior
                        </button>
                        <button class="px-3 py-1 bg-primary text-white rounded">1</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">2</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">3</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Alertas y Notificaciones -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Alertas y Notificaciones</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Alertas -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6">
                    <h3 class="text-lg font-bold text-primary mb-4">Alertas del Sistema</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-success-light border border-success">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-success">¡Operación exitosa!</h4>
                                    <div class="mt-1 text-sm text-green-700">
                                        Los cambios se han guardado correctamente.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 rounded-lg bg-info-light border border-info">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-info"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-info">Información importante</h4>
                                    <div class="mt-1 text-sm text-blue-700">
                                        El sistema se actualizará el próximo sábado a las 2:00 AM.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 rounded-lg bg-warning-light border border-warning">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-warning">Advertencia</h4>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        Su pago está próximo a vencer. Realice el pago antes del 15/01/2024.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 rounded-lg bg-danger-light border border-danger">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-danger"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-danger">¡Error crítico!</h4>
                                    <div class="mt-1 text-sm text-red-700">
                                        No se pudo procesar la transacción. Intente nuevamente.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Badges y Estados -->
                <div class="bg-white rounded-xl shadow-custom-lg p-6">
                    <h3 class="text-lg font-bold text-primary mb-4">Badges y Estados</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Estados de Usuario</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-success-light text-success">
                                    <i class="fas fa-circle text-xs mr-1"></i> Activo
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-warning-light text-warning">
                                    <i class="fas fa-circle text-xs mr-1"></i> Pendiente
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-danger-light text-danger">
                                    <i class="fas fa-circle text-xs mr-1"></i> Suspendido
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
                                    <i class="fas fa-circle text-xs mr-1"></i> Inactivo
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Prioridades</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-danger text-white">
                                    <i class="fas fa-exclamation mr-1"></i> Alta
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-warning text-white">
                                    Media
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-info text-white">
                                    Baja
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Contadores</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="relative">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-primary text-white">
                                        Notificaciones
                                    </span>
                                    <span class="absolute -top-2 -right-2 h-5 w-5 bg-danger rounded-full flex items-center justify-center text-xs text-white">
                                        5
                                    </span>
                                </span>
                                
                                <span class="relative">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">
                                        Mensajes
                                    </span>
                                    <span class="absolute -top-2 -right-2 h-5 w-5 bg-success rounded-full flex items-center justify-center text-xs text-white">
                                        12
                                    </span>
                                </span>
                                
                                <span class="badge-pulse">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-danger text-white">
                                        Urgente
                                    </span>
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Íconos de Estado</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="h-10 w-10 rounded-full bg-success-light flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-check text-success"></i>
                                    </div>
                                    <p class="text-xs text-gray-600">Completado</p>
                                </div>
                                <div class="text-center">
                                    <div class="h-10 w-10 rounded-full bg-warning-light flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <p class="text-xs text-gray-600">En Proceso</p>
                                </div>
                                <div class="text-center">
                                    <div class="h-10 w-10 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-times text-danger"></i>
                                    </div>
                                    <p class="text-xs text-gray-600">Error</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Modales -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Modales y Diálogos</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg p-6">
                <h3 class="text-lg font-bold text-primary mb-4">Ejemplos de Modales</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Botón para abrir modal de confirmación -->
                    <button id="openConfirmModal" class="px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        <i class="fas fa-question-circle mr-2"></i>Modal de Confirmación
                    </button>
                    
                    <!-- Botón para abrir modal de éxito -->
                    <button id="openSuccessModal" class="px-4 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-check-circle mr-2"></i>Modal de Éxito
                    </button>
                    
                    <!-- Botón para abrir modal de error -->
                    <button id="openErrorModal" class="px-4 py-3 bg-danger text-white rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-exclamation-circle mr-2"></i>Modal de Error
                    </button>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-medium text-gray-700 mb-3">Modal de Formulario</h4>
                    <button id="openFormModal" class="px-4 py-3 bg-secondary text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-edit mr-2"></i>Abrir Modal con Formulario
                    </button>
                </div>
            </div>
        </section>
        
        <!-- Sección: Progreso y Loaders -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Barras de Progreso y Loaders</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Barras de progreso -->
                    <div>
                        <h3 class="text-lg font-bold text-primary mb-4">Barras de Progreso</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Carga del Sistema</span>
                                    <span class="text-sm font-medium text-primary">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary h-2.5 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Espacio de Almacenamiento</span>
                                    <span class="text-sm font-medium text-warning">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-warning h-2.5 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Proceso de Importación</span>
                                    <span class="text-sm font-medium text-success">100%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-success h-2.5 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Sincronización de Datos</span>
                                    <span class="text-sm font-medium text-danger">30%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-danger h-2.5 rounded-full" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loaders -->
                    <div>
                        <h3 class="text-lg font-bold text-primary mb-4">Loaders y Spinners</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <div class="loader"></div>
                                <span>Cargando datos del sistema...</span>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="loader" style="border-top-color: #E58E21;"></div>
                                <span>Procesando transacción...</span>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="loader" style="border-top-color: #10B981;"></div>
                                <span>Generando reporte...</span>
                            </div>
                            
                            <div class="pt-4">
                                <h4 class="font-medium text-gray-700 mb-2">Loader con porcentaje</h4>
                                <div class="relative w-32 h-32 mx-auto">
                                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" fill="none" stroke="#E5E7EB" stroke-width="10" />
                                        <circle cx="50" cy="50" r="45" fill="none" stroke="#005FA2" stroke-width="10" 
                                                stroke-dasharray="283" stroke-dashoffset="70" stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-primary">75%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección: Iconos -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-primary mb-6">Iconos y Símbolos</h2>
            
            <div class="bg-white rounded-xl shadow-custom-lg p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <!-- Iconos de acciones -->
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-primary-light transition">
                        <i class="fas fa-edit text-primary text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Editar</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-success-light transition">
                        <i class="fas fa-check text-success text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Aprobar</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-danger-light transition">
                        <i class="fas fa-trash text-danger text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Eliminar</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-info-light transition">
                        <i class="fas fa-eye text-info text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Ver</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-warning-light transition">
                        <i class="fas fa-download text-warning text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Descargar</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-secondary-light transition">
                        <i class="fas fa-print text-secondary text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Imprimir</p>
                    </div>
                    
                    <!-- Iconos de estados -->
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-check-circle text-success text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Éxito</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-exclamation-circle text-warning text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Advertencia</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-times-circle text-danger text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Error</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-info-circle text-info text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Información</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-question-circle text-primary text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Ayuda</p>
                    </div>
                    
                    <div class="text-center p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <i class="fas fa-cog text-gray-600 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Configuración</p>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-medium text-gray-700 mb-3">Iconos de Navegación</h4>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-home text-primary mr-2"></i>
                            <span>Inicio</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user text-primary mr-2"></i>
                            <span>Perfil</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-cog text-primary mr-2"></i>
                            <span>Configuración</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bell text-primary mr-2"></i>
                            <span>Notificaciones</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar text-primary mr-2"></i>
                            <span>Reportes</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-sign-out-alt text-primary mr-2"></i>
                            <span>Salir</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200 text-center">
            <p class="text-gray-600">
                Plantilla de Componentes UI/UX - Sistema de Gestión de Pagos de Internet
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Todos los componentes son reutilizables y responsivos. Incluye animaciones, efectos y estilos optimizados.
            </p>
        </footer>
    </div>
    
    <!-- =========================================== -->
    <!-- MODALES PREESTABLECIDOS - PARA REUTILIZAR -->
    <!-- =========================================== -->
    
    <!-- Modal de Confirmación -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 animate-fade-in">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-primary-light flex items-center justify-center mr-4">
                        <i class="fas fa-question-circle text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-primary">Confirmar Acción</h3>
                        <p class="text-gray-600">¿Está seguro de realizar esta acción?</p>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-6">
                    Esta acción no se puede deshacer. Por favor, confirme que desea continuar.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="confirmAction" class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark transition flex-1">
                        <i class="fas fa-check mr-2"></i>Confirmar
                    </button>
                    <button id="cancelAction" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition flex-1">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Éxito -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 animate-slide-in-right">
            <div class="p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-success-light flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-success text-4xl"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-success mb-2">¡Operación Exitosa!</h3>
                <p class="text-gray-700 mb-6">
                    La operación se ha completado correctamente. Los cambios han sido guardados.
                </p>
                
                <button id="closeSuccessModal" class="px-6 py-3 bg-success text-white font-medium rounded-lg hover:bg-green-600 transition">
                    <i class="fas fa-check mr-2"></i>Aceptar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal de Error -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 animate-slide-in-left">
            <div class="p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-danger text-4xl"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-danger mb-2">¡Error!</h3>
                <p class="text-gray-700 mb-6">
                    Ha ocurrido un error al procesar la solicitud. Por favor, intente nuevamente.
                </p>
                
                <div class="flex gap-3">
                    <button id="closeErrorModal" class="px-6 py-3 bg-danger text-white font-medium rounded-lg hover:bg-red-600 transition flex-1">
                        <i class="fas fa-times mr-2"></i>Cerrar
                    </button>
                    <button id="retryAction" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition flex-1">
                        <i class="fas fa-redo mr-2"></i>Reintentar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Formulario -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 animate-fade-in">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-primary">Nuevo Registro</h3>
                    <button id="closeFormModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="modalForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary-light" 
                                   placeholder="Ingrese nombre" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Apellido</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary-light" 
                                   placeholder="Ingrese apellido" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary-light" 
                               placeholder="ejemplo@correo.com" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Descripción</label>
                        <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary-light" 
                                  placeholder="Escriba una descripción..."></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelFormModal" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark transition">
                            Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- =========================================== -->
    <!-- SCRIPTS PARA COMPONENTES -->
    <!-- =========================================== -->
    
    <script>
        // Configuración global
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar contador animado
            animateCounter('animatedCounter', 0, 24, 2000);
            
            // Inicializar gráficos
            initCharts();
            
            // Configurar eventos de formulario
            setupFormEvents();
            
            // Configurar eventos de modales
            setupModalEvents();
        });
        
        // Función para animar contador
        function animateCounter(elementId, start, end, duration) {
            const element = document.getElementById(elementId);
            let startTimestamp = null;
            
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const current = Math.floor(progress * (end - start) + start);
                
                element.textContent = current;
                
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            
            window.requestAnimationFrame(step);
        }
        
        // Función para inicializar gráficos
        function initCharts() {
            // Gráfico de barras
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ventas',
                        data: [65, 59, 80, 81, 56, 55, 40, 72, 85, 90, 75, 88],
                        backgroundColor: '#005FA2',
                        borderColor: '#004a80',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Gráfico circular
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Activos', 'Pendientes', 'Suspendidos', 'Inactivos'],
                    datasets: [{
                        data: [65, 15, 10, 10],
                        backgroundColor: [
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#6B7280'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Gráfico de líneas
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['2020', '2021', '2022', '2023'],
                    datasets: [{
                        label: 'Ingresos (miles)',
                        data: [120, 190, 300, 450],
                        borderColor: '#005FA2',
                        backgroundColor: 'rgba(0, 95, 162, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Función para configurar eventos de formulario
        function setupFormEvents() {
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            
            // Contador de caracteres
            if (messageTextarea && charCount) {
                messageTextarea.addEventListener('input', function() {
                    const currentLength = this.value.length;
                    charCount.textContent = `${currentLength}/500`;
                    
                    if (currentLength > 500) {
                        charCount.classList.remove('text-gray-500');
                        charCount.classList.add('text-danger');
                    } else {
                        charCount.classList.remove('text-danger');
                        charCount.classList.add('text-gray-500');
                    }
                });
            }
            
            // Validación de formulario
            const sampleForm = document.getElementById('sampleForm');
            if (sampleForm) {
                sampleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Simular envío
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
                    submitBtn.disabled = true;
                    
                    setTimeout(() => {
                        alert('Formulario enviado exitosamente (simulación)');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                });
            }
            
            // Botón de cancelar
            const cancelBtn = document.getElementById('cancelBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (confirm('¿Está seguro de cancelar? Los cambios no guardados se perderán.')) {
                        sampleForm.reset();
                        if (charCount) charCount.textContent = '0/500';
                    }
                });
            }
        }
        
        // Función para configurar eventos de modales
        function setupModalEvents() {
            // Modal de confirmación
            const confirmModal = document.getElementById('confirmModal');
            const openConfirmModal = document.getElementById('openConfirmModal');
            const confirmAction = document.getElementById('confirmAction');
            const cancelAction = document.getElementById('cancelAction');
            
            if (openConfirmModal) {
                openConfirmModal.addEventListener('click', () => {
                    confirmModal.classList.remove('hidden');
                });
            }
            
            if (confirmAction) {
                confirmAction.addEventListener('click', () => {
                    alert('Acción confirmada');
                    confirmModal.classList.add('hidden');
                });
            }
            
            if (cancelAction) {
                cancelAction.addEventListener('click', () => {
                    confirmModal.classList.add('hidden');
                });
            }
            
            // Modal de éxito
            const successModal = document.getElementById('successModal');
            const openSuccessModal = document.getElementById('openSuccessModal');
            const closeSuccessModal = document.getElementById('closeSuccessModal');
            
            if (openSuccessModal) {
                openSuccessModal.addEventListener('click', () => {
                    successModal.classList.remove('hidden');
                });
            }
            
            if (closeSuccessModal) {
                closeSuccessModal.addEventListener('click', () => {
                    successModal.classList.add('hidden');
                });
            }
            
            // Modal de error
            const errorModal = document.getElementById('errorModal');
            const openErrorModal = document.getElementById('openErrorModal');
            const closeErrorModal = document.getElementById('closeErrorModal');
            const retryAction = document.getElementById('retryAction');
            
            if (openErrorModal) {
                openErrorModal.addEventListener('click', () => {
                    errorModal.classList.remove('hidden');
                });
            }
            
            if (closeErrorModal) {
                closeErrorModal.addEventListener('click', () => {
                    errorModal.classList.add('hidden');
                });
            }
            
            if (retryAction) {
                retryAction.addEventListener('click', () => {
                    alert('Reintentando acción...');
                    errorModal.classList.add('hidden');
                });
            }
            
            // Modal de formulario
            const formModal = document.getElementById('formModal');
            const openFormModal = document.getElementById('openFormModal');
            const closeFormModal = document.getElementById('closeFormModal');
            const cancelFormModal = document.getElementById('cancelFormModal');
            const modalForm = document.getElementById('modalForm');
            
            if (openFormModal) {
                openFormModal.addEventListener('click', () => {
                    formModal.classList.remove('hidden');
                });
            }
            
            if (closeFormModal) {
                closeFormModal.addEventListener('click', () => {
                    formModal.classList.add('hidden');
                });
            }
            
            if (cancelFormModal) {
                cancelFormModal.addEventListener('click', () => {
                    if (confirm('¿Cancelar el registro?')) {
                        formModal.classList.add('hidden');
                    }
                });
            }
            
            if (modalForm) {
                modalForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    alert('Registro guardado exitosamente');
                    formModal.classList.add('hidden');
                    modalForm.reset();
                });
            }
            
            // Cerrar modales al hacer clic fuera
            const modals = [confirmModal, successModal, errorModal, formModal];
            modals.forEach(modal => {
                if (modal) {
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                        }
                    });
                }
            });
            
            // Cerrar modales con Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    modals.forEach(modal => {
                        if (modal && !modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                        }
                    });
                }
            });
        }
        
        // Funciones de utilidad globales
        const UIUtils = {
            // Mostrar notificación toast
            showToast: function(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white animate-fade-in z-50 ${
                    type === 'success' ? 'bg-success' :
                    type === 'error' ? 'bg-danger' :
                    type === 'warning' ? 'bg-warning' : 'bg-primary'
                }`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${
                            type === 'success' ? 'check-circle' :
                            type === 'error' ? 'times-circle' :
                            type === 'warning' ? 'exclamation-triangle' : 'info-circle'
                        } mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            },
            
            // Mostrar loader en botón
            showButtonLoader: function(button, text = 'Procesando...') {
                const originalHTML = button.innerHTML;
                button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${text}`;
                button.disabled = true;
                
                return {
                    stop: function() {
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }
                };
            },
            
            // Formatear número como moneda
            formatCurrency: function(amount, currency = '$') {
                return `${currency}${amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
            },
            
            // Formatear fecha
            formatDate: function(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            },
            
            // Validar email
            validateEmail: function(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },
            
            // Copiar al portapapeles
            copyToClipboard: function(text) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showToast('Copiado al portapapeles', 'success');
                }).catch(() => {
                    this.showToast('Error al copiar', 'error');
                });
            }
        };
        
        // Hacer las utilidades disponibles globalmente
        window.UIUtils = UIUtils;
    </script>
</body>
</html>