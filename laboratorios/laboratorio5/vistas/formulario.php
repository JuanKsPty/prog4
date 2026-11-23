<?php require __DIR__ . '/php/sitio.php'; ?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('entrenador.titulo') ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="trainer-page">
    <a class="skip-link" href="#contenido"><?= t('comun.saltar') ?></a>

    <?php menu('vistas/formulario.php'); ?>

    <header class="page-header">
        <p class="eyebrow"><?= t('entrenador.eyebrow') ?></p>
        <h1><?= t('entrenador.h1') ?></h1>
        <p class="lead"><?= t('entrenador.lead') ?></p>
    </header>

    <main id="contenido" class="trainer-layout">
        <section class="panel trainer-form">
            <p><?= t('entrenador.intro') ?></p>
            <form action="php/entrenador_post.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend><?= t('entrenador.datos') ?></legend>
                    <div class="field-grid">
                        <div class="field">
                            <label for="nombre"><?= t('entrenador.nombre') ?></label>
                            <input type="text" id="nombre" name="nombre" maxlength="15" autocomplete="given-name">
                        </div>
                        <div class="field">
                            <label for="apellido"><?= t('entrenador.apellido') ?></label>
                            <input type="text" id="apellido" name="apellido" maxlength="15" autocomplete="family-name">
                        </div>
                        <div class="field">
                            <label for="password"><?= t('entrenador.contrasena') ?></label>
                            <div class="password-field">
                                <input type="password" id="password" name="password" autocomplete="new-password">
                                <button type="button" class="toggle-password" aria-label="<?= t('entrenador.mostrar_contrasena') ?>" aria-pressed="false">
                                    <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.2"/><path d="M6.6 6.6C3.9 8.4 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label for="correo"><?= t('entrenador.correo') ?></label>
                            <input type="email" id="correo" name="correo" placeholder="<?= t('entrenador.correo_ejemplo') ?>" autocomplete="email">
                        </div>
                        <div class="field">
                            <label for="edad"><?= t('entrenador.edad') ?></label>
                            <input type="number" id="edad" name="edad" min="1" max="105" inputmode="numeric">
                        </div>
                        <div class="field">
                            <label for="fecha_nacimiento"><?= t('entrenador.nacimiento') ?></label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" autocomplete="bday">
                        </div>
                        <fieldset class="choice-fieldset field-full">
                            <legend><?= t('entrenador.genero') ?></legend>
                            <div class="choice-group">
                                <span class="choice">
                                    <input type="radio" id="masculino" name="genero" value="masculino">
                                    <label for="masculino"><?= t('entrenador.masculino') ?></label>
                                </span>
                                <span class="choice">
                                    <input type="radio" id="femenino" name="genero" value="femenino">
                                    <label for="femenino"><?= t('entrenador.femenino') ?></label>
                                </span>
                                <span class="choice">
                                    <input type="radio" id="otro" name="genero" value="otro">
                                    <label for="otro"><?= t('entrenador.otro') ?></label>
                                </span>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?= t('entrenador.intereses') ?></legend>
                    <div class="choice-group">
                        <span class="choice">
                            <input type="checkbox" id="musica" name="intereses[]" value="musica">
                            <label for="musica"><?= t('entrenador.musica') ?></label>
                        </span>
                        <span class="choice">
                            <input type="checkbox" id="deporte" name="intereses[]" value="deporte">
                            <label for="deporte"><?= t('entrenador.deporte') ?></label>
                        </span>
                        <span class="choice">
                            <input type="checkbox" id="cine" name="intereses[]" value="cine">
                            <label for="cine"><?= t('entrenador.cine') ?></label>
                        </span>
                        <span class="choice">
                            <input type="checkbox" id="cocina" name="intereses[]" value="cocina">
                            <label for="cocina"><?= t('entrenador.cocina') ?></label>
                        </span>
                        <span class="choice">
                            <input type="checkbox" id="fotografia" name="intereses[]" value="fotografia">
                            <label for="fotografia"><?= t('entrenador.fotografia') ?></label>
                        </span>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?= t('entrenador.mas') ?></legend>
                    <div class="field">
                        <label for="comentarios"><?= t('entrenador.comentarios') ?></label>
                        <textarea id="comentarios" name="comentarios" rows="7" cols="40" placeholder="<?= t('entrenador.comentarios_placeholder') ?>"></textarea>
                    </div>
                    <div class="field-grid">
                        <div class="field">
                            <label for="provincia"><?= t('entrenador.provincia') ?></label>
                            <select id="provincia" name="provincia">
                                <option value=""><?= t('entrenador.provincia_elegir') ?></option>
                                <option value="bocas-del-toro">Bocas del Toro</option>
                                <option value="cocle">Coclé</option>
                                <option value="colon">Colón</option>
                                <option value="chiriqui">Chiriquí</option>
                                <option value="darien">Darién</option>
                                <option value="herrera">Herrera</option>
                                <option value="los-santos">Los Santos</option>
                                <option value="panama">Panamá</option>
                                <option value="veraguas">Veraguas</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="archivo"><?= t('entrenador.archivo') ?></label>
                            <input type="file" id="archivo" name="archivo">
                        </div>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary"><?= t('entrenador.limpiar') ?></button>
                    <button type="submit" class="btn btn-primary"><?= t('entrenador.enviar') ?></button>
                </div>
            </form>
        </section>

        <aside class="trainer-aside" aria-label="<?= t('entrenador.vista_previa') ?>">
            <div class="trainer-card">
                <div class="trainer-card-top">
                    <span class="eyebrow"><?= t('entrenador.licencia') ?></span>
                    <img src="../img/pokemon-logo.png" alt="Pokémon">
                </div>
                <div class="trainer-card-body">
                    <span class="trainer-avatar" id="preview-initials" aria-hidden="true">?</span>
                    <div>
                        <p class="trainer-name" id="preview-name"><?= $textos['js']['tu_nombre'] ?></p>
                        <p class="trainer-region" id="preview-region"><?= $textos['js']['provincia_por_elegir'] ?></p>
                    </div>
                </div>
                <ul class="trainer-interests" id="preview-interests" aria-label="<?= t('entrenador.intereses') ?>">
                    <li class="is-empty"><?= $textos['js']['sin_intereses_aun'] ?></li>
                </ul>
            </div>
            <div class="adventure-note">
                <h2><?= t('entrenador.aventura_titulo') ?></h2>
                <p><?= t('entrenador.aventura_texto') ?></p>
            </div>
        </aside>
    </main>

    <?php pie(t('entrenador.pie')); ?>

    <?php textos_js(); ?>
    <script>
        const form = document.querySelector('.trainer-form form')
        const nombre = document.getElementById('nombre')
        const apellido = document.getElementById('apellido')
        const provincia = document.getElementById('provincia')
        const password = document.getElementById('password')
        const togglePassword = document.querySelector('.toggle-password')
        const previewName = document.getElementById('preview-name')
        const previewInitials = document.getElementById('preview-initials')
        const previewRegion = document.getElementById('preview-region')
        const previewInterests = document.getElementById('preview-interests')

        function updatePreview() {
            const first = nombre.value.trim()
            const last = apellido.value.trim()
            const initials = `${first.charAt(0)}${last.charAt(0)}`.toUpperCase()

            previewName.textContent = `${first} ${last}`.trim() || TEXTOS.tu_nombre
            previewInitials.textContent = initials || '?'
            previewRegion.textContent = provincia.value
                ? provincia.selectedOptions[0].textContent
                : TEXTOS.provincia_por_elegir

            const interests = [...form.querySelectorAll('input[name="intereses[]"]:checked')]
                .map(input => input.labels[0].textContent)

            previewInterests.innerHTML = ''
            if (!interests.length) {
                const li = document.createElement('li')
                li.className = 'is-empty'
                li.textContent = TEXTOS.sin_intereses_aun
                previewInterests.appendChild(li)
                return
            }
            interests.forEach(interest => {
                const li = document.createElement('li')
                li.textContent = interest
                previewInterests.appendChild(li)
            })
        }

        form.addEventListener('input', updatePreview)
        form.addEventListener('change', updatePreview)
        form.addEventListener('reset', () => setTimeout(updatePreview))

        togglePassword.addEventListener('click', () => {
            const isVisible = password.type === 'text'
            password.type = isVisible ? 'password' : 'text'
            togglePassword.setAttribute('aria-pressed', !isVisible)
        })
    </script>
</body>
</html>
