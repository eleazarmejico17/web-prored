<?php
declare(strict_types=1);

/**
 * Acuse de recibo automático al consumidor, con la constancia PDF adjunta.
 * Transporte: mail() nativo (Exim en cPanel). Sin dependencias externas.
 */

/**
 * Construye asunto, cabeceras y cuerpo MIME del acuse (función pura, testeable).
 *
 * @param array  $r   Fila completa de reclamaciones.
 * @param string $pdf Bytes del PDF de la constancia.
 * @param string $from Remitente (email válido).
 * @param string $fromName Nombre visible del remitente.
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

    // Asunto UTF-8 codificado (evita caracteres rotados en clientes de correo)
    $subjectRaw = 'Hoja de Reclamación ' . $codigo . ' recibida (' . $tipoMay . ')';
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
        . chunk_split(base64_encode($texto), 76, $crlf) . $crlf
        . '--' . $boundary . $crlf
        . 'Content-Type: application/pdf; name="' . $codigo . '.pdf"' . $crlf
        . 'Content-Transfer-Encoding: base64' . $crlf
        . 'Content-Disposition: attachment; filename="' . $codigo . '.pdf"' . $crlf . $crlf
        . chunk_split(base64_encode($pdf), 76, $crlf) . $crlf
        . '--' . $boundary . '--' . $crlf;

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
 * Envía el acuse con la constancia adjunta.
 * Devuelve false si el correo está deshabilitado, los datos no son
 * enviables o mail() falla. NUNCA lanza excepción (el reclamo ya está guardado).
 */
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
