<?php require __DIR__ . '/php/sitio.php'; ?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('tipos.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <?php colores_sesion(); ?>
</head>
<body class="types-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/tipos.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('tipos.eyebrow') ?></p>
        <h1><?= t('tipos.h1') ?></h1>
        <p class="lead"><?= t('tipos.lead') ?></p>
    </header>

    <main id="contenido" class="types-layout">
        <section class="panel types-intro">
            <div class="types-intro-copy">
                <p><?= t('tipos.intro') ?></p>
            </div>
            <div>
                <p class="eyebrow"><?= t('tipos.disponibles') ?></p>
                <ul id="types-list"></ul>
            </div>
        </section>

        <section class="panel type-chart">
            <p class="panel-title"><?= t('tipos.tabla') ?></p>
            <div class="table-scroll">
                <table class="chart-table">
                    <thead>
                        <tr>
                            <th scope="col"><?= t('tipos.col_tipo') ?></th>
                            <th scope="col" class="col-strong"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg><?= t('tipos.col_fuerte') ?></th>
                            <th scope="col" class="col-weak"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg><?= t('tipos.col_debil') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><?= chip('fire') ?></th>
                            <td><div class="type-list"><?= chip('grass') ?><?= chip('ice') ?><?= chip('bug') ?></div></td>
                            <td><div class="type-list"><?= chip('water') ?><?= chip('rock') ?><?= chip('ground') ?></div></td>
                        </tr>
                        <tr>
                            <th scope="row"><?= chip('water') ?></th>
                            <td><div class="type-list"><?= chip('fire') ?><?= chip('rock') ?><?= chip('ground') ?></div></td>
                            <td><div class="type-list"><?= chip('grass') ?><?= chip('electric') ?></div></td>
                        </tr>
                        <tr>
                            <th scope="row"><?= chip('grass') ?></th>
                            <td><div class="type-list"><?= chip('water') ?><?= chip('rock') ?><?= chip('ground') ?></div></td>
                            <td><div class="type-list"><?= chip('fire') ?><?= chip('ice') ?><?= chip('flying') ?></div></td>
                        </tr>
                        <tr>
                            <th scope="row"><?= chip('electric') ?></th>
                            <td><div class="type-list"><?= chip('water') ?><?= chip('flying') ?></div></td>
                            <td><div class="type-list"><?= chip('ground') ?></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="types-aside">
            <p class="eyebrow"><?= t('tipos.destacados') ?></p>
            <span class="aside-sub"><?= t('tipos.destacados_sub') ?></span>
            <div id="type-examples"></div>
        </aside>
    </main>

    <?php pie(t('tipos.pie')); ?>

    <?php textos_js(); ?>
    <script src="../js/pokemon-types.js"></script>
    <script>
        const urlPokeApi = "https://pokeapi.co/api/v2/pokemon/"
        const typesListElement = document.getElementById('types-list')
        const typeExamplesContainer = document.getElementById('type-examples')

        fetch("https://pokeapi.co/api/v2/type?limit=18")
            .then(response => response.json())
            .then(data => {
                data.results.forEach(type => {
                    const li = document.createElement('li')
                    li.className = 'type-chip'
                    li.dataset.type = type.name
                    li.textContent = typeName(type.name)
                    typesListElement.appendChild(li)
                })
            })
            .catch(error => console.error('Error fetching types:', error))

        const examplePokemons = [
            { id: 4, type: 'fire' },
            { id: 7, type: 'water' },
            { id: 1, type: 'grass' },
        ]

        Promise.all(examplePokemons.map(p => fetch(urlPokeApi + p.id).then(r => r.json())))
            .then(pokemons => {
                pokemons.forEach((pokemon, index) => {
                    const { type } = examplePokemons[index]
                    const artwork = pokemon.sprites.other['official-artwork'].front_default || pokemon.sprites.front_default
                    const figure = document.createElement('figure')
                    figure.innerHTML = `
                        <div class="figure-art" data-type="${type}">
                            <img src="${artwork}" alt="${pokemon.name}">
                        </div>
                        <figcaption>
                            <strong>${pokemon.name}</strong>
                            ${typeChip(type)}
                        </figcaption>
                    `
                    typeExamplesContainer.appendChild(figure)
                })
            })
            .catch(error => console.error('Error fetching Pokémon data:', error))
    </script>
</body>
</html>
