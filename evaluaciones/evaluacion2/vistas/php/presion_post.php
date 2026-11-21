<?php
require 'funciones.php';
require 'plantilla.php';

$nombre = requerir_sesion();
solo_post('../presion.html');

$sistolica = leer_numero('sistolica');
$diastolica = leer_numero('diastolica');

$errores = [];
if ($sistolica === null || $sistolica < 50 || $sistolica > 300) {
    $errores[] = 'Escribe la presión sistólica (el número de arriba) entre 50 y 300 mm Hg.';
}
if ($diastolica === null || $diastolica < 30 || $diastolica > 200) {
    $errores[] = 'Escribe la presión diastólica (el número de abajo) entre 30 y 200 mm Hg.';
}
if (!$errores && $sistolica <= $diastolica) {
    $errores[] = 'La sistólica debe ser mayor que la diastólica. Revisa que no estén invertidas.';
}

abrir_pagina('Resultado de presión arterial', 'presion', $nombre);
?>
                <header class="encabezado">
                    <p class="antetitulo">Resultado</p>
                    <h1 class="titulo">Tu presión arterial</h1>
                </header>

<?php if ($errores): ?>
                <section class="aviso" role="alert">
                    <h2 class="aviso-titulo">No pudimos interpretar tu lectura</h2>
                    <ul class="aviso-lista">
                        <?php foreach ($errores as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
<?php else:
    $categorias = categorias_presion();
    $clave = clasificar_presion($sistolica, $diastolica);
    $categoria = $categorias[$clave];
    $lectura = formato_numero($sistolica, 0) . '/' . formato_numero($diastolica, 0);
?>
                <article class="resultado <?= $categoria['tono'] ?>">
                    <div class="resultado-arte">
                        <?= ilustracion($categoria['imagen'], 'presion', $categoria['nombre']) ?>
                    </div>
                    <div class="resultado-datos">
                        <p class="resultado-etiqueta">Tu presión arterial es</p>
                        <p class="resultado-valor"><?= $lectura ?><span class="resultado-unidad">mm Hg</span></p>
                        <p class="insignia"><?= $categoria['nombre'] ?></p>
                        <p class="resultado-frase">Con una presión de <strong><?= $lectura ?> mm Hg</strong>, tu resultado se considera <strong><?= $categoria['nombre'] ?></strong>.</p>
                        <p class="resultado-consejo"><?= $categoria['consejo'] ?></p>
                        <dl class="resultado-entrada">
                            <div><dt>Sistólica</dt><dd><?= formato_numero($sistolica, 0) ?> mm Hg</dd></div>
                            <div><dt>Diastólica</dt><dd><?= formato_numero($diastolica, 0) ?> mm Hg</dd></div>
                        </dl>
                    </div>
                </article>

                <?= escala($categorias, $clave, 'Dónde estás en la escala de presión arterial') ?>
<?php endif; ?>

                <div class="acciones">
                    <a class="boton boton-primario" href="../presion.html">Ingresar otra lectura</a>
                    <a class="boton boton-secundario" href="menu.php">Volver al menú</a>
                </div>
<?php
cerrar_pagina();
