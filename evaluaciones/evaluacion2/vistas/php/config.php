<?php
// Datos de conexión a MySQL.
// En el servidor vienen de variables de entorno (Dokploy); en XAMPP se usan los valores por defecto.

function config_bd() {
    $clave = getenv('DB_PASS');

    return [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'puerto' => getenv('DB_PORT') ?: '3306',
        'base' => getenv('DB_NAME') ?: 'consultorio_abc',
        'usuario' => getenv('DB_USER') ?: 'root',
        'clave' => $clave === false ? '' : $clave,
        // El usuario de prueba solo se crea en local (XAMPP), nunca en el servidor publicado
        'usuario_prueba' => getenv('DB_HOST') === false,
    ];
}

// Cuenta para que el profesor pueda entrar sin registrarse. Contraseña: Prueba1234
const USUARIO_PRUEBA = [
    'nombre' => 'Paciente Prueba',
    'correo' => 'prueba@consultorio.test',
    'clave_hash' => '$2y$10$kJZgOsJFy4neT2UJcxCDhuDfa0YqbFNQDQAmzw4nB/2/jvMLWash.',
];
