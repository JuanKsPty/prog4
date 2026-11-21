<?php
require 'funciones.php';
require 'plantilla.php';

$nombre = requerir_sesion();
solo_post('../imc.html');

$peso = leer_numero('peso');
$estatura = leer_numero('estatura');

$errores = [];
if ($peso === null || $peso < 1 || $peso > 500) {
    $errores[] = 'Escribe un peso válido en kilogramos, entre 1 y 500.';
}
if ($estatura === null || $estatura < 0.5 || $estatura > 2.5) {
    $errores[] = 'Escribe la estatura en metros, entre 0.50 y 2.50 (por ejemplo 1.70).';
}

abrir_pagina('Resultado del IMC', 'imc', $nombre);
?>
                <header class="encabezado">
                    <p class="antetitulo">Resultado</p>
                    <h1 class="titulo">Tu índice de masa corporal</h1>
                </header>

<?php if ($errores): ?>
                <section class="aviso" role="alert">
                    <h2 class="aviso-titulo">No pudimos calcular tu IMC</h2>
                    <ul class="aviso-lista">
                        <?php foreach ($errores as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
<?php else:
    $imc = calcular_imc($peso, $estatura);
    $categorias = categorias_imc();
    $clave = clasificar_imc($imc);
    $categoria = $categorias[$clave];
?>
                <article class="resultado <?= $categoria['tono'] ?>">
                    <div class="resultado-arte">
                        <?= ilustracion($categoria['imagen'], 'figura', $categoria['nombre']) ?>
                    </div>
                    <div class="resultado-datos">
                        <p class="resultado-etiqueta">Tu IMC es</p>
                        <p class="resultado-valor"><?= formato_numero($imc) ?><span class="resultado-unidad">kg/m²</span></p>
                        <p class="insignia"><?= $categoria['nombre'] ?></p>
                        <p class="resultado-frase">Tu IMC es <strong><?= formato_numero($imc) ?></strong> y se considera <strong><?= $categoria['nombre'] ?></strong>.</p>
                        <p class="resultado-consejo"><?= $categoria['consejo'] ?></p>
                        <dl class="resultado-entrada">
                            <div><dt>Peso</dt><dd><?= formato_numero($peso, 1) ?> kg</dd></div>
                            <div><dt>Estatura</dt><dd><?= formato_numero($estatura) ?> m</dd></div>
                        </dl>
                    </div>
                </article>

                <?= escala($categorias, $clave, 'Dónde estás en la escala de IMC') ?>
<?php endif; ?>

                <div class="acciones">
                    <a class="boton boton-primario" href="../imc.html">Calcular otra vez</a>
                    <a class="boton boton-secundario" href="menu.php">Volver al menú</a>
                </div>
<?php
cerrar_pagina();
