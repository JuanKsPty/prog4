<?php
require 'funciones.php';

exigir_post('registro.php');

$nombre = preg_replace('/\s+/', ' ', leer('nombre'));
$correo = mb_strtolower(leer('correo'));
$clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';
$confirmacion = is_string($_POST['confirmacion'] ?? null) ? $_POST['confirmacion'] : '';

$errores = [];
if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 80) {
    $errores['nombre'] = 'Escribe tu nombre, entre 2 y 80 caracteres.';
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || mb_strlen($correo) > 160) {
    $errores['correo'] = 'Escribe un correo válido, por ejemplo nombre@correo.com.';
} elseif (buscar_usuario_por_correo($correo) !== null) {
    $errores['correo'] = 'Ya existe una cuenta con ese correo. Inicia sesión o usa otro correo.';
}
if (mb_strlen($clave) < 8) {
    $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
} elseif (strlen($clave) > 72) {
    $errores['clave'] = 'La contraseña puede tener como máximo 72 caracteres.';
}
if (!isset($errores['clave']) && $clave !== $confirmacion) {
    $errores['confirmacion'] = 'Las contraseñas no coinciden.';
}

if ($errores) {
    guardar_flash(['errores' => $errores, 'valores' => ['nombre' => $nombre, 'correo' => $correo]]);
    redirigir('registro.php');
}

try {
    $id = crear_usuario($nombre, $correo, $clave);
} catch (PDOException $error) {
    // 23000: otra solicitud registró el mismo correo justo antes
    if ($error->getCode() !== '23000') {
        throw $error;
    }
    guardar_flash([
        'errores' => ['correo' => 'Ya existe una cuenta con ese correo. Inicia sesión o usa otro correo.'],
        'valores' => ['nombre' => $nombre, 'correo' => $correo],
    ]);
    redirigir('registro.php');
}

iniciar_sesion($id, $nombre);
guardar_flash(['exito' => 'Tu cuenta está lista. Empieza con tu primera medición.']);
redirigir('vistas/panel.php');
