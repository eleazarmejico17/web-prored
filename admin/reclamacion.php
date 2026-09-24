<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/auth.php';

$admin = lr_admin_require();
$pdo = lr_db();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM reclamaciones WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) {
    http_response_code(404);
    exit('Reclamación no encontrada.');
}

$estados = [
    'RECIBIDO' => 'Recibido',
    'EN_REVISION' => 'En revisión',
    'EN_ATENCION' => 'En atención',
    'PENDIENTE_INFORMACION' => 'Pendiente de información',
    'RESPONDIDO' => 'Respondido',
    'CERRADO' => 'Cerrado',
];

$mensaje = '';
$error = '';

// Actualizar estado / respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!lr_csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Sesión expirada.';
    } else {
        $accion = $_POST['accion'] ?? '';
        $nuevoEstado = $_POST['estado'] ?? '';
        $respuesta = trim((string)($_POST['respuesta'] ?? ''));
        $acciones = trim((string)($_POST['acciones_adoptadas'] ?? ''));
        $medio = trim((string)($_POST['medio_envio_respuesta'] ?? ''));

        if ($accion === 'actualizar') {
            if (!isset($estados[$nuevoEstado])) {
                $error = 'Estado inválido.';
            } else {
                $fechaRespuesta = null;
                if (in_array($nuevoEstado, ['RESPONDIDO', 'CERRADO'], true) && $respuesta !== '') {
                    $fechaRespuesta = date('Y-m-d H:i:s');
                }
                // Solo pisa respuesta/acciones/medio si el admin envió valor (no anula lo previo al vaciar)
                $sets = ['estado = ?', 'usuario_responsable = ?'];
                $updParams = [
                    $nuevoEstado,
                    $admin['username'],
                ];
                if ($medio !== '') {
                    $sets[] = 'medio_envio_respuesta = ?';
                    $updParams[] = $medio;
                }
                if ($respuesta !== '') {
                    $sets[] = 'respuesta = ?';
                    $updParams[] = $respuesta;
                }
                if ($acciones !== '') {
                    $sets[] = 'acciones_adoptadas = ?';
                    $updParams[] = $acciones;
                }
                if ($fechaRespuesta !== null) {
                    $sets[] = 'fecha_respuesta = COALESCE(?, fecha_respuesta)';
                    $updParams[] = $fechaRespuesta;
                }
                $updParams[] = $id;
                $pdo->prepare(
                    'UPDATE reclamaciones SET ' . implode(', ', $sets) . ' WHERE id = ?'
                )->execute($updParams);
                lr_historial($id, 'ESTADO_CAMBIADO', 'Estado → ' . $nuevoEstado, (int)$admin['id']);
                if ($respuesta !== '' && !empty($r['email'])) {
                    $mailCfg = lr_config()['mail'] ?? [];
                    if (!empty($mailCfg['enabled']) && !empty($mailCfg['from'])) {
                        $headers = 'From: ' . $mailCfg['from_name'] . ' <' . $mailCfg['from'] . ">\r\n"
                            . "Content-Type: text/plain; charset=UTF-8\r\n";
                        @mail(
                            $r['email'],
                            'Respuesta a su Hoja de Reclamación ' . $r['codigo'],
                            "Estimado(a) consumidor:\n\nCódigo: {$r['codigo']}\n\n{$respuesta}\n\n"
                            . lr_config()['proveedor']['razon_social'] . "\n",
                            $headers
                        );
                        lr_historial($id, 'RESPUESTA_ENVIADA', 'Respuesta enviada por correo.', (int)$admin['id']);
                    }
                }
                $mensaje = 'Cambios guardados.';
                // refrescar
                $stmt->execute([$id]);
                $r = $stmt->fetch();
            }
        }
    }
}

// Historial
$hist = $pdo->prepare(
    'SELECT h.*, u.username, u.nombre
     FROM reclamacion_historial h
     LEFT JOIN admin_users u ON u.id = h.usuario_id
     WHERE h.reclamacion_id = ?
     ORDER BY h.created_at DESC'
);
$hist->execute([$id]);
$historial = $hist->fetchAll();

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$vencido = $r['estado'] !== 'CERRADO' && $r['estado'] !== 'RESPONDIDO' && $r['fecha_limite_respuesta'] < date('Y-m-d');
$constanciaUrl = rtrim((string)(lr_config()['base_url'] ?? ''), '/') . '/api/constancia.php?codigo='
    . urlencode($r['codigo']) . '&token=' . urlencode($r['constancia_token']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title><?= e($r['codigo']) ?> — Admin</title>
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="top">
  <div class="top-inner">
    <strong>Libro de Reclamaciones</strong>
    <nav>
      <a href="index.php">Listado</a>
      <span class="user"><?= e($admin['nombre'] ?: $admin['username']) ?></span>
      <a href="logout.php" class="logout">Salir</a>
    </nav>
  </div>
</header>

<main class="wrap">
  <p><a href="index.php">&larr; Volver al listado</a></p>

  <?php if ($mensaje): ?><div class="ok"><?= e($mensaje) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>

  <div class="detail-grid">
    <section class="card">
      <div class="card-head">
        <h1 class="mono"><?= e($r['codigo']) ?></h1>
        <span class="tag st-<?= e(strtolower($r['estado'])) ?>"><?= e($estados[$r['estado']] ?? $r['estado']) ?></span>
        <span class="tag <?= $r['tipo'] === 'queja' ? 'tag-q' : 'tag-r' ?>"><?= e(strtoupper($r['tipo'])) ?></span>
        <?php if ($vencido): ?><span class="tag tag-venc">VENCIDO</span><?php endif; ?>
      </div>

      <dl class="kv">
        <dt>Registro</dt><dd><?= e(date('d/m/Y H:i', strtotime($r['fecha_registro']))) ?></dd>
        <dt>Límite de respuesta</dt><dd><?= e(date('d/m/Y', strtotime($r['fecha_limite_respuesta']))) ?></dd>
        <dt>Fecha de respuesta</dt><dd><?= $r['fecha_respuesta'] ? e(date('d/m/Y H:i', strtotime($r['fecha_respuesta']))) : '—' ?></dd>
        <dt>Medio de envío</dt><dd><?= e($r['medio_envio_respuesta'] ?: '—') ?></dd>
        <dt>Responsable</dt><dd><?= e($r['usuario_responsable'] ?: '—') ?></dd>
        <dt>Canal</dt><dd><?= e($r['canal_presentacion']) ?></dd>
        <dt>IP</dt><dd class="mono"><?= e($r['ip_registro'] ?: '—') ?></dd>
      </dl>

      <h2>Consumidor</h2>
      <dl class="kv">
        <dt>Tipo persona</dt><dd><?= $r['tipo_persona'] === 'juridica' ? 'Persona jurídica' : 'Persona natural' ?></dd>
        <dt>Nombre / Razón social</dt><dd><?= e($r['nombre_razon_social']) ?></dd>
        <dt>Documento</dt><dd><?= e(strtoupper($r['tipo_documento'])) ?> <?= e($r['numero_documento']) ?></dd>
        <dt>Domicilio</dt><dd><?= e($r['domicilio']) ?></dd>
        <dt>Teléfono</dt><dd><?= e($r['telefono']) ?></dd>
        <dt>Email</dt><dd><?= e($r['email']) ?></dd>
        <?php if ((int)$r['es_menor']): ?>
        <dt>Representante</dt><dd><?= e($r['representante_nombre']) ?> (<?= e($r['representante_documento']) ?>)</dd>
        <?php endif; ?>
      </dl>

      <h2>Bien / Servicio</h2>
      <dl class="kv">
        <dt>Tipo</dt><dd><?= $r['tipo_bien'] === 'producto' ? 'Producto' : 'Servicio' ?></dd>
        <dt>Servicio</dt><dd><?= e($r['servicio']) ?></dd>
        <dt>Descripción</dt><dd><?= e($r['descripcion_bien'] ?: '—') ?></dd>
        <dt>Monto</dt><dd><?= $r['monto_reclamado'] !== null ? 'S/ ' . e(number_format((float)$r['monto_reclamado'], 2)) : '—' ?></dd>
      </dl>

      <h2>Detalle del reclamo</h2>
      <pre class="texto"><?= e($r['detalle']) ?></pre>

      <h2>Pedido del consumidor</h2>
      <pre class="texto"><?= e($r['pedido']) ?></pre>

      <p class="constancia">
        <a href="<?= e($constanciaUrl) ?>" target="_blank" rel="noopener">Ver constancia (imprimible)</a>
        &nbsp;·&nbsp;
        <a href="<?= e($constanciaUrl) ?>" target="_blank" rel="noopener" class="btn-sm" id="btnPdf">Generar PDF / Imprimir</a>
      </p>
    </section>

    <section class="card">
      <h2>Atención</h2>
      <form method="post" action="reclamacion.php?id=<?= (int)$r['id'] ?>">
        <input type="hidden" name="csrf" value="<?= lr_csrf_token() ?>">
        <input type="hidden" name="accion" value="actualizar">

        <label>Estado</label>
        <select name="estado">
          <?php foreach ($estados as $val => $lab): ?>
          <option value="<?= e($val) ?>" <?= $r['estado'] === $val ? 'selected' : '' ?>><?= e($lab) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Respuesta al consumidor</label>
        <textarea name="respuesta" rows="6" placeholder="Texto de la respuesta…"><?= e($r['respuesta']) ?></textarea>

        <label>Acciones adoptadas</label>
        <textarea name="acciones_adoptadas" rows="3" placeholder="Medidas correctivas…"><?= e($r['acciones_adoptadas']) ?></textarea>

        <label>Medio de envío</label>
        <select name="medio_envio_respuesta">
          <option value="">— Sin definir —</option>
          <?php foreach (['Correo electrónico', 'WhatsApp', 'Teléfono', 'Presencial', 'Otro'] as $m): ?>
          <option value="<?= e($m) ?>" <?= $r['medio_envio_respuesta'] === $m ? 'selected' : '' ?>><?= e($m) ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit">Guardar cambios</button>
      </form>

      <h2>Historial</h2>
      <ul class="hist">
        <?php if (!$historial): ?><li>Sin movimientos.</li><?php endif; ?>
        <?php foreach ($historial as $h): ?>
        <li>
          <span class="mono"><?= e(date('d/m/Y H:i', strtotime($h['created_at']))) ?></span>
          — <strong><?= e($h['accion']) ?></strong>
          <?= $h['descripcion'] ? '· ' . e($h['descripcion']) : '' ?>
          <?= $h['username'] ? '<br><small>' . e($h['nombre'] ?: $h['username']) . '</small>' : '' ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </div>
</main>
</body>
</html>
