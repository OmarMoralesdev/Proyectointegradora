create database login2;
use login2;
-- Crear la tabla de usuarios
CREATE TABLE USUARIOS (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidopaterno VARCHAR(50) NOT NULL,
    apellidomaterno VARCHAR(50) NOT NULL,
    roles ENUM('usuario', 'administrador') NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fechalogin TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);