# Consultorio ABC: portal de pacientes

Evaluación 2 de Programación IV (grupo 1SG131, Universidad Tecnológica de Panamá).
Integrantes: Juan Castillo y Agustín Traverso.

El paciente crea una cuenta, inicia sesión y calcula su IMC, su glucosa en sangre y su presión arterial. Cada medición se guarda con su diagnóstico en MySQL.

## Cómo abrirlo en XAMPP

1. Copia esta carpeta (`evaluacion2`) dentro de `C:\xampp\htdocs\`.
2. Abre el panel de XAMPP e inicia **Apache** y **MySQL**.
3. Entra a <http://localhost/evaluacion2/>.
4. Pulsa **Crear cuenta**, regístrate y empieza a medir.

La base de datos `consultorio_abc` y sus tablas se crean solas la primera vez que se abre el sitio. Si prefieres crearlas a mano, importa `sql/consultorio_abc.sql` desde phpMyAdmin.

### Requisitos

- PHP 8.0 o superior con la extensión `pdo_mysql` (XAMPP ya la trae activada).
- MySQL o MariaDB.

### Conexión a la base de datos

Por defecto se usa la configuración de XAMPP: servidor `localhost`, puerto `3306`, usuario `root` sin contraseña. Si tu MySQL usa otros datos, cámbialos en `vistas/php/config.php`.

## Estructura

| Archivo | Qué hace |
|---|---|
| `index.php`, `login.php`, `registro.php` | Páginas públicas |
| `vistas/panel.php` | "Bienvenido <nombre>" y el menú de los tres indicadores |
| `vistas/imc.php`, `glucosa.php`, `presion.php` | Formulario y diagnóstico de cada indicador |
| `vistas/php/funciones.php` | Funciones de cálculo, diagnóstico y validación |
| `vistas/php/*_post.php` | Reciben cada formulario y guardan la medición |
| `vistas/php/auth.php`, `db.php`, `config.php` | Sesión, protección CSRF y conexión a MySQL |
| `vistas/php/plantilla.php` | Encabezado, menú y pie de página con los integrantes |
| `css/styles.css`, `js/`, `img/` | Estilos, scripts de interfaz e ilustraciones |
| `sql/consultorio_abc.sql` | Esquema de la base de datos |

Todos los cálculos se hacen en PHP. Los archivos de `js/` solo sirven para la interfaz (mostrar la contraseña y cerrar el menú de usuario).

## Decisiones sobre las tablas de referencia

- **Glucosa menor de 70 mg/dl:** la tabla del enunciado no cubre ese caso. El sitio lo muestra como "Glucosa baja" (hipoglucemia) y aconseja consultar al médico si se repite.
- **Glucosa en ayunas de exactamente 100 mg/dl:** se clasifica como "Sin diabetes". De 100.1 a 125.9 es prediabetes y desde 126 es diabetes.
- **Presión arterial:** se aplica la categoría más grave que cumpla la sistólica o la diastólica, empezando por la crisis hipertensiva.

## Entrega

En el .zip para el profesor no hace falta incluir `Dockerfile` ni `.dockerignore`: solo sirven para publicar el sitio en el servidor y XAMPP no los usa.
