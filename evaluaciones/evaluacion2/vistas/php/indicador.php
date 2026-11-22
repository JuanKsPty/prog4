<?php
// Página de un indicador: formulario, resultado de la última medición enviada, historial y tabla de referencia

function frase_resultado($medicion, $lectura, $categoria) {
    $nombre = '<strong>' . mostrar($categoria['nombre']) . '</strong>';
    if ($medicion['tipo'] === 'imc') {
        return 'Su IMC es <strong>' . $lectura['valor'] . '</strong> y se categoriza como ' . $nombre . '.';
    }
    if ($medicion['tipo'] === 'glucosa') {
        $cuando = $medicion['momento'] === 'ayunas' ? 'en ayunas' : 'después de comer';
        return 'Con <strong>' . $lectura['valor'] . ' mg/dl</strong> ' . $cuando . ', tu resultado se considera ' . $nombre . '.';
    }
    return 'Con <strong>' . $lectura['valor'] . ' mm Hg</strong>, tu presión se considera ' . $nombre . '.';
}

function etiqueta_resultado($medicion) {
    if ($medicion['tipo'] === 'imc') {
        return 'Tu índice de masa corporal';
    }
    if ($medicion['tipo'] === 'glucosa') {
        return $medicion['momento'] === 'ayunas' ? 'Tu glucosa en ayunas' : 'Tu glucosa después de comer';
    }
    return 'Tu presión arterial';
}

function tarjeta_resultado($medicion) {
    $categoria = categoria_de_medicion($medicion);
    $lectura = lectura_de_medicion($medicion);
    $iconos = ['imc' => 'figura', 'glucosa' => 'glucosa', 'presion' => 'presion'];
    ob_start();
    ?>
            <section class="resultado <?= $categoria['tono'] ?>" aria-labelledby="titulo-resultado" tabindex="-1" id="resultado">
                <div class="resultado-arte">
                    <?= ilustracion($categoria['imagen'], $iconos[$medicion['tipo']], 'Ilustración: ' . $categoria['nombre']) ?>
                </div>
                <div class="resultado-cuerpo">
                    <h2 class="resultado-etiqueta" id="titulo-resultado"><?= etiqueta_resultado($medicion) ?></h2>
                    <p class="resultado-valor"><?= $lectura['valor'] ?> <span class="resultado-unidad"><?= $lectura['unidad'] ?></span></p>
                    <?= insignia($categoria) ?>
                    <p class="resultado-frase"><?= frase_resultado($medicion, $lectura, $categoria) ?></p>
                    <?= banda(categorias_de($medicion['tipo'], $medicion['momento'] ?? 'ayunas'), $medicion['categoria'], posicion_de_medicion($medicion)) ?>
                    <p class="resultado-consejo"><?= mostrar($categoria['consejo']) ?></p>
                    <p class="resultado-meta"><?= icono('reloj', 'icono icono-pequeno') ?> <?= fecha_relativa($medicion['creado_en']) ?></p>
                </div>
            </section>
    <?php
    return ob_get_clean();
}

function resultado_vacio($tipo) {
    $indicador = indicadores()[$tipo];
    ob_start();
    ?>
            <section class="resultado resultado-vacio" aria-labelledby="titulo-resultado">
                <div class="resultado-arte">
                    <?= ilustracion($indicador['imagen'], $tipo) ?>
                </div>
                <div class="resultado-cuerpo">
                    <h2 class="resultado-etiqueta" id="titulo-resultado">Tu resultado aparecerá aquí</h2>
                    <p class="resultado-texto">Completa el formulario y verás tu diagnóstico, en qué parte de la escala estás y un consejo. Cada medición se guarda en tu historial.</p>
                </div>
            </section>
    <?php
    return ob_get_clean();
}

function historial_indicador($tipo, $usuario_id, $actual_id) {
    $mediciones = mediciones_recientes($usuario_id, 6, $tipo);
    $indicador = indicadores()[$tipo];
    ob_start();
    ?>
            <section class="tarjeta historial" aria-labelledby="titulo-historial">
                <h2 class="seccion-titulo" id="titulo-historial">Tus mediciones de <?= $tipo === 'imc' ? 'IMC' : mb_strtolower($indicador['titulo']) ?></h2>
                <?php if (!$mediciones): ?>
                    <p class="vacio-texto">Sin mediciones todavía.</p>
                <?php else: ?>
                    <ul class="lista-mediciones">
                        <?php foreach ($mediciones as $medicion):
                            $lectura = lectura_de_medicion($medicion);
                            $categoria = categoria_de_medicion($medicion);
                        ?>
                            <li class="medicion<?= (int) $medicion['id'] === $actual_id ? ' es-actual' : '' ?>">
                                <span class="medicion-fecha"><?= fecha_relativa($medicion['creado_en']) ?></span>
                                <span class="medicion-lectura"><?= $lectura['valor'] ?> <span class="medicion-unidad"><?= $lectura['unidad'] ?></span></span>
                                <?php if ($lectura['detalle'] !== ''): ?><span class="medicion-detalle"><?= mostrar($lectura['detalle']) ?></span><?php endif; ?>
                                <?= insignia($categoria) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
    <?php
    return ob_get_clean();
}

function tabla_referencia($tipo) {
    ob_start();
    ?>
            <section class="tarjeta referencia" aria-labelledby="titulo-referencia">
                <h2 class="seccion-titulo" id="titulo-referencia">Tabla de referencia</h2>
                <?php if ($tipo === 'glucosa'):
                    $ayunas = categorias_glucosa('ayunas');
                    $despues = categorias_glucosa('posprandial');
                ?>
                    <table class="tabla">
                        <thead><tr><th scope="col">Diagnóstico</th><th scope="col">En ayunas</th><th scope="col">Después de comer</th></tr></thead>
                        <tbody>
                            <?php foreach ($ayunas as $clave => $categoria): ?>
                                <tr>
                                    <th scope="row"><span class="punto <?= $categoria['tono'] ?>"></span><?= mostrar($categoria['nombre']) ?></th>
                                    <td><?= $categoria['rango'] ?> mg/dl</td>
                                    <td><?= $despues[$clave]['rango'] ?> mg/dl</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <table class="tabla">
                        <thead><tr><th scope="col">Categoría</th><th scope="col"><?= $tipo === 'imc' ? 'IMC (kg/m²)' : 'Sistólica / diastólica (mm Hg)' ?></th></tr></thead>
                        <tbody>
                            <?php foreach (categorias_de($tipo) as $categoria): ?>
                                <tr>
                                    <th scope="row"><span class="punto <?= $categoria['tono'] ?>"></span><?= mostrar($categoria['nombre']) ?></th>
                                    <td><?= $categoria['rango'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <?php if ($tipo === 'imc'): ?>
                    <p class="referencia-nota">Fórmula: IMC = peso (kg) ÷ estatura² (m).</p>
                <?php elseif ($tipo === 'presion'): ?>
                    <p class="referencia-nota">Cuenta la categoría más alta de los dos números.</p>
                <?php endif; ?>
            </section>
    <?php
    return ob_get_clean();
}

// Arma la página completa; $campos es el HTML de los campos propios de cada formulario
function pagina_indicador($tipo, $usuario, $campos, $boton) {
    $indicador = indicadores()[$tipo];
    $flash = tomar_flash();
    $medicion = null;
    if (isset($_GET['m']) && ctype_digit((string) $_GET['m'])) {
        $medicion = buscar_medicion((int) $_GET['m'], $usuario['id'], $tipo);
    }

    abrir_portal($indicador['titulo'], $tipo, $usuario);
    ?>
        <header class="encabezado">
            <h1 class="titulo"><?= mostrar($indicador['titulo']) ?></h1>
        </header>

        <?= aviso($flash['aviso'] ?? '') ?>

        <div class="indicador">
            <section class="tarjeta formulario-tarjeta" aria-labelledby="titulo-formulario">
                <h2 class="seccion-titulo" id="titulo-formulario">Nueva medición</h2>
                <form class="formulario" action="<?= url('vistas/php/' . $tipo . '_post.php') ?>" method="post">
                    <?= campo_csrf() ?>
                    <?= $campos($flash['errores'] ?? [], $flash['valores'] ?? []) ?>
                    <button class="boton boton-primario" type="submit"><?= $boton ?></button>
                </form>
            </section>

            <?= $medicion ? tarjeta_resultado($medicion) : resultado_vacio($tipo) ?>
        </div>

        <div class="indicador-secundario">
            <?= tabla_referencia($tipo) ?>
            <?= historial_indicador($tipo, $usuario['id'], $medicion ? (int) $medicion['id'] : 0) ?>
        </div>
    <?php
    cerrar_portal();
}

// Valor anterior de un campo, escapado para un atributo value
function valor($valores, $campo) {
    return mostrar($valores[$campo] ?? '');
}
