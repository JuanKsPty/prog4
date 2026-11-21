<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/cuenta.php');

$clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';
$registro = buscar_usuario($usuario['id']);

if ($registro === null || !password_verify($clave, $registro['clave_hash'])) {
    guardar_flash(['errores' => ['clave' => 'La contraseña no es correcta.']]);
    redirigir('vistas/cuenta.php');
}

// Las mediciones se borran solas por ON DELETE CASCADE
eliminar_usuario($usuario['id']);

// Se vacía la sesión y se cambia su identificador; solo queda el mensaje para la página de login
$_SESSION = [];
session_regenerate_id(true);
guardar_flash(['exito' => 'Eliminamos tu cuenta y todas tus mediciones.']);
redirigir('login.php');
