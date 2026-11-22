<?php
require 'funciones.php';

$usuario = requerir_sesion();
exigir_post('vistas/imc.php');

$peso = leer_numero('peso');
$estatura = leer_numero('estatura');

$errores = validar_imc($peso, $estatura);

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
