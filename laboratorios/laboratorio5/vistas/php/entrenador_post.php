<?php
// Esta página solo responde al envío del formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../formulario.php');
    exit;
}

require __DIR__ . '/sitio.php';

$generos = [
    'masculino' => t('entrenador.masculino'),
    'femenino' => t('entrenador.femenino'),
    'otro' => t('entrenador.otro'),
];

$opciones_intereses = [
    'musica' => t('entrenador.musica'),
    'deporte' => t('entrenador.deporte'),
    'cine' => t('entrenador.cine'),
    'cocina' => t('entrenador.cocina'),
    'fotografia' => t('entrenador.fotografia'),
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
    return $valor === '' ? t('entrenador.no_indicado') : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
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
    $archivo_info = t('entrenador.archivo_error');
}

$nombre_completo = trim("$nombre $apellido");
$iniciales = mb_strtoupper(mb_substr($nombre, 0, 1) . mb_substr($apellido, 0, 1));
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('entrenador.recibidos_titulo') ?></title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body class="trainer-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/formulario.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('entrenador.recibidos_eyebrow') ?></p>
        <h1><?= t('entrenador.recibidos_h1') ?></h1>
        <p class="lead"><?= t('entrenador.recibidos_lead') ?></p>
    </header>

    <main id="contenido" class="trainer-layout">
        <section class="panel trainer-form trainer-result">
            <?php if (empty($_POST)): ?>
                <p><?= t('entrenador.limite') ?></p>
            <?php endif; ?>

            <fieldset>
                <legend><?= t('entrenador.datos') ?></legend>
                <dl class="field-grid trainer-data">
                    <div class="field">
                        <dt><?= t('entrenador.nombre') ?></dt>
                        <dd><?= mostrar($nombre) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.apellido') ?></dt>
                        <dd><?= mostrar($apellido) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.contrasena') ?></dt>
                        <dd><?= mostrar($password) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.correo') ?></dt>
                        <dd><?= mostrar($correo) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.edad') ?></dt>
                        <dd><?= mostrar($edad) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.nacimiento') ?></dt>
                        <dd><?= mostrar($fecha_nacimiento) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.genero') ?></dt>
                        <dd><?= mostrar($genero) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <fieldset>
                <legend><?= t('entrenador.intereses') ?></legend>
                <dl class="trainer-data">
                    <div class="field">
                        <dt><?= t('entrenador.seleccionados') ?></dt>
                        <dd><?= mostrar(implode(', ', $intereses)) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <fieldset>
                <legend><?= t('entrenador.mas') ?></legend>
                <dl class="field-grid trainer-data">
                    <div class="field field-full">
                        <dt><?= t('entrenador.comentarios') ?></dt>
                        <dd><?= nl2br(mostrar($comentarios)) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.provincia') ?></dt>
                        <dd><?= mostrar($provincia) ?></dd>
                    </div>
                    <div class="field">
                        <dt><?= t('entrenador.archivo_adjunto') ?></dt>
                        <dd><?= mostrar($archivo_info) ?></dd>
                    </div>
                </dl>
            </fieldset>

            <div class="form-actions">
                <a class="btn btn-primary" href="../formulario.php"><?= t('entrenador.volver') ?></a>
            </div>
        </section>

        <aside class="trainer-aside" aria-label="<?= t('entrenador.perfil_registrado') ?>">
            <div class="trainer-card">
                <div class="trainer-card-top">
                    <span class="eyebrow"><?= t('entrenador.licencia') ?></span>
                    <img src="../../img/pokemon-logo.png" alt="Pokémon">
                </div>
                <div class="trainer-card-body">
                    <span class="trainer-avatar" aria-hidden="true"><?= $iniciales === '' ? '?' : mostrar($iniciales) ?></span>
                    <div>
                        <p class="trainer-name"><?= $nombre_completo === '' ? t('entrenador.sin_nombre') : mostrar($nombre_completo) ?></p>
                        <p class="trainer-region"><?= $provincia === '' ? t('entrenador.sin_provincia') : mostrar($provincia) ?></p>
                    </div>
                </div>
                <ul class="trainer-interests" aria-label="<?= t('entrenador.intereses') ?>">
                    <?php if (empty($intereses)): ?>
                        <li class="is-empty"><?= t('entrenador.sin_intereses') ?></li>
                    <?php endif; ?>
                    <?php foreach ($intereses as $interes): ?>
                        <li><?= mostrar($interes) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </main>

    <?php pie(t('entrenador.pie')); ?>
</body>
</html>
