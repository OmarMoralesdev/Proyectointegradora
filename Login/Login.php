<?php
// Include database connection
require 'conexion.php';

// Initialize error variable
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Basic validations
    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT idusuario, contraseña, roles FROM USUARIOS WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($idusuario, $hashed_password, $role);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Start user session
                session_start();
                $_SESSION['idusuario'] = $idusuario;
                $_SESSION['correo'] = $correo;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role == 'usuario') {
                    header("Location: Alumno_dashboard.php");
                    exit();
                } elseif ($role == 'administrador') {
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
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php
    if (!empty($error)) {
        echo '<p style="color: red;">' . $error . '</p>';
    }
    ?>
    <form method="post" action="">
        Correo: <input type="email" name="correo" required><br>
        Contraseña: <input type="password" name="password" required><br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>