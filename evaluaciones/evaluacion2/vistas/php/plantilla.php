<?php
// Estructura HTML compartida: documento, barra superior del portal, pie y navegación

function documento_inicio($titulo, $clase_cuerpo = '') {
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= mostrar($titulo) ?> | Consultorio ABC</title>
    <meta name="description" content="Portal de pacientes del Consultorio ABC: calcula tu IMC, tu glucosa en sangre y tu presión arterial, y guarda tu historial.">
    <meta name="theme-color" content="#1f5fd6">
    <link rel="icon" href="<?= url('img/logo.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= url('css/styles.css') ?>">
</head>
<body class="cuerpo <?= $clase_cuerpo ?>">
    <a class="saltar" href="#contenido">Saltar al contenido</a>
    <?php
}

function documento_fin($scripts = []) {
    foreach ($scripts as $script) {
        echo '<script src="' . url('js/' . $script) . '"></script>';
    }
    echo "\n</body>\n</html>\n";
}

function marca($destino) {
    return '<a class="marca" href="' . url($destino) . '">'
        . '<img class="marca-logo" src="' . url('img/logo.svg') . '" alt="" width="36" height="36">'
        . '<span class="marca-nombre">Consultorio ABC</span></a>';
}

function pie() {
    ?>
    <footer class="pie">
        <div class="pie-interior">
            <p class="pie-autores">Hecho por Juan Castillo y Agustín Traverso</p>
            <p class="pie-curso">Programación IV, grupo 1SG131. Universidad Tecnológica de Panamá, 2026.</p>
        </div>
    </footer>
    <?php
}

/* --------------------------------------------------------------------------
   Portal del paciente (páginas con sesión)
   -------------------------------------------------------------------------- */

function abrir_portal($titulo, $activo, $usuario) {
    $opciones = [
        'inicio' => ['Inicio', 'vistas/panel.php'],
        'imc' => ['IMC', 'vistas/imc.php'],
        'glucosa' => ['Glucosa', 'vistas/glucosa.php'],
        'presion' => ['Presión', 'vistas/presion.php'],
    ];

    documento_inicio($titulo, 'cuerpo-portal');
    ?>
    <header class="barra">
        <div class="barra-interior">
            <?= marca('vistas/panel.php') ?>

            <nav class="navegacion" aria-label="Secciones del portal">
                <ul class="navegacion-lista">
                    <?php foreach ($opciones as $clave => [$texto, $destino]): ?>
                        <li>
                            <a class="navegacion-enlace" href="<?= url($destino) ?>"<?= $clave === $activo ? ' aria-current="page"' : '' ?>>
                                <?= icono($clave) ?>
                                <span><?= $texto ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <details class="menu-usuario">
                <summary class="menu-usuario-boton">
                    <span class="avatar" aria-hidden="true"><?= mostrar(iniciales($usuario['nombre'])) ?></span>
                    <span class="menu-usuario-nombre"><?= mostrar($usuario['nombre']) ?></span>
                    <?= icono('abajo', 'icono icono-pequeno') ?>
                </summary>
                <div class="menu-usuario-panel">
                    <a class="menu-usuario-opcion" href="<?= url('vistas/cuenta.php') ?>"<?= $activo === 'cuenta' ? ' aria-current="page"' : '' ?>>
                        <?= icono('usuario') ?> Mi cuenta
                    </a>
                    <form action="<?= url('vistas/php/salir.php') ?>" method="post">
                        <?= campo_csrf() ?>
                        <button class="menu-usuario-opcion" type="submit"><?= icono('salir') ?> Cerrar sesión</button>
                    </form>
                </div>
            </details>
        </div>
    </header>

    <main class="contenido" id="contenido">
    <?php
}

function cerrar_portal() {
    echo "    </main>\n";
    pie();
    documento_fin(['menu.js']);
}

/* --------------------------------------------------------------------------
   Avisos de error o éxito
   -------------------------------------------------------------------------- */

function aviso($texto, $tipo = 'error') {
    if ($texto === '' || $texto === null) {
        return '';
    }
    return '<p class="aviso aviso-' . $tipo . '" role="' . ($tipo === 'error' ? 'alert' : 'status') . '">'
        . icono($tipo === 'error' ? 'alerta' : 'info') . '<span>' . mostrar($texto) . '</span></p>';
}

function error_campo($errores, $campo) {
    if (empty($errores[$campo])) {
        return '';
    }
    return '<p class="campo-error" id="error-' . $campo . '">' . mostrar($errores[$campo]) . '</p>';
}

// Atributos de accesibilidad para un campo con error
function atributos_error($errores, $campo, $ayuda = '') {
    $describe = trim(($ayuda ? $ayuda . ' ' : '') . (empty($errores[$campo]) ? '' : 'error-' . $campo));
    return (empty($errores[$campo]) ? '' : ' aria-invalid="true"') . ($describe ? ' aria-describedby="' . $describe . '"' : '');
}
