<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$codigo = trim((string)($_GET['codigo'] ?? ''));
$token = trim((string)($_GET['token'] ?? ''));

if ($codigo === '' || $token === '' || !preg_match('/^LR-\d{4}-\d{6}$/', $codigo) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    http_response_code(400);
    exit('Solicitud inválida.');
}

$stmt = lr_db()->prepare(
    'SELECT codigo, constancia_token, fecha_registro, tipo, estado,
            nombre_razon_social, tipo_documento, numero_documento,
            servicio, monto_reclamado, fecha_limite_respuesta, pedido
     FROM reclamaciones WHERE codigo = ? LIMIT 1'
);
$stmt->execute([$codigo]);
$r = $stmt->fetch();

if (!$r || !hash_equals($r['constancia_token'], $token)) {
    http_response_code(404);
    exit('Constancia no encontrada.');
}

$cfg = lr_config();
$p = $cfg['proveedor'];
$fecha = (new DateTimeImmutable($r['fecha_registro']))->format('d/m/Y H:i');
$estado = $r['estado'];
$tipo = strtoupper($r['tipo']);
$monto = $r['monto_reclamado'] !== null
    ? 'S/ ' . number_format((float)$r['monto_reclamado'], 2)
    : '—';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Constancia <?= lr_h($r['codigo']) ?></title>
<style>
  * { box-sizing: border-box; }
  body { font-family: Georgia, 'Times New Roman', serif; margin: 0; background: #f4f6f8; color: #222; }
  .wrap { max-width: 800px; margin: 24px auto; background: #fff; padding: 32px; border: 1px solid #ddd; }
  h1 { font-size: 1.25rem; margin: 0 0 4px; color: #005B9F; }
  h2 { font-size: 1rem; margin: 24px 0 8px; border-bottom: 2px solid #005B9F; padding-bottom: 4px; }
  .muted { color: #555; font-size: 0.9rem; }
  .code { font-family: Consolas, monospace; font-size: 1.4rem; font-weight: 700; color: #E58E21; }
  table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 0.95rem; }
  td, th { border: 1px solid #ccc; padding: 8px 10px; text-align: left; vertical-align: top; }
  th { background: #f0f5fa; width: 38%; }
  .actions { margin-top: 28px; display: flex; gap: 12px; flex-wrap: wrap; }
  .btn { display: inline-block; padding: 10px 18px; background: #005B9F; color: #fff; text-decoration: none; border: 0; cursor: pointer; font-size: 1rem; }
  .btn.alt { background: #E58E21; }
  .legal { margin-top: 24px; font-size: 0.8rem; color: #444; line-height: 1.5; }
  @media print {
    body { background: #fff; }
    .wrap { border: 0; margin: 0; padding: 0; max-width: none; }
    .actions { display: none; }
  }
</style>
</head>
<body>
<div class="wrap">
  <p class="muted"><?= lr_h($p['razon_social']) ?> · RUC <?= lr_h($p['ruc']) ?></p>
  <h1>Hoja de Reclamación — Libro de Reclamaciones</h1>
  <p class="muted">Constancia de registro (no sustituye el procedimiento OSIPTEL de telecomunicaciones).</p>

  <p>Código de seguimiento:</p>
  <p class="code"><?= lr_h($r['codigo']) ?></p>

  <table>
    <tr><th>Fecha y hora de registro</th><td><?= lr_h($fecha) ?> (hora Perú)</td></tr>
    <tr><th>Fecha límite de respuesta</th><td><?= lr_h(date('d/m/Y', strtotime($r['fecha_limite_respuesta']))) ?> (15 días hábiles)</td></tr>
    <tr><th>Tipo</th><td><?= lr_h($tipo) ?></td></tr>
    <tr><th>Estado</th><td><?= lr_h($estado) ?></td></tr>
    <tr><th>Consumidor</th><td><?= lr_h($r['nombre_razon_social']) ?> (<?= lr_h(strtoupper($r['tipo_documento'])) ?>: <?= lr_h($r['numero_documento']) ?>)</td></tr>
    <tr><th>Servicio</th><td><?= lr_h($r['servicio']) ?></td></tr>
    <tr><th>Monto reclamado</th><td><?= lr_h($monto) ?></td></tr>
    <tr><th>Pedido</th><td><?= nl2br(lr_h($r['pedido'])) ?></td></tr>
  </table>

  <div class="actions">
    <button type="button" class="btn" onclick="window.print()">Imprimir / Guardar PDF</button>
    <a class="btn alt" href="../libro-reclamaciones/">Volver al formulario</a>
  </div>

  <div class="legal">
    <p><strong><?= lr_h($p['razon_social']) ?></strong> — <?= lr_h($p['domicilio']) ?>. Responda por escrito en un máximo de <strong>15 días hábiles</strong> (Indecopi / D.S. 011-2011-PCM).</p>
    <p>Los reclamos por servicios de telecomunicaciones regulados por OSIPTEL pueden requerir un canal distinto; validación legal pendiente.</p>
  </div>
</div>
</body>
</html>
