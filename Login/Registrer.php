<?php
// Datos de conexión a la base de datos
require 'conexion.php';

// Inicializar variables de error y éxito
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidopaterno = trim($_POST['apellidopaterno']);
    $apellidomaterno = trim($_POST['apellidomaterno']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'usuario'; // Valor predeterminado para el rol
    
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>
</head>
<body>
    <h2>Registrar</h2>
    <?php
    if (!empty($error)) {
        echo '<p style="color: red;">' . $error . '</p>';
    }
    if (!empty($success)) {
        echo '<p style="color: green;">' . $success . '</p>';
    }
    ?>
    <form method="post" action="">
        Nombre: <input type="text" name="nombre" required><br>
        Apellido Paterno: <input type="text" name="apellidopaterno" required><br>
        Apellido Materno: <input type="text" name="apellidomaterno" required><br>
        Correo: <input type="email" name="correo" required><br>
        Contraseña: <input type="password" name="password" required><br>
        Confirmar Contraseña: <input type="password" name="confirm_password" required><br>
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
