<?php
session_start();
require_once 'config/Database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        // 1. Instanciar Database y conectar
        $database = new Database();
        $pdo = $database->getConnection();

        if ($pdo) {
            // 2. Buscar usuario y su rol
            // Nota: Se asume que la contraseña en BD ya está encriptada con password_hash()
            // Si las contraseñas actuales son texto plano, este login fallará hasta que se actualicen.
            $sql = "SELECT u.id_usuario, u.nombre, u.password, u.activo, r.nombre as rol_nombre 
                    FROM usuario u 
                    JOIN rol r ON u.id_rol = r.id_rol 
                    WHERE u.email = :email LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Verificar contraseña y estado
            // Si las contraseñas en DB no están hasheadas (son texto plano), usar: if ($user && $password === $user['password'])
            // Para producción, SIEMPRE usar password_verify con hashes.
            if ($user && password_verify($password, $user['password'])) {
                if ($user['activo'] == 0) {
                    $error = "Tu cuenta está desactivada. Contacta al administrador.";
                } else {
                    // 4. Crear Sesión
                    $_SESSION['user_id'] = $user['id_usuario'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['rol_nombre'];

                    // 5. Redirección Inteligente (Router)
                    // Los roles deben coincidir EXACTAMENTE con la base de datos:
                    // admin, soporte_tecnico, tecnico_campo, ventas, user
                    switch ($user['rol_nombre']) {
                        case 'admin':
                            header("Location: app/views/menu-admin.php");
                            break;

                        case 'ventas':
                            header("Location: app/views/menu-ventas.php");
                            break;

                        case 'soporte_tecnico':
                            header("Location: app/views/menu-soportetecnico.php");
                            break;

                        case 'tecnico_campo':
                            header("Location: app/views/menu-tecnicocampo.php");
                            break;

                        case 'user':
                            header("Location: app/views/menu-user.php");
                            break;

                        default:
                            // Rol no reconocido
                            $error = "Rol de usuario no reconocido: " . htmlspecialchars($user['rol_nombre']);
                            session_destroy(); // Destruir sesión si el rol no es válido
                            break;
                    }
                    if (empty($error)) {
                        exit; // Asegurar que el script se detenga después de la redirección
                    }
                }
            } else {
                $error = "Credenciales incorrectas. Inténtalo de nuevo.";
            }
        } else {
            $error = "Error de conexión a la base de datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ProRed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#005FA2', secondary: '#E58E21' }
                }
            }
        }
    </script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden m-4">
        <div class="bg-primary p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <i class="fas fa-wifi absolute -top-4 -left-4 text-9xl text-white"></i>
            </div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-user-lock text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Pro<span class="text-secondary">Red</span></h2>
                <p class="text-blue-100 text-sm mt-1">Sistema de Gestión ISP</p>
            </div>
        </div>

        <div class="p-8 pt-10">
            <?php if (!empty($error)): ?>
                <div
                    class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r text-sm flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-0.5"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                            placeholder="ejemplo@prored.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                            onclick="togglePassword()">
                            <i class="fas fa-eye text-gray-400 hover:text-primary transition-colors" id="eyeIcon"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <span class="ml-2">Recordarme</span>
                    </label>
                    <a href="#" class="text-primary hover:text-secondary font-medium transition-colors">¿Olvidaste tu
                        clave?</a>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-3 rounded-lg font-bold shadow-lg hover:bg-blue-800 hover:shadow-xl transition-all transform active:scale-95 flex justify-center items-center gap-2">
                    <span>Ingresar al Sistema</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-400">&copy; <?php echo date('Y'); ?> ProRed Perú. Todos los derechos reservados.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>