<div class="space-y-6 animate-fade-in-up">

    <!-- Header con fecha y botón Caja Rápida -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard de Operaciones</h1>
            <p class="text-sm text-gray-500">Resumen de cobranza, instalaciones y validaciones pendientes.</p>
        </div>
        <div class="flex gap-3">
            <span
                class="px-4 py-2 bg-white rounded-lg shadow-sm text-sm font-medium text-gray-600 border border-gray-200">
                <i class="far fa-calendar-alt mr-2 text-primary"></i> <?php echo date('d/m/Y'); ?>
            </span>
            <button onclick="openQuickPayModal()" class="btn-primary">
                <i class="fas fa-cash-register"></i> Caja Rápida
            </button>
        </div>
    </div>

    <!-- Gráfico + Tarjetas laterales -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Gráfico de crecimiento -->
        <div class="card-base p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Crecimiento de Red</h3>
                    <p class="text-xs text-gray-500">Nuevos servicios activados este mes</p>
                </div>
                <div class="flex gap-2">
                    <span class="text-xs font-bold bg-blue-50 text-primary px-2 py-1 rounded border border-blue-100">
                        Meta: 50
                    </span>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>

        <!-- Tarjetas de gestión de pagos y cartera -->
        <div class="space-y-6">

            <!-- Gestión de Pagos (RF-V01) -->
            <div class="card-base p-6 border-l-4 border-warning relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-file-invoice-dollar text-6xl text-warning"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gestión de Pagos</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <h3 class="text-3xl font-bold text-gray-800" id="stat_pagos_validar">--</h3>
                        <span class="text-sm text-warning font-medium">Por Validar</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mb-4">Reportados vía App/Web</p>
                    <button onclick="openValidarPagosModal()" class="w-full btn-secondary text-xs py-2 justify-center">
                        <i class="fas fa-check-double mr-2"></i> Validar Pagos (RF-V01)
                    </button>
                </div>
            </div>

            <!-- Cartera en Mora -->
            <div class="card-base p-6 border-l-4 border-danger">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cartera en Mora</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2" id="stat_cartera_mora">S/ --</h3>
                    </div>
                    <div class="p-2 rounded-full bg-red-50 text-danger animate-pulse">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-3 text-xs">
                    <div class="bg-gray-50 p-2 rounded text-center">
                        <span class="block font-bold text-warning" id="stat_suspendidos">--</span>
                        <span class="text-gray-400">Suspendidos</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded text-center">
                        <span class="block font-bold text-danger" id="stat_cortados">--</span>
                        <span class="text-gray-400">Corte Físico</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de servicios (directorio) -->
    <div class="card-base overflow-hidden">

        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg mb-4">Directorio General de Servicios</h3>

            <form id="searchForm" onsubmit="event.preventDefault(); searchServices();"
                class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="label-prored">Búsqueda General</label>
                    <div class="relative">
                        <input type="text" id="searchInput" class="input-prored pl-9"
                            placeholder="Nombre, DNI, Razón Social...">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="md:col-span-4 hidden"></div>

                <div class="md:col-span-3">
                    <label class="label-prored">Estado del Servicio</label>
                    <select id="searchEstado" class="input-prored bg-white">
                        <option value="">Todos</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="SUSPENDIDO">Suspendido</option>
                        <option value="EN_MORA">En Mora</option>
                        <option value="CORTADO">Cortado</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="btn-primary w-full justify-center">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" onclick="clearFilters()" class="btn-outline w-12 justify-center"
                        title="Limpiar filtros">
                        <i class="fas fa-eraser"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Cliente / Razón Social</th>
                        <th class="px-6 py-4">Plan / Velocidad</th>
                        <th class="px-6 py-4">Dirección (Servicio)</th>
                        <th class="px-6 py-4">Deuda</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="servicesTableBody">
                    <!-- Se llena por JS -->
                </tbody>
            </table>
        </div>

        <!-- Paginación simple (placeholder) -->
        <div class="p-4 border-t border-gray-100 flex justify-center bg-gray-50/30">
            <nav class="flex gap-1">
                <button onclick="alert('Página anterior')"
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white text-sm font-bold shadow-md">1</button>
                <button onclick="alert('Ir a página 2')"
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm">2</button>
                <button onclick="alert('Página siguiente')"
                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </nav>
        </div>
    </div>
</div>

<!-- ========== MODALES ========== -->

<!-- Modal de Detalle del Cliente (solo vista) -->
<div id="clientModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col transform transition-all scale-95"
        id="modalPanel">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl">
                    <span id="m_initials">--</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800" id="m_nombre">Cargando...</h3>
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <span id="m_dni"><i class="far fa-id-card mr-1"></i> --</span>
                        <span class="h-4 w-px bg-gray-300"></span>
                        <span id="m_ubicacion"><i class="fas fa-map-marker-alt mr-1"></i> --</span>
                    </div>
                </div>
            </div>
            <button onclick="closeModal('clientModal')"
                class="text-gray-400 hover:text-danger w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-grow bg-gray-50/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Columna 1: Detalles del servicio -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Detalles del Servicio</h4>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-primary/5 rounded-bl-full"></div>
                        <p class="text-xs text-gray-500 mb-1">Plan Contratado</p>
                        <h2 class="text-lg font-bold text-primary" id="m_plan">--</h2>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-bold text-gray-800" id="m_precio">S/ --</span>
                            <span class="text-xs text-gray-500">/ mes</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm space-y-3">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-sm text-gray-600">IP Asignada</span>
                            <span class="text-sm font-mono font-medium text-gray-800" id="m_ip">--</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-sm text-gray-600">Winbox / Nodo</span>
                            <span class="text-sm font-medium text-gray-800" id="m_winbox">--</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Estado</span>
                            <span id="m_estado_badge"
                                class="text-xs font-bold px-2 py-0.5 rounded bg-gray-100">--</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3">Teléfonos de Contacto</p>
                        <div id="m_telefonos" class="space-y-2"></div>
                    </div>
                </div>

                <!-- Columna 2: Facturación y deudas -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Facturación y Deudas</h4>

                    <div id="m_alert_mora" class="hidden bg-red-50 border-l-4 border-danger p-3 rounded-r-md">
                        <div class="flex gap-2">
                            <i class="fas fa-exclamation-circle text-danger mt-1"></i>
                            <div>
                                <p class="text-sm font-bold text-danger">Servicio en Mora</p>
                                <p class="text-xs text-red-700">Debe: <span id="m_total_deuda">S/ 0.00</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-2">Periodo</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="m_tabla_deuda"></tbody>
                        </table>
                    </div>

                    <button onclick="alert('Generando estado de cuenta PDF...')"
                        class="w-full btn-secondary text-sm py-2">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Generar Estado de Cuenta (PDF)
                    </button>
                </div>

                <!-- Columna 3: Últimos eventos -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Últimos Eventos</h4>

                    <div class="relative pl-4 border-l-2 border-gray-200 space-y-6" id="m_timeline">
                        <!-- Los eventos se cargarán dinámicamente -->
                        <div class="relative">
                            <div
                                class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-primary border-2 border-white">
                            </div>
                            <p class="text-xs text-gray-400 mb-0.5">Hoy, 10:30 AM</p>
                            <p class="text-sm font-bold text-gray-800">Pago Validado</p>
                            <p class="text-xs text-gray-600">Transferencia BCP - S/ 100.00</p>
                        </div>
                    </div>

                    <button onclick="alert('Mostrar historial completo...')" class="btn-outline w-full text-xs mt-4">Ver
                        Historial Completo</button>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeModal('clientModal')" class="btn-outline">Cerrar</button>
            <button onclick="openEditClientModalFromView()" class="btn-primary">
                <i class="fas fa-edit mr-1"></i> Editar Cliente / Servicio
            </button>
        </div>
    </div>
</div>

<!-- Modal de Edición de Cliente/Servicio (con pestañas) -->
<div id="editClientModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all scale-95"
        id="modalPanelEdit">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-edit text-primary mr-2"></i> Editar Cliente /
                Servicio</h3>
            <button onclick="closeModal('editClientModal')" class="text-gray-400 hover:text-danger"><i
                    class="fas fa-times"></i></button>
        </div>

        <!-- Pestañas -->
        <div class="border-b border-gray-200 px-6 pt-4">
            <nav class="flex gap-4">
                <button onclick="switchEditTab('tab1')" id="tab1Btn"
                    class="pb-2 px-1 text-sm font-medium border-b-2 border-primary text-primary">Datos del
                    Cliente</button>
                <button onclick="switchEditTab('tab2')" id="tab2Btn"
                    class="pb-2 px-1 text-sm font-medium text-gray-500 hover:text-primary">Datos del Servicio</button>
                <button onclick="switchEditTab('tab3')" id="tab3Btn"
                    class="pb-2 px-1 text-sm font-medium text-gray-500 hover:text-primary">Migrar Plan</button>
                <button onclick="switchEditTab('tab4')" id="tab4Btn"
                    class="pb-2 px-1 text-sm font-medium text-gray-500 hover:text-primary">Personalizar Plan</button>
            </nav>
        </div>

        <div class="p-6 overflow-y-auto flex-grow bg-gray-50/30">
            <!-- TAB 1: Datos del Cliente -->
            <div id="tab1" class="edit-tab space-y-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Información Personal</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Tipo de Documento</label>
                        <select id="edit_tipo_doc" class="input-prored">
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="CE">Carnet Extranjería</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-prored">Número de Documento</label>
                        <input type="text" id="edit_dni" class="input-prored" placeholder="12345678">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-prored">Nombre / Razón Social</label>
                        <input type="text" id="edit_nombre" class="input-prored" placeholder="Juan Pérez">
                    </div>
                    <div>
                        <label class="label-prored">Teléfono</label>
                        <input type="text" id="edit_telefono" class="input-prored" placeholder="987654321">
                    </div>
                    <div>
                        <label class="label-prored">Correo Electrónico</label>
                        <input type="email" id="edit_email" class="input-prored" placeholder="cliente@mail.com">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-prored">Dirección de Instalación</label>
                        <input type="text" id="edit_direccion" class="input-prored"
                            placeholder="Av. La Marina 159, San Miguel">
                    </div>
                    <div>
                        <label class="label-prored">Referencia</label>
                        <input type="text" id="edit_referencia" class="input-prored" placeholder="Cerca al parque">
                    </div>
                    <div>
                        <label class="label-prored">Coordenadas (opcional)</label>
                        <input type="text" id="edit_coordenadas" class="input-prored" placeholder="-12.043, -77.042">
                    </div>
                </div>
            </div>

            <!-- TAB 2: Datos del Servicio -->
            <div id="tab2" class="edit-tab hidden space-y-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Configuración del Servicio
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Plan Actual</label>
                        <input type="text" id="edit_plan_actual" class="input-prored bg-gray-100" readonly>
                    </div>
                    <div>
                        <label class="label-prored">Estado del Servicio</label>
                        <select id="edit_estado" class="input-prored">
                            <option value="ACTIVO">Activo</option>
                            <option value="SUSPENDIDO">Suspendido</option>
                            <option value="CORTADO">Cortado</option>
                            <option value="EN_MORA">En Mora</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-prored">IP Asignada</label>
                        <input type="text" id="edit_ip" class="input-prored" placeholder="192.168.1.100">
                    </div>
                    <div>
                        <label class="label-prored">Winbox / Nodo</label>
                        <input type="text" id="edit_winbox" class="input-prored" placeholder="Nodo 3 - Puerto 5">
                    </div>
                    <div>
                        <label class="label-prored">MAC Address (opcional)</label>
                        <input type="text" id="edit_mac" class="input-prored" placeholder="AA:BB:CC:DD:EE:FF">
                    </div>
                    <div>
                        <label class="label-prored">Velocidad Contratada (Mbps)</label>
                        <input type="number" id="edit_velocidad" class="input-prored" placeholder="100">
                    </div>
                </div>
            </div>

            <!-- TAB 3: Migrar Plan -->
            <div id="tab3" class="edit-tab hidden space-y-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Seleccionar Nuevo Plan</h4>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="label-prored">Plan Destino</label>
                        <select id="edit_nuevo_plan" class="input-prored">
                            <option value="">-- Seleccione un plan --</option>
                            <option value="1">Plan Básico 50 Mbps + TV</option>
                            <option value="2">Plan Gamer 200 Mbps</option>
                            <option value="3">Plan Pyme 300 Mbps</option>
                            <option value="4">Plan Personalizado</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-prored">Precio del Nuevo Plan (S/)</label>
                        <input type="number" id="edit_nuevo_precio" class="input-prored" placeholder="89.90"
                            step="0.01">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="edit_prorratear" class="rounded border-gray-300">
                        <label for="edit_prorratear" class="text-sm text-gray-600">Aplicar prorrateo por días
                            restantes</label>
                    </div>
                    <div>
                        <label class="label-prored">Fecha de activación</label>
                        <input type="date" id="edit_fecha_activacion" class="input-prored">
                    </div>
                </div>
            </div>

            <!-- TAB 4: Personalizar Plan -->
            <div id="tab4" class="edit-tab hidden space-y-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Configuración Personalizada
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Velocidad de Bajada (Mbps)</label>
                        <input type="number" id="edit_custom_bajada" class="input-prored" placeholder="100">
                    </div>
                    <div>
                        <label class="label-prored">Velocidad de Subida (Mbps)</label>
                        <input type="number" id="edit_custom_subida" class="input-prored" placeholder="100">
                    </div>
                    <div>
                        <label class="label-prored">Canales de TV</label>
                        <input type="text" id="edit_custom_tv" class="input-prored"
                            placeholder="Básico + 10 canales HD">
                    </div>
                    <div>
                        <label class="label-prored">Precio Personalizado (S/)</label>
                        <input type="number" id="edit_custom_precio" class="input-prored" placeholder="129.90"
                            step="0.01">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-prored">Observaciones</label>
                        <textarea id="edit_observaciones" class="input-prored" rows="2"
                            placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end gap-3">
            <button onclick="closeModal('editClientModal')" class="btn-outline">Cancelar</button>
            <button onclick="saveClientChanges()" class="btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
        </div>
    </div>
</div>

<!-- Modal Caja Rápida -->
<div id="quickPayModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-95"
        id="modalPanelQuickPay">
        <div
            class="bg-gradient-to-r from-green-600 to-green-700 p-5 rounded-t-xl flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fas fa-cash-register mr-2"></i> Caja Rápida</h3>
            <button onclick="closeModal('quickPayModal')" class="hover:text-green-100"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="p-6">
            <!-- STEP 1: BÚSQUEDA -->
            <div id="qp_step_search">
                <label class="label-prored mb-2">Buscar Cliente (DNI, RUC o Nombre)</label>
                <div class="relative">
                    <input type="text" id="qp_search" class="input-prored pl-10 text-lg"
                        placeholder="Ingrese DNI (3+ carácteres)..." onkeyup="searchClientPayment(this.value)">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>

                <div id="qp_results" class="mt-4 max-h-60 overflow-y-auto space-y-2 hidden">
                    <!-- Resultados poblados por JS -->
                </div>
            </div>

            <!-- STEP 2: DETALLE Y PAGO -->
            <div id="qp_step_pay" class="hidden space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg" id="qp_client_name">Cliente</h4>
                        <p class="text-sm text-gray-600" id="qp_client_details">DNI - Plan</p>
                        <p class="text-xs text-gray-500 mt-1" id="qp_client_address">Dirección</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-gray-400 uppercase">Total Deuda</span>
                        <span class="block text-2xl font-bold text-danger" id="qp_total_debt">S/ 0.00</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-red-100 text-red-700 mt-1 inline-block"
                            id="qp_months_debt">0 Meses</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Monto a Pagar *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">S/</span>
                            <input type="number" id="qp_amount" class="input-prored pl-8 font-bold text-lg" step="0.01"
                                readonly>
                        </div>
                    </div>
                    <div>
                        <label class="label-prored">Método de Pago</label>
                        <select id="qp_method" class="input-prored">
                            <option value="1">Efectivo</option>
                            <option value="2">Transferencia</option>
                            <option value="3">Yape / Plin</option>
                            <option value="4">Tarjeta</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-prored">Nro. Operación (Opcional)</label>
                        <input type="text" id="qp_operation" class="input-prored" placeholder="Ej. 123456">
                    </div>
                    <div>
                        <label class="label-prored">Banco (Opcional)</label>
                        <select id="qp_bank" class="input-prored">
                            <option value="">-- Seleccionar --</option>
                            <option value="BCP">BCP</option>
                            <option value="INTERBANK">Interbank</option>
                            <option value="BBVA">BBVA</option>
                            <option value="SCOTIABANK">Scotiabank</option>
                            <option value="NACION">Banco de la Nación</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" id="qp_id_deuda">
                <input type="hidden" id="qp_id_servicio">
                <input type="hidden" id="qp_id_cliente">
                <input type="hidden" id="qp_telefono_cliente">

                <div class="flex gap-3 pt-2">
                    <button onclick="qpReset()" class="btn-outline w-1/3 justify-center">Atrás</button>
                    <button onclick="processQuickPayment()"
                        class="btn-primary w-2/3 justify-center bg-green-600 hover:bg-green-700 border-green-600">
                        <i class="fas fa-check-circle mr-2"></i> Registrar Pago
                    </button>
                </div>
            </div>

            <!-- STEP 3: ÉXITO -->
            <div id="qp_step_success" class="hidden text-center py-6">
                <div
                    class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">¡Pago Registrado!</h3>
                <p class="text-gray-600 mb-6">El pago se ha procesado correctamente.</p>

                <div class="flex flex-col gap-3 max-w-xs mx-auto">
                    <button onclick="printReceipt()" class="btn-primary justify-center">
                        <i class="fas fa-print mr-2"></i> Imprimir Comprobante
                    </button>
                    <button onclick="sendWhatsappReceipt()"
                        class="btn-secondary justify-center bg-green-500 border-green-500 hover:bg-green-600 text-white">
                        <i class="fab fa-whatsapp mr-2"></i> Enviar por WhatsApp
                    </button>
                    <button onclick="qpReset(true)" class="btn-outline justify-center mt-2">
                        Nuevo Pago
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Validar Pagos (RF-V01) -->
<div id="validarPagosModal"
    class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl transform transition-all scale-95"
        id="modalPanelValidar">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
            <h3 class="font-bold text-gray-800 text-lg"><i class="fas fa-check-double text-secondary mr-2"></i> Validar
                Pagos Reportados</h3>
            <button onclick="closeModal('validarPagosModal')" class="text-gray-400 hover:text-danger"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="p-6 max-h-96 overflow-y-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-2">Cliente</th>
                        <th class="px-4 py-2">Monto</th>
                        <th class="px-4 py-2">Método</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tablaPagosPendientes">
                    <!-- Se llenará dinámicamente -->
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end">
            <button onclick="closeModal('validarPagosModal')" class="btn-outline">Cerrar</button>
        </div>
    </div>
</div>

<script>
    const controllerUrl = 'http://localhost/web-prored/sis-prored/app/controllers/VentasController.php';
    let lastPaymentId = null;
    let lastPaymentPhone = null;
    let currentEditingId = null; // Almacena el id_servicio que se está editando

    document.addEventListener('DOMContentLoaded', () => {
        loadDashboard();
        searchServices();
    });

    // ========== DASHBOARD ==========
    async function loadDashboard() {
        try {
            const response = await fetch(`${controllerUrl}?op=dashboard`);
            const data = await response.json();

            if (data.stats) {
                document.getElementById('stat_pagos_validar').innerText = data.stats.pagos_por_validar;
                document.getElementById('stat_suspendidos').innerText = data.stats.suspendidos;
                document.getElementById('stat_cortados').innerText = data.stats.cortados;
                document.getElementById('stat_cartera_mora').innerText = 'S/ ' + parseFloat(data.stats.cartera_mora).toFixed(2);
            }

            if (data.chart) {
                renderChart(data.chart);
            }
        } catch (e) {
            console.error('Error loading dashboard:', e);
        }
    }

    function renderChart(chartData) {
        const ctx = document.getElementById('ventasChart');
        if (!ctx) return;

        const labels = chartData.map(d => d.mes);
        const values = chartData.map(d => d.total);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['Sin datos'],
                datasets: [{
                    label: 'Nuevos Servicios',
                    data: values.length ? values : [0],
                    borderColor: '#005FA2',
                    backgroundColor: 'rgba(0, 95, 162, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ========== BÚSQUEDA DE SERVICIOS (TABLA) ==========
    async function searchServices() {
        const search = document.getElementById('searchInput').value;
        const estado = document.getElementById('searchEstado').value;

        const tbody = document.getElementById('servicesTableBody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Cargando...</td></tr>';

        try {
            const response = await fetch(`${controllerUrl}?op=search&search=${encodeURIComponent(search)}&estado=${estado}`);
            const data = await response.json();

            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-400">No se encontraron servicios</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(s => `
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                ${s.nombre_cliente.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">${s.nombre_cliente}</p>
                                <p class="text-xs text-gray-400">${s.dni}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-800 font-medium">${s.nombre_plan}</p>
                        <p class="text-xs text-gray-500">${s.velocidad_bajada}/${s.velocidad_bajada} Mbps</p>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">${s.direccion}</td>
                    <td class="px-6 py-4">
                        <span class="${s.deuda_total > 0 ? 'text-danger font-bold' : 'text-success font-bold'}">
                            S/ ${parseFloat(s.deuda_total).toFixed(2)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClasses(s.estado)}">
                            ${s.estado}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="openServiceModal(${s.id_servicio})" class="text-primary hover:text-primary-dark transition-colors" title="Ver detalles">
                            <i class="fas fa-eye text-lg"></i>
                        </button>
                        <button onclick="openEditClientModal(${s.id_servicio})" class="text-secondary hover:text-orange-600 transition-colors" title="Editar">
                            <i class="fas fa-edit text-lg"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (e) {
            console.error(e);
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Error al cargar datos</td></tr>';
        }
    }

    function getStatusClasses(status) {
        switch (status) {
            case 'ACTIVO': return 'bg-success/10 text-success border border-success/20';
            case 'EN_MORA': return 'bg-warning/10 text-warning border border-warning/20';
            case 'SUSPENDIDO': return 'bg-orange-100 text-orange-700';
            case 'CORTADO': return 'bg-red-100 text-red-700';
            default: return 'bg-gray-100 text-gray-700';
        }
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchEstado').value = '';
        searchServices();
    }

    // ========== MODAL DETALLE DEL CLIENTE (solo vista) ==========
    async function openServiceModal(id) {
        // Reset campos
        document.getElementById('m_nombre').textContent = 'Cargando...';
        document.getElementById('m_dni').innerHTML = '<i class="far fa-id-card mr-1"></i> --';
        document.getElementById('m_ubicacion').innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i> --';
        document.getElementById('m_plan').textContent = '--';
        document.getElementById('m_precio').textContent = 'S/ --';
        document.getElementById('m_ip').textContent = '--';
        document.getElementById('m_winbox').textContent = '--';
        document.getElementById('m_estado_badge').textContent = '--';
        document.getElementById('m_telefonos').innerHTML = '';
        document.getElementById('m_tabla_deuda').innerHTML = '';
        document.getElementById('m_alert_mora').classList.add('hidden');
        document.getElementById('m_initials').textContent = '--';

        openModal('clientModal', 'modalPanel');

        try {
            const res = await fetch(`${controllerUrl}?op=get_service_full_details&id=${id}`);
            if (!res.ok) throw new Error("Error al cargar detalles");
            const s = await res.json();

            const nombre = s.razon_social || `${s.nombres} ${s.apellidos}`;
            document.getElementById('m_nombre').textContent = nombre;
            document.getElementById('m_dni').innerHTML = `<i class="far fa-id-card mr-1"></i> ${s.dni}`;
            document.getElementById('m_ubicacion').innerHTML = `<i class="fas fa-map-marker-alt mr-1"></i> ${s.direccion}`;
            document.getElementById('m_initials').textContent = nombre.substring(0, 2).toUpperCase();

            document.getElementById('m_plan').textContent = s.nombre_plan || 'Sin Plan';
            document.getElementById('m_precio').textContent = `S/ ${parseFloat(s.precio_plan || 0).toFixed(2)}`;
            document.getElementById('m_ip').textContent = s.ip_asignada || 'No asignada';
            document.getElementById('m_winbox').textContent = s.winbox || '--';

            const badge = document.getElementById('m_estado_badge');
            badge.textContent = s.estado;
            badge.className = `text-xs font-bold px-2 py-0.5 rounded ${getStatusClasses(s.estado)}`;

            if (s.telefono) {
                document.getElementById('m_telefonos').innerHTML = `
                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">${s.telefono}</p>
                            <p class="text-xs text-gray-400">Principal</p>
                        </div>
                    </div>`;
            }

            const tbody = document.getElementById('m_tabla_deuda');
            if (s.deudas && s.deudas.length > 0) {
                s.deudas.forEach(d => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="px-4 py-2 text-gray-800">${d.periodo}</td>
                            <td class="px-4 py-2 text-right font-medium text-gray-800">S/ ${parseFloat(d.total).toFixed(2)}</td>
                            <td class="px-4 py-2 text-center text-xs">
                                <span class="${d.estado == 'PAGADO' ? 'text-success' : 'text-danger'} font-bold">${d.estado}</span>
                            </td>
                        </tr>
                    `;
                });

                if (s.deuda_total > 0) {
                    document.getElementById('m_alert_mora').classList.remove('hidden');
                    document.getElementById('m_total_deuda').textContent = `S/ ${parseFloat(s.deuda_total).toFixed(2)}`;
                }
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-2 text-gray-400">Sin historial de deudas</td></tr>';
            }

            // Guardar el id del servicio para usarlo en edición
            currentEditingId = id;
        } catch (e) {
            console.error(e);
            alert("Error: " + e.message);
            closeModal('clientModal');
        }
    }

    // ========== MODAL DE EDICIÓN ==========
    function openEditClientModal(id) {
        currentEditingId = id;
        // Aquí se cargarían los datos del cliente/servicio desde el backend
        // Simulamos carga con datos de ejemplo
        fetch(`${controllerUrl}?op=get_service_full_details&id=${id}`)
            .then(res => res.json())
            .then(s => {
                // Llenar campos del formulario con los datos obtenidos
                document.getElementById('edit_tipo_doc').value = s.tipo_documento || 'DNI';
                document.getElementById('edit_dni').value = s.dni || '';
                document.getElementById('edit_nombre').value = s.razon_social || `${s.nombres} ${s.apellidos}`;
                document.getElementById('edit_telefono').value = s.telefono || '';
                document.getElementById('edit_email').value = s.email || '';
                document.getElementById('edit_direccion').value = s.direccion || '';
                document.getElementById('edit_referencia').value = s.referencia || '';
                document.getElementById('edit_coordenadas').value = s.coordenadas || '';
                document.getElementById('edit_plan_actual').value = s.nombre_plan || '';
                document.getElementById('edit_estado').value = s.estado || 'ACTIVO';
                document.getElementById('edit_ip').value = s.ip_asignada || '';
                document.getElementById('edit_winbox').value = s.winbox || '';
                document.getElementById('edit_mac').value = s.mac || '';
                document.getElementById('edit_velocidad').value = s.velocidad_bajada || '';

                // Abrir el modal
                openModal('editClientModal', 'modalPanelEdit');
            })
            .catch(e => {
                console.error(e);
                alert("No se pudieron cargar los datos para edición.");
            });
    }

    function openEditClientModalFromView() {
        if (currentEditingId) {
            openEditClientModal(currentEditingId);
        } else {
            alert("No hay un servicio seleccionado.");
        }
    }

    function switchEditTab(tabId) {
        // Ocultar todos los tabs
        document.querySelectorAll('.edit-tab').forEach(tab => tab.classList.add('hidden'));
        // Mostrar el seleccionado
        document.getElementById(tabId).classList.remove('hidden');
        // Actualizar estilos de los botones de pestaña
        ['tab1Btn', 'tab2Btn', 'tab3Btn', 'tab4Btn'].forEach(btnId => {
            const btn = document.getElementById(btnId);
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('text-gray-500');
        });
        const activeBtn = document.getElementById(tabId + 'Btn');
        activeBtn.classList.add('border-primary', 'text-primary');
        activeBtn.classList.remove('text-gray-500');
    }

    function saveClientChanges() {
        // Aquí se enviarían los datos al backend
        const data = {
            id_servicio: currentEditingId,
            cliente: {
                tipo_doc: document.getElementById('edit_tipo_doc').value,
                dni: document.getElementById('edit_dni').value,
                nombre: document.getElementById('edit_nombre').value,
                telefono: document.getElementById('edit_telefono').value,
                email: document.getElementById('edit_email').value,
                direccion: document.getElementById('edit_direccion').value,
                referencia: document.getElementById('edit_referencia').value,
                coordenadas: document.getElementById('edit_coordenadas').value
            },
            servicio: {
                estado: document.getElementById('edit_estado').value,
                ip: document.getElementById('edit_ip').value,
                winbox: document.getElementById('edit_winbox').value,
                mac: document.getElementById('edit_mac').value,
                velocidad: document.getElementById('edit_velocidad').value
            },
            migracion: {
                nuevo_plan: document.getElementById('edit_nuevo_plan').value,
                nuevo_precio: document.getElementById('edit_nuevo_precio').value,
                prorratear: document.getElementById('edit_prorratear').checked,
                fecha_activacion: document.getElementById('edit_fecha_activacion').value
            },
            personalizacion: {
                bajada: document.getElementById('edit_custom_bajada').value,
                subida: document.getElementById('edit_custom_subida').value,
                tv: document.getElementById('edit_custom_tv').value,
                precio: document.getElementById('edit_custom_precio').value,
                observaciones: document.getElementById('edit_observaciones').value
            }
        };

        console.log('Guardando cambios:', data);

        // Simular envío exitoso
        alert('Cambios guardados correctamente (simulado).');
        closeModal('editClientModal');
        // Recargar datos del servicio si es necesario
        if (currentEditingId) {
            openServiceModal(currentEditingId);
        }
    }

    // ========== CAJA RÁPIDA ==========
    function openQuickPayModal() {
        qpReset();
        openModal('quickPayModal', 'modalPanelQuickPay');
    }

    function qpReset(full = false) {
        if (full) {
            document.getElementById('qp_search').value = '';
            document.getElementById('qp_results').innerHTML = '';
            document.getElementById('qp_results').classList.add('hidden');
        }
        document.getElementById('qp_step_search').classList.remove('hidden');
        document.getElementById('qp_step_pay').classList.add('hidden');
        document.getElementById('qp_step_success').classList.add('hidden');

        document.getElementById('qp_amount').value = '';
        document.getElementById('qp_operation').value = '';
        document.getElementById('qp_bank').value = '';
        document.getElementById('qp_telefono_cliente').value = '';
    }

    let searchTimeout;
    function searchClientPayment(term) {
        clearTimeout(searchTimeout);
        if (term.length < 3) {
            document.getElementById('qp_results').classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`${controllerUrl}?op=search_client_payment&term=${term}`);
                const data = await res.json();

                const resultsDiv = document.getElementById('qp_results');
                resultsDiv.innerHTML = '';
                resultsDiv.classList.remove('hidden');

                if (data.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-sm text-gray-500 p-2">No se encontraron clientes.</p>';
                    return;
                }

                data.forEach(c => {
                    const el = document.createElement('div');
                    el.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 flex justify-between items-center';
                    el.innerHTML = `
                        <div>
                            <p class="font-bold text-gray-800">${c.nombre_completo}</p>
                            <p class="text-xs text-gray-500">${c.dni} - ${c.nombre_plan || 'Sin Plan'}</p>
                        </div>
                        <div class="text-right">
                             <span class="block font-bold ${c.deuda_total > 0 ? 'text-danger' : 'text-success'}">S/ ${parseFloat(c.deuda_total).toFixed(2)}</span>
                             <span class="text-xs text-gray-400">${c.estado_servicio}</span>
                        </div>
                    `;
                    el.onclick = () => selectClientPayment(c);
                    resultsDiv.appendChild(el);
                });
            } catch (e) {
                console.error(e);
            }
        }, 500);
    }

    async function selectClientPayment(client) {
        document.getElementById('qp_id_cliente').value = client.id_cliente;
        document.getElementById('qp_id_servicio').value = client.id_servicio;
        document.getElementById('qp_id_deuda').value = client.id_deuda_antigua || '';
        document.getElementById('qp_telefono_cliente').value = client.telefono || '';

        document.getElementById('qp_client_name').textContent = client.nombre_completo;
        document.getElementById('qp_client_details').textContent = `${client.dni} - ${client.nombre_plan}`;
        document.getElementById('qp_client_address').textContent = client.direccion || 'Sin dirección';

        const amountInput = document.getElementById('qp_amount');
        amountInput.readOnly = true;

        if (client.deuda_total > 0) {
            document.getElementById('qp_total_debt').textContent = 'S/ ' + parseFloat(client.deuda_total).toFixed(2);
            document.getElementById('qp_months_debt').textContent = client.meses_deuda + ' Meses';
            amountInput.value = client.deuda_total;
        } else {
            document.getElementById('qp_total_debt').textContent = 'S/ 0.00';
            document.getElementById('qp_months_debt').textContent = 'Al día';
            amountInput.value = 'Calculando...';

            try {
                const res = await fetch(`${controllerUrl}?op=get_payment_details&id_servicio=${client.id_servicio}`);
                const details = await res.json();

                if (details.total) {
                    amountInput.value = parseFloat(details.total).toFixed(2);
                    if (details.total_cargos > 0) {
                        document.getElementById('qp_months_debt').textContent = `Plan + ${details.cargos.length} Cargos`;
                    } else {
                        document.getElementById('qp_months_debt').textContent = `Adelanto Mes`;
                    }
                } else {
                    amountInput.value = '0.00';
                    alert("Error al calcular el monto del servicio.");
                }
            } catch (e) {
                console.error(e);
                amountInput.value = 'Error';
            }
        }

        document.getElementById('qp_step_search').classList.add('hidden');
        document.getElementById('qp_step_pay').classList.remove('hidden');
    }

    async function processQuickPayment() {
        const amountStr = document.getElementById('qp_amount').value;
        if (amountStr === 'Calculando...' || amountStr === 'Error') return alert("Espere a que se calcule el monto correctamente.");

        const amount = parseFloat(amountStr);
        const method = document.getElementById('qp_method').value;
        const id_deuda = document.getElementById('qp_id_deuda').value;

        if (!amount || amount <= 0) return alert("Monto inválido para procesar.");

        const payload = {
            id_deuda: id_deuda,
            id_metodo_pago: method,
            id_servicio: document.getElementById('qp_id_servicio').value,
            monto: amount,
            numero_operacion: document.getElementById('qp_operation').value,
            banco: document.getElementById('qp_bank').value
        };

        try {
            const res = await fetch(`${controllerUrl}?op=register_quick_payment`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();

            if (result.success) {
                lastPaymentId = result.pago_id;
                lastPaymentPhone = document.getElementById('qp_telefono_cliente').value;
                document.getElementById('qp_step_pay').classList.add('hidden');
                document.getElementById('qp_step_success').classList.remove('hidden');
                loadDashboard();
            } else {
                alert("Error: " + (result.error || 'Desconocido'));
            }
        } catch (e) {
            console.error(e);
            alert("Error de conexión");
        }
    }

    function printReceipt() {
        if (!lastPaymentId) return;
        window.open(`../views/print_receipt.php?id=${lastPaymentId}`, '_blank');
    }

    function sendWhatsappReceipt() {
        if (!lastPaymentId) return;
        let phone = lastPaymentPhone || '';
        let url = phone ? `https://wa.me/${phone}?text=Hola, su comprobante de pago está disponible.` : `https://wa.me/?text=Hola, su comprobante de pago está disponible.`;
        window.open(url, '_blank');
    }

    // ========== MODAL VALIDAR PAGOS (RF-V01) ==========
    function openValidarPagosModal() {
        // Cargar pagos pendientes desde el backend
        fetch(`${controllerUrl}?op=pagos_pendientes`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tablaPagosPendientes');
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">No hay pagos pendientes</td></tr>';
                } else {
                    tbody.innerHTML = data.map(p => `
                        <tr>
                            <td class="px-4 py-3">${p.cliente}</td>
                            <td class="px-4 py-3">S/ ${parseFloat(p.monto).toFixed(2)}</td>
                            <td class="px-4 py-3">${p.metodo}</td>
                            <td class="px-4 py-3">${p.fecha}</td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="validarPago(${p.id})" class="bg-success text-white text-xs px-3 py-1 rounded hover:bg-green-600 transition-colors">Validar</button>
                            </td>
                        </tr>
                    `).join('');
                }
                openModal('validarPagosModal', 'modalPanelValidar');
            })
            .catch(e => {
                console.error(e);
                alert("Error al cargar pagos pendientes");
            });
    }

    function validarPago(idPago) {
        fetch(`${controllerUrl}?op=validar_pago`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pago: idPago })
        })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Pago validado correctamente');
                    openValidarPagosModal(); // Recargar la lista
                    loadDashboard(); // Actualizar contador
                } else {
                    alert('Error: ' + result.error);
                }
            })
            .catch(e => {
                console.error(e);
                alert('Error de conexión');
            });
    }

    // ========== UTILIDADES DE MODALES ==========
    function openModal(modalId, panelId) {
        const modal = document.getElementById(modalId);
        const panel = document.getElementById(panelId);

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = modal.querySelector('div[class*="transform"]');

        modal.classList.add('opacity-0');
        if (panel) {
            panel.classList.remove('scale-100');
            panel.classList.add('scale-95');
        }
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Exponer funciones globalmente
    window.openQuickPayModal = openQuickPayModal;
    window.clearFilters = clearFilters;
    window.searchServices = searchServices;
    window.openServiceModal = openServiceModal;
    window.openEditClientModal = openEditClientModal;
    window.openEditClientModalFromView = openEditClientModalFromView;
    window.closeModal = closeModal;
    window.openValidarPagosModal = openValidarPagosModal;
    window.validarPago = validarPago;
    window.qpReset = qpReset;
    window.searchClientPayment = searchClientPayment;
    window.processQuickPayment = processQuickPayment;
    window.printReceipt = printReceipt;
    window.sendWhatsappReceipt = sendWhatsappReceipt;
    window.switchEditTab = switchEditTab;
    window.saveClientChanges = saveClientChanges;
</script>