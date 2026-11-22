<?php
require 'php/funciones.php';
require 'php/plantilla.php';
require 'php/indicador.php';

$usuario = requerir_sesion();

$campos = function ($errores, $valores) {
    $momento = $valores['momento'] ?? '';
    ob_start();
    ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="lectura">Lectura del glucómetro</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="number" id="lectura" name="lectura" min="10" max="1000" step="any" inputmode="decimal" placeholder="Ej. 95" value="<?= valor($valores, 'lectura') ?>" required<?= atributos_error($errores, 'lectura') ?>>
                            <span class="campo-unidad">mg/dl</span>
                        </div>
                        <?= error_campo($errores, 'lectura') ?>
                    </div>
                    <fieldset class="campo campo-opciones"<?= atributos_error($errores, 'momento') ?>>
                        <legend class="campo-etiqueta">¿Cuándo te la tomaste?</legend>
                        <div class="opciones">
                            <label class="opcion">
                                <input type="radio" name="momento" value="ayunas" required<?= $momento === 'ayunas' ? ' checked' : '' ?>>
                                <span class="opcion-titulo">En ayunas</span>
                                <span class="opcion-detalle">8 h sin comer</span>
                            </label>
                            <label class="opcion">
                                <input type="radio" name="momento" value="posprandial"<?= $momento === 'posprandial' ? ' checked' : '' ?>>
                                <span class="opcion-titulo">Después de comer</span>
                                <span class="opcion-detalle">2 h después</span>
                            </label>
                        </div>
                        <?= error_campo($errores, 'momento') ?>
                    </fieldset>
    <?php
    return ob_get_clean();
};

pagina_indicador('glucosa', $usuario, $campos, 'Ver mi diagnóstico');
