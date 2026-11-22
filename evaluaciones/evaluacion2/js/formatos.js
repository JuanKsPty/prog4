// Formato de campos mientras se escribe. Solo da forma al texto: los cálculos se hacen en PHP.

// Estatura: se escriben solo los dígitos y el punto se pone solo después del primero (170 → 1.70)
document.querySelectorAll('[data-formato="estatura"]').forEach(campo => {
    campo.addEventListener('input', () => {
        const digitos = campo.value.replace(/\D/g, '').slice(0, 3)
        campo.value = digitos.length > 1 ? digitos[0] + '.' + digitos.slice(1) : digitos
    })
})
