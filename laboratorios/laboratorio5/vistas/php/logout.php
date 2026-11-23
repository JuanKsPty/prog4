<?php
require __DIR__ . '/config.php';

// Se cierra la sesión: se borran sus variables, la cookie de la sesión y la sesión misma
if (!empty($_SESSION['usuario'])) {
    session_unset();
    $parametros = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $parametros['path'], $parametros['domain'], $parametros['secure'], $parametros['httponly']);
    session_destroy();
}

header('Location: ' . $raiz . 'vistas/login.php?salida=1');
exit;
