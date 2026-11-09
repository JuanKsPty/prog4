<?php
// Esta página solo responde al envío del formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../formulario.html');
    exit;
}

$generos = [
    'masculino' => 'Masculino',
    'femenino' => 'Femenino',
    'otro' => 'Otro',
];

$opciones_intereses = [
    'musica' => 'Música',
    'deporte' => 'Deporte',
    'cine' => 'Cine',
    'cocina' => 'Cocina',
    'fotografia' => 'Fotografía',
];

$provincias = [
    'bocas-del-toro' => 'Bocas del Toro',
    'cocle' => 'Coclé',
    'colon' => 'Colón',
    'chiriqui' => 'Chiriquí',
    'darien' => 'Darién',
    'herrera' => 'Herrera',
    'los-santos' => 'Los Santos',
    'panama' => 'Panamá',
    'veraguas' => 'Veraguas',
];

function leer($campo) {
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

// htmlspecialchars evita que lo escrito en el formulario se ejecute como HTML
function mostrar($valor) {
    return $valor === '' ? 'No indicado' : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

$nombre = leer('nombre');
$apellido = leer('apellido');
$password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
$correo = leer('correo');
$edad = leer('edad');
$fecha_nacimiento = leer('fecha_nacimiento');
$genero = $generos[leer('genero')] ?? '';
$comentarios = leer('comentarios');
$provincia = $provincias[leer('provincia')] ?? '';

$intereses = [];
foreach ((array) ($_POST['intereses'] ?? []) as $interes) {
    if (is_string($interes) && isset($opciones_intereses[$interes])) {
        $intereses[] = $opciones_intereses[$interes];
    }
}

$archivo = $_FILES['archivo'] ?? null;
if ($archivo === null || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
    $archivo_info = '';
} elseif ($archivo['error'] === UPLOAD_ERR_OK) {
    $archivo_info = $archivo['name'] . ' (' . $archivo['type'] . ', ' . $archivo['size'] . ' bytes)';
} else {
    $archivo_info = 'No se pudo subir el archivo';
}

$nombre_completo = trim("$nombre $apellido");
$iniciales = mb_strtoupper(mb_substr($nombre, 0, 1) . mb_substr($apellido, 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos recibidos | Pokémon Lab</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body class="trainer-page">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>

    <nav class="site-nav" aria-label="Navegación principal">
        <a class="nav-brand" href="../../index.html" aria-label="Pokémon Lab, inicio"><img src="../../img/pokemon-logo.png" alt="Pokémon"></a>
        <div class="nav-links">
            <a href="../../index.html">Inicio</a>
            <a href="../index.html">Pokédex</a>
            <a href="../tipos.html">Tipos</a>
            <a href="../multimedia.html">Multimedia</a>
            <a href="../formulario.html" aria-current="page">Entrenador</a>
        </div>
    </nav>

    <header class="page-header">
        <p class="eyebrow">Datos del entrenador</p>
        <h1>Datos recibidos</h1>
        <p class="lead">Esto es lo que enviaste en el formulario.</p>
    </header>

    <main id="contenido" class="trainer-layout">
        <section class="panel trainer-form trainer-result">
            <?php if (empty($_POST)): ?>
                <p>El envío superó el límite de tamaño del servidor y no se recibieron los datos.</p>
            <?php endif; ?>

            <fieldset>
                <legend>Datos personales</legend>
                <dl class="field-grid trainer-data">
                    <div class="field">
                        <dt>Nombre</dt>
                        <dd><?= mostrar($nombre) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Apellido</dt>
                        <dd><?= mostrar($apellido) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Contraseña</dt>
                        <dd><?= mostrar($password) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Correo</dt>
                        <dd><?= mostrar($correo) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Edad</dt>
                        <dd><?= mostrar($edad) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Fecha de nacimiento</dt>
                        <dd><?= mostrar($fecha_nacimiento) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Género</dt>
                        <dd><?= mostrar($genero) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <fieldset>
                <legend>Intereses</legend>
                <dl class="trainer-data">
                    <div class="field">
                        <dt>Seleccionados</dt>
                        <dd><?= mostrar(implode(', ', $intereses)) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <fieldset>
                <legend>Más sobre ti</legend>
                <dl class="field-grid trainer-data">
                    <div class="field field-full">
                        <dt>Comentarios</dt>
                        <dd><?= nl2br(mostrar($comentarios)) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Provincia</dt>
                        <dd><?= mostrar($provincia) ?></dd>
                    </div>
                    <div class="field">
                        <dt>Archivo adjunto</dt>
                        <dd><?= mostrar($archivo_info) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <div class="form-actions">
                <a class="btn btn-primary" href="../formulario.html">Volver al formulario</a>
            </div>
        </section>

        <aside class="trainer-aside" aria-label="Perfil registrado">
            <div class="trainer-card">
                <div class="trainer-card-top">
                    <span class="eyebrow">Licencia de entrenador</span>
                    <img src="../../img/pokemon-logo.png" alt="Pokémon">
                </div>
                <div class="trainer-card-body">
                    <span class="trainer-avatar" aria-hidden="true"><?= $iniciales === '' ? '?' : mostrar($iniciales) ?></span>
                    <div>
                        <p class="trainer-name"><?= $nombre_completo === '' ? 'Sin nombre' : mostrar($nombre_completo) ?></p>
                        <p class="trainer-region"><?= $provincia === '' ? 'Sin provincia' : mostrar($provincia) ?></p>
                    </div>
                </div>
                <ul class="trainer-interests" aria-label="Intereses">
                    <?php if (empty($intereses)): ?>
                        <li class="is-empty">Sin intereses</li>
                    <?php endif; ?>
                    <?php foreach ($intereses as $interes): ?>
                        <li><?= mostrar($interes) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </main>

    <footer class="site-footer">
        <img src="../../img/pokemon-logo.png" alt="Pokémon">
        <p>Pokémon Lab · Entrenador</p>
    </footer>
</body>
</html>
