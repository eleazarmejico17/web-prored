# Prompts de Migración a Laravel (Dashboards por Rol)

A continuación, se presentan los prompts detallados para recrear cada uno de los dashboards del sistema ISP/NOC en Laravel, basándonos en la lógica visual del sistema anterior en PHP vanilla.

**Contexto General (Entrégale esto a la IA antes o junto a los prompts):**
> Actúa como un experto desarrollador frontend en Laravel. Tu tarea es construir vistas (dashboards) utilizando Tailwind CSS puro y componentes de Blade. 
> Reglas estrictas:
> 1. Todas las vistas extienden de `layouts.app` (`@extends('layouts.app')` y `@section('content')`).
> 2. No incluyas la etiqueta `<html>`, `<head>`, ni el `<aside>` del sidebar o navbars en la vista del dashboard, ya que están en el layout.
> 3. Utiliza diseño moderno tipo panel administrativo ISP / NOC (colores: primario azul `#005FA2`, secundario naranja `#E58E21`, fondos gris claro, bordes suaves, sombras `shadow-sm` a `shadow-md`, esquinas redondeadas `rounded-xl`).
> 4. NO incluyas lógica backend real (ni `auth()`, ni variables de BD reales, ni directivas que rompan si no hay datos). Usa arreglos simulados (`$mockData` en PHP dentro de la vista usando `@php ... @endphp`) o texto quemado realista ("Cliente Demo", "Fibra 300 Mbps").
> 5. Asegúrate de incluir íconos usando FontAwesome (`fas fa-wifi`, etc.).

---

## 1. Prompt - Dashboard Administrador (Super Admin)

**Prompt:**
Crea la vista principal del Dashboard para el rol **Administrador** en Laravel Blade (`resources/views/admin/dashboard.blade.php`).

**Requisitos visuales y de estructura:**
1. **Encabezado interno:** Título "Resumen General - Super Admin" y un botón primario "Generar Reporte Mensual".
2. **Kpis (Tarjetas superiores):** Muestra 4 tarjetas de estadísticas usando un grid.
   - Total Usuarios Activos (ej. 1,245) con ícono de usuarios.
   - Ingresos del Mes (ej. S/ 45,200) con ícono de dinero/gráfico.
   - Tickets Abiertos (ej. 12 críticos) con ícono de alerta en rojo.
   - Nodos Caídos / Alertas de Red (ej. 0) con color verde de éxito.
3. **Gráfico simulado:** Un contenedor central ancho que diga "Crecimiento de Red y Facturación (Simulación Chart.js)" con un placeholder de canvas.
4. **Tabla de Auditoría Reciente:** Una tabla limpia con Tailwind que muestre los últimos 5 movimientos del sistema (Ej. "Usuario X modificó plan de Cliente Y", fecha, IP, estado).
5. Usa colores corporativos (Azul primario y Naranja secundario) en botones y bordes decorativos.

---

## 2. Prompt - Dashboard Cliente (User / Mi Portal)

**Prompt:**
Crea la vista principal del Dashboard para el rol **Cliente** en Laravel Blade (`resources/views/cliente/dashboard.blade.php`).

**Requisitos visuales y de estructura:**
1. **Encabezado:** Saludo dinámico (ej. "Buenos días, Carlos Demo 👋") y un botón para "Reportar Avería".
2. **Tarjeta Principal del Servicio (Hero Card):** 
   - Debe destacar con fondo blanco y un pequeño borde azul izquierdo. 
   - Mostrar el Plan Actual (Ej. "Fibra Óptica 100 Mbps"), Dirección de Instalación, Velocidad, y un Badge (etiqueta) verde indicando "ACTIVO".
3. **Facturación del Mes (Grid lateral o inferior a la Principal):**
   - Resumen del mes actual: Mensualidad Base, Cargos Extra (si los hay), Total a Pagar gigante.
   - Fecha de vencimiento ("Vence el 05 de cada mes") o mensaje de "Vencido" en rojo.
   - Dos botones: "Pagar / Reportar Pago" (primario) y "Descargar Recibo" (outline).
4. **Métricas de Consumo:** Una barra de progreso de Tailwind que simule el consumo del mes (ej. "324 GB consumidos") indicando que es Ilimitado.
5. **Widgets extra:**
   - Lista de "TVs Registradas" (Ej. Deco Sala, Deco Dormitorio) con íconos de TV.
   - Tabla pequeña de "Últimos Pagos" (Fecha, Método, Monto, Estado "Validado").
6. **Banner Promocional:** Un banner moderno interactivo (ej. Gradiente de fondo) ofreciendo "Mejorar la velocidad / Upgrade".

---

## 3. Prompt - Dashboard Soporte Técnico (Nivel 2)

**Prompt:**
Crea la vista principal del Dashboard para el rol **Soporte Técnico** en Laravel Blade (`resources/views/soporte/dashboard.blade.php`).

**Requisitos visuales y de estructura:**
1. **Encabezado:** "Dashboard de Soporte Técnico" con un badge de la fecha actual y botón "+ Nuevo Ticket".
2. **KPIS de Gestión:** Grid de 3 tarjetas:
   - Tickets Hoy (ej. 24)
   - Resueltos en Oficina / Remoto (ej. 15)
   - Escalados a Visita Técnica (ej. 5).
3. **Cola de Atención (Nivel 2):**
   - Una tabla con diseño limpio que ocupe gran parte de la pantalla.
   - Columnas: Ticket/Hora, Cliente/Servicio, Motivo/Diagnóstico (con notas en cursiva), Prioridad (Badges rojo, naranja, verde), Acción.
   - El botón de Acción debe decir "Atender".
4. **Gráfico de Tipología:** A la derecha de la tabla, un panel para un gráfico Circular (Doughnut) que muestre "Tipología de Incidentes" (Corte fibra, Router, Lentitud, etc.).
5. **Modal Simulado:** Incluye el código HTML (oculto por defecto con clases de Tailwind como `hidden`) del modal de atención que contenga:
   - Datos del cliente, IP, Nodo, Plan.
   - Sección de teléfonos (Principal/Secundario) con botones circulares para WhatsApp, SMS, Llamar.
   - Botones gigantes para "Resolver (Nivel 2)" o "Escalar a Visita (Nivel 3)".

---

## 4. Prompt - Dashboard Técnico de Campo

**Prompt:**
Crea la vista principal del Dashboard para el rol **Técnico de Campo** en Laravel Blade (`resources/views/tecnico/dashboard.blade.php`). Piensa en un diseño **Mobile-first** (prioridad móviles y tablets).

**Requisitos visuales y de estructura:**
1. **Encabezado:** Saludo al técnico (ej. "¡Hola, Técnico Demo!") y un resumen de "Ruta del Día".
2. **Métricas de Ruta:**
   - Visitas Pendientes (ej. 4).
   - Instalaciones Completadas Hoy (ej. 2).
   - Averías Resueltas (ej. 1).
3. **Lista de Tareas (Agenda / Tarjetas verticales amplias):**
   - En lugar de tabla normal, diseña tarjetas amigables para móvil, cada una representando una orden de trabajo (OT).
   - Mostrar: Tipo (Badge Azul para Instalación, Badge Naranja para Avería), Nombre Cliente, Dirección (con link a Maps simulado), Hora programada.
   - Botones de acción rápida: "Llamar Cliente", "WhatsApp", "Comenzar Trabajo" (Botón primario).
4. **Buscador/Filtro:** Un input simple para buscar por Nro de Orden o DNI.
5. **Diseño:** Las tarjetas de tareas deben tener mucho padding, textos grandes leíbles bajo el sol y botones accesibles con el dedo (táctiles).

---

## 5. Prompt - Dashboard Ventas / Operaciones

**Prompt:**
Crea la vista principal del Dashboard para el rol **Ventas/Operaciones** en Laravel Blade (`resources/views/ventas/dashboard.blade.php`).

**Requisitos visuales y de estructura:**
1. **Encabezado:** "Dashboard de Operaciones" y un botón destacado color verde para "Caja Rápida".
2. **Métricas de Operaciones y Cobranza:**
   - Crecimiento de red (Gráfico o KPI simple).
   - Tarjeta destacada de "Cartera en Mora" (Mostrar monto S/ X) y desglose (Suspendidos, Corte físico).
   - Tarjeta de "Pagos por Validar" (Clientes que enviaron voucher) con un botón "Validar Pagos (RF-V01)".
3. **Directorio General de Servicios:**
   - Un panel con un formulario de búsqueda integrado (buscador de texto, filtro desplegable por Estado: Activo, Suspendido, En Mora, Cortado).
   - Tabla de resultados con: Cliente, Plan, Dirección, Deuda (en rojo si hay importe), Estado (Badges colorizados) y Acciones (Ver detalle, Editar).
4. **Diseño de Interfaz:** Optimizado para escritorio. Usa el componente de layout mencionado, asegura buen contraste en los estados de deuda (Rojo) y cuentas al día (Verde).

---

### Mejoras visuales sugeridas respecto al sistema PHP Vanilla:
* Transiciones y micro-interacciones al hacer hover sobre botones y tarjetas de servicio.
* Carga asíncrona simulada (Skeleton loaders de Tailwind) en vez de texto plano de "Cargando".
* Adaptabilidad Dark Mode (opcional, configurando clases `dark:` en Tailwind) ya que es muy solicitado por técnicos en trabajo de campo y monitoreo de red (NOC).
* Diseño más responsivo para las tablas extensas (scroll horizontal oculto o listados en vista móvil).
