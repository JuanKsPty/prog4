<?php
require __DIR__ . '/php/sitio.php';

$mensaje = '';

// Se verifica si fue presionado el botón enviar
if (isset($_POST['enviar'])) {
    $usuario = is_string($_POST['usuario'] ?? null) ? trim($_POST['usuario']) : '';
    $clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';

    if ($usuario === '' || $clave === '') {
        $mensaje = t('login.vacios');
    } elseif ($usuario === USUARIO_PRUEBA && $clave === CLAVE_PRUEBA) {
        // Se guarda el usuario en la sesión y se pasa a la página segura
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;
        header('Location: colores.php');
        exit;
    } else {
        $mensaje = t('login.incorrecto');
    }
}

$con_sesion = !empty($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('login.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <?php colores_sesion(); ?>
</head>
<body class="session-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/login.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('login.eyebrow') ?></p>
        <h1><?= t('login.h1') ?></h1>
        <p class="lead"><?= t('login.lead') ?></p>
    </header>

    <main id="contenido" class="session-layout">
        <section class="panel session-panel">
            <?php if ($con_sesion): ?>
                <p class="session-message is-success" role="status"><?= t('login.activa', htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8')) ?></p>
                <div class="form-actions">
                    <a class="btn btn-secondary" href="php/logout.php"><?= t('nav.salir', htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8')) ?></a>
                    <a class="btn btn-primary" href="colores.php"><?= t('login.ir_colores') ?></a>
                </div>
            <?php else: ?>
                <?php if ($mensaje !== ''): ?>
                    <p class="session-message is-error" role="alert"><?= $mensaje ?></p>
                <?php elseif (isset($_GET['salida'])): ?>
                    <p class="session-message is-success" role="status"><?= t('login.finalizada') ?></p>
                <?php endif; ?>
                <form class="session-form" name="login" method="post" action="login.php">
                    <div class="field">
                        <label for="usuario"><?= t('login.usuario') ?></label>
                        <input type="text" id="usuario" name="usuario" autocomplete="username" value="<?= htmlspecialchars($usuario ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="field">
                        <label for="clave"><?= t('login.clave') ?></label>
                        <input type="password" id="clave" name="clave" autocomplete="current-password">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="enviar" value="1"><?= t('login.enviar') ?></button>
                    </div>
                </form>
            <?php endif; ?>
        </section>

        <aside class="adventure-note">
            <h2><?= t('login.prueba_titulo') ?></h2>
            <p><?= t('login.prueba_texto') ?></p>
            <p><?= t('login.sesion_texto') ?></p>
        </aside>
    </main>

    <?php pie(t('login.pie')); ?>
</body>
</html>
