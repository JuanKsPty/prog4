<?php
// Encabezado, barra lateral y pie que comparten las páginas PHP

function abrir_pagina($titulo, $activo, $nombre) {
    $opciones = [
        'menu' => ['Menú principal', 'menu.php'],
        'imc' => ['IMC', '../imc.html'],
        'glucosa' => ['Glucosa en sangre', '../glucosa.html'],
        'presion' => ['Presión arterial', '../presion.html'],
    ];
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= mostrar($titulo) ?> | Consultorio ABC</title>
    <link rel="icon" href="../../img/logo.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body class="cuerpo">
    <a class="saltar" href="#contenido">Saltar al contenido</a>

    <div class="app">
        <header class="barra">
            <a class="marca" href="menu.php">
                <img class="marca-logo" src="../../img/logo.svg" alt="" width="44" height="44">
                <span class="marca-texto">Consultorio ABC</span>
            </a>

            <nav class="barra-navegacion" aria-label="Menú principal">
                <p class="menu-titulo">Menú</p>
                <ul class="menu">
                    <?php foreach ($opciones as $clave => [$texto, $enlace]): ?>
                        <li>
                            <a class="menu-enlace" href="<?= $enlace ?>"<?= $clave === $activo ? ' aria-current="page"' : '' ?>>
                                <?= icono($clave) ?>
                                <?= $texto ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="barra-usuario">
                <span class="avatar" aria-hidden="true"><?= mostrar(iniciales($nombre)) ?></span>
                <div class="barra-usuario-datos">
                    <p class="barra-usuario-nombre"><?= mostrar($nombre) ?></p>
                    <p class="barra-usuario-rol">Paciente</p>
                </div>
                <a class="enlace-salir" href="salir.php" title="Cerrar sesión">
                    <?= icono('salir') ?>
                    <span class="solo-lectores">Cerrar sesión</span>
                </a>
            </div>
        </header>

        <div class="principal">
            <main class="contenido" id="contenido">
    <?php
}

function cerrar_pagina() {
    ?>
            </main>

            <footer class="pie">
                <p class="pie-integrantes">Juan Castillo · Agustín Traverso</p>
                <p class="pie-curso">Programación IV · Grupo 1SG131 · Universidad Tecnológica de Panamá · 2026</p>
            </footer>
        </div>
    </div>
</body>
</html>
    <?php
}
