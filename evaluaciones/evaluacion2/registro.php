<?php
require 'vistas/php/funciones.php';
require 'vistas/php/plantilla.php';
require 'vistas/php/acceso.php';

if (usuario_actual() !== null) {
    redirigir('vistas/panel.php');
}

$flash = tomar_flash();
$errores = $flash['errores'] ?? [];
$valores = $flash['valores'] ?? [];

abrir_acceso('Crear cuenta', 'registro');
?>
                <h1 class="acceso-titulo">Crea tu cuenta</h1>
                <p class="acceso-texto">Para guardar tu historial de mediciones.</p>

                <?= aviso($flash['aviso'] ?? '') ?>

                <form class="formulario" action="vistas/php/registro_post.php" method="post">
                    <?= campo_csrf() ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="nombre">Nombre</label>
                        <input class="campo-entrada" type="text" id="nombre" name="nombre" autocomplete="name" minlength="2" maxlength="80" value="<?= mostrar($valores['nombre'] ?? '') ?>" required<?= atributos_error($errores, 'nombre', 'ayuda-nombre') ?>>
                        <p class="campo-ayuda" id="ayuda-nombre">Es el nombre con el que te saludaremos.</p>
                        <?= error_campo($errores, 'nombre') ?>
                    </div>
                    <div class="campo">
                        <label class="campo-etiqueta" for="correo">Correo</label>
                        <input class="campo-entrada" type="email" id="correo" name="correo" autocomplete="email" maxlength="160" value="<?= mostrar($valores['correo'] ?? '') ?>" required<?= atributos_error($errores, 'correo') ?>>
                        <?= error_campo($errores, 'correo') ?>
                    </div>
                    <?= campo_clave('clave', 'Contraseña', 'new-password', $errores, 'Al menos 8 caracteres.') ?>
                    <?= campo_clave('confirmacion', 'Repite la contraseña', 'new-password', $errores) ?>
                    <button class="boton boton-primario boton-ancho" type="submit">Crear cuenta</button>
                </form>

                <p class="acceso-cambio">¿Ya tienes cuenta? <a class="enlace" href="login.php">Inicia sesión</a></p>
<?php
cerrar_acceso();
