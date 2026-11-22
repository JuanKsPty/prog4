<?php
// Sesión, rutas, mensajes entre páginas y protección CSRF

require_once __DIR__ . '/db.php';

date_default_timezone_set('America/Panama');

function usa_https() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => usa_https(),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

/* --------------------------------------------------------------------------
   Rutas relativas a la carpeta del sitio (funciona en la raíz del dominio y en una subcarpeta de XAMPP)
   -------------------------------------------------------------------------- */

function raiz() {
    static $prefijo = null;
    if ($prefijo === null) {
        $sitio = realpath(__DIR__ . '/../..');
        $carpeta = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
        $resto = trim(substr($carpeta, strlen($sitio)), DIRECTORY_SEPARATOR);
        $prefijo = $resto === '' ? '' : str_repeat('../', substr_count($resto, DIRECTORY_SEPARATOR) + 1);
    }
    return $prefijo;
}

function url($ruta) {
    return raiz() . $ruta;
}

function redirigir($ruta) {
    header('Location: ' . url($ruta));
    exit;
}

/* --------------------------------------------------------------------------
   Usuario en sesión
   -------------------------------------------------------------------------- */

function usuario_actual() {
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }
    return ['id' => (int) $_SESSION['usuario_id'], 'nombre' => $_SESSION['usuario_nombre']];
}

// Devuelve el usuario conectado; si no hay sesión o la cuenta ya no existe, lo manda a iniciar sesión
function requerir_sesion() {
    $usuario = usuario_actual();
    if ($usuario === null) {
        redirigir('login.php');
    }
    if (buscar_usuario($usuario['id']) === null) {
        cerrar_sesion();
        redirigir('login.php');
    }
    return $usuario;
}

function iniciar_sesion($id, $nombre) {
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int) $id;
    $_SESSION['usuario_nombre'] = $nombre;
}

function cerrar_sesion() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    }
    session_destroy();
}

/* --------------------------------------------------------------------------
   Mensajes de una página a la siguiente (errores y datos escritos)
   -------------------------------------------------------------------------- */

function guardar_flash($datos) {
    $_SESSION['flash'] = $datos;
}

function tomar_flash() {
    $datos = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $datos;
}

/* --------------------------------------------------------------------------
   CSRF: cada formulario lleva un token que solo conoce esta sesión
   -------------------------------------------------------------------------- */

function token_csrf() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function campo_csrf() {
    return '<input type="hidden" name="csrf" value="' . token_csrf() . '">';
}

// Los manejadores solo aceptan POST con un token válido; si no, vuelven al formulario
function exigir_post($formulario) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirigir($formulario);
    }
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
        guardar_flash(['aviso' => 'La página estuvo abierta demasiado tiempo. Vuelve a enviar el formulario.']);
        redirigir($formulario);
    }
}
