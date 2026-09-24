<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    lr_json_out(['success' => false, 'message' => 'Método no permitido.'], 405);
}

// Intentar leer JSON o formulario
$raw = file_get_contents('php://input') ?: '';
$ct = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ct, 'application/json') !== false) {
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        lr_json_out(['success' => false, 'message' => 'Cuerpo JSON inválido.'], 400);
    }
} else {
    $data = $_POST;
}

// Honeypot: campo oculto "website" debe quedar vacío
if (!empty($data['website'])) {
    lr_json_out(['success' => false, 'message' => 'Solicitud rechazada.'], 400);
}

// CSRF
$token = $data['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
if (!lr_csrf_check(is_string($token) ? $token : null)) {
    lr_json_out(['success' => false, 'message' => 'Sesión expirada. Recargue la página.'], 403);
}

// Rate limit
$max = (int)(lr_config()['rate_limit']['max_por_hora'] ?? 5);
if (!lr_rate_ok('crear_reclamacion', $max)) {
    lr_json_out([
        'success' => false,
        'message' => 'Demasiadas solicitudes. Intente de nuevo más tarde.',
    ], 429);
}

// ---------------------------------------------------------------------------
// Validación server-side
// ---------------------------------------------------------------------------
$errors = [];

$str = static function ($k, $max = 255) use ($data): string {
    $v = trim((string)($data[$k] ?? ''));
    if (mb_strlen($v) > $max) {
        $v = mb_substr($v, 0, $max);
    }
    return $v;
};

$tipoPersona = in_array($data['tipo_persona'] ?? '', ['natural', 'juridica'], true)
    ? $data['tipo_persona'] : 'natural';
$nombre = $str('nombre_razon_social', 200);
$tipoDoc = $data['tipo_documento'] ?? '';
$numeroDoc = preg_replace('/\s+/', '', $str('numero_documento', 20));
$domicilio = $str('domicilio', 255);
$telefono = preg_replace('/[^\d+]/', '', $str('telefono', 30));
$email = filter_var($str('email', 150), FILTER_VALIDATE_EMAIL) ? $str('email', 150) : '';

if ($nombre === '') {
    $errors['nombre_razon_social'] = 'Nombre o razón social es obligatorio.';
}
if (!in_array($tipoDoc, ['dni', 'ce', 'ruc', 'otro'], true)) {
    $errors['tipo_documento'] = 'Tipo de documento inválido.';
}
if ($numeroDoc === '' || !preg_match('/^[0-9A-Za-z-]{5,20}$/', $numeroDoc)) {
    $errors['numero_documento'] = 'Número de documento inválido.';
}
if ($domicilio === '') {
    $errors['domicilio'] = 'Domicilio es obligatorio.';
}
if ($telefono === '' || strlen(preg_replace('/\D/', '', $telefono)) < 7) {
    $errors['telefono'] = 'Teléfono inválido.';
}
if ($email === '') {
    $errors['email'] = 'Correo electrónico inválido.';
}

$esMenor = !empty($data['es_menor']) && $data['es_menor'] !== '0' && $data['es_menor'] !== 'no';
$repNombre = '';
$repDoc = '';
if ($esMenor) {
    $repNombre = $str('representante_nombre', 200);
    $repDoc = preg_replace('/\s+/', '', $str('representante_documento', 30));
    if ($repNombre === '') {
        $errors['representante_nombre'] = 'Nombre del representante es obligatorio.';
    }
    if ($repDoc === '') {
        $errors['representante_documento'] = 'Documento del representante es obligatorio.';
    }
}

$tipoBien = in_array($data['tipo_bien'] ?? '', ['producto', 'servicio'], true)
    ? $data['tipo_bien'] : 'servicio';
$servicio = $str('servicio', 100);
$descripcionBien = $str('descripcion_bien', 500);
if ($servicio === '') {
    $errors['servicio'] = 'Seleccione o describa el servicio.';
}

$monto = null;
if (isset($data['monto_reclamado']) && $data['monto_reclamado'] !== '' && $data['monto_reclamado'] !== null) {
    $montoRaw = str_replace([',', ' '], '.', (string)$data['monto_reclamado']);
    if (!is_numeric($montoRaw)) {
        $errors['monto_reclamado'] = 'Monto inválido.';
    } elseif ((float)$montoRaw < 0 || (float)$montoRaw > 9999999.99) {
        $errors['monto_reclamado'] = 'Monto fuera de rango.';
    } else {
        $monto = number_format((float)$montoRaw, 2, '.', '');
    }
}

$tipo = strtolower(trim((string)($data['tipo'] ?? '')));
if (!in_array($tipo, ['reclamo', 'queja'], true)) {
    $errors['tipo'] = 'Seleccione Reclamo o Queja.';
}

$detalle = trim((string)($data['detalle'] ?? ''));
$pedido = trim((string)($data['pedido'] ?? ''));
if ($detalle === '' || preg_match('/^\s*$/', $detalle)) {
    $errors['detalle'] = 'El detalle es obligatorio.';
} elseif (mb_strlen($detalle) > 5000) {
    $errors['detalle'] = 'El detalle no puede superar 5000 caracteres.';
}
if ($pedido === '' || preg_match('/^\s*$/', $pedido)) {
    $errors['pedido'] = 'El pedido del consumidor es obligatorio.';
} elseif (mb_strlen($pedido) > 3000) {
    $errors['pedido'] = 'El pedido no puede superar 3000 caracteres.';
}

if ($errors) {
    lr_json_out(['success' => false, 'message' => 'Revise los campos marcados.', 'errors' => $errors], 422);
}

// ---------------------------------------------------------------------------
// Registro transaccional + código correlativo
// ---------------------------------------------------------------------------
$pdo = lr_db();
$anio = (int)date('Y');
$limite = lr_fecha_limite_habiles((int)(lr_config()['dias_habiles_respuesta'] ?? 15));
$token = bin2hex(random_bytes(32));

try {
    $pdo->beginTransaction();

    // Lock del contador del año (evita códigos duplicados bajo concurrencia)
    $st = $pdo->prepare('SELECT ultimo FROM reclamaciones_contador WHERE anio = ? FOR UPDATE');
    $st->execute([$anio]);
    $row = $st->fetch();
    if (!$row) {
        $pdo->prepare('INSERT INTO reclamaciones_contador (anio, ultimo) VALUES (?, 0)')
            ->execute([$anio]);
        $ultimo = 0;
    } else {
        $ultimo = (int)$row['ultimo'];
    }
    $numero = $ultimo + 1;
    $pdo->prepare('UPDATE reclamaciones_contador SET ultimo = ? WHERE anio = ?')
        ->execute([$numero, $anio]);
    $codigo = sprintf('LR-%d-%06d', $anio, $numero);

    $sql = 'INSERT INTO reclamaciones (
        numero, codigo, constancia_token, fecha_registro,
        tipo_persona, nombre_razon_social, tipo_documento, numero_documento,
        domicilio, telefono, email,
        es_menor, representante_nombre, representante_documento,
        tipo_bien, servicio, descripcion_bien, monto_reclamado,
        tipo, detalle, pedido, estado, fecha_limite_respuesta,
        canal_presentacion, ip_registro
        ) VALUES (
        ?, ?, ?, NOW(),
        ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, 'RECIBIDO', ?,
        'WEB', ?
    )';

    $pdo->prepare($sql)->execute([
        $numero,
        $codigo,
        $token,
        $tipoPersona,
        $nombre,
        $tipoDoc,
        $numeroDoc,
        $domicilio,
        $telefono,
        $email,
        $esMenor ? 1 : 0,
        $esMenor ? $repNombre : null,
        $esMenor ? $repDoc : null,
        $tipoBien,
        $servicio,
        $descripcionBien !== '' ? $descripcionBien : null,
        $monto,
        $tipo,
        $detalle,
        $pedido,
        $limite,
        lr_client_ip(),
    ]);

    $id = (int)$pdo->lastInsertId();
    lr_historial($id, 'RECLAMACION_CREADA', 'Registro desde web. Código ' . $codigo);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Libro reclamaciones: ' . $e->getMessage());
    lr_json_out([
        'success' => false,
        'message' => 'No fue posible registrar la Hoja de Reclamación. Intente de nuevo.',
    ], 500);
}

// Copia por correo (opcional)
$mailCfg = lr_config()['mail'] ?? [];
$emailEnviado = false;
if (!empty($mailCfg['enabled']) && !empty($mailCfg['from']) && filter_var($mailCfg['from'], FILTER_VALIDATE_EMAIL)) {
    $baseUrl = rtrim((string)(lr_config()['base_url'] ?? ''), '/');
    $link = $baseUrl . '/api/constancia.php?codigo=' . urlencode($codigo) . '&token=' . urlencode($token);
    $subject = 'Constancia Libro de Reclamaciones ' . $codigo;
    $body = "Su Hoja de Reclamación fue registrada.\n\n"
        . "Código: {$codigo}\n"
        . "Fecha límite de respuesta: {$limite}\n"
        . "Constancia: {$link}\n\n"
        . lr_config()['proveedor']['razon_social'] . "\n";
    $headers = 'From: ' . $mailCfg['from_name'] . ' <' . $mailCfg['from'] . ">\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n";
    $safeEmail = $email;
    if (@mail($safeEmail, $subject, $body, $headers)) {
        $emailEnviado = true;
        lr_historial($id, 'RESPUESTA_ENVIADA', 'Copia de constancia enviada al consumidor.');
    }
}

lr_json_out([
    'success' => true,
    'codigo' => $codigo,
    'fecha_limite_respuesta' => $limite,
    'constancia_url' => '/api/constancia.php?codigo=' . urlencode($codigo) . '&token=' . urlencode($token),
    'email_enviado' => $emailEnviado,
    'mensaje' => 'La Hoja de Reclamación fue registrada correctamente.',
]);
