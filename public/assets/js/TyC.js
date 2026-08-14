// ====================================================
// TÉRMINOS Y CONDICIONES MODAL
// ====================================================

const tycData = `
<div class="tyc-header">
    <h2>Términos y Condiciones</h2>
    <span class="promo-title">TÉRMINOS Y CONDICIONES DE INSTALACIÓN GRATUITA DE FIBRA ÓPTICA</span>
</div>

<div class="tyc-content">

    <div class="tyc-section">
        <h3>1. Alcance de la instalación gratuita</h3>
        <p>La instalación gratuita aplica únicamente para las contrataciones nuevas de los planes de Internet y Dúo (Internet + IPTV) ofrecidos por la empresa.</p>
        <p>La instalación incluye:</p>
        <ul>
            <li>Instalación y configuración de un (01) router WiFi.</li>
            <li>Tendido e instalación de hasta 180 metros de fibra óptica desde el punto de distribución más cercano hasta el domicilio del cliente.</li>
            <li>Configuración y pruebas de funcionamiento del servicio contratado.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>2. Excedente de fibra óptica</h3>
        <p>La instalación gratuita cubre un máximo de 180 metros de fibra óptica.</p>
        <p>Cuando la distancia requerida para la instalación supere los 180 metros incluidos, el cliente deberá asumir los costos correspondientes al material y trabajo adicional necesario para completar la instalación.</p>
        <p>Dicho costo será informado y aprobado por el cliente antes de realizar la instalación.</p>
    </div>

    <div class="tyc-section">
        <h3>3. Condiciones para la instalación</h3>
        <p>La instalación estará sujeta a:</p>
        <ul>
            <li>Disponibilidad de cobertura técnica en la zona.</li>
            <li>Factibilidad técnica determinada por el personal de la empresa.</li>
            <li>Existencia de capacidad disponible en la infraestructura de red.</li>
            <li>Acceso seguro al inmueble para realizar los trabajos de instalación.</li>
        </ul>
        <p>La empresa podrá reprogramar o rechazar una instalación cuando existan condiciones técnicas o de seguridad que impidan la correcta prestación del servicio.</p>
    </div>

    <div class="tyc-section">
        <h3>4. Materiales y equipos</h3>
        <p>Los equipos entregados por la empresa son cedidos en calidad de préstamo.</p>
        <p>El cliente se compromete a:</p>
        <ul>
            <li>Utilizar adecuadamente los equipos instalados.</li>
            <li>No manipular, modificar o trasladar los equipos sin autorización de la empresa.</li>
            <li>Reportar cualquier falla o incidencia a los canales oficiales de atención.</li>
        </ul>
    </div>

    <div class="tyc-section">
        <h3>5. Trabajos adicionales</h3>
        <p>La instalación gratuita no incluye:</p>
        <ul>
            <li>Canaleteado especial o decoración de cableado.</li>
            <li>Obras civiles, perforaciones complejas o trabajos de albañilería.</li>
            <li>Instalación de puntos adicionales de red o televisión.</li>
            <li>Reubicaciones posteriores del servicio.</li>
            <li>Materiales adicionales solicitados por el cliente.</li>
        </ul>
        <p>Cualquier trabajo adicional será cotizado y aprobado previamente por el cliente.</p>
    </div>

    <div class="tyc-section">
        <h3>6. Aceptación</h3>
        <p>La contratación del servicio implica la aceptación de los presentes términos y condiciones de instalación, así como de las políticas comerciales y técnicas vigentes de la empresa.</p>
    </div>

</div>

<div class="tyc-footer">
    <a href="https://api.whatsapp.com/send?phone=51991445527&text=%C2%A1Hola!%20Quiero%20la%20promoci%C3%B3n%20de%20instalaci%C3%B3n%20gratuita%20de%20fibra%20%C3%B3ptica" target="_blank" rel="noopener noreferrer" class="btn-claim-offer">
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
