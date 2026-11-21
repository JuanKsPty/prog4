// Muestra u oculta la contraseña del formulario de inicio de sesión
const botonVerClave = document.getElementById('ver-clave')
const campoClave = document.getElementById('clave')

botonVerClave.addEventListener('click', () => {
    const mostrar = campoClave.type === 'password'
    campoClave.type = mostrar ? 'text' : 'password'
    botonVerClave.classList.toggle('esta-visible', mostrar)
    botonVerClave.setAttribute('aria-pressed', String(mostrar))
    botonVerClave.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña')
})
