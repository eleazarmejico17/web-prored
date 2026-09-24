<?php
declare(strict_types=1);

/**
 * Correos del Libro de Reclamaciones (transporte mail() nativo, Exim en cPanel):
 *  - Acuse al consumidor, con la constancia PDF adjunta.
 *  - Copia de aviso al administrador cuando se registra una nueva hoja.
 */

/**
 * Construye asunto, cabeceras y cuerpo MIME multipart (función pura, testeable).
 *
 * @param string      $texto      Cuerpo del mensaje (UTF-8, sin adjunto).
 * @param string|null $pdf        Bytes del PDF adjunto, o null sin adjunto.
 * @param string      $subjectRaw Asunto en texto plano (se codifica UTF-8).
 * @param string      $from       Remitente (email válido).
 * @param string      $fromName   Nombre visible del remitente.
 * @return array{subject: string, headers: string, body: string}
 */
function lr_mail_build(string $texto, ?string $pdf, string $subjectRaw, string $from, string $fromName): array
{
    $subject = '=?UTF-8?B?' . base64_encode($subjectRaw) . '?=';
    $boundary = '=lr_' . bin2hex(random_bytes(12));
    $crlf = "\r\n";

    $headers = 'From: ' . lr_mail_encode_display($fromName, $from) . $crlf
        . 'Reply-To: ' . $from . $crlf
        . 'MIME-Version: 1.0' . $crlf
        . 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $crlf;

    $body = '--' . $boundary . $crlf
        . 'Content-Type: text/plain; charset="UTF-8"' . $crlf
        . 'Content-Transfer-Encoding: base64' . $crlf . $crlf
        . chunk_split(base64_encode($texto), 76, $crlf) . $crlf;

    if ($pdf !== null) {
        $nombre = $subjectRaw;
        if (preg_match('/LR-\d{4}-\d{6}/', $subjectRaw, $m)) {
            $nombre = $m[0];
        } else {
            $nombre = 'constancia';
        }
        $body .= '--' . $boundary . $crlf
            . 'Content-Type: application/pdf; name="' . $nombre . '.pdf"' . $crlf
            . 'Content-Transfer-Encoding: base64' . $crlf
            . 'Content-Disposition: attachment; filename="' . $nombre . '.pdf"' . $crlf . $crlf
            . chunk_split(base64_encode($pdf), 76, $crlf) . $crlf;
    }

    $body .= '--' . $boundary . '--' . $crlf;

    return ['subject' => $subject, 'headers' => $headers, 'body' => $body];
}

/** Codifica "Nombre <correo>" cuando el nombre contiene caracteres no ASCII. */
function lr_mail_encode_display(string $name, string $email): string
{
    $name = trim(str_replace(['"', "\r", "\n"], '', $name));
    if ($name === '') {
        return $email;
    }
    if (preg_match('/[^\x20-\x7E]/', $name)) {
        $name = '=?UTF-8?B?' . base64_encode($name) . '?=';
    } else {
        $name = '"' . $name . '"';
    }
    return $name . ' <' . $email . '>';
}

/**
 * Construye el acuse al consumidor (función pura).
 *
 * @param array $r Fila completa de reclamaciones.
 * @return array{subject: string, headers: string, body: string}
 */
function lr_email_recibido_mensaje(array $r, string $pdf, string $from, string $fromName): array
{
    $codigo = (string)$r['codigo'];
    $tipo = $r['tipo'] === 'queja' ? 'Queja' : 'Reclamo';
    $tipoMay = strtoupper($tipo);
    $limite = date('d/m/Y', strtotime((string)$r['fecha_limite_respuesta']));
    $fecha = date('d/m/Y H:i', strtotime((string)$r['fecha_registro']));
    $base = rtrim((string)lr_config()['base_url'], '/');
    $link = $base . '/api/pdf.php?codigo=' . rawurlencode($codigo)
        . '&token=' . rawurlencode((string)$r['constancia_token']);
    $p = lr_config()['proveedor'];

    $texto = "Estimado/a {$r['nombre_razon_social']}:\n\n"
        . "Hemos recibido su {$tipoMay} registrado en el Libro de Reclamaciones de ProRed.\n\n"
        . "  Código de seguimiento: {$codigo}\n"
        . "  Fecha de registro: {$fecha}\n"
        . "  Plazo de respuesta: 15 días hábiles (hasta el {$limite})\n\n"
        . "Adjunto encontrará la constancia en formato PDF de su Hoja de Reclamación.\n"
        . "También puede descargarla en cualquier momento desde:\n  {$link}\n\n"
        . "---\n"
        . "Este es un correo automático; no responda a este mensaje.\n"
        . "{$p['razon_social']} - RUC {$p['ruc']}\n";

    $subjectRaw = 'Hoja de Reclamación ' . $codigo . ' recibida (' . $tipoMay . ')';
    return lr_mail_build($texto, $pdf, $subjectRaw, $from, $fromName);
}

/**
 * Construye el aviso al administrador ante un nuevo registro (función pura).
 *
 * @param array $r Fila completa de reclamaciones.
 * @return array{subject: string, headers: string, body: string}
 */
function lr_email_admin_mensaje(array $r, string $pdf, string $from, string $fromName): array
{
    $codigo = (string)$r['codigo'];
    $tipoMay = $r['tipo'] === 'queja' ? 'QUEJA' : 'RECLAMO';
    $limite = date('d/m/Y', strtotime((string)$r['fecha_limite_respuesta']));
    $fecha = date('d/m/Y H:i', strtotime((string)$r['fecha_registro']));
    $base = rtrim((string)lr_config()['base_url'], '/');
    $linkPdf = $base . '/api/pdf.php?codigo=' . rawurlencode($codigo)
        . '&token=' . rawurlencode((string)$r['constancia_token']);
    $p = lr_config()['proveedor'];

    $doc = strtoupper((string)$r['tipo_documento']) . ': ' . $r['numero_documento'];
    $monto = $r['monto_reclamado'] !== null
        ? 'S/ ' . number_format((float)$r['monto_reclamado'], 2)
        : 'No aplica';

    $texto = "Nueva Hoja de Reclamación registrada en el Libro Virtual ProRed.\n\n"
        . "  Código: {$codigo} ({$tipoMay})\n"
        . "  Fecha de registro: {$fecha}\n"
        . "  Plazo de respuesta: 15 días hábiles (hasta el {$limite})\n\n"
        . "  Consumidor: {$r['nombre_razon_social']} — {$doc}\n"
        . "  Correo: {$r['email']}\n"
        . "  Teléfono: {$r['telefono']}\n"
        . "  Bien o servicio: {$r['servicio']} (monto: {$monto})\n\n"
        . "Ver en el panel: {$base}/admin/\n"
        . "Constancia PDF: {$linkPdf}\n\n"
        . "---\n"
        . "Aviso automático del Libro de Reclamaciones · {$p['razon_social']}\n";

    $subjectRaw = 'Nueva ' . ($r['tipo'] === 'queja' ? 'Queja' : 'Reclamación')
        . ' ' . $codigo . ' — Libro Virtual ProRed';
    return lr_mail_build($texto, $pdf, $subjectRaw, $from, $fromName);
}

/** Acuse al consumidor. Devuelve false si está deshabilitado o mail() falla. */
function lr_email_recibido(array $r): bool
{
    try {
        $cfg = lr_config()['mail'] ?? [];
        if (empty($cfg['enabled'])) {
            return false;
        }
        $from = (string)($cfg['from'] ?? '');
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $to = (string)($r['email'] ?? '');
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $pdf = lr_pdf_constancia($r);
        $msg = lr_email_recibido_mensaje($r, $pdf, $from, (string)($cfg['from_name'] ?? 'Libro de Reclamaciones'));
        $ok = @mail($to, $msg['subject'], $msg['body'], $msg['headers']);
        if (!$ok) {
            error_log('Libro reclamaciones: mail() fallo para ' . $r['codigo']);
        }
        return $ok;
    } catch (Throwable $e) {
        error_log('Libro reclamaciones (correo): ' . $e->getMessage());
        return false;
    }
}

/**
 * Copia de aviso al administrador (MAIL_ADMIN) ante un nuevo registro.
 * Devuelve false si no está configurado o mail() falla. NUNCA lanza excepción.
 */
function lr_email_admin_nueva(array $r): bool
{
    try {
        $cfg = lr_config()['mail'] ?? [];
        if (empty($cfg['enabled'])) {
            return false;
        }
        $to = (string)($cfg['admin'] ?? '');
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $from = (string)($cfg['from'] ?? '');
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $pdf = lr_pdf_constancia($r);
        $msg = lr_email_admin_mensaje($r, $pdf, $from, (string)($cfg['from_name'] ?? 'Libro de Reclamaciones'));
        $ok = @mail($to, $msg['subject'], $msg['body'], $msg['headers']);
        if (!$ok) {
            error_log('Libro reclamaciones: mail() de aviso admin fallo para ' . $r['codigo']);
        }
        return $ok;
    } catch (Throwable $e) {
        error_log('Libro reclamaciones (aviso admin): ' . $e->getMessage());
        return false;
    }
}
