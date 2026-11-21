<?php
require 'funciones.php';

exigir_post('vistas/panel.php');

cerrar_sesion();
redirigir('index.php');
