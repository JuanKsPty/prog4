<?php
// Estructura de las páginas de inicio de sesión y registro

function abrir_acceso($titulo, $activa) {
    documento_inicio($titulo, 'cuerpo-acceso');
    ?>
    <main class="acceso" id="contenido">
        <div class="acceso-tarjeta">
            <aside class="acceso-arte">
                <?= marca('index.php') ?>
                <img class="acceso-imagen" src="<?= url('img/login.webp') ?>" alt="" width="720" height="720">
                <p class="acceso-arte-texto">Tu IMC, tu glucosa y tu presión arterial, con su diagnóstico y tu historial en un solo lugar.</p>
            </aside>

            <div class="acceso-contenido">
                <div class="acceso-marca-movil"><?= marca('index.php') ?></div>
                <nav class="pestanas" aria-label="Acceso">
                    <a class="pestana" href="<?= url('login.php') ?>"<?= $activa === 'login' ? ' aria-current="page"' : '' ?>>Iniciar sesión</a>
                    <a class="pestana" href="<?= url('registro.php') ?>"<?= $activa === 'registro' ? ' aria-current="page"' : '' ?>>Crear cuenta</a>
                </nav>
    <?php
}

function cerrar_acceso() {
    ?>
            </div>
        </div>
    </main>
    <?php
    pie();
    documento_fin(['clave.js']);
}

function campo_clave($id, $etiqueta, $autocompletar, $errores, $ayuda = '') {
    ob_start();
    ?>
                    <div class="campo">
                        <label class="campo-etiqueta" for="<?= $id ?>"><?= $etiqueta ?></label>
                        <div class="campo-clave">
                            <input class="campo-entrada" type="password" id="<?= $id ?>" name="<?= $id ?>" autocomplete="<?= $autocompletar ?>" required<?= $id !== 'clave' || $autocompletar === 'new-password' ? ' minlength="8"' : '' ?><?= atributos_error($errores, $id, $ayuda ? 'ayuda-' . $id : '') ?>>
                            <button class="boton-ver-clave" type="button" aria-label="Mostrar contraseña" aria-pressed="false" data-campo="<?= $id ?>">
                                <svg class="icono icono-mostrar" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="icono icono-ocultar" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.2"/><path d="M6.6 6.6C3.9 8.4 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                            </button>
                        </div>
                        <?php if ($ayuda): ?><p class="campo-ayuda" id="ayuda-<?= $id ?>"><?= $ayuda ?></p><?php endif; ?>
                        <?= error_campo($errores, $id) ?>
                    </div>
    <?php
    return ob_get_clean();
}
