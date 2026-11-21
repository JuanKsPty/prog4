// Muestra u oculta las contraseñas de los formularios de acceso
document.querySelectorAll('.boton-ver-clave').forEach(boton => {
    const campo = document.getElementById(boton.dataset.campo)

    boton.addEventListener('click', () => {
        const mostrar = campo.type === 'password'
        campo.type = mostrar ? 'text' : 'password'
        boton.classList.toggle('esta-visible', mostrar)
        boton.setAttribute('aria-pressed', String(mostrar))
        boton.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña')
    })
})
