<div class="space-y-6 animate-fade-in-up">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Mi Perfil</h1>
        <p class="text-sm text-gray-500">Administra tu información personal y seguridad de la cuenta.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="space-y-6 lg:col-span-1">

            <div class="card-base p-8 flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-primary to-primary-dark opacity-10">
                </div>

                <div class="relative z-10 w-32 h-32 rounded-full bg-white p-1 shadow-lg mb-4 group cursor-pointer">
                    <div
                        class="w-full h-full rounded-full bg-primary/10 flex items-center justify-center text-4xl font-bold text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        MG
                    </div>
                    <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-white"
                        title="Usuario Activo"></div>
                </div>

                <h2 class="text-xl font-bold text-gray-800">María González</h2>
                <span
                    class="inline-block bg-secondary/10 text-secondary text-xs font-bold px-3 py-1 rounded-full mt-1 mb-6">
                    Ejecutiva de Ventas
                </span>

                <div class="w-full space-y-4 text-left border-t border-gray-100 pt-6">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Correo Electrónico</p>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-envelope w-6 text-center mr-2 text-primary"></i>
                            maria.gonzalez@prored.pe
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">ID Usuario</p>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-id-badge w-6 text-center mr-2 text-primary"></i>
                            #USR-004
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Fecha de Registro</p>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-calendar-alt w-6 text-center mr-2 text-primary"></i>
                            15 Ene, 2025
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-base p-6 bg-blue-50 border border-blue-100">
                <h3 class="text-sm font-bold text-blue-800 mb-3"><i class="fas fa-chart-pie mr-2"></i> Mi Rendimiento
                    (Mes)</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Ventas Realizadas</span>
                        <span class="font-bold">12</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Pagos Cobrados</span>
                        <span class="font-bold">S/ 4,250</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="card-base p-6">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-primary"></i> Seguridad y Credenciales
                    </h3>
                    <p class="text-sm text-gray-500">Actualiza tu contraseña periódicamente para mantener tu cuenta
                        segura.</p>
                </div>

                <form onsubmit="updatePassword(event)" class="space-y-5 max-w-lg">

                    <div>
                        <label class="label-prored">Contraseña Actual</label>
                        <div class="relative">
                            <input type="password" id="currentPass" class="input-prored pr-10"
                                placeholder="Ingrese su contraseña actual" required>
                            <button type="button" onclick="togglePass('currentPass', this)"
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-primary focus:outline-none">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label-prored">Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" id="newPass" class="input-prored pr-10"
                                    placeholder="Mínimo 6 caracteres" required minlength="6">
                                <button type="button" onclick="togglePass('newPass', this)"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-primary focus:outline-none">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="label-prored">Confirmar Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" id="confirmPass" class="input-prored pr-10"
                                    placeholder="Repita la contraseña" required minlength="6">
                                <button type="button" onclick="togglePass('confirmPass', this)"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-primary focus:outline-none">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 space-y-1 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="font-bold text-gray-700 mb-1">Requisitos de seguridad:</p>
                        <p><i class="fas fa-check text-green-500 mr-1"></i> Mínimo 6 caracteres</p>
                        <p><i class="fas fa-check text-green-500 mr-1"></i> Al menos un número recomendado</p>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="btn-primary px-8">
                            <i class="fas fa-save mr-2"></i> Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-base overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-sm">
                        <i class="fas fa-history text-gray-500 mr-2"></i> Actividad Reciente de Inicio de Sesión
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-white text-gray-400 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3">Dispositivo / Navegador</th>
                                <th class="px-5 py-3">Dirección IP</th>
                                <th class="px-5 py-3">Fecha y Hora</th>
                                <th class="px-5 py-3 text-right">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr>
                                <td class="px-5 py-3 flex items-center gap-2">
                                    <i class="fas fa-desktop text-gray-400"></i> Windows 10 - Chrome
                                </td>
                                <td class="px-5 py-3">192.168.1.45</td>
                                <td class="px-5 py-3 text-green-600 font-bold">Ahora mismo</td>
                                <td class="px-5 py-3 text-right"><span
                                        class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">Actual</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 flex items-center gap-2">
                                    <i class="fas fa-mobile-alt text-gray-400"></i> Android - App ProRed
                                </td>
                                <td class="px-5 py-3">181.65.20.10</td>
                                <td class="px-5 py-3">Ayer, 18:30 PM</td>
                                <td class="px-5 py-3 text-right"><span class="text-gray-400">Cerrada</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // --- Lógica de Interfaz ---

    // 1. Alternar visibilidad de contraseña
    function togglePass(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // 2. Simulación de actualización
    function updatePassword(e) {
        e.preventDefault();

        const current = document.getElementById('currentPass').value;
        const newP = document.getElementById('newPass').value;
        const confirmP = document.getElementById('confirmPass').value;

        // Validaciones simples
        if (newP !== confirmP) {
            showToast('Las nuevas contraseñas no coinciden.', 'error');
            return;
        }

        if (newP === current) {
            showToast('La nueva contraseña no puede ser igual a la actual.', 'warning');
            return;
        }

        // Simulación de éxito
        // Aquí iría la llamada AJAX a tu backend (update usuario set password = ...)
        showToast('Contraseña actualizada correctamente.', 'success');
        e.target.reset();
    }

    // Función Toast reutilizable (Si no la tienes global, aquí una simple)
    function showToast(msg, type = 'success') {
        const colors = {
            success: 'bg-green-600',
            error: 'bg-red-600',
            warning: 'bg-yellow-500'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle'
        };

        const toast = document.createElement('div');
        toast.className = `fixed top-5 right-5 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl z-50 flex items-center gap-3 animate-bounce`;
        toast.innerHTML = `<i class="fas ${icons[type]} text-xl"></i> <span class="font-medium">${msg}</span>`;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>