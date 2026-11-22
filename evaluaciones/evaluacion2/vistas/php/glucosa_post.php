<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/glucosa.php');

$lectura = leer_numero('lectura');
$momento = leer('momento');

$errores = validar_glucosa($lectura, $momento);

if ($errores) {
    guardar_flash(['errores' => $errores, 'valores' => ['lectura' => leer('lectura'), 'momento' => $momento]]);
    redirigir('vistas/glucosa.php');
}

$id = guardar_medicion($usuario['id'], 'glucosa', clasificar_glucosa($lectura, $momento), [
    'glucosa' => round($lectura, 1),
    'momento' => $momento,
]);

redirigir('vistas/glucosa.php?m=' . $id);
