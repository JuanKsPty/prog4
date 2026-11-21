<?php
require 'funciones.php';
require 'plantilla.php';

$nombre = requerir_sesion();
solo_post('../glucosa.html');

$lectura = leer_numero('lectura');
$momento = leer('momento');
$momentos = momentos_glucosa();

$errores = [];
if ($lectura === null || $lectura < 10 || $lectura > 1000) {
    $errores[] = 'Escribe la lectura del glucómetro en mg/dl, entre 10 y 1000.';
}
if (!isset($momentos[$momento])) {
    $errores[] = 'Indica si tomaste la lectura en ayunas o después de comer.';
}

abrir_pagina('Resultado de glucosa', 'glucosa', $nombre);
?>
                <header class="encabezado">
                    <p class="antetitulo">Resultado</p>
                    <h1 class="titulo">Tu glucosa en sangre</h1>
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
    $categorias = categorias_glucosa($momento);
    $clave = clasificar_glucosa($lectura, $momento);
    $categoria = $categorias[$clave];
    $decimales = floor($lectura) == $lectura ? 0 : 1;
?>
                <article class="resultado <?= $categoria['tono'] ?>">
                    <div class="resultado-arte">
                        <?= ilustracion($categoria['imagen'], 'glucosa', $categoria['nombre']) ?>
                    </div>
                    <div class="resultado-datos">
                        <p class="resultado-etiqueta">Tu glucosa <?= $momento === 'ayunas' ? 'en ayunas' : 'posprandial' ?> es</p>
                        <p class="resultado-valor"><?= formato_numero($lectura, $decimales) ?><span class="resultado-unidad">mg/dl</span></p>
                        <p class="insignia"><?= $categoria['nombre'] ?></p>
                        <p class="resultado-frase">Con una lectura de <strong><?= formato_numero($lectura, $decimales) ?> mg/dl</strong> <?= $momento === 'ayunas' ? 'en ayunas' : 'después de comer' ?>, tu resultado se considera <strong><?= $categoria['nombre'] ?></strong>.</p>
                        <p class="resultado-consejo"><?= $categoria['consejo'] ?></p>
                        <dl class="resultado-entrada">
                            <div><dt>Lectura</dt><dd><?= formato_numero($lectura, $decimales) ?> mg/dl</dd></div>
                            <div><dt>Momento</dt><dd><?= $momentos[$momento] ?></dd></div>
                        </dl>
                    </div>
                </article>

                <?= escala($categorias, $clave, 'Dónde estás en la escala de glucosa (' . ($momento === 'ayunas' ? 'en ayunas' : 'posprandial') . ')') ?>
<?php endif; ?>

                <div class="acciones">
                    <a class="boton boton-primario" href="../glucosa.html">Ingresar otra lectura</a>
                    <a class="boton boton-secundario" href="menu.php">Volver al menú</a>
                </div>
<?php
cerrar_pagina();
