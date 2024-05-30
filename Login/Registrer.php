<?php
// Datos de conexión a la base de datos
require 'conexion.php';

// Inicializar variables de error y éxito
$error = '';
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'usuario'; // Valor predeterminado para los roles

    // Validaciones básicas
    if (empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || empty($correo) || empty($password) || empty($confirm_password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $error = "Formato de correo inválido.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!preg_match("/^[a-zA-ZáéíóÉÍÓÚñÑ\s]+$/", $nombre) || !preg_match("/^[a-zA-ZáéíóÉÍÓÚñÑ\s]+$/", $apellido_paterno) || !preg_match("/^[a-zA-ZáéíóÉÍÓÚñÑ\s]+$/", $apellido_materno)) {
        $error = "El nombre y los apellidos solo deben contener letras.";
    } else {


        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM USUARIOS WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            // Hashear la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO USUARIOS (nombre, apellido_paterno, apellido_materno, roles, correo, contraseña) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nombre, $apellido_paterno, $apellido_materno, $role, $correo, $hashed_password);
            
            if ($stmt->execute()) {

                $success = "Registro exitoso! Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario: " . $stmt->error;
            }
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Registro de Usuario</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #D8D8D8;
        }
        .form-container {
            max-width: 550px; /* Limitar el ancho máximo del formulario */
            margin: auto; /* Centrarr el formulario horizontalmente */
            padding: 30px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 0 100px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <div class="container mt-5 pt-5">
        <div class="form-container">
            <form method="post" action="" onsubmit="return validarApellidos()">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Apellidos</span>
                    <input type="text" name="apellido_paterno" placeholder="Paterno" aria-label="Paterno" class="form-control" required>
                    <input type="text" name="apellido_materno" placeholder="Materno" aria-label="Materno" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" id="correo" aria-describedby="emailHelp" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" placeholder="Mínimo 8 dígitos" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                </div>
                <center>

    <div style="display: flex; justify-content: center;">
        <a class="btn btn-primary" href="Login.php">Regresar</a>
        <div style="flex: 0 1 70%"></div> 
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </div>
    
</center>
            </form>
        </div>
    </div>

    <?php if ($success): ?>
    <!-- Ventana modal de éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Registro Exitoso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¡Registro exitoso! Ahora puedes <a href="login.php">iniciar sesión</a>.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    </script>

<?php elseif (!empty($error)): ?>
    <!-- Ventana modal de error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error en el Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $error; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetForm()">Reintentar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();

        function resetForm() {
            document.getElementById('registerForm').reset();
        } 
    </script>

<?php endif; ?>

</body>
</html>
</html>