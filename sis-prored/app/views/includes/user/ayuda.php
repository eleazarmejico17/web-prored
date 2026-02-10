<?php
// --- CONFIGURACIÓN DE CONTACTO ---
$soporte_whatsapp = "51987654321"; // Número para la API de WhatsApp
$soporte_telefono = "064-234567";
$horario_atencion = "Lun - Sab: 8:00 AM - 8:00 PM";

// --- BASE DE DATOS DE PREGUNTAS FRECUENTES (FAQ) ---
$faqs = [
    [
        'categoria' => 'Internet y Conectividad',
        'icono' => 'fa-wifi',
        'preguntas' => [
            [
                'pregunta' => 'Mi internet está lento, ¿qué puedo hacer?',
                'respuesta' => 'Sigue estos pasos para mejorar tu velocidad: <br>
                                1. <strong>Reinicia tu router:</strong> Desconéctalo de la corriente eléctrica por 10 segundos y vuélvelo a conectar. Espera 2 minutos a que las luces se estabilicen.<br>
                                2. <strong>Acércate al router:</strong> La señal WiFi pierde fuerza con la distancia y las paredes.<br>
                                3. <strong>Desconecta dispositivos:</strong> Si hay muchos equipos descargando o viendo streaming, la velocidad se divide.'
            ],
            [
                'pregunta' => 'No tengo conexión y aparece una luz roja en el router (LOS)',
                'respuesta' => 'La luz roja parpadeante en "LOS" indica una interrupción física en la fibra óptica. <br>
                                Esto puede deberse a un cable roto en la calle o una desconexión en tu zona. <br>
                                <strong class="text-red-500">Acción recomendada:</strong> Genera un ticket de soporte inmediato o contáctanos por WhatsApp enviando una foto del router.'
            ],
            [
                'pregunta' => '¿Cómo puedo cambiar mi clave de WiFi?',
                'respuesta' => 'Por seguridad, el cambio de clave se gestiona a través de nuestro soporte. <br>
                                Crea un <strong>Nuevo Ticket</strong> con el asunto "Cambio de Clave", indicando el nuevo nombre de red y contraseña que deseas. Lo aplicaremos remotamente en minutos.'
            ]
        ]
    ],
    [
        'categoria' => 'Facturación y Pagos',
        'icono' => 'fa-file-invoice-dollar',
        'preguntas' => [
            [
                'pregunta' => '¿Dónde puedo reportar mi pago?',
                'respuesta' => 'Puedes reportar tus transferencias o depósitos directamente desde la opción <strong><a href="?pagina=pagos" class="text-primary hover:underline">Pagos</a></strong> en el menú lateral. Recuerda tener la foto de tu comprobante lista.'
            ],
            [
                'pregunta' => '¿Qué pasa si me atraso en mi pago?',
                'respuesta' => 'El servicio se suspende automáticamente 48 horas después de la fecha de vencimiento. <br>
                                Para reactivarlo, debes cancelar el total de la deuda pendiente. La reconexión es automática en un plazo máximo de 1 hora tras validar el pago.'
            ]
        ]
    ]
];
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="bg-primary rounded-xl p-8 text-center text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <i class="fas fa-life-ring absolute -top-10 -left-10 text-9xl"></i>
            <i class="fas fa-question absolute bottom-10 right-10 text-8xl"></i>
        </div>

        <div class="relative z-10 max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-2">¿Cómo podemos ayudarte hoy?</h1>
            <p class="text-blue-100 mb-6">Busca soluciones rápidas o contacta con nuestro equipo de expertos.</p>

            <div class="relative">
                <input type="text" id="faqSearch" onkeyup="filtrarFaqs()"
                    placeholder="Escribe tu problema (ej: internet lento, clave wifi...)"
                    class="w-full px-6 py-3 rounded-full text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-400 shadow-md transition-shadow">
                <button
                    class="absolute right-2 top-1.5 bg-secondary text-white p-1.5 rounded-full w-9 h-9 flex items-center justify-center hover:bg-orange-600 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-base p-6 text-center hover:shadow-lg transition-all border-b-4 border-green-500 group">
            <div
                class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl group-hover:scale-110 transition-transform">
                <i class="fab fa-whatsapp"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg">Chat por WhatsApp</h3>
            <p class="text-sm text-gray-500 mb-4">Respuesta promedio: 5 min</p>
            <a href="https://wa.me/<?= $soporte_whatsapp ?>?text=Hola,%20tengo%20un%20problema%20con%20mi%20servicio%20(ID: Cliente)"
                target="_blank" class="btn-primary bg-green-600 hover:bg-green-700 w-full justify-center shadow-md">
                <i class="fab fa-whatsapp mr-2"></i> Iniciar Chat
            </a>
        </div>

        <div class="card-base p-6 text-center hover:shadow-lg transition-all border-b-4 border-primary group">
            <div
                class="w-16 h-16 bg-blue-100 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-3xl group-hover:scale-110 transition-transform">
                <i class="fas fa-headset"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg">Central Telefónica</h3>
            <p class="text-sm text-gray-500 mb-4">
                <?= $horario_atencion ?>
            </p>
            <a href="tel:<?= $soporte_telefono ?>" class="btn-primary w-full justify-center shadow-md">
                <i class="fas fa-phone-alt mr-2"></i> Llamar Ahora
            </a>
        </div>

        <div class="card-base p-6 text-center hover:shadow-lg transition-all border-b-4 border-secondary group">
            <div
                class="w-16 h-16 bg-orange-100 text-secondary rounded-full flex items-center justify-center mx-auto mb-4 text-3xl group-hover:scale-110 transition-transform">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg">Generar Ticket</h3>
            <p class="text-sm text-gray-500 mb-4">Reporte detallado de averías</p>
            <a href="?pagina=tickets" class="btn-secondary w-full justify-center shadow-md">
                <i class="fas fa-plus-circle mr-2"></i> Crear Reporte
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <div class="lg:col-span-2 space-y-8" id="faqContainer">

            <h2 class="text-xl font-bold text-gray-800 border-l-4 border-primary pl-3">Preguntas Frecuentes</h2>

            <?php foreach ($faqs as $indexCat => $categoria): ?>
                <div class="faq-category">
                    <h3 class="flex items-center gap-2 font-bold text-gray-600 mb-4 mt-6">
                        <i class="fas <?= $categoria['icono'] ?> text-primary"></i>
                        <?= $categoria['categoria'] ?>
                    </h3>

                    <div class="space-y-3">
                        <?php foreach ($categoria['preguntas'] as $indexPreg => $item): ?>
                            <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
                                <button
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none"
                                    onclick="toggleFaq('faq-<?= $indexCat ?>-<?= $indexPreg ?>')">
                                    <span class="font-medium text-gray-700">
                                        <?= $item['pregunta'] ?>
                                    </span>
                                    <i id="icon-faq-<?= $indexCat ?>-<?= $indexPreg ?>"
                                        class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div id="faq-<?= $indexCat ?>-<?= $indexPreg ?>"
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 animate-fade-in-up">
                                    <?= $item['respuesta'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div id="noResults" class="hidden text-center py-8">
                <div class="text-gray-300 mb-2 text-4xl"><i class="fas fa-search"></i></div>
                <p class="text-gray-500">No encontramos resultados para tu búsqueda.</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl p-6 text-white shadow-lg">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-shield-alt text-yellow-400 mr-2"></i> Consejos de
                    Seguridad</h3>
                <ul class="text-sm space-y-3 text-gray-300">
                    <li class="flex gap-2">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>No compartas tu clave WiFi con desconocidos.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>Cambia tu contraseña al menos cada 3 meses.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>Evita conectar dispositivos que no reconozcas.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center">
                <h4 class="font-bold text-primary mb-2">¿Necesitas una visita técnica?</h4>
                <p class="text-xs text-gray-500 mb-4">Nuestros técnicos están disponibles de Lunes a Sábado.</p>
                <button onclick="window.location.href='?pagina=tickets'"
                    class="text-sm font-bold text-secondary hover:text-orange-600 underline">
                    Solicitar visita aquí
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para el acordeón
    function toggleFaq(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        // Cerrar otros (opcional, para comportamiento de acordeón estricto)
        // document.querySelectorAll('[id^="faq-"]').forEach(el => {
        //    if (el.id !== id) el.classList.add('hidden');
        // });

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    // Función de filtrado simple
    function filtrarFaqs() {
        const input = document.getElementById('faqSearch');
        const filter = input.value.toLowerCase();
        const categories = document.getElementsByClassName('faq-category');
        let foundAny = false;

        Array.from(categories).forEach(cat => {
            const items = cat.querySelectorAll('button');
            let hasVisibleItems = false;

            items.forEach(btn => {
                const text = btn.textContent || btn.innerText;
                const parentDiv = btn.parentElement;

                if (text.toLowerCase().indexOf(filter) > -1) {
                    parentDiv.style.display = "";
                    hasVisibleItems = true;
                    foundAny = true;
                } else {
                    parentDiv.style.display = "none";
                }
            });

            // Ocultar categoría si no tiene preguntas visibles
            cat.style.display = hasVisibleItems ? "" : "none";
        });

        document.getElementById('noResults').classList.toggle('hidden', foundAny);
    }
</script>