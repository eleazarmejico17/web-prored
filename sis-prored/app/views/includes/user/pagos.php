<?php
// --- MOCK DATA: Simulación de Base de Datos ---

// Servicios del Cliente
$servicios = [
    ['id' => 1001, 'direccion' => 'Av. Giraldez 550, Huancayo', 'plan' => 'Fibra 100 Mbps'],
    ['id' => 1002, 'direccion' => 'Jr. Puno 123, El Tambo', 'plan' => 'Fibra 50 Mbps']
];

// Deudas Pendientes (Simulación de consulta WHERE estado='PENDIENTE')
$deudas_pendientes = [
    1001 => [
        ['id' => 501, 'periodo' => 'Febrero 2026', 'monto' => 89.90, 'vencimiento' => '05/02/2026'],
        ['id' => 502, 'periodo' => 'Enero 2026', 'monto' => 89.90, 'vencimiento' => '05/01/2026'] // Mora
    ],
    1002 => [] // Este servicio está al día
];

// Métodos de Pago Activos
$metodos_pago = [
    'YAPE' => ['nombre' => 'Yape / Plin', 'icono' => 'fas fa-mobile-alt', 'info' => '987 654 321'],
    'BCP' => ['nombre' => 'Transferencia BCP', 'icono' => 'fas fa-university', 'info' => '355-12345678-0-99'],
    'BBVA' => ['nombre' => 'Depósito BBVA', 'icono' => 'fas fa-university', 'info' => '0011-0200-0100123456'],
    'OFICINA' => ['nombre' => 'Pago en Oficina', 'icono' => 'fas fa-map-marker-alt', 'info' => 'Efectivo / Tarjeta']
];

// Verificar si venimos de un botón "Pagar" específico
$action = $_GET['action'] ?? '';
?>

<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reportar Pago</h1>
            <p class="text-sm text-gray-500">Registra tus transferencias para validar tu mensualidad.</p>
        </div>

        <div
            class="bg-red-50 text-red-700 px-4 py-2 rounded-lg border border-red-100 flex items-center gap-3 text-sm animate-pulse">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <div>
                <p class="font-bold">Tienes facturas pendientes</p>
                <p class="text-xs">Evita el corte reportando tu pago hoy.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="card-base p-6 text-center relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                <h3 class="font-bold text-gray-800 mb-4 flex items-center justify-center gap-2">
                    <i class="fas fa-qrcode text-purple-600"></i> Yape / Plin
                </h3>

                <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 inline-block mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=987654321" alt="QR Yape"
                        class="w-32 h-32 mx-auto">
                </div>

                <p class="font-bold text-2xl text-gray-800 tracking-wider">987 654 321</p>
                <p class="text-xs text-gray-500">A nombre de: ProRed Peru S.A.C.</p>
            </div>

            <div class="card-base p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-university text-primary"></i> Transferencias
                </h3>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 hover:border-primary transition-colors cursor-pointer group"
                        onclick="copiarTexto('35512345678099')">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-primary text-sm">BCP (Soles)</span>
                            <i class="far fa-copy text-gray-400 group-hover:text-primary"></i>
                        </div>
                        <p class="font-mono text-gray-600 text-sm">355-12345678-0-99</p>
                        <p class="text-[10px] text-gray-400">CCI: 002-355-12345678099-12</p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 hover:border-primary transition-colors cursor-pointer group"
                        onclick="copiarTexto('001102000100123456')">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-blue-600 text-sm">BBVA (Soles)</span>
                            <i class="far fa-copy text-gray-400 group-hover:text-primary"></i>
                        </div>
                        <p class="font-mono text-gray-600 text-sm">0011-0200-0100123456</p>
                    </div>
                </div>
            </div>

            <div class="card-base p-0 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-secondary"></i> Pagos en Efectivo
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Av. Giraldez 123, Of. 202 - Huancayo</p>
                </div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.666996238356!2d-75.21252192589585!3d-12.066468642270387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x910e964555555555%3A0x123456789abcdef!2sHuancayo!5e0!3m2!1ses-419!2spe!4v1700000000000!5m2!1ses-419!2spe"
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
                <div class="p-3 text-center">
                    <p class="text-[10px] text-gray-400"><i class="far fa-clock"></i> Lun - Sáb: 9:00 AM - 7:00 PM</p>
                </div>
            </div>

        </div>

        <div class="lg:col-span-3">
            <div class="card-base p-6 md:p-8 h-full">
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">
                    <i class="fas fa-file-invoice-dollar text-primary mr-2"></i> Formulario de Registro
                </h2>

                <form id="paymentForm" onsubmit="submitPayment(event)" class="space-y-6">

                    <div>
                        <label class="label-prored block mb-2 font-bold text-sm text-gray-700">Selecciona tu
                            Servicio</label>
                        <select id="servicio_select"
                            class="input-prored w-full bg-gray-50 border-gray-300 rounded-lg p-2.5"
                            onchange="cargarDeudas()">
                            <option value="">-- Seleccione una dirección --</option>
                            <?php foreach ($servicios as $s): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= $s['plan'] ?> -
                                    <?= $s['direccion'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="deuda_container" class="hidden animate-fade-in-up">
                        <label class="label-prored block mb-2 font-bold text-sm text-gray-700">¿Qué mensualidad estás
                            pagando?</label>
                        <select id="deuda_select"
                            class="input-prored w-full bg-white border-primary rounded-lg p-2.5 border-2">
                        </select>
                        <p class="text-xs text-gray-500 mt-1 text-right">Monto a pagar: <span id="monto_preview"
                                class="font-bold text-primary">S/ 0.00</span></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label-prored text-xs font-bold text-gray-500 uppercase">Método Usado</label>
                            <select id="metodo_pago" class="input-prored w-full p-2 rounded border" required>
                                <option value="YAPE">Yape / Plin</option>
                                <option value="BCP">Transferencia BCP</option>
                                <option value="BBVA">Transferencia BBVA</option>
                                <option value="INTERBANK">Transferencia Interbank</option>
                                <option value="AGENTE">Agente / Bodega</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-prored text-xs font-bold text-gray-500 uppercase">Fecha del Pago</label>
                            <input type="date" id="fecha_pago" class="input-prored w-full p-2 rounded border"
                                value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div>
                        <label class="label-prored text-xs font-bold text-gray-500 uppercase">N° de Operación /
                            Referencia</label>
                        <input type="text" id="num_operacion" class="input-prored w-full p-2 rounded border font-mono"
                            placeholder="Ej: 1234567" required>
                        <p class="text-[10px] text-gray-400 mt-1">Este número aparece en tu comprobante digital o
                            físico.</p>
                    </div>

                    <div>
                        <label class="label-prored block mb-2 font-bold text-sm text-gray-700">Foto del
                            Comprobante</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors relative overflow-hidden"
                                id="dropzone">

                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropzone-content">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-500"><span class="font-semibold">Clic para subir</span>
                                        o arrastra aquí</p>
                                    <p class="text-xs text-gray-500">JPG, PNG o PDF (MAX. 5MB)</p>
                                </div>

                                <img id="preview-img"
                                    class="hidden absolute inset-0 w-full h-full object-cover opacity-80" />

                                <input id="dropzone-file" type="file" class="hidden" accept="image/*,application/pdf"
                                    onchange="previewFile()" required />
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full btn-primary py-3.5 text-lg shadow-lg hover:shadow-xl transition-all flex justify-center items-center gap-2">
                        <span>Registrar Pago</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>

                    <p class="text-xs text-center text-gray-400">
                        <i class="fas fa-lock"></i> Tu pago pasará a revisión por el área de ventas.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Mock Data de Deudas en JS
    const deudasDB = <?= json_encode($deudas_pendientes) ?>;

    // 1. Lógica de Servicio -> Deuda
    function cargarDeudas() {
        const servicioId = document.getElementById('servicio_select').value;
        const deudaSelect = document.getElementById('deuda_select');
        const container = document.getElementById('deuda_container');
        const preview = document.getElementById('monto_preview');

        deudaSelect.innerHTML = ''; // Limpiar

        if (!servicioId) {
            container.classList.add('hidden');
            return;
        }

        const deudas = deudasDB[servicioId];

        if (deudas && deudas.length > 0) {
            deudas.forEach(d => {
                const option = document.createElement('option');
                option.value = d.id;
                option.text = `${d.periodo} - (Vence: ${d.vencimiento})`;
                option.setAttribute('data-monto', d.monto);
                deudaSelect.appendChild(option);
            });
            // Actualizar preview inicial
            actualizarMonto();
            // Mostrar
            container.classList.remove('hidden');
        } else {
            // Si no hay deudas
            const option = document.createElement('option');
            option.text = "¡Estás al día! No hay pagos pendientes.";
            deudaSelect.appendChild(option);
            deudaSelect.disabled = true;
            container.classList.remove('hidden');
            preview.textContent = "S/ 0.00";
        }
    }

    // Actualizar monto al cambiar select
    document.getElementById('deuda_select').addEventListener('change', actualizarMonto);

    function actualizarMonto() {
        const select = document.getElementById('deuda_select');
        if (select.selectedIndex === -1) return;
        const monto = select.options[select.selectedIndex].getAttribute('data-monto');
        if (monto) {
            document.getElementById('monto_preview').textContent = 'S/ ' + parseFloat(monto).toFixed(2);
        }
    }

    // 2. Preview de Archivo
    function previewFile() {
        const file = document.getElementById('dropzone-file').files[0];
        const preview = document.getElementById('preview-img');
        const content = document.getElementById('dropzone-content');

        if (file) {
            // Si es imagen
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    content.classList.add('opacity-0'); // Ocultar texto pero mantener estructura
                }
                reader.readAsDataURL(file);
            } else {
                // Si es PDF u otro
                preview.classList.add('hidden');
                content.classList.remove('opacity-0');
                content.innerHTML = `<i class="fas fa-file-pdf text-red-500 text-3xl mb-2"></i><p class="font-bold text-gray-700">${file.name}</p>`;
            }
        }
    }

    // 3. Simular Envío
    function submitPayment(e) {
        e.preventDefault();

        // Validar selección de deuda
        const deudaSelect = document.getElementById('deuda_select');
        if (deudaSelect.disabled || deudaSelect.value === "") {
            alert("⚠️ No tienes deudas pendientes o no has seleccionado una.");
            return;
        }

        // Simulación de proceso
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        btn.disabled = true;

        setTimeout(() => {
            alert("✅ ¡Pago registrado correctamente!\n\nTu comprobante ha sido enviado a validación. Recibirás una notificación cuando sea aprobado.");
            // Resetear form
            e.target.reset();
            document.getElementById('preview-img').classList.add('hidden');
            document.getElementById('dropzone-content').classList.remove('opacity-0');
            document.getElementById('deuda_container').classList.add('hidden');

            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }

    // Copiar al portapapeles
    function copiarTexto(texto) {
        navigator.clipboard.writeText(texto).then(() => {
            alert("Copiado al portapapeles: " + texto);
        });
    }
</script>