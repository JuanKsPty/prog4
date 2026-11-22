<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/presion.php');

$sistolica = leer_numero('sistolica');
$diastolica = leer_numero('diastolica');

$errores = validar_presion($sistolica, $diastolica);

if ($errores) {
    guardar_flash(['errores' => $errores, 'valores' => ['sistolica' => leer('sistolica'), 'diastolica' => leer('diastolica')]]);
    redirigir('vistas/presion.php');
}

$sistolica = (int) round($sistolica);
$diastolica = (int) round($diastolica);
$id = guardar_medicion($usuario['id'], 'presion', clasificar_presion($sistolica, $diastolica), [
    'sistolica' => $sistolica,
    'diastolica' => $diastolica,
]);

redirigir('vistas/presion.php?m=' . $id);
