<?php
// print_receipt.php
// A simple standalone script to render a receipt. 
// Ideally this would be a View rendered by a Controller, but for simplicity/speed we'll do it here.

// Adjust paths to reach app core
require_once __DIR__ . '/../../config/config.php'; // If exists, or just manual connect
require_once __DIR__ . '/../../models/VentasModel.php';

// Quick DB Connect (Copying from BaseModel logic for standalone script)
// In a real app, this should go through the router.
class ReceiptPrinter
{
    private $model;

    public function __construct()
    {
        $this->model = new VentasModel();
    }

    public function render($id)
    {
        $data = $this->model->getReceiptData($id);

        if (!$data) {
            die("Comprobante no encontrado.");
        }
        ?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Comprobante de Pago -
                <?php echo $data['numero_operacion'] ?? 'S/N'; ?>
            </title>
            <style>
                body {
                    font-family: 'Courier New', Courier, monospace;
                    max-width: 300px;
                    margin: 0 auto;
                    padding: 20px;
                    background: #fff;
                }

                .header {
                    text-align: center;
                    border-bottom: 1px dashed #000;
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                }

                .logo {
                    font-weight: bold;
                    font-size: 1.2em;
                }

                .info {
                    font-size: 0.9em;
                    margin-bottom: 5px;
                }

                .table {
                    width: 100%;
                    font-size: 0.9em;
                    margin-bottom: 10px;
                }

                .table td {
                    padding: 2px 0;
                }

                .total {
                    border-top: 1px dashed #000;
                    border-bottom: 1px dashed #000;
                    padding: 5px 0;
                    margin-top: 10px;
                    font-weight: bold;
                    text-align: right;
                }

                .footer {
                    text-align: center;
                    font-size: 0.8em;
                    margin-top: 20px;
                }

                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>

        <body onload="window.print()">
            <div class="header">
                <div class="logo">ISP PRORED</div>
                <div class="info">RUC: 20123456789</div>
                <div class="info">Av. Principal 123, Lima</div>
                <div class="info">Tel: (01) 123-4567</div>
            </div>

            <div class="info">
                <strong>Comprobante de Pago</strong><br>
                Fecha:
                <?php echo date('d/m/Y H:i', strtotime($data['fecha_pago'])); ?><br>
                Nro:
                <?php echo $data['referencia']; ?><br>
                Cliente:
                <?php echo $data['nombre_cliente']; ?><br>
                DNI/RUC:
                <?php echo $data['dni']; ?>
            </div>

            <br>
            <table class="table">
                <tr>
                    <td>Concepto</td>
                    <td style="text-align: right;">Monto</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: 1px solid #eee;"></td>
                </tr>
                <tr>
                    <td>Pago Servicio Internet<br><small>
                            <?php echo $data['nombre_plan']; ?>
                        </small></td>
                    <td style="text-align: right;">S/
                        <?php echo number_format($data['monto'], 2); ?>
                    </td>
                </tr>
            </table>

            <div class="total">
                TOTAL: S/
                <?php echo number_format($data['monto'], 2); ?>
            </div>

            <div class="info">
                <small>Operación:
                    <?php echo $data['numero_operacion'] ?? '-'; ?>
                </small>
            </div>

            <div class="footer">
                <p>¡Gracias por su preferencia!</p>
                <p>www.prored.com.pe</p>
            </div>

            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Imprimir</button>
            </div>
        </body>

        </html>
        <?php
    }
}

$id = $_GET['id'] ?? null;
if ($id) {
    $printer = new ReceiptPrinter();
    $printer->render($id);
} else {
    echo "ID Requerido";
}
?>