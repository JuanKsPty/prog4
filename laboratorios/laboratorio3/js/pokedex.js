const apiUrl = 'https://pokeapi.co/api/v2/pokemon/'
const tableBody = document.getElementById('pokemon-table-body')
const searchInput = document.getElementById('pokemon-search')
const status = document.getElementById('pokedex-status')
const detail = document.getElementById('pokemon-detail')

const STAT_NAMES = {
    hp: 'PS',
    attack: 'Ataque',
    defense: 'Defensa',
    'special-attack': 'At. Esp.',
    'special-defense': 'Def. Esp.',
    speed: 'Velocidad'
}

const DEFAULT_STATUS = 'Mostrando los primeros 20 Pokémon.'
const speakerIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 5 6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/><path d="M19 5a10 10 0 0 1 0 14"/></svg>'

let catalog = []
let selectedId = null
let searchRequest = 0
let searchTimer = null
const cryAudio = new Audio()

const formatMeasure = value => value.toLocaleString('es', { minimumFractionDigits: 1, maximumFractionDigits: 1 })
const spriteFor = pokemon => pokemon.sprites.front_default
    || `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemon.id}.png`

async function fetchPokemon(nameOrId) {
    const response = await fetch(`${apiUrl}${encodeURIComponent(nameOrId)}`)
    if (!response.ok) throw new Error(`Pokémon no encontrado: ${nameOrId}`)
    return response.json()
}

function renderSkeleton(rows = 6) {
    tableBody.innerHTML = Array.from({ length: rows }, () => `
        <tr class="skeleton-row" aria-hidden="true">
            <td><span class="skeleton" style="width: 40px"></span></td>
            <td><span class="skeleton skeleton-sprite"></span></td>
            <td><span class="skeleton" style="width: 110px"></span></td>
            <td><span class="skeleton" style="width: 70px; height: 22px"></span></td>
        </tr>`).join('')
}

function renderMessage(title, text, withRetry = false) {
    tableBody.innerHTML = `
        <tr class="table-message">
            <td colspan="4">
                <strong>${title}</strong>
                <span>${text}</span>
                ${withRetry ? '<br><button type="button" class="btn btn-secondary" data-retry>Reintentar</button>' : ''}
            </td>
        </tr>`
    tableBody.querySelector('[data-retry]')?.addEventListener('click', loadCatalog)
}

function renderPokemons(pokemons) {
    tableBody.innerHTML = ''

    if (!pokemons.length) {
        renderMessage('No encontramos ese Pokémon.', 'Prueba con otro nombre o número.')
        status.textContent = 'Sin resultados'
        return
    }

    pokemons.forEach(pokemon => {
        const isSelected = pokemon.id === selectedId
        const row = document.createElement('tr')
        row.dataset.id = pokemon.id
        row.classList.toggle('is-selected', isSelected)
        row.innerHTML = `
            <td><span class="dex-number">${formatDexNumber(pokemon.id)}</span></td>
            <td><img src="${spriteFor(pokemon)}" alt="${pokemon.name}" width="72" height="72" loading="lazy"></td>
            <td><button type="button" class="row-select" aria-pressed="${isSelected}">${pokemon.name}</button></td>
            <td><div class="type-list">${pokemon.types.map(({ type }) => typeChip(type.name)).join('')}</div></td>
        `
        row.addEventListener('click', () => selectPokemon(pokemon))
        tableBody.appendChild(row)
    })

    status.textContent = searchInput.value.trim()
        ? `${pokemons.length} resultado${pokemons.length === 1 ? '' : 's'}`
        : DEFAULT_STATUS
}

function renderDetail(pokemon) {
    const mainType = pokemon.types[0].type.name
    const artwork = pokemon.sprites.other?.['official-artwork']?.front_default || spriteFor(pokemon)
    const stats = pokemon.stats.map(({ base_stat, stat }) => `
        <li>
            <span class="stat-name">${STAT_NAMES[stat.name] || stat.name}</span>
            <span class="stat-bar"><span style="--value: ${Math.min(100, Math.round(base_stat / 180 * 100))}%"></span></span>
            <span class="stat-value">${base_stat}</span>
        </li>`).join('')

    detail.dataset.type = mainType
    detail.innerHTML = `
        <div class="detail-hero">
            <figure class="detail-art"><img src="${artwork}" alt="Arte oficial de ${pokemon.name}"></figure>
            <div class="detail-info">
                <span class="detail-number">${formatDexNumber(pokemon.id)}</span>
                <h2>${pokemon.name}</h2>
                <div class="type-list">${pokemon.types.map(({ type }) => typeChip(type.name)).join('')}</div>
                <dl class="detail-measures">
                    <div><dt>Altura</dt><dd>${formatMeasure(pokemon.height / 10)} m</dd></div>
                    <div><dt>Peso</dt><dd>${formatMeasure(pokemon.weight / 10)} kg</dd></div>
                </dl>
            </div>
        </div>
        <ul class="stat-list" aria-label="Estadísticas base">${stats}</ul>
        <button type="button" class="btn btn-dark detail-cry">${speakerIcon} Escuchar grito</button>
    `

    const cryButton = detail.querySelector('.detail-cry')
    const cryUrl = pokemon.cries?.latest || pokemon.cries?.legacy
    if (!cryUrl) {
        cryButton.disabled = true
        return
    }
    cryButton.addEventListener('click', () => {
        cryAudio.src = cryUrl
        cryAudio.volume = 0.6
        cryButton.classList.add('is-playing')
        cryAudio.onended = () => cryButton.classList.remove('is-playing')
        cryAudio.play().catch(() => cryButton.classList.remove('is-playing'))
    })
}

function selectPokemon(pokemon, { scroll = true } = {}) {
    selectedId = pokemon.id

    tableBody.querySelectorAll('tr[data-id]').forEach(row => {
        const isSelected = Number(row.dataset.id) === pokemon.id
        row.classList.toggle('is-selected', isSelected)
        row.querySelector('.row-select').setAttribute('aria-pressed', isSelected)
    })

    cryAudio.pause()
    renderDetail(pokemon)

    if (scroll && window.matchMedia('(max-width: 960px)').matches) {
        detail.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
}

async function loadCatalog() {
    renderSkeleton()
    status.textContent = 'Cargando Pokédex…'

    try {
        const response = await fetch(`${apiUrl}?limit=20`)
        if (!response.ok) throw new Error('No se pudo cargar la Pokédex')
        const data = await response.json()
        catalog = await Promise.all(data.results.map(pokemon => fetchPokemon(pokemon.name)))
        renderPokemons(catalog)
        if (!selectedId) selectPokemon(catalog[0], { scroll: false })
    } catch (error) {
        renderMessage('No se pudo cargar la Pokédex.', 'Revisa tu conexión e inténtalo de nuevo.', true)
        status.textContent = 'No se pudo cargar la Pokédex.'
        console.error(error)
    }
}

async function searchRemote(query, requestId) {
    status.textContent = 'Buscando en la Pokédex nacional…'

    try {
        const pokemon = await fetchPokemon(query)
        if (requestId !== searchRequest) return
        renderPokemons([pokemon])
        selectPokemon(pokemon, { scroll: false })
    } catch (error) {
        if (requestId !== searchRequest) return
        renderPokemons([])
    }
}

searchInput.addEventListener('input', event => {
    const query = event.target.value.trim().toLowerCase().replace(/^#/, '')
    const requestId = ++searchRequest
    clearTimeout(searchTimer)

    if (!query) {
        renderPokemons(catalog)
        return
    }

    const number = /^\d+$/.test(query) ? Number(query) : null
    const matches = catalog.filter(pokemon => number ? pokemon.id === number : pokemon.name.includes(query))
    if (matches.length) {
        renderPokemons(matches)
        return
    }

    searchTimer = setTimeout(() => searchRemote(number ?? query, requestId), 300)
})

loadCatalog()
