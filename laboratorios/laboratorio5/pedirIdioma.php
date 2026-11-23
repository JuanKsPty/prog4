<?php
require __DIR__ . '/vistas/php/config.php';

// Página a la que se regresa después de elegir el idioma
$volver = $_GET['volver'] ?? '';
$volver = pagina_valida($volver) && $volver !== 'pedirIdioma.php' ? $volver : 'index.php';

$opciones = [
    'es' => 'Ver el sitio en español',
    'en' => 'View the site in English',
    'fr' => 'Voir le site en français',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idioma · Language · Langue | Pokémon Lab</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="language-page">
    <main class="language-layout">
        <section class="panel language-card" aria-labelledby="idioma-titulo">
            <img class="language-logo" src="img/pokemon-logo.png" alt="Pokémon">
            <h1 id="idioma-titulo">
                <span lang="es">Elige tu idioma</span>
                <span lang="en">Choose your language</span>
                <span lang="fr">Choisissez votre langue</span>
            </h1>
            <ul class="language-options">
                <?php foreach ($opciones as $codigo => $texto): ?>
                    <li>
                        <a class="language-option" lang="<?= $codigo ?>" href="vistas/php/grabarCookie.php?idiomaUsuario=<?= $codigo ?>&amp;volver=<?= urlencode($volver) ?>">
                            <span class="language-code" aria-hidden="true"><?= strtoupper($codigo) ?></span>
                            <span>
                                <strong><?= IDIOMAS[$codigo] ?></strong>
                                <span class="language-detail"><?= $texto ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="language-note">
                <span lang="es">Tu elección se guarda en una cookie durante un mes.</span>
                <span lang="en">Your choice is saved in a cookie for one month.</span>
                <span lang="fr">Votre choix est enregistré dans un cookie pendant un mois.</span>
            </p>
        </section>
    </main>
</body>
</html>
