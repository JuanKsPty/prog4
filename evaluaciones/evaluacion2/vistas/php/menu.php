<?php
require 'funciones.php';
require 'plantilla.php';

$nombre = requerir_sesion();

function fecha_de_hoy() {
    $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $hoy = new DateTime('now', new DateTimeZone('America/Panama'));
    return ucfirst($dias[$hoy->format('w')]) . ' ' . $hoy->format('j') . ' de ' . $meses[$hoy->format('n') - 1];
}

$opciones = [
    [
        'enlace' => '../imc.html',
        'etiqueta' => 'Peso y estatura',
        'titulo' => 'Índice de masa corporal',
        'texto' => 'Descubre si tu peso es adecuado para tu estatura.',
        'tono' => 'tono-azul',
        'imagen' => 'menu-imc',
        'icono' => 'imc',
    ],
    [
        'enlace' => '../glucosa.html',
        'etiqueta' => 'Lectura del glucómetro',
        'titulo' => 'Glucosa en sangre',
        'texto' => 'Interpreta tu lectura en ayunas o después de comer.',
        'tono' => 'tono-menta',
        'imagen' => 'menu-glucosa',
        'icono' => 'glucosa',
    ],
    [
        'enlace' => '../presion.html',
        'etiqueta' => 'Sistólica y diastólica',
        'titulo' => 'Presión arterial',
        'texto' => 'Conoce en qué categoría está tu presión.',
        'tono' => 'tono-rojo',
        'imagen' => 'menu-presion',
        'icono' => 'presion',
    ],
];

abrir_pagina('Menú principal', 'menu', $nombre);
?>
                <section class="bienvenida" aria-labelledby="titulo-bienvenida">
                    <div>
                        <p class="antetitulo">Portal del paciente</p>
                        <h1 class="bienvenida-titulo" id="titulo-bienvenida">Bienvenido <?= mostrar($nombre) ?></h1>
                        <p class="bienvenida-texto">¿Qué quieres revisar hoy? Elige una opción para obtener tu diagnóstico.</p>
                    </div>
                    <p class="bienvenida-fecha"><span>Hoy es</span><?= fecha_de_hoy() ?></p>
                </section>

                <h2 class="solo-lectores">Opciones del menú</h2>
                <div class="opciones">
                    <?php foreach ($opciones as $opcion): ?>
                        <a class="tarjeta-opcion <?= $opcion['tono'] ?>" href="<?= $opcion['enlace'] ?>">
                            <div class="tarjeta-opcion-arte">
                                <?= ilustracion($opcion['imagen'], $opcion['icono']) ?>
                            </div>
                            <div class="tarjeta-opcion-cuerpo">
                                <span class="tarjeta-opcion-etiqueta"><?= $opcion['etiqueta'] ?></span>
                                <h3 class="tarjeta-opcion-titulo"><?= $opcion['titulo'] ?></h3>
                                <p class="tarjeta-opcion-texto"><?= $opcion['texto'] ?></p>
                                <span class="tarjeta-opcion-accion">Calcular <?= icono('flecha') ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <aside class="consejo">
                    <?= icono('info') ?>
                    <p>Estos resultados son orientativos y no reemplazan una consulta médica. Si algún valor sale fuera de lo normal, agenda una cita en el consultorio.</p>
                </aside>
<?php
cerrar_pagina();
