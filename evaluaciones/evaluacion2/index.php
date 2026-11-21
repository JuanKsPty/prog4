<?php
require 'vistas/php/funciones.php';
require 'vistas/php/plantilla.php';

$usuario = usuario_actual();

documento_inicio('Portal de pacientes', 'cuerpo-landing');
?>
    <header class="landing-barra">
        <div class="landing-barra-interior">
            <?= marca('index.php') ?>
            <nav class="landing-acciones" aria-label="Acceso">
                <?php if ($usuario): ?>
                    <a class="boton boton-primario" href="vistas/panel.php">Ir a mi panel</a>
                <?php else: ?>
                    <a class="enlace landing-entrar" href="login.php">Iniciar sesión</a>
                    <a class="boton boton-primario" href="registro.php">Crear cuenta</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main id="contenido" class="portada">
        <div class="portada-texto">
            <h1 class="portada-titulo">Revisa tu IMC, tu glucosa y tu presión en un minuto</h1>
            <p class="portada-bajada">El portal de pacientes del Consultorio ABC te da el diagnóstico con las mismas tablas que usamos en consulta y guarda cada medición para que veas cómo vas cambiando.</p>
            <div class="portada-acciones">
                <?php if ($usuario): ?>
                    <a class="boton boton-primario boton-grande" href="vistas/panel.php">Ir a mi panel</a>
                <?php else: ?>
                    <a class="boton boton-primario boton-grande" href="registro.php">Crear mi cuenta</a>
                    <a class="boton boton-secundario boton-grande" href="login.php">Ya tengo cuenta</a>
                <?php endif; ?>
            </div>
        </div>
        <img class="portada-imagen" src="img/login.webp" alt="Estetoscopio, expediente, glucómetro y báscula" width="720" height="720">
    </main>
<?php
pie();
documento_fin();
