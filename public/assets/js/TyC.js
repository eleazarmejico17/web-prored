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
        <h3>PLANES DÚO (INTERNET + TV CABLE)</h3>
        <p><strong>Empresa prestadora del servicio</strong></p>
        <p>PRORED, identificada con RUC 20608786598, con domicilio en Av. Ramón Castilla Nº 631 - Concepción (Huancayo, Junín), es responsable de la prestación de los servicios de telecomunicaciones ofrecidos en la presente promoción.</p>
    </div>

    <div class="tyc-section">
        <h3>Alcance de la Promoción</h3>
        <p>La promoción es válida exclusivamente para la contratación de planes Dúo (Internet + TV Cable), sujeta a disponibilidad y factibilidad técnica en la zona del solicitante.</p>
    </div>

    <div class="tyc-section">
        <h3>Beneficio Promocional</h3>
        <p>La promoción consiste en un descuento del 50% sobre el valor total del servicio de TV cable, el cual incluye el costo base del servicio y los cargos por televisores adicionales contratados.</p>
        <p>Este beneficio no aplica sobre el servicio de internet ni sobre otros servicios adicionales.</p>
    </div>

    <div class="tyc-section">
        <h3>Vigencia del Beneficio</h3>
        <p>El descuento promocional será aplicable únicamente durante la primera y segunda mensualidad del servicio contratado.</p>
        <p>A partir de la tercera mensualidad, el cliente pagará el precio regular vigente del plan al momento de la facturación.</p>
    </div>

    <div class="tyc-section">
        <h3>Condiciones del Servicio de TV Cable</h3>
        <ul>
            <li>El servicio incluye hasta dos (2) televisores sin costo adicional.</li>
            <li>Por cada televisor adicional, se aplicará un cargo mensual de S/7.00 (siete soles) por punto adicional, el cual estará sujeto al descuento promocional del 50% durante la primera y segunda mensualidad.</li>
            <li>El servicio se brinda mediante conexión física a través de cable coaxial.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Condiciones de Instalación</h3>
        <ul>
            <li>La instalación estándar del servicio es gratuita.</li>
            <li>En caso el cliente solicite televisores adicionales, se cobrará un costo de S/10.00 (diez soles) por instalación por cada televisor adicional.</li>
            <li>La instalación estándar no incluye trabajos adicionales tales como canalizaciones especiales, cableado estructurado adicional, adecuaciones eléctricas u otros requerimientos fuera de lo convencional.</li>
            <li>Cualquier trabajo adicional será previamente informado y asumido por el cliente.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Vigencia de la Promoción</h3>
        <p>La promoción será válida únicamente para contrataciones realizadas desde el 01 de mayo hasta el 15 de mayo del 2026.</p>
    </div>

    <div class="tyc-section">
        <h3>Condiciones de Facturación y Pagos</h3>
        <ul>
            <li>Los pagos del servicio se factura por adelantado.</li>
            <li>El cliente deberá pagar la primera mensualidad completa al momento de la contratación, independientemente de la fecha de instalación o activación del servicio, aplicándose el descuento promocional correspondiente.</li>
            <li>Para la segunda mensualidad, se realizará un prorrateo considerando el saldo a favor generado en la primera facturación, aplicándose igualmente el descuento promocional.</li>
            <li>A partir de la tercera mensualidad, la facturación se realizará de forma regular, sin aplicación de descuentos promocionales.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Condiciones de Contratación</h3>
        <p>La contratación del servicio está sujeta a evaluación de cobertura y factibilidad técnica, considerando disponibilidad de red, condiciones geográficas y capacidad operativa.</p>
        <p>La empresa se reserva el derecho de rechazar solicitudes que no cumplan con los requisitos técnicos o comerciales.</p>
    </div>

    <div class="tyc-section">
        <h3>Condiciones Económicas</h3>
        <ul>
            <li>El cliente se compromete a realizar el pago mensual del servicio contratado conforme a las tarifas vigentes.</li>
            <li>El incumplimiento en los pagos podrá generar la suspensión del servicio, de acuerdo con la normativa aplicable.</li>
            <li>En caso de suspensión del servicio por falta de pago, el cliente perderá automáticamente el beneficio promocional, aun cuando se encuentre dentro del periodo de vigencia del mismo. En consecuencia, cualquier reactivación del servicio se realizará aplicando la tarifa regular vigente, sin derecho a reactivación del descuento promocional.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>Condiciones Generales</h3>
        <ul>
            <li>La promoción no es acumulable con otras ofertas vigentes.</li>
            <li>Aplica únicamente para nuevas contrataciones, salvo disposición comercial distinta.</li>
            <li>Las condiciones establecidas no afectan contratos previamente adquiridos.</li>
            <li>Cualquier modificación se realizará conforme a la normativa vigente y sin afectar beneficios ya otorgados durante su periodo de vigencia.</li>
        </ul>
    </div>
</div>

<div class="tyc-footer">
    <a href="https://api.whatsapp.com/send?phone=51991445527&text=¡Hola!%20Quiero%20la%20promoción%20Dúo%20Max%20Ahorro" target="_blank" class="btn-claim-offer"><i class="fab fa-whatsapp"></i> Reclamar Oferta</a>
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
    document.body.style.overflow = 'auto';
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeTyCModal();
    }
});
