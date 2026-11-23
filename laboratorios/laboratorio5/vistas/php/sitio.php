<?php
// Se incluye al inicio de cada página del sitio: idioma y contador de visitas con cookies,
// textos traducidos, menú y pie de página
require __DIR__ . '/config.php';

// --- Idioma -----------------------------------------------------------------
// Se comprueba si existe la cookie. Si no existe (o no es un idioma válido),
// se envía al usuario a la página donde elige el idioma.
if (!isset($_COOKIE['idiomaUsuario']) || !isset(IDIOMAS[$_COOKIE['idiomaUsuario']])) {
    header('Location: ' . $raiz . 'pedirIdioma.php?volver=' . urlencode($pagina_actual));
    exit;
}

$idioma = $_COOKIE['idiomaUsuario'];
$textos = require dirname(__DIR__, 2) . "/idiomas/$idioma.php";

// --- Contador de visitas del mes --------------------------------------------
// "contador" guarda las visitas y "visitasDesde" el momento de la primera visita.
// Ambas vencen un mes después de esa primera visita; al vencer, el conteo vuelve a empezar.
$ahora = time();
$visitas_desde = (int) ($_COOKIE['visitasDesde'] ?? 0);

if (isset($_COOKIE['contador']) && $visitas_desde > 0 && $visitas_desde + DURACION_COOKIES > $ahora) {
    $visitas = (int) $_COOKIE['contador'] + 1;
} else {
    $visitas = 1;
    $visitas_desde = $ahora;
}

$visitas_vencen = $visitas_desde + DURACION_COOKIES;
guardar_cookie('contador', $visitas, $visitas_vencen);
guardar_cookie('visitasDesde', $visitas_desde, $visitas_vencen);

// --- Textos -----------------------------------------------------------------
// Devuelve el texto en el idioma elegido; los %s y %d se reemplazan por los valores dados
function t($clave, ...$valores) {
    global $textos;
    $texto = $textos[$clave] ?? $clave;
    return $valores ? sprintf($texto, ...$valores) : $texto;
}

function fecha($momento) {
    return date(t('formato.fecha'), $momento);
}

function chip($tipo) {
    global $textos;
    return '<span class="type-chip" data-type="' . $tipo . '">' . $textos['tipos'][$tipo] . '</span>';
}

// Textos que usan los scripts de JavaScript
function textos_js() {
    global $textos, $idioma;
    $datos = $textos['js'] + ['idioma' => $idioma, 'tipos' => $textos['tipos']];
    echo '<script>const TEXTOS = ' . json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . "</script>\n";
}

// --- Menú y pie ---------------------------------------------------------------
function menu($actual) {
    global $raiz, $idioma, $pagina_actual;

    $enlaces = [
        'index.php' => t('nav.inicio'),
        'vistas/index.php' => t('nav.pokedex'),
        'vistas/tipos.php' => t('nav.tipos'),
        'vistas/multimedia.php' => t('nav.multimedia'),
        'vistas/formulario.php' => t('nav.entrenador'),
    ];
    // Al cambiar de idioma se vuelve a la misma página (desde datos recibidos, al formulario)
    $volver = urlencode(pagina_valida($pagina_actual) ? $pagina_actual : $actual);
    ?>
    <nav class="site-nav" aria-label="<?= t('nav.aria') ?>">
        <a class="nav-brand" href="<?= $raiz ?>index.php" aria-label="<?= t('nav.marca') ?>"><img src="<?= $raiz ?>img/pokemon-logo.png" alt="Pokémon"></a>
        <div class="nav-links">
            <?php foreach ($enlaces as $pagina => $texto): ?>
                <a href="<?= $raiz . $pagina ?>"<?= $pagina === $actual ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
            <?php endforeach; ?>
        </div>
        <div class="nav-tools">
            <div class="lang-switch" role="group" aria-label="<?= t('nav.idioma') ?>">
                <?php foreach (IDIOMAS as $codigo => $nombre): ?>
                    <a href="<?= $raiz ?>vistas/php/grabarCookie.php?idiomaUsuario=<?= $codigo ?>&amp;volver=<?= $volver ?>" lang="<?= $codigo ?>" title="<?= $nombre ?>"<?= $codigo === $idioma ? ' aria-current="true"' : '' ?>><?= strtoupper($codigo) ?></a>
                <?php endforeach; ?>
            </div>
            <a class="nav-tool" href="<?= $raiz ?>vistas/php/borrarCookie.php"><?= t('nav.borrar') ?></a>
        </div>
    </nav>
    <?php
}

function pie($texto) {
    global $raiz, $visitas;
    ?>
    <footer class="site-footer">
        <img src="<?= $raiz ?>img/pokemon-logo.png" alt="Pokémon">
        <p><?= $texto ?></p>
        <p class="footer-visits"><?= t('pie.visitas', $visitas) ?></p>
    </footer>
    <?php
}
