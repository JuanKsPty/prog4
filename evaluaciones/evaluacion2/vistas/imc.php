<?php
require 'php/funciones.php';
require 'php/plantilla.php';
require 'php/indicador.php';

$usuario = requerir_sesion();

$campos = function ($errores, $valores) {
    ob_start();
    ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="peso">Peso</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="number" id="peso" name="peso" min="1" max="500" step="0.1" inputmode="decimal" placeholder="Ej. 70" value="<?= valor($valores, 'peso') ?>" required<?= atributos_error($errores, 'peso') ?>>
                            <span class="campo-unidad">kg</span>
                        </div>
                        <?= error_campo($errores, 'peso') ?>
                    </div>
                    <div class="campo">
                        <label class="campo-etiqueta" for="estatura">Estatura</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="text" id="estatura" name="estatura" inputmode="numeric" maxlength="4" autocomplete="off" data-formato="estatura" placeholder="Ej. 1.70" value="<?= valor($valores, 'estatura') ?>" required<?= atributos_error($errores, 'estatura') ?>>
                            <span class="campo-unidad">m</span>
                        </div>
                        <?= error_campo($errores, 'estatura') ?>
                    </div>
    <?php
    return ob_get_clean();
};

pagina_indicador('imc', $usuario, $campos, 'Calcular mi IMC');
