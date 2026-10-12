const apiUrl = 'https://pokeapi.co/api/v2/pokemon/'
const tableBody = document.getElementById('pokemon-table-body')
const searchInput = document.getElementById('pokemon-search')
const status = document.getElementById('pokedex-status')

let catalog = []
let searchRequest = 0

function renderPokemons(pokemons) {
    tableBody.innerHTML = ''

    if (!pokemons.length) {
        tableBody.innerHTML = '<tr><td colspan="2">No encontramos ese Pokémon. Prueba con otro nombre.</td></tr>'
        status.textContent = 'Sin resultados'
        return
    }

    pokemons.forEach(pokemon => {
        const row = document.createElement('tr')
        const pokemonId = pokemon.id || pokemon.url.split('/')[6]
        row.innerHTML = `
            <td>${pokemon.name}</td>
            <td><img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemonId}.png" alt="${pokemon.name}"></td>
        `
        tableBody.appendChild(row)
    })

    status.textContent = searchInput.value
        ? `${pokemons.length} resultado${pokemons.length === 1 ? '' : 's'}`
        : 'Mostrando los primeros 20 Pokémon.'
}

fetch(`${apiUrl}?limit=20`)
    .then(response => {
        if (!response.ok) throw new Error('No se pudo cargar la Pokédex')
        return response.json()
    })
    .then(data => {
        catalog = data.results
        renderPokemons(catalog)
    })
    .catch(error => {
        status.textContent = 'No se pudo cargar la Pokédex.'
        console.error(error)
    })

searchInput.addEventListener('input', async event => {
    const query = event.target.value.trim().toLowerCase()
    const requestId = ++searchRequest

    if (!query) {
        renderPokemons(catalog)
        return
    }

    const matches = catalog.filter(pokemon => pokemon.name.includes(query))
    if (matches.length) {
        renderPokemons(matches)
        return
    }

    try {
        const response = await fetch(`${apiUrl}${encodeURIComponent(query)}`)
        if (!response.ok) {
            renderPokemons([])
            return
        }

        const pokemon = await response.json()
        if (requestId === searchRequest) renderPokemons([pokemon])
    } catch (error) {
        if (requestId === searchRequest) {
            status.textContent = 'No se pudo completar la búsqueda.'
            console.error(error)
        }
    }
})
