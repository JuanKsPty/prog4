<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/imc.php');

$peso = leer_numero('peso');
$estatura = leer_numero('estatura');

$errores = [];
if ($peso === null || $peso < 1 || $peso > 500) {
    $errores['peso'] = 'Escribe tu peso en kilogramos, entre 1 y 500.';
}
if ($estatura === null || $estatura < 0.5 || $estatura > 2.5) {
    $errores['estatura'] = $estatura !== null && $estatura >= 50 && $estatura <= 250
        ? 'Parece que escribiste centímetros. Escríbela en metros, por ejemplo ' . formato_numero($estatura / 100) . '.'
        : 'Escribe tu estatura en metros, entre 0.50 y 2.50.';
}

if ($errores) {
    guardar_flash(['errores' => $errores, 'valores' => ['peso' => leer('peso'), 'estatura' => leer('estatura')]]);
    redirigir('vistas/imc.php');
}

$imc = calcular_imc($peso, $estatura);
$id = guardar_medicion($usuario['id'], 'imc', clasificar_imc($imc), [
    'peso' => round($peso, 1),
    'estatura' => round($estatura, 2),
    'imc' => $imc,
]);

redirigir('vistas/imc.php?m=' . $id);
