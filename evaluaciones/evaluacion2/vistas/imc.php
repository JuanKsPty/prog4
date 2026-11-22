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
                            <input class="campo-entrada" type="number" id="peso" name="peso" min="1" max="500" step="0.1" inputmode="decimal" placeholder="70" value="<?= valor($valores, 'peso') ?>" required<?= atributos_error($errores, 'peso') ?>>
                            <span class="campo-unidad">kg</span>
                        </div>
                        <?= error_campo($errores, 'peso') ?>
                    </div>
                    <div class="campo">
                        <label class="campo-etiqueta" for="estatura">Estatura</label>
                        <div class="campo-unidad-grupo">
                            <input class="campo-entrada" type="number" id="estatura" name="estatura" min="0.5" step="0.01" inputmode="decimal" placeholder="1.70" value="<?= valor($valores, 'estatura') ?>" required<?= atributos_error($errores, 'estatura', 'ayuda-estatura') ?>>
                            <span class="campo-unidad">m</span>
                        </div>
                        <p class="campo-ayuda" id="ayuda-estatura">En metros, por ejemplo 1.70.</p>
                        <?= error_campo($errores, 'estatura') ?>
                    </div>
    <?php
    return ob_get_clean();
};

pagina_indicador('imc', $usuario, 'Escribe tu peso y tu estatura para saber si tu peso es adecuado.', $campos, 'Calcular mi IMC');
