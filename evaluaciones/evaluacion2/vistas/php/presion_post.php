<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/presion.php');

$sistolica = leer_numero('sistolica');
$diastolica = leer_numero('diastolica');

$errores = [];
if ($sistolica === null || $sistolica < 50 || $sistolica > 300) {
    $errores['sistolica'] = 'Escribe la sistólica (el número de arriba), entre 50 y 300.';
}
if ($diastolica === null || $diastolica < 30 || $diastolica > 200) {
    $errores['diastolica'] = 'Escribe la diastólica (el número de abajo), entre 30 y 200.';
}
if (!$errores && $sistolica <= $diastolica) {
    $errores['sistolica'] = 'La sistólica debe ser mayor que la diastólica. Revisa que no estén invertidas.';
}

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
