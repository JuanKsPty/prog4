<?php
require 'php/funciones.php';
require 'php/plantilla.php';

$usuario = requerir_sesion();
$flash = tomar_flash();
$errores = $flash['errores'] ?? [];
$registro = buscar_usuario($usuario['id']);
$total = total_mediciones($usuario['id']);
$desde = new DateTime($registro['creado_en']);
$meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

abrir_portal('Mi cuenta', 'cuenta', $usuario);
?>
        <header class="encabezado">
            <h1 class="titulo">Mi cuenta</h1>
        </header>

        <?= aviso($flash['aviso'] ?? '') ?>

        <div class="cuenta">
            <section class="tarjeta" aria-labelledby="titulo-datos">
                <h2 class="seccion-titulo" id="titulo-datos">Datos de la cuenta</h2>
                <dl class="datos-cuenta">
                    <div><dt>Nombre</dt><dd><?= mostrar($registro['nombre']) ?></dd></div>
                    <div><dt>Correo</dt><dd><?= mostrar($registro['correo']) ?></dd></div>
                    <div><dt>Cuenta creada</dt><dd><?= $desde->format('j') . ' de ' . $meses[$desde->format('n') - 1] . ' de ' . $desde->format('Y') ?></dd></div>
                    <div><dt>Mediciones guardadas</dt><dd><?= $total ?></dd></div>
                </dl>
            </section>

            <section class="tarjeta zona-peligro" aria-labelledby="titulo-eliminar">
                <h2 class="seccion-titulo" id="titulo-eliminar">Eliminar mi cuenta</h2>
                <p class="zona-peligro-texto"><?= texto_eliminar_cuenta($total) ?> No se puede deshacer.</p>
                <form class="formulario" action="<?= url('vistas/php/cuenta_post.php') ?>" method="post">
                    <?= campo_csrf() ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="clave">Escribe tu contraseña para confirmar</label>
                        <input class="campo-entrada" type="password" id="clave" name="clave" autocomplete="current-password" required<?= atributos_error($errores, 'clave') ?>>
                        <?= error_campo($errores, 'clave') ?>
                    </div>
                    <button class="boton boton-peligro" type="submit">Eliminar mi cuenta</button>
                </form>
            </section>
        </div>
<?php
cerrar_portal();
