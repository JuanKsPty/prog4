<?php
require 'funciones.php';

solo_post(RUTA_LOGIN);

$usuario = leer('usuario');
$clave = leer('clave');

// Login sencillo: basta con escribir un nombre y una contraseña
if ($usuario === '' || $clave === '') {
    header('Location: ' . RUTA_LOGIN);
    exit;
}

session_regenerate_id(true);
$_SESSION['nombre'] = mb_substr($usuario, 0, 40);

header('Location: menu.php');
exit;
