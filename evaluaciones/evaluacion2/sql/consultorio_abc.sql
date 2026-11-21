-- Base de datos del Consultorio ABC
-- El sitio crea la base y las tablas solo si no existen, así que importar este archivo es opcional.
-- Sirve para revisar el esquema o para crearlo a mano desde phpMyAdmin.

CREATE DATABASE IF NOT EXISTS consultorio_abc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE consultorio_abc;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(160) NOT NULL,
    clave_hash VARCHAR(255) NOT NULL,
    creado_en DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY correo_unico (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mediciones (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    tipo ENUM('imc', 'glucosa', 'presion') NOT NULL,
    categoria VARCHAR(20) NOT NULL,
    peso DECIMAL(5,1) NULL,
    estatura DECIMAL(3,2) NULL,
    imc DECIMAL(5,2) NULL,
    glucosa DECIMAL(6,1) NULL,
    momento ENUM('ayunas', 'posprandial') NULL,
    sistolica SMALLINT UNSIGNED NULL,
    diastolica SMALLINT UNSIGNED NULL,
    creado_en DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY mediciones_usuario (usuario_id, tipo, creado_en),
    CONSTRAINT mediciones_usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
