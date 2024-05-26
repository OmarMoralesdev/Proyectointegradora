<?php
// Datos de conexión a la base de datos
require 'conexion.php';

// Inicializar variables de error y éxito
$error = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidopaterno = trim($_POST['apellidopaterno']);
    $apellidomaterno = trim($_POST['apellidomaterno']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'usuario'; // Valor predeterminado para los roles
    
    // Validaciones básicas
    if (empty($nombre) || empty($apellidopaterno) || empty($apellidomaterno) || empty($correo) || empty($password) || empty($confirm_password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de correo inválido.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT idusuario FROM USUARIOS WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            // Hashear la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO USUARIOS (nombre, apellidopaterno, apellidomaterno, roles, correo, contraseña) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nombre, $apellidopaterno, $apellidomaterno, $role, $correo, $hashed_password);
            
            if ($stmt->execute()) {
                $success = true;
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Bootstrap Example</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">

<!-- Formulario de registro -->
<form id="registerForm" method="post" action="">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Nombre completo</label>
        <input name="nombre" class="form-control">
        <br>
        <div class="input-group">
            <span class="input-group-text">Apellidos</span>
            <input type="text" name="apellidopaterno" placeholder="paterno" aria-label="First name" class="form-control">
            <input type="text" name="apellidomaterno" placeholder="materno" aria-label="Last name" class="form-control">
        </div>
        <br>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo electrónico</label>
            <input type="email" name="correo" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Contraseña</label>
            <input type="password" name="password" placeholder="Mínimo 8 dígitos" class="form-control" id="exampleInputPassword1">
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirm_password" class="form-control" id="exampleInputPassword1">
    </div>
    <center>
        <button type="submit" class="btn btn-primary" value="Registrar">Confirmar</button>
    </center>
</form>

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
