<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/constancia.php';

$codigo = trim((string)($_GET['codigo'] ?? ''));
$token = trim((string)($_GET['token'] ?? ''));
$download = isset($_GET['dl']) && $_GET['dl'] !== '0';

if ($codigo === '' || $token === '' || !preg_match('/^LR-\d{4}-\d{6}$/', $codigo) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    http_response_code(400);
    exit('Solicitud inválida.');
}

$stmt = lr_db()->prepare('SELECT * FROM reclamaciones WHERE codigo = ? LIMIT 1');
$stmt->execute([$codigo]);
$r = $stmt->fetch();

if (!$r || !hash_equals($r['constancia_token'], $token)) {
    http_response_code(404);
    exit('Constancia no encontrada.');
}

$content = lr_pdf_constancia($r);

header('Content-Type: application/pdf');
header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . $r['codigo'] . '.pdf"');
header('Content-Length: ' . strlen($content));
header('Cache-Control: private, max-age=300');
echo $content;
