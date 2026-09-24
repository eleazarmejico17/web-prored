<?php
declare(strict_types=1);

/**
 * Generador PDF mínimo en PHP puro (sin librerías externas).
 * Helvetica + WinAnsiEncoding — cubre español (á é í ó ú ñ ¿ ¡ — ·).
 */
final class SimplePdf
{
    public const PAGE_W = 595.28;   // A4 pt
    public const PAGE_H = 841.89;
    public const MARGIN = 48.0;

    public const BLUE = [0, 91, 159];
    public const BLUE_SOFT = [238, 244, 250];
    public const BLUE_LINE = [198, 218, 238];
    public const ORANGE = [229, 142, 33];
    public const TEXT = [34, 34, 34];
    public const MUTED = [95, 95, 95];
    public const LINE = [213, 220, 228];
    public const ROW_ALT = [250, 251, 252];
    public const LEGAL_BG = [255, 248, 236];
    public const WHITE = [255, 255, 255];

    /** @var array<int, string> */
    private array $pages = [];
    private string $current = '';
    private float $y = 0.0;
    private float $lineHeight = 14.0;
    private float $fontSize = 11.0;
    private string $font = 'F1';
    private bool $pageStarted = false;
    private int $pageNo = 0;
    private int $rowToggle = 0;

    public function __construct()
    {
        $this->newPage();
    }

    public function newPage(): void
    {
        if ($this->pageStarted) {
            $this->pages[] = $this->current;
        }
        $this->pageNo++;
        $this->current = '';
        $this->pageStarted = true;
        $this->y = self::PAGE_H - self::MARGIN;
        $this->font = 'F1';
        $this->fontSize = 11.0;
        $this->lineHeight = 14.0;

        if ($this->pageNo > 1) {
            $this->runningHeader();
        }
    }

    private function runningHeader(): void
    {
        $this->setFill(self::BLUE);
        $this->rect(0.0, self::PAGE_H - 28.0, self::PAGE_W, 28.0, true);
        $prevFont = $this->font;
        $prevSize = $this->fontSize;
        $this->font = 'F2';
        $this->fontSize = 9.0;
        $this->setFill(self::WHITE);
        $this->text(self::MARGIN, self::PAGE_H - 19.0, 'Hoja de Reclamacion Virtual');
        $this->font = $prevFont;
        $this->fontSize = $prevSize;
        $this->y = self::PAGE_H - self::MARGIN - 10.0;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getPageNo(): int
    {
        return $this->pageNo;
    }

    /** @param array{0:int,1:int,2:int} $rgb */
    public function setFill(array $rgb): void
    {
        $this->current .= sprintf("%.3f %.3f %.3f rg\n", $rgb[0] / 255, $rgb[1] / 255, $rgb[2] / 255);
    }

    /** @param array{0:int,1:int,2:int} $rgb */
    public function setStroke(array $rgb): void
    {
        $this->current .= sprintf("%.3f %.3f %.3f RG\n", $rgb[0] / 255, $rgb[1] / 255, $rgb[2] / 255);
    }

    public function rect(float $x, float $y, float $w, float $h, bool $fill = false, bool $stroke = false): void
    {
        $op = $fill && $stroke ? 'B' : ($fill ? 'f' : ($stroke ? 'S' : 'S'));
        $this->current .= sprintf("%.2f %.2f %.2f %.2f re %s\n", $x, $y, $w, $h, $op);
    }

    public function line(float $x1, float $y1, float $x2, float $y2, float $width = 1.0): void
    {
        $this->current .= sprintf("%.2f w %.2f %.2f m %.2f %.2f l S\n", $width, $x1, $y1, $x2, $y2);
    }

    public function setFont(string $family, float $size): void
    {
        $this->font = ($family === 'bold') ? 'F2' : 'F1';
        $this->fontSize = $size;
        $this->lineHeight = $size * 1.35;
    }

    public function setLeading(float $lh): void
    {
        $this->lineHeight = $lh;
    }

    /** Ancho aproximado en pt del texto con la fuente activa. */
    public function width(string $s): float
    {
        $len = function_exists('mb_strlen') ? mb_strlen($s) : strlen($s);
        return $len * $this->fontSize * 0.50;
    }

    public function text(float $x, float $y, string $s): float
    {
        $enc = $this->encode($s);
        $this->current .= sprintf(
            "BT /%s %.2f Tf %.2f %.2f Td (%s) Tj ET\n",
            $this->font,
            $this->fontSize,
            $x,
            $y,
            $enc
        );
        return $this->width($s);
    }

    public function textRight(float $rightX, float $y, string $s): void
    {
        $w = $this->width($s);
        $this->text($rightX - $w, $y, $s);
    }

    public function textCenter(float $centerX, float $y, string $s): void
    {
        $w = $this->width($s);
        $this->text($centerX - $w / 2, $y, $s);
    }

    /** @return array<int, string> */
    public function wrapText(string $s, float $maxWidth): array
    {
        $words = preg_split('/\s+/u', trim($s), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (!$words) {
            return [''];
        }
        $lines = [];
        $line = '';
        foreach ($words as $w) {
            $try = ($line === '') ? $w : $line . ' ' . $w;
            if ($this->width($try) > $maxWidth && $line !== '') {
                $lines[] = $line;
                $line = $w;
            } else {
                $line = $try;
            }
        }
        if ($line !== '') {
            $lines[] = $line;
        }
        return $lines;
    }

    public function ensureSpace(float $needed): void
    {
        if ($this->y - $needed < self::MARGIN + 18.0) {
            $this->newPage();
        }
    }

    public function writeLine(string $s, float $indent = 0.0, float $x = 0.0): void
    {
        $this->ensureSpace($this->lineHeight);
        $xx = $x > 0 ? $x : self::MARGIN + $indent;
        $this->text($xx, $this->y - $this->fontSize, $s);
        $this->y -= $this->lineHeight;
    }

    public function writeWrapped(string $s, float $indent = 0.0, float $maxWidth = 0.0): void
    {
        $left = self::PAGE_W - 2 * self::MARGIN - $indent;
        if ($maxWidth > 0) {
            $left = $maxWidth;
        }
        $lines = $this->wrapText($s, $left);
        foreach ($lines as $ln) {
            $this->writeLine($ln, $indent);
        }
    }

    public function spacer(float $h): void
    {
        $this->y -= $h;
        if ($this->y < self::MARGIN) {
            $this->newPage();
        }
    }

    // ------------------------------------------------------------------
    // Componentes de diseño
    // ------------------------------------------------------------------

    /** Barra superior a sangre con título y código. */
    public function heroHeader(string $title, string $subtitle, string $codigo): void
    {
        $h = 92.0;
        $y0 = self::PAGE_H - $h;
        $this->setFill(self::BLUE);
        $this->rect(0.0, $y0, self::PAGE_W, $h, true);
        // franja naranja inferior del hero
        $this->setFill(self::ORANGE);
        $this->rect(0.0, $y0, self::PAGE_W, 4.0, true);

        $this->setFill(self::WHITE);
        $this->setFont('bold', 18);
        $this->text(self::MARGIN, $y0 + 52.0, $title);
        $this->setFont('normal', 9.5);
        $this->setFill([214, 230, 246]);
        $this->text(self::MARGIN, $y0 + 36.0, $subtitle);

        $this->setFont('bold', 15);
        $this->setFill(self::ORANGE);
        $this->textRight(self::PAGE_W - self::MARGIN, $y0 + 52.0, $codigo);
        $this->setFont('normal', 7.5);
        $this->setFill([214, 230, 246]);
        $this->textRight(self::PAGE_W - self::MARGIN, $y0 + 38.0, 'CODIGO DE SEGUIMIENTO');

        $this->y = $y0 - 16.0;
    }

    /** Caja del proveedor (3 líneas clave/valor). */
    public function providerBox(array $p): void
    {
        $rows = [
            ['PROVEEDOR', $p['razon_social'] ?? ''],
            ['RUC', $p['ruc'] ?? ''],
            ['DOMICILIO', $p['domicilio'] ?? ''],
        ];
        $labelW = 92.0;
        $rowH = 15.5;
        $boxH = $rowH * count($rows) + 8.0;
        $x = self::MARGIN;
        $w = self::PAGE_W - 2 * self::MARGIN;
        $yTop = $this->y;
        $yBot = $yTop - $boxH;

        $this->setFill(self::BLUE_SOFT);
        $this->setStroke(self::BLUE_LINE);
        $this->rect($x, $yBot, $w, $boxH, true, true);
        $this->setFill(self::BLUE);
        $this->rect($x, $yBot, 3.5, $boxH, true);

        $this->setFont('bold', 8.5);
        $yy = $yTop - 4.0 - 10.5;
        foreach ($rows as [$k, $v]) {
            $this->setFill(self::BLUE);
            $this->text($x + 12.0, $yy, $k . ':');
            $this->setFont('normal', 9.5);
            $this->setFill(self::TEXT);
            $this->text($x + 12.0 + $labelW, $yy, (string)$v);
            $this->setFont('bold', 8.5);
            $yy -= $rowH;
        }
        $this->y = $yBot - 12.0;
    }

    /** Fila meta compacta (fecha, plazo, tipo, estado) en 4 columnas. */
    public function metaStrip(array $items): void
    {
        $x = self::MARGIN;
        $w = self::PAGE_W - 2 * self::MARGIN;
        $cols = max(1, count($items));
        $colW = $w / $cols;
        $h = 30.0;
        $yBot = $this->y - $h;

        $this->setFill(self::ROW_ALT);
        $this->setStroke(self::LINE);
        $this->rect($x, $yBot, $w, $h, true, true);

        $i = 0;
        foreach ($items as [$k, $v]) {
            $cx = $x + $i * $colW + 8.0;
            $this->setFont('normal', 7.0);
            $this->setFill(self::MUTED);
            $this->text($cx, $this->y - 11.0, mb_strtoupper($k));
            $this->setFont('bold', 9.5);
            $this->setFill(self::TEXT);
            $this->text($cx, $this->y - 24.0, (string)$v);
            if ($i > 0) {
                $this->setStroke(self::LINE);
                $this->line($x + $i * $colW, $this->y, $x + $i * $colW, $yBot, 0.6);
            }
            $i++;
        }
        $this->y = $yBot - 13.0;
    }

    /** Encabezado de sección numerado: "1. TÍTULO". */
    public function sectionHeader(string $num, string $title): void
    {
        $this->ensureSpace(34.0);
        $this->y -= 10.0;
        $x = self::MARGIN;
        $badge = 15.0;

        $this->setFill(self::BLUE);
        $this->rect($x, $this->y - 11.0, $badge, $badge, true);
        $this->setFill(self::WHITE);
        $this->setFont('bold', 9.5);
        $tw = $this->width($num);
        $this->text($x + ($badge - $tw) / 2, $this->y - 8.5, $num);

        $this->setFill(self::BLUE);
        $this->setFont('bold', 10.5);
        $this->text($x + $badge + 7.0, $this->y - 8.5, $title);

        $lineY = $this->y - 15.0;
        $this->setStroke(self::BLUE_LINE);
        $this->line($x, $lineY, self::PAGE_W - self::MARGIN, $lineY, 0.8);
        $this->y = $lineY - 6.0;
        $this->setFont('normal', 10);
        $this->setFill(self::TEXT);
    }

    /**
     * Fila de tabla con borde: etiqueta (columna gris) + valor (wrap).
     * Alterna fondo de fila para lectura.
     */
    public function tableRow(string $label, string $value, bool $forceWhite = false): void
    {
        $x = self::MARGIN;
        $w = self::PAGE_W - 2 * self::MARGIN;
        $labelW = 148.0;
        $valueW = $w - $labelW;

        $prevSize = $this->fontSize;
        $this->fontSize = 9.5;
        $lines = $this->wrapText($value !== '' ? $value : '—', $valueW - 14.0);
        $lineH = 12.0;
        $rowH = max(19.5, count($lines) * $lineH + 7.0);

        if ($this->y - $rowH < self::MARGIN + 18.0) {
            $this->newPage();
        }

        $yTop = $this->y;
        $yBot = $yTop - $rowH;
        $alt = !$forceWhite && ($this->rowToggle % 2 === 1);

        $this->setFill($alt ? self::ROW_ALT : self::WHITE);
        $this->rect($x, $yBot, $w, $rowH, true);
        $this->setFill($alt ? [242, 246, 250] : self::BLUE_SOFT);
        $this->rect($x, $yBot, $labelW, $rowH, true);

        $this->setStroke(self::LINE);
        $this->rect($x, $yBot, $w, $rowH, false, true);
        $this->line($x + $labelW, $yTop, $x + $labelW, $yBot, 0.6);

        $this->setFont('bold', 8.0);
        $this->setFill([60, 78, 98]);
        $this->text($x + 8.0, $yTop - 13.0, mb_strtoupper($label));

        $this->setFont('normal', 9.5);
        $this->setFill(self::TEXT);
        $yy = $yTop - 12.5;
        foreach ($lines as $ln) {
            $this->text($x + $labelW + 7.0, $yy, $ln);
            $yy -= $lineH;
        }

        $this->y = $yBot;
        $this->rowToggle++;
        $this->fontSize = $prevSize;
    }

    /** Bloque de texto libre dentro de un recuadro (detalle / pedido). */
    public function textPanel(string $text, float $minH = 0.0): void
    {
        $x = self::MARGIN;
        $w = self::PAGE_W - 2 * self::MARGIN;
        $pad = 8.0;

        $prev = $this->fontSize;
        $this->fontSize = 9.5;
        $lines = $this->wrapText($text !== '' ? $text : '—', $w - 2 * $pad);
        $lineH = 12.0;
        $h = max($minH, count($lines) * $lineH + 2 * $pad);

        if ($this->y - $h < self::MARGIN + 18.0) {
            $this->newPage();
        }

        $yTop = $this->y;
        $yBot = $yTop - $h;

        $this->setFill(self::WHITE);
        $this->setStroke(self::LINE);
        $this->rect($x, $yBot, $w, $h, true, true);
        $this->setFill(self::BLUE);
        $this->rect($x, $yBot, 3.0, $h, true);

        $this->setFont('normal', 9.5);
        $this->setFill(self::TEXT);
        $yy = $yTop - $pad - 8.0;
        foreach ($lines as $ln) {
            $this->text($x + $pad + 4.0, $yy, $ln);
            $yy -= $lineH;
        }

        $this->y = $yBot - 8.0;
        $this->fontSize = $prev;
    }

    /** Caja legal al pie (bg crema + barra naranja). */
    public function legalBox(array $lines, float $titleSize = 9.5): void
    {
        $x = self::MARGIN;
        $w = self::PAGE_W - 2 * self::MARGIN;
        $pad = 9.0;

        $content = [];
        foreach ($lines as $item) {
            [$kind, $txt] = $item;
            if ($kind === 'title') {
                $this->setFont('bold', $titleSize);
                $content[] = ['title', $this->wrapText($txt, $w - 2 * $pad), $titleSize];
            } else {
                $fs = ($kind === 'small') ? 7.6 : 8.2;
                $this->setFont('normal', $fs);
                $content[] = ['text', $this->wrapText($txt, $w - 2 * $pad), $fs, $kind];
            }
        }

        $h = $pad * 2 + 4.0;
        foreach ($content as $c) {
            $fs = $c[2];
            $h += count($c[1]) * ($fs * 1.40) + 3.0;
        }

        $this->ensureSpace($h + 6.0);
        $yTop = $this->y;
        $yBot = $yTop - $h;

        $this->setFill(self::LEGAL_BG);
        $this->setStroke(self::ORANGE);
        $this->rect($x, $yBot, $w, $h, true, true);
        $this->setFill(self::ORANGE);
        $this->rect($x, $yBot, 4.0, $h, true);

        $yy = $yTop - $pad - 7.0;
        foreach ($content as $c) {
            $fs = $c[2];
            $isTitle = $c[0] === 'title';
            $this->setFont($isTitle ? 'bold' : 'normal', $fs);
            if ($isTitle) {
                $this->setFill([140, 84, 10]);
            } elseif (($c[3] ?? '') === 'muted') {
                $this->setFill([120, 100, 70]);
            } else {
                $this->setFill([90, 70, 40]);
            }
            foreach ($c[1] as $ln) {
                $this->text($x + $pad + 6.0, $yy, $ln);
                $yy -= $fs * 1.40;
            }
            $yy -= 3.0;
        }

        $this->y = $yBot;
    }

    public function footerNote(string $s): void
    {
        $this->y -= 6.0;
        $this->setFont('normal', 7.5);
        $this->setFill(self::MUTED);
        $lines = $this->wrapText($s, self::PAGE_W - 2 * self::MARGIN);
        foreach ($lines as $ln) {
            $this->ensureSpace(11.0);
            $this->text(self::MARGIN, $this->y - 7.0, $ln);
            $this->y -= 10.5;
        }
        $this->setFont('normal', 10);
        $this->setFill(self::TEXT);
    }

    // ------------------------------------------------------------------
    // Output
    // ------------------------------------------------------------------

    public function output(string $filename = ''): string
    {
        if ($this->pageStarted) {
            $this->pages[] = $this->current;
            $this->pageStarted = false;
        }

        $nPages = count($this->pages);
        $fontF1 = 3 + $nPages * 2;
        $fontF2 = $fontF1 + 1;

        $pageObjIds = [];
        $contentObjIds = [];
        for ($i = 0; $i < $nPages; $i++) {
            $pid = 3 + $i * 2;
            $pageObjIds[] = $pid;
            $contentObjIds[] = $pid + 1;
        }

        // Pie de página con paginación en cada página
        $genFecha = date('d/m/Y H:i');
        foreach ($this->pages as $i => $content) {
            $num = $i + 1;
            $lineY = 34.0;
            $content .= sprintf(
                "0.83 0.86 0.90 RG 0.6 w %.2f %.2f m %.2f %.2f l S\n",
                self::MARGIN,
                $lineY,
                self::PAGE_W - self::MARGIN,
                $lineY
            );
            $content .= sprintf(
                "BT /F1 7.5 Tf 0.45 0.45 0.45 rg %.2f %.2f Td (%s) Tj ET\n",
                self::MARGIN,
                22.0,
                $this->encode('Libro de Reclamaciones · ProRed')
            );
            $pageLabel = sprintf('Pagina %d de %d', $num, $nPages);
            $this->fontSize = 7.5;
            $pw = $this->width($pageLabel);
            $content .= sprintf(
                "BT /F1 7.5 Tf 0.45 0.45 0.45 rg %.2f %.2f Td (%s) Tj ET\n",
                self::PAGE_W - self::MARGIN - $pw,
                22.0,
                $this->encode($pageLabel)
            );
            $gen = 'Generado: ' . $genFecha;
            $this->fontSize = 7.5;
            $gw = $this->width($gen);
            $content .= sprintf(
                "BT /F1 7.5 Tf 0.55 0.55 0.55 rg %.2f %.2f Td (%s) Tj ET\n",
                (self::PAGE_W - $gw) / 2,
                22.0,
                $this->encode($gen)
            );
            $this->pages[$i] = $content;
        }

        $objects = [];
        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
        $kidsStr = implode(' ', array_map(fn($id) => $id . ' 0 R', $pageObjIds));
        $objects[2] = "<< /Type /Pages /Kids [{$kidsStr}] /Count {$nPages} >>";

        foreach ($this->pages as $i => $content) {
            $pid = $pageObjIds[$i];
            $cid = $contentObjIds[$i];
            $objects[$pid] = sprintf(
                "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2f %.2f] /Contents %d 0 R /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> >>",
                self::PAGE_W,
                self::PAGE_H,
                $cid,
                $fontF1,
                $fontF2
            );
            $objects[$cid] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";
        }

        $objects[$fontF1] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>";
        $objects[$fontF2] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>";

        $infoId = $fontF2 + 1;
        $title = $this->encode($filename !== '' ? basename($filename, '.pdf') : 'Hoja de Reclamacion');
        $objects[$infoId] = "<< /Title ({$title}) /Producer (ProRed SimplePdf) >>";

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];
        ksort($objects);
        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
        }
        $xrefPos = strlen($pdf);
        $maxId = max(array_keys($objects));
        $pdf .= "xref\n0 " . ($maxId + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            if (isset($offsets[$i])) {
                $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
            } else {
                $pdf .= "0000000000 65535 f \n";
            }
        }
        $pdf .= "trailer\n<< /Size " . ($maxId + 1) . " /Root 1 0 R /Info {$infoId} 0 R >>\n";
        $pdf .= "startxref\n{$xrefPos}\n%%EOF\n";
        return $pdf;
    }

    private function encode(string $s): string
    {
        $enc = false;
        foreach (['Windows-1252', 'CP1252', 'ISO-8859-1'] as $cs) {
            $enc = @iconv('UTF-8', $cs . '//TRANSLIT//IGNORE', $s);
            if ($enc !== false) {
                break;
            }
        }
        if ($enc === false) {
            $enc = preg_replace('/[^\x20-\x7E]/', '?', $s) ?? $s;
        }
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $enc);
    }
}
