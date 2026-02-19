<?php
require_once '../../config/Database.php';

class UsuarioController
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function listar()
    {
        try {
            // Unimos con la tabla rol para obtener el nombre del rol
            // Unimos con cliente para obtener datos reales si es un cliente
            $sql = "SELECT 
                        u.id_usuario, 
                        u.id_cliente,
                        u.nombre, 
                        u.email, 
                        COALESCE(c.dni, '') as dni, 
                        COALESCE(ct.numero, '') as telefono, 
                        COALESCE(s_dir.direccion, c.ubigeo, '') as direccion, 
                        u.activo, 
                        '' as created_at, 
                        r.nombre as rol 
                    FROM usuario u 
                    INNER JOIN rol r ON u.id_rol = r.id_rol 
                    LEFT JOIN cliente c ON u.id_cliente = c.id_cliente
                    LEFT JOIN cliente_telefono ct ON c.id_cliente = ct.id_cliente AND ct.principal = 1
                    LEFT JOIN (SELECT id_cliente, direccion FROM servicio GROUP BY id_cliente) s_dir ON c.id_cliente = s_dir.id_cliente
                    ORDER BY u.id_usuario DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usuarios);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function listarClientesSinUsuario()
    {
        try {
            // Clientes que NO estan en la tabla usuario
            $sql = "SELECT 
                        c.id_cliente, 
                        CASE 
                            WHEN c.razon_social IS NOT NULL THEN c.razon_social 
                            ELSE CONCAT(c.nombres, ' ', c.apellidos) 
                        END as nombre_completo,
                        c.dni,
                        c.email,
                        (SELECT numero FROM cliente_telefono WHERE id_cliente = c.id_cliente AND principal = 1 LIMIT 1) as telefono,
                        (SELECT direccion FROM servicio WHERE id_cliente = c.id_cliente LIMIT 1) as direccion
                    FROM cliente c
                    LEFT JOIN usuario u ON c.id_cliente = u.id_cliente
                    WHERE u.id_cliente IS NULL AND c.activo = 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($clientes);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function guardar()
    {
        try {
            // Recoger datos del POST
            $id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
            $nombre = trim($_POST['nombre']);
            // $dni = trim($_POST['dni']); 
            $email = trim($_POST['email']);
            // $telefono = trim($_POST['telefono']); 
            // $direccion = trim($_POST['direccion']); 
            $rol_nombre = $_POST['rol'];
            $estado = $_POST['estado'];
            $password = $_POST['password'];
            $id_cliente = isset($_POST['id_cliente']) && $_POST['id_cliente'] !== '' ? $_POST['id_cliente'] : NULL;

            // 1. Obtener ID del Rol
            $stmtRol = $this->pdo->prepare("SELECT id_rol FROM rol WHERE nombre = :nombre LIMIT 1");
            $stmtRol->execute(['nombre' => strtolower($rol_nombre)]);
            $rolData = $stmtRol->fetch(PDO::FETCH_ASSOC);

            if (!$rolData) {
                // Si no encuentra el rol por nombre exacto, intentar mapear 'CLIENTE' a 'user'
                if ($rol_nombre === 'CLIENTE') {
                    $stmtRol->execute(['nombre' => 'user']);
                    $rolData = $stmtRol->fetch(PDO::FETCH_ASSOC);
                }

                if (!$rolData) {
                    echo json_encode(['status' => false, 'msg' => 'Rol no válido']);
                    return;
                }
            }
            $id_rol = $rolData['id_rol'];

            if (empty($id_usuario)) {
                // --- INSERTAR ---

                // Validar duplicados (email)
                $stmtCheck = $this->pdo->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
                $stmtCheck->execute(['email' => $email]);
                if ($stmtCheck->rowCount() > 0) {
                    echo json_encode(['status' => false, 'msg' => 'El correo ya está registrado.']);
                    return;
                }

                // Hashear password
                if (empty($password)) {
                    echo json_encode(['status' => false, 'msg' => 'La contraseña es obligatoria para nuevos usuarios.']);
                    return;
                }
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Insertamos solo los campos que existen en la tabla usuario
                $sql = "INSERT INTO usuario (id_rol, id_cliente, nombre, email, password, activo) 
                        VALUES (:id_rol, :id_cliente, :nombre, :email, :password, :activo)";

                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([
                    'id_rol' => $id_rol,
                    'id_cliente' => $id_cliente,
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $passwordHash,
                    'activo' => $estado
                ]);

                if ($result) {
                    echo json_encode(['status' => true, 'msg' => 'Usuario creado correctamente.']);
                } else {
                    echo json_encode(['status' => false, 'msg' => 'Error al crear usuario.']);
                }

            } else {
                // --- ACTUALIZAR ---
                // Validar duplicados (excluyendo al usuario actual)
                $stmtCheck = $this->pdo->prepare("SELECT id_usuario FROM usuario WHERE email = :email AND id_usuario != :id");
                $stmtCheck->execute(['email' => $email, 'id' => $id_usuario]);
                if ($stmtCheck->rowCount() > 0) {
                    echo json_encode(['status' => false, 'msg' => 'El correo ya está registrado en otro usuario.']);
                    return;
                }

                // NOTA: id_cliente no se suele actualizar una vez vinculado, pero si fuera necesario se podria agregar.
                // Por ahora lo dejamos fijo o SOLO si viene nulo y queremos asignarlo.
                // Asumiremos que no cambia el vínculo con el cliente en la edición simple.

                $sql = "UPDATE usuario SET id_rol=:id_rol, nombre=:nombre, email=:email, activo=:activo";
                $params = [
                    'id_rol' => $id_rol,
                    'nombre' => $nombre,
                    'email' => $email,
                    'activo' => $estado,
                    'id_usuario' => $id_usuario
                ];

                // Si hay password nueva, actualizarla
                if (!empty($password)) {
                    $sql .= ", password=:password";
                    $params['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $sql .= " WHERE id_usuario=:id_usuario";

                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute($params);

                if ($result) {
                    echo json_encode(['status' => true, 'msg' => 'Usuario actualizado correctamente.']);
                } else {
                    echo json_encode(['status' => false, 'msg' => 'Error al actualizar usuario.']);
                }
            }

        } catch (Exception $e) {
            echo json_encode(['status' => false, 'msg' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function eliminar()
    {
        try {
            $id = $_POST['id_usuario'];

            $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id");
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => true, 'msg' => 'Usuario eliminado.']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'No se pudo eliminar (posiblemente tiene registros asociados). Intente desactivarlo.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'msg' => 'Error: ' . $e->getMessage()]);
        }
    }
}

// Router simple para manejar las peticiones AJAX
if (isset($_GET['op'])) {
    $controller = new UsuarioController();
    switch ($_GET['op']) {
        case 'listar':
            $controller->listar();
            break;
        case 'guardar':
            $controller->guardar();
            break;
        case 'eliminar':
            $controller->eliminar();
            break;
        case 'listarClientesSinUsuario':
            $controller->listarClientesSinUsuario();
            break;
    }
}
?>