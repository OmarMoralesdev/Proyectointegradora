<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un ususario
if (!isset($_SESSION['correo']) || $_SESSION['role'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

echo "Bienvenido al Dashboard Administrativo, " . $_SESSION['correo'] . "!";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrativo</title>
</head>
<body>
    <h1>Dashboard Administrativo</h1>
    <p>Contenido exclusivo para administrativos.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>