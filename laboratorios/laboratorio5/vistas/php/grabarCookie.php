<?php
require __DIR__ . '/config.php';

// Se graba la cookie del idioma elegido con una validez de un mes
$idioma_elegido = $_GET['idiomaUsuario'] ?? '';
if (is_string($idioma_elegido) && isset(IDIOMAS[$idioma_elegido])) {
    guardar_cookie('idiomaUsuario', $idioma_elegido, time() + DURACION_COOKIES);
}

// Se regresa a la página desde la que se eligió el idioma (o al inicio)
$volver = $_GET['volver'] ?? '';
header('Location: ' . $raiz . (pagina_valida($volver) ? $volver : 'index.php'));
exit;
