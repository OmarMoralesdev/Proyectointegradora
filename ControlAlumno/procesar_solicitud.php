<?php
// Datos de la base de datos
$host = '127.0.0.1';
$db = 'eventos';
$user = 'root';
$pass = '';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellidopaterno= $_POST['apellidopaterno'];
$apellidomaterno= $_POST['apellidomaterno'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$tipo_evento = $_POST['tipo_evento'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$detalles = $_POST['detalles'];

// Insertar datos en la tabla Usuarios
$sql_usuarios = "INSERT INTO Usuarios (nombre, apellidopaterno, apellidomaterno, correo, telefono) VALUES ('$nombre','$apellidopaterno', '$apellidomaterno', '$correo', '$telefono')";
if ($conn->query($sql_usuarios) === TRUE) {
    $usuario_id = $conn->insert_id;

    // Insertar datos en la tabla Eventos
    $sql_eventos = "INSERT INTO Eventos (usuario_id, tipo_evento, fecha, hora, detalles) VALUES ('$usuario_id', '$tipo_evento', '$fecha', '$hora', '$detalles')";
    if ($conn->query($sql_eventos) === TRUE) {
        header('Location: confirmacion.html');
    } else {
        echo "Error: " . $sql_eventos . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_usuarios . "<br>" . $conn->error;
}

$conn->close();
?>