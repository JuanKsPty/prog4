<?php
require 'funciones.php';

$_SESSION = [];
session_destroy();

header('Location: ' . RUTA_LOGIN);
exit;
