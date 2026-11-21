<?php
require 'vistas/php/funciones.php';
require 'vistas/php/plantilla.php';

$usuario = usuario_actual();
$indicadores = indicadores();
$detalles = [
    'imc' => 'Con tu peso y tu estatura calculamos tu índice de masa corporal y te decimos si tu peso es adecuado.',
    'glucosa' => 'Escribe la lectura de tu glucómetro y si te la tomaste en ayunas o después de comer.',
    'presion' => 'Con los dos números de tu tensiómetro te decimos en qué categoría está tu presión.',
];

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

    <main id="contenido">
        <section class="portada">
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
        </section>

        <section class="landing-seccion" aria-labelledby="titulo-indicadores">
            <div class="landing-seccion-interior">
                <h2 class="landing-titulo" id="titulo-indicadores">Qué puedes revisar</h2>
                <p class="landing-bajada">Cada resultado se ubica en una escala de colores, así sabes al instante si estás en un rango sano o si conviene ver al médico.</p>

                <div class="filas-indicador">
                    <?php foreach ($indicadores as $tipo => $indicador): ?>
                        <article class="fila-indicador">
                            <?= ilustracion($indicador['imagen'], $tipo, '', 'fila-indicador-imagen') ?>
                            <div class="fila-indicador-texto">
                                <h3 class="fila-indicador-titulo"><?= $indicador['titulo'] ?></h3>
                                <p class="fila-indicador-detalle"><?= $detalles[$tipo] ?></p>
                            </div>
                            <div class="fila-indicador-escala">
                                <?= banda(categorias_de($tipo), null, 0) ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="landing-seccion landing-seccion-pasos" aria-labelledby="titulo-pasos">
            <div class="landing-seccion-interior">
                <h2 class="landing-titulo" id="titulo-pasos">Cómo funciona</h2>
                <ol class="pasos">
                    <li class="paso">
                        <h3 class="paso-titulo">Crea tu cuenta</h3>
                        <p class="paso-texto">Solo necesitas tu nombre, un correo y una contraseña.</p>
                    </li>
                    <li class="paso">
                        <h3 class="paso-titulo">Escribe tus datos</h3>
                        <p class="paso-texto">Tu peso y estatura, la lectura del glucómetro o los números del tensiómetro.</p>
                    </li>
                    <li class="paso">
                        <h3 class="paso-titulo">Recibe tu diagnóstico</h3>
                        <p class="paso-texto">Ves tu categoría, un consejo y tu historial para comparar con mediciones anteriores.</p>
                    </li>
                </ol>
            </div>
        </section>

        <section class="cierre" aria-labelledby="titulo-cierre">
            <div class="cierre-interior">
                <h2 class="cierre-titulo" id="titulo-cierre">Empieza con tu primera medición</h2>
                <p class="cierre-texto">Los resultados son orientativos y no reemplazan una consulta médica.</p>
                <a class="boton boton-blanco boton-grande" href="<?= $usuario ? 'vistas/panel.php' : 'registro.php' ?>"><?= $usuario ? 'Ir a mi panel' : 'Crear mi cuenta' ?></a>
            </div>
        </section>
    </main>
<?php
pie();
documento_fin();
