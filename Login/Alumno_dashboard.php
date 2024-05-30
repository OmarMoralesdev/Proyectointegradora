<?php
session_start();

if (!isset($_SESSION['correo']) || $_SESSION['role'] !== 'usuario') {
    header("Location: login.php");
    exit;
}

echo "Bienvenido al Dashboard de Alumnos, " . $_SESSION['correo'] . "!";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Alumno</title>
</head>
<body>
    <h1>Dashboard Alumno</h1>
    <p>Contenido exclusivo para alumnos.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>