// Los nombres de los tipos llegan desde PHP en el idioma elegido (TEXTOS.tipos)
const TYPE_NAMES = TEXTOS.tipos

// Reemplaza %d o %s en un texto traducido
function texto(clave, valor) {
    return TEXTOS[clave].replace(/%[ds]/, valor)
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
