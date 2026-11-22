<?php
require 'php/funciones.php';
require 'php/plantilla.php';
require 'php/indicador.php';

$usuario = requerir_sesion();

$campos = function ($errores, $valores) {
    ob_start();
    ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="sistolica">Sistólica</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="number" id="sistolica" name="sistolica" min="50" max="300" step="1" inputmode="numeric" placeholder="Ej. 120" value="<?= valor($valores, 'sistolica') ?>" required<?= atributos_error($errores, 'sistolica', 'ayuda-sistolica') ?>>
                            <span class="campo-unidad">mm Hg</span>
                        </div>
                        <p class="campo-ayuda" id="ayuda-sistolica">Número de arriba</p>
                        <?= error_campo($errores, 'sistolica') ?>
                    </div>
                    <div class="campo">
                        <label class="campo-etiqueta" for="diastolica">Diastólica</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="number" id="diastolica" name="diastolica" min="30" max="200" step="1" inputmode="numeric" placeholder="Ej. 80" value="<?= valor($valores, 'diastolica') ?>" required<?= atributos_error($errores, 'diastolica', 'ayuda-diastolica') ?>>
                            <span class="campo-unidad">mm Hg</span>
                        </div>
                        <p class="campo-ayuda" id="ayuda-diastolica">Número de abajo</p>
                        <?= error_campo($errores, 'diastolica') ?>
                    </div>
    <?php
    return ob_get_clean();
};

pagina_indicador('presion', $usuario, $campos, 'Ver mi diagnóstico');
