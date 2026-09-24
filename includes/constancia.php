<?php
declare(strict_types=1);

/**
 * Generación de la constancia PDF (compartida por api/pdf.php
 * y el correo de acuse con adjunto).
 */
require_once __DIR__ . '/pdf.php';

/**
 * @param array $r Fila completa de la tabla reclamaciones.
 * @return string Bytes del PDF.
 */
function lr_pdf_constancia(array $r): string
{
    $cfg = lr_config();
    $p = $cfg['proveedor'];

    $fecha = (new DateTimeImmutable($r['fecha_registro']))->format('d/m/Y H:i');
    $limite = date('d/m/Y', strtotime($r['fecha_limite_respuesta']));
    $tipoLabel = $r['tipo'] === 'queja' ? 'QUEJA' : 'RECLAMO';
    $monto = $r['monto_reclamado'] !== null
        ? 'S/ ' . number_format((float)$r['monto_reclamado'], 2)
        : 'No aplica';

    $personaLabel = $r['tipo_persona'] === 'juridica' ? 'Persona jurídica' : 'Persona natural';
    $docLabel = strtoupper((string)$r['tipo_documento']) . ': ' . $r['numero_documento'];

    $pdf = new SimplePdf();

    // ---- Cabecera corporativa -------------------------------------------------
    $pdf->heroHeader(
        'HOJA DE RECLAMACIÓN VIRTUAL',
        'Libro de Reclamaciones · ' . ($p['nombre_comercial'] ?? 'ProRed') . ' · ' . $p['razon_social'],
        $r['codigo']
    );

    // ---- Proveedor ------------------------------------------------------------
    $pdf->providerBox($p);

    // ---- Meta strip -----------------------------------------------------------
    $pdf->metaStrip([
        ['Fecha de registro', $fecha],
        ['Plazo de respuesta', $limite . ' (15 d.h.)'],
        ['Tipo', $tipoLabel],
        ['Estado', (string)$r['estado']],
    ]);

    // ---- 1. Identificación del consumidor -------------------------------------
    $pdf->sectionHeader('1', 'IDENTIFICACIÓN DEL CONSUMIDOR RECLAMANTE');
    $pdf->tableRow('Tipo de persona', $personaLabel);
    $pdf->tableRow($r['tipo_persona'] === 'juridica' ? 'Razón social' : 'Nombre completo', (string)$r['nombre_razon_social']);
    $pdf->tableRow('Documento de identidad', $docLabel);
    $pdf->tableRow('Domicilio', (string)$r['domicilio']);
    $pdf->tableRow('Teléfono', (string)$r['telefono']);
    $pdf->tableRow('Correo electrónico', (string)$r['email']);
    if ((int)$r['es_menor'] === 1) {
        $pdf->tableRow(
            'Representante (menor)',
            trim((string)$r['representante_nombre'] . ' — Doc. ' . (string)$r['representante_documento'])
        );
    }

    // ---- 2. Bien o servicio ----------------------------------------------------
    $pdf->sectionHeader('2', 'BIEN O SERVICIO RECLAMADO');
    $pdf->tableRow('Tipo de bien', $r['tipo_bien'] === 'producto' ? 'Producto' : 'Servicio');
    $pdf->tableRow('Servicio o producto', (string)$r['servicio']);
    if ($r['descripcion_bien']) {
        $pdf->tableRow('Descripción', (string)$r['descripcion_bien']);
    }
    $pdf->tableRow('Monto reclamado', $monto);

    // ---- 3. Detalle ------------------------------------------------------------
    $pdf->sectionHeader('3', 'DETALLE DEL HECHO (' . $tipoLabel . ')');
    $pdf->textPanel((string)$r['detalle']);

    // ---- 4. Pedido -------------------------------------------------------------
    $pdf->sectionHeader('4', 'PEDIDO DEL CONSUMIDOR');
    $pdf->textPanel((string)$r['pedido']);

    // ---- 5. Respuesta (opcional) ----------------------------------------------
    if ($r['respuesta'] !== null && $r['respuesta'] !== '') {
        $pdf->sectionHeader('5', 'RESPUESTA DEL PROVEEDOR');
        $pdf->tableRow(
            'Fecha de respuesta',
            $r['fecha_respuesta'] ? date('d/m/Y H:i', strtotime($r['fecha_respuesta'])) : '—'
        );
        $pdf->tableRow('Medio de envío', (string)($r['medio_envio_respuesta'] ?: '—'));
        $pdf->textPanel((string)$r['respuesta']);
        if ($r['acciones_adoptadas']) {
            $pdf->sectionHeader('6', 'ACCIONES ADOPTADAS');
            $pdf->textPanel((string)$r['acciones_adoptadas']);
        }
    }

    // ---- Texto legal -----------------------------------------------------------
    $pdf->spacer(8);
    $pdf->legalBox([
        ['title', 'HOJA DE RECLAMACIÓN VIRTUAL*'],
        ['small', '*La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.'],
        ['normal', 'El proveedor debe dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles, el cual es improrrogable.'],
        ['small', 'Documento generado por el Libro de Reclamaciones de ' . $p['razon_social']
            . ' · RUC ' . $p['ruc'] . '. conserve el código de seguimiento ' . $r['codigo'] . ' para futuras consultas.'],
    ]);

    return $pdf->output($r['codigo']);
}
