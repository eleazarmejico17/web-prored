<?php
session_start();

class AuthController
{
    private $usuarioModel;
    private $clienteModel;

    public function __construct()
    {
        require_once 'models/UsuarioModel.php';
        require_once 'models/ClienteModel.php';
        $this->usuarioModel = new UsuarioModel();
        $this->clienteModel = new ClienteModel();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuario = $this->usuarioModel->validarCredenciales($email, $password);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol_nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];

                // Si es cliente, también guardamos su ID
                if ($usuario['rol_nombre'] == 'CLIENTE') {
                    $cliente = $this->clienteModel->getClienteByEmail($email);
                    if ($cliente) {
                        $_SESSION['cliente_id'] = $cliente['id_cliente'];
                    }
                }

                // Redirigir según rol
                $this->redirigirSegunRol($usuario['rol_nombre']);
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                header('Location: views/auth/login.php');
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: views/auth/login.php');
    }

    public function checkAuth($rolesPermitidos = [])
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: views/auth/login.php');
            exit();
        }

        if (!empty($rolesPermitidos) && !in_array($_SESSION['usuario_rol'], $rolesPermitidos)) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            $this->redirigirSegunRol($_SESSION['usuario_rol']);
            exit();
        }
    }

    private function redirigirSegunRol($rol)
    {
        switch ($rol) {
            case 'CLIENTE':
                header('Location: views/cliente/dashboard.php');
                break;
            case 'VENTAS':
                header('Location: views/ventas/dashboard.php');
                break;
            case 'SOPORTE':
                header('Location: views/soporte/dashboard.php');
                break;
            case 'TECNICO':
                header('Location: views/tecnico/dashboard.php');
                break;
            case 'ADMIN':
                header('Location: views/admin/dashboard.php');
                break;
            default:
                header('Location: views/auth/login.php');
        }
        exit();
    }

    public function cambiarPassword()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_SESSION['usuario_id'];
            $passwordActual = $_POST['password_actual'];
            $passwordNuevo = $_POST['password_nuevo'];
            $passwordConfirmar = $_POST['password_confirmar'];

            if ($passwordNuevo !== $passwordConfirmar) {
                $_SESSION['error'] = 'Las contraseñas no coinciden';
                return false;
            }

            $result = $this->usuarioModel->cambiarPassword($usuarioId, $passwordActual, $passwordNuevo);

            if ($result) {
                $_SESSION['success'] = 'Contraseña cambiada exitosamente';
                return true;
            } else {
                $_SESSION['error'] = 'La contraseña actual es incorrecta';
                return false;
            }
        }
    }
}
?>