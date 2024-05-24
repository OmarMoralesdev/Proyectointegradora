<?php
// Datos de conexión a la base de datos
$servername = "127.0.0.1";
$db_username = "root"; // Cambia esto por tu nombre de usuario de MySQL
$db_password = ""; // Cambia esto por tu contraseña de MySQL
$dbname = "integradoraiglesia";

// Crear conexión
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>