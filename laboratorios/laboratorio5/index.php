<?php require __DIR__ . '/vistas/php/sitio.php'; ?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('inicio.titulo') ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="home-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('index.php'); ?>

    <header class="hero">
        <div class="hero-copy">
            <img class="hero-logo" src="img/pokemon-logo.png" alt="Pokémon">
            <h1>Pokémon Lab</h1>
            <p class="hero-lead"><?= t('inicio.lead') ?></p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="vistas/index.php"><?= t('inicio.abrir_pokedex') ?></a>
                <a class="btn btn-ghost" href="vistas/formulario.php"><?= t('inicio.crear_perfil') ?></a>
            </div>
            <dl class="hero-stats">
                <div><dt><?= t('inicio.stat_pokedex') ?></dt><dd>20</dd></div>
                <div><dt><?= t('inicio.stat_tipos') ?></dt><dd>18</dd></div>
                <div><dt><?= t('inicio.stat_gritos') ?></dt><dd>4</dd></div>
                <div><dt><?= t('inicio.stat_visitas') ?></dt><dd><?= $visitas ?></dd></div>
            </dl>
        </div>
        <div class="hero-art" aria-hidden="true">
            <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png" alt="" width="475" height="475">
        </div>
    </header>

    <main id="contenido">
        <section class="mission">
            <div>
                <p class="eyebrow"><?= t('inicio.bienvenida') ?></p>
                <h2><?= t('inicio.mision_titulo') ?></h2>
            </div>
            <div class="mission-copy">
                <p><?= t('inicio.mision_texto') ?></p>
                <p class="mission-callout"><?= t('inicio.mision_callout') ?></p>
            </div>
        </section>

        <section class="routes" aria-labelledby="rutas-titulo">
            <div class="section-head">
                <p class="eyebrow"><?= t('inicio.explora') ?></p>
                <h2 id="rutas-titulo"><?= t('inicio.rutas') ?></h2>
            </div>
            <div class="route-grid">
                <article class="route-card">
                    <div class="route-top">
                        <span class="route-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h6a4 4 0 0 1 4 4v12a3 3 0 0 0-3-3H2z"/><path d="M22 4h-6a4 4 0 0 0-4 4v12a3 3 0 0 1 3-3h7z"/></svg>
                        </span>
                        <span class="route-number">01</span>
                    </div>
                    <h3><?= t('nav.pokedex') ?></h3>
                    <p><?= t('inicio.ruta1_texto') ?></p>
                    <a class="route-link" href="vistas/index.php"><?= t('inicio.abrir_pokedex') ?> <span aria-hidden="true">→</span></a>
                </article>
                <article class="route-card">
                    <div class="route-top">
                        <span class="route-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        </span>
                        <span class="route-number">02</span>
                    </div>
                    <h3><?= t('inicio.ruta2_titulo') ?></h3>
                    <p><?= t('inicio.ruta2_texto') ?></p>
                    <a class="route-link" href="vistas/tipos.php"><?= t('inicio.ruta2_enlace') ?> <span aria-hidden="true">→</span></a>
                </article>
                <article class="route-card">
                    <div class="route-top">
                        <span class="route-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                        </span>
                        <span class="route-number">03</span>
                    </div>
                    <h3><?= t('inicio.ruta3_titulo') ?></h3>
                    <p><?= t('inicio.ruta3_texto') ?></p>
                    <a class="route-link" href="vistas/formulario.php"><?= t('inicio.crear_perfil') ?> <span aria-hidden="true">→</span></a>
                </article>
            </div>
        </section>

        <section class="cookies-panel panel" aria-labelledby="cookies-titulo">
            <div class="cookies-copy">
                <p class="eyebrow"><?= t('inicio.cookies_eyebrow') ?></p>
                <h2 id="cookies-titulo"><?= t('inicio.cookies_titulo') ?></h2>
                <p><?= t('inicio.cookies_texto') ?></p>
                <a class="btn btn-secondary" href="vistas/php/borrarCookie.php"><?= t('nav.borrar') ?></a>
            </div>
            <dl class="cookies-data">
                <div><dt><?= t('inicio.cookie_idioma') ?></dt><dd><?= IDIOMAS[$idioma] ?></dd></div>
                <div><dt><?= t('inicio.cookie_visitas') ?></dt><dd><?= $visitas ?></dd></div>
                <div><dt><?= t('inicio.cookie_desde') ?></dt><dd><?= fecha($visitas_desde) ?></dd></div>
                <div><dt><?= t('inicio.cookie_reinicio') ?></dt><dd><?= fecha($visitas_vencen) ?></dd></div>
            </dl>
        </section>
    </main>

    <?php pie(t('inicio.pie')); ?>
</body>
</html>
