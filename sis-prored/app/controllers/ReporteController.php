<?php
class ReporteController
{
    private $reporteModel;

    public function __construct()
    {
        require_once 'models/ReporteModel.php';
        $this->reporteModel = new ReporteModel();
    }

    // Generar reporte financiero
    public function generarReporteFinanciero($tipo, $filtros = [])
    {
        switch ($tipo) {
            case 'mensual':
                return $this->reporteModel->getReporteFinancieroMensual($filtros);
            case 'anual':
                return $this->reporteModel->getReporteFinancieroAnual($filtros);
            case 'comparativo':
                return $this->reporteModel->getReporteComparativo($filtros);
            default:
                return ['error' => 'Tipo de reporte no válido'];
        }
    }

    // Generar reporte de morosidad
    public function generarReporteMorosidad($detallado = false)
    {
        if ($detallado) {
            return $this->reporteModel->getReporteMorosidadDetallado();
        }
        return $this->reporteModel->getReporteMorosidadResumido();
    }

    // Exportar reporte
    public function exportarReporte($tipo, $formato, $datos)
    {
        switch ($formato) {
            case 'excel':
                return $this->exportarExcel($tipo, $datos);
            case 'pdf':
                return $this->exportarPDF($tipo, $datos);
            case 'csv':
                return $this->exportarCSV($tipo, $datos);
            default:
                return ['error' => 'Formato no soportado'];
        }
    }

    private function exportarExcel($tipo, $datos)
    {
        require_once 'libraries/ExcelExporter.php';
        $excelExporter = new ExcelExporter();

        $nombreArchivo = "reporte_{$tipo}_" . date('Ymd_His') . ".xlsx";
        $rutaArchivo = "exports/" . $nombreArchivo;

        $excelExporter->exportar($datos, $rutaArchivo);

        return ['success' => true, 'archivo' => $rutaArchivo];
    }

    private function exportarPDF($tipo, $datos)
    {
        require_once 'libraries/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();

        $nombreArchivo = "reporte_{$tipo}_" . date('Ymd_His') . ".pdf";
        $rutaArchivo = "exports/" . $nombreArchivo;

        $pdfGenerator->generarReporte($tipo, $datos, $rutaArchivo);

        return ['success' => true, 'archivo' => $rutaArchivo];
    }

    private function exportarCSV($tipo, $datos)
    {
        $nombreArchivo = "reporte_{$tipo}_" . date('Ymd_His') . ".csv";
        $rutaArchivo = "exports/" . $nombreArchivo;

        $archivo = fopen($rutaArchivo, 'w');

        // Escribir encabezados
        if (!empty($datos)) {
            fputcsv($archivo, array_keys($datos[0]));

            // Escribir datos
            foreach ($datos as $fila) {
                fputcsv($archivo, $fila);
            }
        }

        fclose($archivo);

        return ['success' => true, 'archivo' => $rutaArchivo];
    }
}
?>