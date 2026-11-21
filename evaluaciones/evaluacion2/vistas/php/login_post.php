<?php
require 'funciones.php';

exigir_post('login.php');

$correo = mb_strtolower(leer('correo'));
$clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';

$usuario = $correo === '' ? null : buscar_usuario_por_correo($correo);

// Mismo mensaje si el correo no existe o la contraseña no coincide, para no revelar qué cuentas hay
if ($usuario === null || !password_verify($clave, $usuario['clave_hash'])) {
    guardar_flash([
        'aviso' => 'Correo o contraseña incorrectos.',
        'valores' => ['correo' => $correo],
    ]);
    redirigir('login.php');
}

iniciar_sesion($usuario['id'], $usuario['nombre']);
redirigir('vistas/panel.php');
