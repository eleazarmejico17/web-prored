// ====================================================
// TÉRMINOS Y CONDICIONES MODAL
// ====================================================

const tycData = `
<div class="tyc-header">
    <h2>Términos y Condiciones</h2>
    <span class="promo-title">Promoción Dúo Max Ahorro</span>
</div>

<div class="tyc-content">

    <div class="tyc-section">
        <h3>PLANES DÚO (INTERNET + TV CABLE / IPTV)</h3>
        <p><strong>Empresa prestadora del servicio</strong></p>
        <p>PRORED, identificada con RUC 20608786598, con domicilio en Av. Ramón Castilla Nº 631 - Concepción (Huancayo, Junín), es responsable de la prestación de los servicios de telecomunicaciones ofrecidos en la presente promoción.</p>
    </div>

    <div class="tyc-section">
        <h3>Alcance de la Promoción</h3>
        <ul>
            <li>Planes Dúo (Internet + TV Cable)</li>
            <li>Servicio adicional de TV vía IPTV (Nuplin)</li>
        </ul>
        <p>Sujeto a disponibilidad y factibilidad técnica.</p>
    </div>

    <div class="tyc-section">
        <h3>Beneficio Promocional</h3>
        <ul>
            <li>50% de descuento en TV cable (incluyendo puntos adicionales)</li>
            <li>50% de descuento en IPTV (Nuplin)</li>
        </ul>
        <p>No aplica sobre el servicio de internet.</p>
    </div>

    <div class="tyc-section">
        <h3>Planes IPTV incluidos</h3>
        <ul>
            <li>Plan Básico: S/10.40</li>
            <li>Plan Básico Plus: S/22.30</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Vigencia del Beneficio</h3>
        <p>Aplica durante la primera y segunda mensualidad.</p>
        <p>Desde el tercer mes se aplica tarifa regular.</p>
    </div>

    <div class="tyc-section">
        <h3>TV Cable</h3>
        <ul>
            <li>Incluye hasta 2 TVs sin costo adicional</li>
            <li>TV adicional: S/7 mensual (con descuento en 2 primeros meses)</li>
            <li>Instalación adicional: S/10 por TV extra</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>IPTV (Nuplin)</h3>
        <ul>
            <li>Servicio digital que requiere internet</li>
            <li>No requiere instalación física</li>
            <li>Compatible según dispositivo del cliente</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Instalación</h3>
        <ul>
            <li>Instalación estándar GRATIS</li>
            <li>No incluye trabajos adicionales</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Vigencia de la Promoción</h3>
        <p>Del 01 de mayo al 15 de mayo del 2026</p>
    </div>

    <div class="tyc-section">
        <h3>Pagos y Facturación</h3>
        <ul>
            <li>Pagos por adelantado</li>
            <li>Primer mes se paga completo</li>
            <li>Segundo mes se ajusta con saldo a favor</li>
            <li>Desde el tercer mes: tarifa regular</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Condiciones de Contratación</h3>
        <p>Sujeto a evaluación de cobertura y factibilidad técnica.</p>
    </div>

    <div class="tyc-section">
        <h3>Condiciones Económicas</h3>
        <ul>
            <li>Falta de pago puede generar suspensión</li>
            <li>La suspensión elimina el beneficio promocional</li>
            <li>Reactivación sin descuento</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Condiciones Generales</h3>
        <ul>
            <li>No acumulable</li>
            <li>Solo nuevas contrataciones</li>
            <li>No afecta contratos previos</li>
        </ul>
    </div>

</div>

<div class="tyc-footer">
    <a href="https://api.whatsapp.com/send?phone=51991445527&text=¡Hola!%20Quiero%20la%20promoción%20Dúo%20Max%20Ahorro" target="_blank" class="btn-claim-offer">
        <i class="fab fa-whatsapp"></i> Reclamar Oferta
    </a>
</div>
`;

// Crear el modal
function initTyCModal() {
    // Crear contenedor del modal si no existe
    if (!document.getElementById('tycModal')) {
        const modal = document.createElement('div');
        modal.id = 'tycModal';
        modal.className = 'tyc-modal';
        modal.innerHTML = `
            <div class="tyc-modal-overlay"></div>
            <div class="tyc-modal-container">
                <button class="tyc-close-btn" onclick="closeTyCModal()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="tyc-modal-content" id="tycModalContent"></div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Cargar contenido
        document.getElementById('tycModalContent').innerHTML = tycData;
    }
}

// Abrir modal
function openTyCModal() {
    initTyCModal();
    const modal = document.getElementById('tycModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Event listener al overlay para cerrar
    const overlay = modal.querySelector('.tyc-modal-overlay');
    overlay.addEventListener('click', closeTyCModal);
}

// Cerrar modal
function closeTyCModal() {
    const modal = document.getElementById('tycModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeTyCModal();
    }
});
