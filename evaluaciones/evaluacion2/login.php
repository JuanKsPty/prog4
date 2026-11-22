<?php
require 'vistas/php/funciones.php';
require 'vistas/php/plantilla.php';
require 'vistas/php/acceso.php';

if (usuario_actual() !== null) {
    redirigir('vistas/panel.php');
}

$flash = tomar_flash();
$valores = $flash['valores'] ?? [];

abrir_acceso('Iniciar sesión', 'login');
?>
                <h1 class="acceso-titulo">Inicia sesión</h1>

                <?= aviso($flash['exito'] ?? '', 'exito') ?>
                <?= aviso($flash['aviso'] ?? '') ?>

                <form class="formulario" action="vistas/php/login_post.php" method="post">
                    <?= campo_csrf() ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="correo">Correo</label>
                        <input class="campo-entrada" type="email" id="correo" name="correo" autocomplete="email" maxlength="160" value="<?= mostrar($valores['correo'] ?? '') ?>" required autofocus>
                    </div>
                    <?= campo_clave('clave', 'Contraseña', 'current-password', []) ?>
                    <button class="boton boton-primario boton-ancho" type="submit">Iniciar sesión</button>
                </form>

                <p class="acceso-cambio">¿No tienes cuenta? <a class="enlace" href="registro.php">Crea una en un minuto</a></p>
<?php
cerrar_acceso();
