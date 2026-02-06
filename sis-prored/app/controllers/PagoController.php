<?php
class PagoController
{
    private $pagoModel;

    public function __construct()
    {
        require_once 'models/PagoModel.php';
        $this->pagoModel = new PagoModel();
    }

    // Generar comprobante PDF
    public function generarComprobantePago($pagoId)
    {
        $pago = $this->pagoModel->getPagoById($pagoId);

        if (!$pago) {
            return ['success' => false, 'error' => 'Pago no encontrado'];
        }

        require_once 'libraries/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();

        // Datos para el comprobante
        $datosComprobante = [
            'numero' => $pago['numero_comprobante'],
            'fecha' => date('d/m/Y', strtotime($pago['fecha_pago'])),
            'cliente' => $pago['nombre_cliente'],
            'dni' => $pago['dni_cliente'],
            'direccion' => $pago['direccion_servicio'],
            'plan' => $pago['nombre_plan'],
            'desglose' => [
                'mensualidad' => $pago['monto_base'],
                'cargos_adicionales' => $pago['cargos_adicionales'],
                'mora' => $pago['mora'],
                'total' => $pago['total_pagado']
            ],
            'metodo_pago' => $pago['metodo_pago'],
            'numero_operacion' => $pago['numero_operacion'],
            'periodo' => $pago['periodo'],
            'proximo_vencimiento' => $pago['proximo_vencimiento']
        ];

        $rutaPdf = $pdfGenerator->generarComprobantePago($datosComprobante);

        if ($rutaPdf) {
            // Guardar ruta en base de datos
            $this->pagoModel->guardarRutaComprobante($pagoId, $rutaPdf);

            return ['success' => true, 'ruta_pdf' => $rutaPdf];
        }

        return ['success' => false, 'error' => 'Error al generar el comprobante'];
    }

    // Enviar notificaciones de pago
    public function enviarRecordatoriosPago()
    {
        $recordatorios = $this->pagoModel->obtenerRecordatoriosPendientes();

        $resultados = [];
        foreach ($recordatorios as $recordatorio) {
            $resultado = $this->enviarRecordatorio($recordatorio);
            $resultados[] = $resultado;
        }

        return $resultados;
    }

    private function enviarRecordatorio($recordatorio)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $mensaje = $this->generarMensajeRecordatorio($recordatorio);

        // Enviar por WhatsApp
        $resultadoWhatsApp = $notificador->enviarWhatsApp(
            $recordatorio['telefono_cliente'],
            $mensaje
        );

        // Enviar por email si está configurado
        if (!empty($recordatorio['email_cliente'])) {
            $resultadoEmail = $notificador->enviarEmail(
                $recordatorio['email_cliente'],
                'Recordatorio de pago - ProRed',
                $mensaje
            );
        }

        // Registrar envío
        $this->pagoModel->registrarEnvioRecordatorio($recordatorio['id'], 'WHATSAPP');

        return ['success' => true, 'cliente' => $recordatorio['nombre_cliente']];
    }

    private function generarMensajeRecordatorio($recordatorio)
    {
        $diasMora = $recordatorio['dias_mora'];
        $totalPagar = $recordatorio['total_pendiente'];

        if ($diasMora == -5) {
            return "Recordatorio: Su pago vence en 5 días.\n"
                . "Monto: S/ " . number_format($totalPagar, 2) . "\n"
                . "Vence: " . $recordatorio['fecha_vencimiento'] . "\n"
                . "Datos para pago:\n"
                . "BCP: 123-456789-0-01\n"
                . "Interbank: 456-789123-1-02";
        } elseif ($diasMora == 0) {
            return "Hoy vence su pago.\n"
                . "Monto: S/ " . number_format($totalPagar, 2) . "\n"
                . "Evite suspensiones realizando su pago hoy.";
        } elseif ($diasMora > 0) {
            return "ALERTA: Su pago tiene {$diasMora} días de mora.\n"
                . "Monto total: S/ " . number_format($totalPagar, 2) . "\n"
                . "Su servicio será suspendido en " . (3 - $diasMora) . " días.\n"
                . "Regularice su deuda para evitar suspensiones.";
        }

        return "";
    }
}
?>