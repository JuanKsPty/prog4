<?php
require 'php/funciones.php';
require 'php/plantilla.php';

$usuario = requerir_sesion();
$flash = tomar_flash();
$ultimas = ultima_por_tipo($usuario['id']);
$recientes = mediciones_recientes($usuario['id'], 8);
$total = total_mediciones($usuario['id']);
$indicadores = indicadores();

abrir_portal('Inicio', 'inicio', $usuario);
?>
        <?= aviso($flash['exito'] ?? '', 'exito') ?>

        <header class="saludo">
            <h1 class="saludo-titulo">Bienvenido <?= mostrar($usuario['nombre']) ?></h1>
            <p class="saludo-texto"><?= fecha_de_hoy() ?><?= $total > 0 ? ' · ' . $total . ($total === 1 ? ' medición' : ' mediciones') : '' ?></p>
        </header>

        <section aria-labelledby="titulo-indicadores">
            <h2 class="solo-lectores" id="titulo-indicadores">Tus indicadores</h2>
            <div class="indicadores">
                <?php foreach ($indicadores as $tipo => $indicador):
                    $medicion = $ultimas[$tipo];
                ?>
                    <article class="indicador-tarjeta<?= $medicion ? ' ' . categoria_de_medicion($medicion)['tono'] : ' sin-datos' ?>">
                        <div class="indicador-tarjeta-cabecera">
                            <?= ilustracion($indicador['imagen'], $tipo, '', 'indicador-tarjeta-imagen') ?>
                            <div>
                                <h3 class="indicador-tarjeta-titulo"><?= mostrar($indicador['titulo']) ?></h3>
                                <p class="indicador-tarjeta-fecha">
                                    <?= $medicion ? 'Última medición: ' . mb_strtolower(fecha_relativa($medicion['creado_en'])) : 'Sin mediciones todavía' ?>
                                </p>
                            </div>
                        </div>

                        <?php if ($medicion):
                            $lectura = lectura_de_medicion($medicion);
                            $categoria = categoria_de_medicion($medicion);
                        ?>
                            <div class="indicador-tarjeta-lectura">
                                <p class="indicador-tarjeta-valor"><?= $lectura['valor'] ?> <span class="indicador-tarjeta-unidad"><?= $lectura['unidad'] ?></span></p>
                                <span class="indicador-tarjeta-categoria <?= $categoria['tono'] ?>"><?= mostrar($categoria['nombre']) ?></span>
                            </div>
                            <?= banda(categorias_de($tipo, $medicion['momento'] ?? 'ayunas'), $medicion['categoria'], posicion_de_medicion($medicion), false) ?>
                            <a class="boton boton-secundario" href="<?= url($indicador['pagina']) ?>">Medir de nuevo</a>
                        <?php else: ?>
                            <div class="indicador-tarjeta-lectura">
                                <p class="indicador-tarjeta-valor">—</p>
                                <span class="indicador-tarjeta-categoria es-pendiente">Pendiente</span>
                            </div>
                            <?= banda(categorias_de($tipo), null, 0, false) ?>
                            <a class="boton boton-primario" href="<?= url($indicador['pagina']) ?>">Medir <?= $tipo === 'imc' ? 'mi IMC' : 'mi ' . mb_strtolower($indicador['nombre']) ?></a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="tarjeta recientes" aria-labelledby="titulo-recientes">
            <div class="recientes-cabecera">
                <h2 class="seccion-titulo" id="titulo-recientes">Mediciones recientes</h2>
                <p class="nota-medica"><?= icono('info') ?> Estos resultados son orientativos y no reemplazan una consulta médica.</p>
            </div>
            <?php if (!$recientes): ?>
                <div class="vacio">
                    <?= icono('reloj', 'icono vacio-icono') ?>
                    <p class="vacio-texto">Aquí aparecerán tus mediciones.</p>
                </div>
            <?php else: ?>
                <ul class="lista-mediciones lista-mediciones-panel">
                    <?php foreach ($recientes as $medicion):
                        $lectura = lectura_de_medicion($medicion);
                        $categoria = categoria_de_medicion($medicion);
                        $indicador = $indicadores[$medicion['tipo']];
                    ?>
                        <li class="medicion">
                            <span class="medicion-fecha"><?= fecha_relativa($medicion['creado_en']) ?></span>
                            <a class="medicion-indicador" href="<?= url($indicador['pagina'] . '?m=' . (int) $medicion['id']) ?>"><?= icono($medicion['tipo']) ?> <?= $indicador['nombre'] ?></a>
                            <span class="medicion-lectura"><?= $lectura['valor'] ?> <span class="medicion-unidad"><?= $lectura['unidad'] ?></span></span>
                            <?= insignia($categoria) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

<?php
cerrar_portal();
