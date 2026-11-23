<?php require __DIR__ . '/php/sitio.php'; ?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('pokedex.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="pokedex-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/index.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('pokedex.eyebrow') ?></p>
        <h1><?= t('pokedex.h1') ?></h1>
        <p class="lead"><?= t('pokedex.lead') ?></p>
    </header>

    <main id="contenido" class="pokedex-layout">
        <section class="panel">
            <div class="pokedex-toolbar">
                <div>
                    <p class="toolbar-title"><?= t('pokedex.toolbar') ?></p>
                    <span id="pokedex-status" role="status"><?= $textos['js']['estado_inicial'] ?></span>
                </div>
                <label class="search-field" for="pokemon-search"><?= t('pokedex.buscar') ?>
                    <span class="search-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input type="search" id="pokemon-search" placeholder="<?= t('pokedex.placeholder') ?>" autocomplete="off" spellcheck="false">
                    </span>
                </label>
            </div>
            <div class="table-scroll">
                <table class="dex-table">
                    <caption class="sr-only"><?= t('pokedex.caption') ?></caption>
                    <thead>
                        <tr>
                            <th scope="col"><?= t('pokedex.col_numero') ?></th>
                            <th scope="col"><?= t('pokedex.col_imagen') ?></th>
                            <th scope="col"><?= t('pokedex.col_nombre') ?></th>
                            <th scope="col"><?= t('pokedex.col_tipo') ?></th>
                        </tr>
                    </thead>
                    <tbody id="pokemon-table-body">
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="dex-aside" aria-label="<?= t('pokedex.ficha_aria') ?>">
            <div class="dex-device">
                <div class="device-top" aria-hidden="true">
                    <span class="device-lens"></span>
                    <span class="device-led yellow"></span>
                    <span class="device-led green"></span>
                    <span class="device-label"><?= t('pokedex.ficha') ?></span>
                </div>
                <div class="device-screen" id="pokemon-detail">
                    <div class="detail-placeholder" aria-hidden="true">
                        <span class="skeleton"></span>
                        <span class="skeleton" style="width: 60%; height: 28px"></span>
                        <span class="skeleton" style="width: 40%"></span>
                        <span class="skeleton"></span>
                        <span class="skeleton"></span>
                    </div>
                </div>
            </div>
            <p class="fun-fact"><small><?= t('pokedex.dato') ?></small></p>
        </aside>
    </main>

    <?php pie(t('pokedex.pie')); ?>

    <?php textos_js(); ?>
    <script src="../js/pokemon-types.js"></script>
    <script src="../js/pokedex.js"></script>
</body>
</html>
