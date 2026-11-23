<?php
require __DIR__ . '/config.php';

// Se borran las cookies del sitio antes de que venzan: idioma y contador de visitas
borrar_cookie('idiomaUsuario');
borrar_cookie('contador');
borrar_cookie('visitasDesde');

// Al volver al inicio, como ya no hay idioma, se vuelve a preguntar
header('Location: ' . $raiz . 'index.php');
exit;
