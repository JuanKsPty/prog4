<?php
require __DIR__ . '/php/sitio.php';

$con_sesion = !empty($_SESSION['usuario']);

if ($con_sesion) {
    // Se verifica si no existen las variables de color en la sesión y se les asigna el valor original
    if (!isset($_SESSION['colorfondo'])) {
        $_SESSION['colorfondo'] = '';
    }
    if (!isset($_SESSION['colortexto'])) {
        $_SESSION['colortexto'] = '';
    }

    // Se verifica si fue presionado el botón enviar y se guardan los colores en la sesión
    if (isset($_REQUEST['enviar'])) {
        $fondo = $_REQUEST['Opcolorfondo'] ?? '';
        $texto = $_REQUEST['Opcolortxt'] ?? '';
        $_SESSION['colorfondo'] = is_string($fondo) && isset(COLORES[$fondo]) ? $fondo : '';
        $_SESSION['colortexto'] = is_string($texto) && isset(COLORES[$texto]) ? $texto : '';
        header('Location: colores.php?aviso=guardado');
        exit;
    }

    if (isset($_REQUEST['restablecer'])) {
        $_SESSION['colorfondo'] = '';
        $_SESSION['colortexto'] = '';
        header('Location: colores.php?aviso=restablecido');
        exit;
    }
}

$aviso = $_GET['aviso'] ?? '';

function opciones_color($uso, $elegido) {
    ?>
    <option value="" data-color=""<?= $elegido === '' ? ' selected' : '' ?>><?= t('colores.original') ?></option>
    <?php foreach (COLORES as $nombre => $tonos): ?>
        <option value="<?= $nombre ?>" data-color="<?= $tonos[$uso] ?>"<?= $elegido === $nombre ? ' selected' : '' ?>><?= t("color.$nombre") ?></option>
    <?php endforeach;
}
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('colores.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <?php colores_sesion(); ?>
</head>
<body class="session-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/colores.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('colores.eyebrow') ?></p>
        <h1><?= t('colores.h1') ?></h1>
        <p class="lead"><?= t('colores.lead') ?></p>
    </header>

    <main id="contenido" class="session-layout">
        <?php if (!$con_sesion): ?>
            <section class="panel session-panel">
                <p class="session-message is-error"><strong><?= t('colores.restringida_titulo') ?></strong><br><?= t('colores.restringida_texto') ?></p>
                <div class="form-actions">
                    <a class="btn btn-primary" href="login.php"><?= t('nav.login') ?></a>
                </div>
            </section>
        <?php else: ?>
            <section class="panel session-panel">
                <p><?= t('login.activa', htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8')) ?></p>
                <?php if ($aviso === 'guardado'): ?>
                    <p class="session-message is-success" role="status"><?= t('colores.guardado') ?></p>
                <?php elseif ($aviso === 'restablecido'): ?>
                    <p class="session-message is-success" role="status"><?= t('colores.restablecido') ?></p>
                <?php endif; ?>
                <form class="session-form" method="post" action="colores.php">
                    <div class="field">
                        <label for="Opcolorfondo"><?= t('colores.fondo') ?></label>
                        <select id="Opcolorfondo" name="Opcolorfondo">
                            <?php opciones_color('fondo', $_SESSION['colorfondo']); ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="Opcolortxt"><?= t('colores.texto') ?></label>
                        <select id="Opcolortxt" name="Opcolortxt">
                            <?php opciones_color('texto', $_SESSION['colortexto']); ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary" name="restablecer" value="1"><?= t('colores.restablecer') ?></button>
                        <button type="submit" class="btn btn-primary" name="enviar" value="1"><?= t('colores.guardar') ?></button>
                    </div>
                </form>
            </section>

            <aside class="color-preview" aria-label="<?= t('colores.vista_previa') ?>">
                <p class="eyebrow"><?= t('colores.vista_previa') ?></p>
                <div class="color-sample" id="muestra-colores">
                    <h2><?= t('colores.ejemplo_titulo') ?></h2>
                    <p><?= t('colores.ejemplo_texto') ?></p>
                </div>
            </aside>
        <?php endif; ?>
    </main>

    <?php pie(t('colores.pie')); ?>

    <?php if ($con_sesion): ?>
        <script>
            // La vista previa cambia al elegir un color, antes de guardarlo
            const muestra = document.getElementById('muestra-colores')
            const selectFondo = document.getElementById('Opcolorfondo')
            const selectTexto = document.getElementById('Opcolortxt')

            function actualizarMuestra() {
                muestra.style.setProperty('--muestra-fondo', selectFondo.selectedOptions[0].dataset.color || null)
                muestra.style.setProperty('--muestra-texto', selectTexto.selectedOptions[0].dataset.color || null)
            }

            selectFondo.addEventListener('change', actualizarMuestra)
            selectTexto.addEventListener('change', actualizarMuestra)
            actualizarMuestra()
        </script>
    <?php endif; ?>
</body>
</html>
