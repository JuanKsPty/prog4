const TYPE_NAMES = {
    normal: 'Normal',
    fighting: 'Lucha',
    flying: 'Volador',
    poison: 'Veneno',
    ground: 'Tierra',
    rock: 'Roca',
    bug: 'Bicho',
    ghost: 'Fantasma',
    steel: 'Acero',
    fire: 'Fuego',
    water: 'Agua',
    grass: 'Planta',
    electric: 'Eléctrico',
    psychic: 'Psíquico',
    ice: 'Hielo',
    dragon: 'Dragón',
    dark: 'Siniestro',
    fairy: 'Hada'
}

function typeName(type) {
    return TYPE_NAMES[type] || type
}

function typeChip(type) {
    return `<span class="type-chip" data-type="${type}">${typeName(type)}</span>`
}

function formatDexNumber(id) {
    return `#${String(id).padStart(3, '0')}`
}
