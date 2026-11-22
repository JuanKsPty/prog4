<?php
// Conexión a MySQL con PDO. Crea la base y las tablas la primera vez que se usan.

require_once __DIR__ . '/config.php';

function conectar() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $config = config_bd();
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $servidor = "mysql:host={$config['host']};port={$config['puerto']};charset=utf8mb4";

    try {
        try {
            $pdo = new PDO("$servidor;dbname={$config['base']}", $config['usuario'], $config['clave'], $opciones);
        } catch (PDOException $error) {
            // 1049: la base no existe todavía (pasa en XAMPP la primera vez)
            if ((int) $error->getCode() !== 1049) {
                throw $error;
            }
            $pdo = new PDO($servidor, $config['usuario'], $config['clave'], $opciones);
            $base = str_replace('`', '', $config['base']);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$base` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$base`");
        }
        crear_tablas($pdo);
        if ($config['usuario_prueba']) {
            crear_usuario_prueba($pdo);
        }
    } catch (PDOException $error) {
        error_log('Consultorio ABC, error de base de datos: ' . $error->getMessage());
        mostrar_error_bd();
    }

    return $pdo;
}

function crear_tablas($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(80) NOT NULL,
        correo VARCHAR(160) NOT NULL,
        clave_hash VARCHAR(255) NOT NULL,
        creado_en DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY correo_unico (correo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS mediciones (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

// INSERT IGNORE: si el correo ya existe no hace nada
function crear_usuario_prueba($pdo) {
    $consulta = $pdo->prepare('INSERT IGNORE INTO usuarios (nombre, correo, clave_hash, creado_en) VALUES (?, ?, ?, ?)');
    $consulta->execute([USUARIO_PRUEBA['nombre'], USUARIO_PRUEBA['correo'], USUARIO_PRUEBA['clave_hash'], date('Y-m-d H:i:s')]);
}

// Página mínima cuando MySQL no responde: no muestra detalles técnicos al visitante
function mostrar_error_bd() {
    http_response_code(503);
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Servicio no disponible | Consultorio ABC</title></head>';
    echo '<body style="font-family: system-ui, sans-serif; max-width: 34rem; margin: 15vh auto; padding: 0 1.5rem; color: #13203b">';
    echo '<h1>No pudimos conectar con la base de datos</h1>';
    echo '<p>El portal no puede leer ni guardar datos en este momento. Vuelve a intentarlo en unos minutos.</p>';
    echo '</body></html>';
    exit;
}
