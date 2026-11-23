<?php
// Configuración que comparten todas las páginas: sesión, colores, rutas, idiomas y cookies

date_default_timezone_set('America/Panama');

// Usuario de prueba, como en la guía
const USUARIO_PRUEBA = 'test';
const CLAVE_PRUEBA = 'test';

// Colores que se pueden elegir y el tono que se usa para el fondo y para el texto,
// claros para el fondo y oscuros para el texto para que todo se pueda leer
const COLORES = [
    'rojo' => ['fondo' => '#fde8e4', 'texto' => '#7f1d0e'],
    'verde' => ['fondo' => '#e5f3df', 'texto' => '#1f5a16'],
    'azul' => ['fondo' => '#e4e9fb', 'texto' => '#1f2b7a'],
    'cian' => ['fondo' => '#ddf3f5', 'texto' => '#0b5762'],
    'amarillo' => ['fondo' => '#fff5cc', 'texto' => '#6b5000'],
    'gris' => ['fondo' => '#ebecef', 'texto' => '#2f323a'],
];

function traducirColor($color, $uso) {
    return COLORES[$color][$uso] ?? null;
}

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

// La sesión guarda el usuario que inició sesión y los colores que eligió.
// Tiene nombre propio y la misma ruta que las cookies para no mezclarse con otros sitios del servidor.
session_name('pokemonlab');
session_set_cookie_params(['path' => $ruta_cookies, 'httponly' => true, 'samesite' => 'Lax']);
session_start();

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
