<?php require __DIR__ . '/php/sitio.php'; ?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('multimedia.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="multimedia-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/multimedia.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('multimedia.eyebrow') ?></p>
        <h1><?= t('multimedia.h1') ?></h1>
        <p class="lead"><?= t('multimedia.lead') ?></p>
    </header>

    <main id="contenido" class="multimedia-layout">
        <section class="sound-panel">
            <p class="panel-title"><?= t('multimedia.panel') ?></p>
            <ol class="steps">
                <li><?= t('multimedia.paso1') ?></li>
                <li><?= t('multimedia.paso2') ?></li>
            </ol>
            <div id="cry-buttons" class="cry-grid">
                <span class="cry-skeleton" aria-hidden="true"></span>
                <span class="cry-skeleton" aria-hidden="true"></span>
                <span class="cry-skeleton" aria-hidden="true"></span>
                <span class="cry-skeleton" aria-hidden="true"></span>
            </div>
            <audio id="cry-player" controls></audio>
        </section>

        <section class="panel video-panel">
            <p class="panel-title"><?= t('multimedia.video') ?></p>
            <div class="video-frame">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/Rsv2VkoIpk0?si=gmdnVcygYiylrBPT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </section>
    </main>

    <?php pie(t('multimedia.pie')); ?>

    <?php textos_js(); ?>
    <script src="../js/pokemon-types.js"></script>
    <script>
        const urlPokeApi = "https://pokeapi.co/api/v2/pokemon/"
        const cryButtonsContainer = document.getElementById('cry-buttons')
        const cryPlayer = document.getElementById('cry-player')
        const cryPokemons = [1, 4, 7, 25]
        let activeButton = null

        function setActiveButton(button) {
            cryButtonsContainer.querySelectorAll('.cry-button').forEach(item => {
                item.setAttribute('aria-pressed', item === button)
                item.classList.remove('is-playing')
            })
            activeButton = button
        }

        Promise.all(cryPokemons.map(id => fetch(urlPokeApi + id).then(r => r.json())))
            .then(pokemons => {
                cryButtonsContainer.innerHTML = ''
                pokemons.forEach(pokemon => {
                    const button = document.createElement('button')
                    button.type = 'button'
                    button.className = 'cry-button'
                    button.setAttribute('aria-pressed', 'false')
                    button.innerHTML = `
                        <img class="cry-sprite" src="${pokemon.sprites.front_default}" alt="" width="64" height="64">
                        <span class="cry-text">
                            <span class="cry-name">${pokemon.name}</span>
                            <span class="cry-meta">${formatDexNumber(pokemon.id)} · ${typeName(pokemon.types[0].type.name)}</span>
                        </span>
                        <span class="cry-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M7 4v16l13-8z" fill="currentColor"/></svg>
                            <span class="cry-eq"><i></i><i></i><i></i></span>
                        </span>
                    `
                    button.addEventListener('click', () => {
                        setActiveButton(button)
                        cryPlayer.src = pokemon.cries.latest
                        cryPlayer.play()
                    })
                    cryButtonsContainer.appendChild(button)
                })
            })
            .catch(error => {
                cryButtonsContainer.innerHTML = `<p>${TEXTOS.gritos_error}</p>`
                console.error('Error fetching Pokémon data:', error)
            })

        cryPlayer.addEventListener('play', () => activeButton?.classList.add('is-playing'))
        cryPlayer.addEventListener('pause', () => activeButton?.classList.remove('is-playing'))
        cryPlayer.addEventListener('ended', () => activeButton?.classList.remove('is-playing'))
    </script>
</body>
</html>
