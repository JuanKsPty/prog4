// Cierra el menú de usuario al hacer clic fuera de él o al presionar Escape
const menuUsuario = document.querySelector('.menu-usuario')

if (menuUsuario) {
    document.addEventListener('click', evento => {
        if (menuUsuario.open && !menuUsuario.contains(evento.target)) {
            menuUsuario.open = false
        }
    })

    document.addEventListener('keydown', evento => {
        if (evento.key === 'Escape' && menuUsuario.open) {
            menuUsuario.open = false
            menuUsuario.querySelector('summary').focus()
        }
    })
}
