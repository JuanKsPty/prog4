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
    ];
}
