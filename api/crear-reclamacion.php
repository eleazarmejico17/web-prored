<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/constancia.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    lr_json_out(['success' => false, 'message' => 'Método no permitido.'], 405);
}

// Intentar leer JSON o formulario
$raw = file_get_contents('php://input') ?: '';
if (strlen($raw) > 65536) {
    lr_json_out(['success' => false, 'message' => 'Cuerpo de la solicitud demasiado grande.'], 413);
}
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

// Origen (complemento al CSRF): Origin/Referer, si vienen, deben ser del mismo host
if (!lr_mismo_origen()) {
    lr_json_out(['success' => false, 'message' => 'Origen no permitido.'], 403);
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
    // Saneado: sin etiquetas HTML ni caracteres de control (defensa XSS en almacenamiento)
    return lr_txt((string)($data[$k] ?? ''), $max);
};

$tipoPersona = $data['tipo_persona'] ?? '';
if (!in_array($tipoPersona, ['natural', 'juridica'], true)) {
    $errors['tipo_persona'] = 'Seleccione tipo de persona.';
    $tipoPersona = 'natural';
}
$nombre = $str('nombre_razon_social', 200);
$tipoDoc = $data['tipo_documento'] ?? '';
$numeroDoc = preg_replace('/\s+/', '', $str('numero_documento', 20));
$domicilio = $str('domicilio', 255);
$telefonoRaw = $str('telefono', 30);
$telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
$emailRaw = $str('email', 150);
$email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ? $emailRaw : '';

// Formato de documento según tipo
$docOk = false;
if (!in_array($tipoDoc, ['dni', 'ce', 'ruc', 'otro'], true)) {
    $errors['tipo_documento'] = 'Seleccione el tipo de documento.';
} else {
    switch ($tipoDoc) {
        case 'dni':
            $docOk = (bool)preg_match('/^\d{8}$/', $numeroDoc);
            if (!$docOk) {
                $errors['numero_documento'] = 'El DNI debe tener 8 dígitos.';
            }
            break;
        case 'ruc':
            $docOk = (bool)preg_match('/^\d{11}$/', $numeroDoc);
            if (!$docOk) {
                $errors['numero_documento'] = 'El RUC debe tener 11 dígitos.';
            }
            break;
        case 'ce':
            $docOk = (bool)preg_match('/^[0-9A-Za-z-]{6,12}$/', $numeroDoc);
            if (!$docOk) {
                $errors['numero_documento'] = 'Carné de extranjería inválido (6–12 caracteres).';
            }
            break;
        default: // otro
            $docOk = (bool)preg_match('/^[0-9A-Za-z-]{5,20}$/', $numeroDoc);
            if (!$docOk) {
                $errors['numero_documento'] = 'Número de documento inválido (5–20 caracteres).';
            }
    }
}

// Persona jurídica → debe identificarse con RUC
if ($tipoPersona === 'juridica' && $tipoDoc !== 'ruc') {
    $errors['tipo_documento'] = 'Para persona jurídica seleccione RUC.';
    $docOk = false;
}
if ($numeroDoc === '' && !isset($errors['numero_documento'])) {
    $errors['numero_documento'] = 'El número de documento es obligatorio.';
}

// Nombre / razón social
if ($nombre === '') {
    $errors['nombre_razon_social'] = 'Nombre o razón social es obligatorio.';
} elseif (mb_strlen($nombre) < 3) {
    $errors['nombre_razon_social'] = 'Ingrese al menos 3 caracteres.';
} elseif (!preg_match('/^[\p{L}\p{N} .,&\'\/-]+$/u', $nombre)) {
    $errors['nombre_razon_social'] = 'Nombre con caracteres no válidos.';
}

if ($domicilio === '') {
    $errors['domicilio'] = 'Domicilio es obligatorio.';
} elseif (mb_strlen($domicilio) < 5) {
    $errors['domicilio'] = 'Ingrese una dirección más completa.';
}

// Teléfono Perú: móvil 9 dígitos (empieza con 9); fijo 7–8 dígitos o 9 con 0 inicial (área); admite +51
$digits = preg_replace('/\D/', '', $telefono);
if (str_starts_with($telefono, '+51')) {
    $digits = substr($digits, 2);
}
$len = strlen($digits);
$telOk = ($len === 9 && $digits[0] === '9')          // móvil
    || ($len === 9 && $digits[0] === '0')            // fijo con código de área (01, 064…)
    || ($len >= 7 && $len <= 8);                     // fijo sin código
if ($telefono === '' || !$telOk) {
    $errors['telefono'] = 'Teléfono inválido (ej. 999 999 999 o 064 123456).';
}

if ($emailRaw === '' || $email === '') {
    $errors['email'] = 'Correo electrónico inválido.';
}

$esMenor = !empty($data['es_menor']) && $data['es_menor'] !== '0' && $data['es_menor'] !== 'no';
$repNombre = '';
$repDoc = '';
if ($esMenor) {
    $repNombre = $str('representante_nombre', 200);
    $repDoc = preg_replace('/\s+/', '', $str('representante_documento', 30));
    if (mb_strlen($repNombre) < 3) {
        $errors['representante_nombre'] = 'Nombre del representante obligatorio (mín. 3 caracteres).';
    }
    if (!preg_match('/^\d{8}$/', $repDoc) && !preg_match('/^[0-9A-Za-z-]{5,20}$/', $repDoc)) {
        $errors['representante_documento'] = 'Documento del representante inválido.';
    }
}

$tipoBien = in_array($data['tipo_bien'] ?? '', ['producto', 'servicio'], true)
    ? $data['tipo_bien'] : 'servicio';
$servicio = $str('servicio', 100);
$descripcionBien = $str('descripcion_bien', 500);
if ($servicio === '') {
    $errors['servicio'] = 'Seleccione o describa el servicio.';
} elseif (mb_strlen($servicio) < 3) {
    $errors['servicio'] = 'Describa el servicio con al menos 3 caracteres.';
}

$monto = null;
if (isset($data['monto_reclamado']) && $data['monto_reclamado'] !== '' && $data['monto_reclamado'] !== null) {
    $montoRaw = str_replace([',', ' '], '.', (string)$data['monto_reclamado']);
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $montoRaw)) {
        $errors['monto_reclamado'] = 'Monto inválido (solo números, ej. 99.90).';
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

$detalle = lr_txt((string)($data['detalle'] ?? ''), 5000);
$pedido = lr_txt((string)($data['pedido'] ?? ''), 3000);
if ($detalle === '') {
    $errors['detalle'] = 'El detalle es obligatorio.';
} elseif (mb_strlen($detalle) < 20) {
    $errors['detalle'] = 'Describe los hechos con al menos 20 caracteres.';
} elseif (mb_strlen($detalle) > 5000) {
    $errors['detalle'] = 'El detalle no puede superar 5000 caracteres.';
}
if ($pedido === '') {
    $errors['pedido'] = 'El pedido del consumidor es obligatorio.';
} elseif (mb_strlen($pedido) < 10) {
    $errors['pedido'] = 'Especifica tu pedido con al menos 10 caracteres.';
} elseif (mb_strlen($pedido) > 3000) {
    $errors['pedido'] = 'El pedido no puede superar 3000 caracteres.';
}

// Declaración de veracidad: obligatoria (no basta con omitir el campo)
$declaro = $data['declaro'] ?? null;
if ($declaro === null || $declaro === false || $declaro === ''
    || in_array($declaro, ['0', 'no', 'false'], true)) {
    $errors['declaro'] = 'Debe declarar la veracidad de la información.';
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
        ?, ?, ?, \'RECIBIDO\', ?,
        \'WEB\', ?
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

// Acuse de recibo al consumidor (PDF adjunto). Nunca afecta el registro ya guardado.
$baseUrl = rtrim((string)(lr_config()['base_url'] ?? ''), '/');
$pdfUrl = $baseUrl . '/api/pdf.php?codigo=' . urlencode($codigo) . '&token=' . urlencode($token);
$emailEnviado = false;
try {
    $stmt = $pdo->prepare('SELECT * FROM reclamaciones WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $fila = $stmt->fetch();
    if ($fila && lr_email_recibido($fila)) {
        $emailEnviado = true;
        lr_historial($id, 'ACUSE_ENVIADO', 'Acuse de recibo enviado a ' . $email . ' con PDF adjunto.');
    }
} catch (Throwable $e) {
    error_log('Libro reclamaciones (correo): ' . $e->getMessage());
}

lr_json_out([
    'success' => true,
    'codigo' => $codigo,
    'fecha_limite_respuesta' => $limite,
    'pdf_url' => $pdfUrl,
    'email_enviado' => $emailEnviado,
    'mensaje' => 'La Hoja de Reclamación fue registrada correctamente.',
]);
