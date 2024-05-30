<?php

require 'conexion.php';
$error = '';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Validaciones básicas
    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("SELECT id_usuario, contraseña, roles FROM USUARIOS WHERE correo = ?");
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario, $hashed_password, $role);
            $stmt->fetch();

            // Verificar contraseña
            if (password_verify($password, $hashed_password)) {
                // Iniciar sesión del usuario
                session_start();
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['correo'] = $correo;
                $_SESSION['role'] = $role;

                // Redirigir según el rol del usuario
                if ($role === 'usuario') {
                    header("Location: Alumno_dashboard.php");
                    exit();
                } elseif ($role === 'administrador') {
                    header("Location: Administrativo_dashboard.php");
                    exit();
                }
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } else {
            $error = "Correo o contraseña incorrectos.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet" type='text/css'>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<body>
    <div class="form-wrapper">
        <div class="form-side">
            <!-- <img src="assets/ofin.png" class='logo' alt="Ofin"> -->
            </a>
            <form class="my-form" method="post" action="login.php">
                <?php if (!empty($error)) : ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="form-welcome-row">
                    <h1>Inicia sesión &#128520;</h1>
                </div>
                <div class="text-field">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" autocomplete="off" placeholder="Correo electrónico" required>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                        <path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28"></path>
                    </svg>
                </div>
                <div class="text-field">
                    <label for="password">Contraseña:</label>
                    <input id="password" type="password" name="password" placeholder="Contraseña" required>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path>
                        <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                        <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                    </svg>
                </div>
                <button type="submit" class="my-form__button">Iniciar sesión</button>
                <div class="my-form__actions">
                    <a href="Reset_Password.php" title="Restablecer contraseña">Restablecer contraseña</a>
                    <a href="Registrer.php" title="Crear cuenta">Crear una cuenta</a>
                </div>
            </form>
        </div>
        <div class="info-side">
            <!-- <img src="assets/mock.png" alt="Mock" class="mockup"> -->
        </div>
    </div>

    <?php if (!empty($error)) : ?>
        <script>
            $(document).ready(function() {
                $('#errorModal').modal('show');
            });
        </script>
    <?php endif; ?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $error; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

