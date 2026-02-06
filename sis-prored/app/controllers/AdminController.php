<?php
class AdminController
{
    private $adminModel;
    private $usuarioModel;
    private $planModel;
    private $materialModel;

    public function __construct()
    {
        require_once 'models/AdminModel.php';
        require_once 'models/UsuarioModel.php';
        require_once 'models/PlanModel.php';
        require_once 'models/MaterialModel.php';

        $this->adminModel = new AdminModel();
        $this->usuarioModel = new UsuarioModel();
        $this->planModel = new PlanModel();
        $this->materialModel = new MaterialModel();
    }

    // RF-A01: Gestionar Empleados y Usuarios del Sistema
    public function crearEmpleado($datosEmpleado)
    {
        // Generar contraseña temporal
        $passwordTemp = $this->generarPasswordTemporal();
        $datosEmpleado['password_temp'] = $passwordTemp;

        $resultado = $this->adminModel->crearEmpleado($datosEmpleado);

        if ($resultado) {
            // Enviar credenciales por email
            $this->enviarCredenciales($datosEmpleado['email'], $passwordTemp);

            return ['success' => true, 'password_temp' => $passwordTemp];
        }

        return ['success' => false, 'error' => 'Error al crear el empleado'];
    }

    public function actualizarEmpleado($empleadoId, $datosEmpleado)
    {
        return $this->adminModel->actualizarEmpleado($empleadoId, $datosEmpleado);
    }

    public function cambiarEstadoEmpleado($empleadoId, $activo)
    {
        return $this->adminModel->cambiarEstadoEmpleado($empleadoId, $activo);
    }

    public function resetearPassword($empleadoId)
    {
        $passwordTemp = $this->generarPasswordTemporal();

        $resultado = $this->adminModel->resetearPassword($empleadoId, $passwordTemp);

        if ($resultado) {
            // Obtener email del empleado
            $empleado = $this->usuarioModel->getUsuarioById($empleadoId);

            // Enviar nueva contraseña por email
            $this->enviarNuevaPassword($empleado['email'], $passwordTemp);

            return ['success' => true, 'password_temp' => $passwordTemp];
        }

        return ['success' => false, 'error' => 'Error al resetear la contraseña'];
    }

    // RF-A02: Configurar Parámetros de Morosidad
    public function obtenerConfiguracionMorosidad()
    {
        return $this->adminModel->getConfiguracionMorosidad();
    }

    public function guardarConfiguracionMorosidad($configuracion)
    {
        // Validar valores
        $validacion = $this->validarConfiguracionMorosidad($configuracion);

        if (!$validacion['valido']) {
            return ['success' => false, 'error' => $validacion['mensaje']];
        }

        return $this->adminModel->guardarConfiguracionMorosidad($configuracion);
    }

    // RF-A03: Gestionar Catálogo de Planes
    public function crearPlan($datosPlan)
    {
        return $this->planModel->crearPlan($datosPlan);
    }

    public function actualizarPlan($planId, $datosPlan)
    {
        return $this->planModel->actualizarPlan($planId, $datosPlan);
    }

    public function cambiarEstadoPlan($planId, $activo)
    {
        return $this->planModel->cambiarEstadoPlan($planId, $activo);
    }

    // RF-A04: Gestionar Catálogo de Materiales Técnicos
    public function crearMaterial($datosMaterial)
    {
        return $this->materialModel->crearMaterial($datosMaterial);
    }

    public function actualizarMaterial($materialId, $datosMaterial)
    {
        return $this->materialModel->actualizarMaterial($materialId, $datosMaterial);
    }

    public function cambiarEstadoMaterial($materialId, $activo)
    {
        return $this->materialModel->cambiarEstadoMaterial($materialId, $activo);
    }

    // RF-A05: Configurar Métodos de Pago
    public function obtenerMetodosPago()
    {
        return $this->adminModel->getMetodosPago();
    }

    public function actualizarMetodosPago($metodosPago)
    {
        return $this->adminModel->actualizarMetodosPago($metodosPago);
    }

    // RF-A06: Visualizar Reportes y Estadísticas
    public function generarReporteFinanciero($fechaInicio, $fechaFin)
    {
        return $this->adminModel->getReporteFinanciero($fechaInicio, $fechaFin);
    }

    public function generarReporteMorosidad($filtros = [])
    {
        return $this->adminModel->getReporteMorosidad($filtros);
    }

    public function generarReporteTickets($fechaInicio, $fechaFin)
    {
        return $this->adminModel->getReporteTickets($fechaInicio, $fechaFin);
    }

    public function generarReporteVisitasTecnicas($fechaInicio, $fechaFin)
    {
        return $this->adminModel->getReporteVisitasTecnicas($fechaInicio, $fechaFin);
    }

    public function generarReporteServicios()
    {
        return $this->adminModel->getReporteServicios();
    }

    public function generarReporteEmpleados($periodo)
    {
        return $this->adminModel->getReporteEmpleados($periodo);
    }

    // RF-A07: Auditoría del Sistema
    public function obtenerLogsAuditoria($filtros = [])
    {
        return $this->adminModel->getLogsAuditoria($filtros);
    }

    // Métodos auxiliares
    private function generarPasswordTemporal()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }

    private function enviarCredenciales($email, $passwordTemp)
    {
        // Implementar envío de email con credenciales
        require_once 'libraries/EmailSender.php';
        $emailSender = new EmailSender();

        $asunto = "Credenciales de acceso - Sistema ProRed";
        $mensaje = "Bienvenido al Sistema ProRed.\n\n"
            . "Sus credenciales de acceso son:\n"
            . "Email: $email\n"
            . "Contraseña temporal: $passwordTemp\n\n"
            . "Por seguridad, cambie su contraseña después del primer acceso.\n\n"
            . "Acceda al sistema en: https://sistema.prored.com";

        $emailSender->enviarEmail($email, $asunto, $mensaje);
    }

    private function enviarNuevaPassword($email, $passwordTemp)
    {
        require_once 'libraries/EmailSender.php';
        $emailSender = new EmailSender();

        $asunto = "Contraseña restablecida - Sistema ProRed";
        $mensaje = "Su contraseña ha sido restablecida.\n\n"
            . "Su nueva contraseña temporal es: $passwordTemp\n\n"
            . "Por seguridad, cambie su contraseña después del acceso.\n\n"
            . "Acceda al sistema en: https://sistema.prored.com";

        $emailSender->enviarEmail($email, $asunto, $mensaje);
    }

    private function validarConfiguracionMorosidad($configuracion)
    {
        if ($configuracion['horas_suspension'] <= 0) {
            return ['valido' => false, 'mensaje' => 'Las horas para suspensión deben ser mayores a 0'];
        }

        if ($configuracion['horas_corte'] <= $configuracion['horas_suspension']) {
            return ['valido' => false, 'mensaje' => 'Las horas para corte deben ser mayores que las horas para suspensión'];
        }

        if ($configuracion['porcentaje_mora'] < 0 || $configuracion['porcentaje_mora'] > 100) {
            return ['valido' => false, 'mensaje' => 'El porcentaje de mora debe estar entre 0 y 100'];
        }

        return ['valido' => true];
    }
}
?>