create database Taller_EuroService;
-- drop database taller_euroservic;
use Taller_EuroService;

create table USUARIOS(
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    roles ENUM('usuario', 'administrador') NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(90) NOT NULL,
    fechalogin TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 