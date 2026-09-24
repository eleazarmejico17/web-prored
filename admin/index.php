<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/auth.php';

$admin = lr_admin_require();
$pdo = lr_db();

// Filtros
$fEstado = trim((string)($_GET['estado'] ?? ''));
$fTipo = trim((string)($_GET['tipo'] ?? ''));
$fBusca = trim((string)($_GET['q'] ?? ''));
$fDesde = trim((string)($_GET['desde'] ?? ''));
$fHasta = trim((string)($_GET['hasta'] ?? ''));

$where = [];
$params = [];

$estados = ['RECIBIDO', 'EN_REVISION', 'EN_ATENCION', 'PENDIENTE_INFORMACION', 'RESPONDIDO', 'CERRADO'];
if (in_array($fEstado, $estados, true)) {
    $where[] = 'estado = ?';
    $params[] = $fEstado;
}
if (in_array($fTipo, ['reclamo', 'queja'], true)) {
    $where[] = 'tipo = ?';
    $params[] = $fTipo;
}
if ($fBusca !== '') {
    $where[] = '(codigo LIKE ? OR nombre_razon_social LIKE ? OR numero_documento LIKE ? OR email LIKE ?)';
    $like = '%' . addcslashes($fBusca, '%_\\') . '%';
    array_push($params, $like, $like, $like, $like);
}
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fDesde)) {
    $where[] = 'fecha_registro >= ?';
    $params[] = $fDesde . ' 00:00:00';
}
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fHasta)) {
    $where[] = 'fecha_registro <= ?';
    $params[] = $fHasta . ' 23:59:59';
}

$sqlWhere = $where ? (' WHERE ' . implode(' AND ', $where)) : '';

// KPIs (siempre globales, sin filtros) — "vencidos" alineado con la tabla:
// estado pendiente de respuesta (no RESPONDIDO ni CERRADO) y plazo vencido
$kpi = $pdo->query(
    "SELECT
        COUNT(*) AS total,
        SUM(estado = 'RECIBIDO') AS recibidos,
        SUM(estado IN ('EN_REVISION','EN_ATENCION')) AS en_proceso,
        SUM(estado = 'RESPONDIDO') AS respondidos,
        SUM(estado = 'CERRADO') AS cerrados,
        SUM(estado NOT IN ('RESPONDIDO','CERRADO') AND fecha_limite_respuesta < CURDATE()) AS vencidos
     FROM reclamaciones"
)->fetch();

$page = max(1, (int)($_GET['page'] ?? 1));
$porPagina = 20;
$offset = ($page - 1) * $porPagina;

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM reclamaciones' . $sqlWhere);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPaginas = max(1, (int)ceil($total / $porPagina));

$listParams = array_merge($params, [$porPagina, $offset]);
$listStmt = $pdo->prepare(
    'SELECT id, codigo, fecha_registro, tipo, estado, nombre_razon_social,
            tipo_documento, numero_documento, servicio, tipo_bien,
            fecha_limite_respuesta, email, telefono
     FROM reclamaciones' . $sqlWhere .
    ' ORDER BY fecha_registro DESC LIMIT ? OFFSET ?'
);
$listStmt->execute($listParams);
$rows = $listStmt->fetchAll();

$estadoLabel = [
    'RECIBIDO' => 'Recibido',
    'EN_REVISION' => 'En revisión',
    'EN_ATENCION' => 'En atención',
    'PENDIENTE_INFORMACION' => 'Pendiente de información',
    'RESPONDIDO' => 'Respondido',
    'CERRADO' => 'Cerrado',
];

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Reclamaciones — Admin</title>
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="top">
  <div class="top-inner">
    <strong>Libro de Reclamaciones</strong>
    <nav>
      <a href="index.php" class="active">Listado</a>
      <span class="user"><?= e($admin['nombre'] ?: $admin['username']) ?></span>
      <a href="logout.php" class="logout">Salir</a>
    </nav>
  </div>
</header>

<main class="wrap">
  <section class="kpis">
    <div class="kpi"><span class="n"><?= (int)$kpi['total'] ?></span><span class="l">Total</span></div>
    <div class="kpi"><span class="n"><?= (int)$kpi['recibidos'] ?></span><span class="l">Recibidos</span></div>
    <div class="kpi"><span class="n"><?= (int)$kpi['en_proceso'] ?></span><span class="l">En proceso</span></div>
    <div class="kpi"><span class="n"><?= (int)$kpi['respondidos'] ?></span><span class="l">Respondidos</span></div>
    <div class="kpi"><span class="n"><?= (int)$kpi['cerrados'] ?></span><span class="l">Cerrados</span></div>
    <div class="kpi warn"><span class="n"><?= (int)$kpi['vencidos'] ?></span><span class="l">Vencidos</span></div>
  </section>

  <form class="filters" method="get" action="index.php">
    <input type="search" name="q" placeholder="Código, nombre, DNI, email…" value="<?= e($fBusca) ?>">
    <select name="estado">
      <option value="">Todos los estados</option>
      <?php foreach ($estados as $st): ?>
      <option value="<?= e($st) ?>" <?= $fEstado === $st ? 'selected' : '' ?>><?= e($estadoLabel[$st]) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="tipo" title="Tipo">
      <option value="">Reclamos y quejas</option>
      <option value="reclamo" <?= $fTipo === 'reclamo' ? 'selected' : '' ?>>Solo reclamos</option>
      <option value="queja" <?= $fTipo === 'queja' ? 'selected' : '' ?>>Solo quejas</option>
    </select>
    <input type="date" name="desde" value="<?= e($fDesde) ?>" title="Desde">
    <input type="date" name="hasta" value="<?= e($fHasta) ?>" title="Hasta">
    <button type="submit">Filtrar</button>
    <a class="btn-link" href="index.php">Limpiar</a>
  </form>

  <p class="meta"><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></p>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Consumidor</th>
          <th>Servicio</th>
          <th>Estado</th>
          <th>Límite</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
        <tr><td colspan="8" class="empty">Sin resultados.</td></tr>
        <?php endif; ?>
        <?php foreach ($rows as $r): $vencido = $r['estado'] !== 'CERRADO' && $r['estado'] !== 'RESPONDIDO' && $r['fecha_limite_respuesta'] < date('Y-m-d'); ?>
        <tr>
          <td class="mono"><?= e($r['codigo']) ?></td>
          <td><?= e(date('d/m/Y H:i', strtotime($r['fecha_registro']))) ?></td>
          <td><span class="tag <?= $r['tipo'] === 'queja' ? 'tag-q' : 'tag-r' ?>"><?= e(strtoupper($r['tipo'])) ?></span></td>
          <td>
            <?= e($r['nombre_razon_social']) ?><br>
            <small><?= e(strtoupper($r['tipo_documento'])) ?> <?= e($r['numero_documento']) ?></small>
          </td>
          <td><?= e($r['servicio']) ?></td>
          <td><span class="tag st-<?= e(strtolower($r['estado'])) ?>"><?= e($estadoLabel[$r['estado']] ?? $r['estado']) ?></span></td>
          <td class="<?= $vencido ? 'vencido' : '' ?>"><?= e(date('d/m/Y', strtotime($r['fecha_limite_respuesta']))) ?></td>
          <td><a class="btn-sm" href="reclamacion.php?id=<?= (int)$r['id'] ?>">Ver</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPaginas > 1): ?>
  <nav class="pager">
    <?php for ($p = 1; $p <= $totalPaginas; $p++):
      $qs = $_GET;
      $qs['page'] = $p;
      $href = 'index.php?' . http_build_query($qs);
    ?>
      <a href="<?= e($href) ?>" class="<?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </nav>
  <?php endif; ?>
</main>
</body>
</html>
