<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/glucosa.php');

$lectura = leer_numero('lectura');
$momento = leer('momento');

$errores = [];
if ($lectura === null || $lectura < 10 || $lectura > 1000) {
    $errores['lectura'] = 'Escribe la lectura del glucómetro en mg/dl, entre 10 y 1000.';
}
if (!isset(momentos_glucosa()[$momento])) {
    $errores['momento'] = 'Indica si tomaste la lectura en ayunas o después de comer.';
}

if ($errores) {
    guardar_flash(['errores' => $errores, 'valores' => ['lectura' => leer('lectura'), 'momento' => $momento]]);
    redirigir('vistas/glucosa.php');
}

$id = guardar_medicion($usuario['id'], 'glucosa', clasificar_glucosa($lectura, $momento), [
    'glucosa' => round($lectura, 1),
    'momento' => $momento,
]);

redirigir('vistas/glucosa.php?m=' . $id);
