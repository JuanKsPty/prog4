<?php
// Configuración que comparten todas las páginas: rutas, idiomas y cookies

date_default_timezone_set('America/Panama');

// Las cookies del sitio duran un mes (30 días en segundos)
const DURACION_COOKIES = 30 * 24 * 60 * 60;

const IDIOMAS = [
    'es' => 'Español',
    'en' => 'English',
    'fr' => 'Français',
];

// Carpeta del laboratorio en el disco y página actual relativa a ella, por ejemplo "vistas/tipos.php"
$carpeta_sitio = str_replace('\\', '/', dirname(__DIR__, 2));
$script_actual = str_replace('\\', '/', realpath($_SERVER['SCRIPT_FILENAME']));
$pagina_actual = substr($script_actual, strlen($carpeta_sitio) + 1);

// Prefijo para llegar a la raíz del sitio desde la página actual: "", "../" o "../../"
$raiz = str_repeat('../', substr_count($pagina_actual, '/'));

// Ruta web del sitio (por ejemplo "/laboratorios/laboratorio5/"). Las cookies se guardan con esta ruta
// para que todas las páginas, estén en la carpeta que estén, lean las mismas cookies.
$ruta_cookies = substr($_SERVER['SCRIPT_NAME'], 0, -strlen($pagina_actual));
if ($ruta_cookies === '' || !str_ends_with($ruta_cookies, '/')) {
    $ruta_cookies = '/';
}

function guardar_cookie($nombre, $valor, $vence) {
    global $ruta_cookies;
    setcookie($nombre, (string) $valor, [
        'expires' => $vence,
        'path' => $ruta_cookies,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function borrar_cookie($nombre) {
    global $ruta_cookies;
    setcookie($nombre, '', [
        'expires' => time() - 3600,
        'path' => $ruta_cookies,
    ]);
}

// Solo se acepta volver a una página del sitio, nunca a otra dirección
function pagina_valida($pagina) {
    global $carpeta_sitio;
    return is_string($pagina)
        && preg_match('#^(vistas/)?[a-z_]+\.php$#', $pagina)
        && is_file("$carpeta_sitio/$pagina");
}
