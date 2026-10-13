# Programación IV

Laboratorios de Desarrollo Web de Juan David Castillo, grupo 1SG131, Universidad Tecnológica de Panamá.

**Ver los laboratorios en línea:** https://juankspty.github.io/prog4/

La página principal lista todos los laboratorios, del más reciente al más antiguo. Cada uno se abre tal como fue entregado, con sus propias páginas, estilos y scripts.

## Laboratorios

| N.° | Laboratorio | Tema | Abrir |
| --- | --- | --- | --- |
| 1 | Pokédex Web | Estructura y etiquetas semánticas de HTML5 | [laboratorio1](https://juankspty.github.io/prog4/laboratorios/laboratorio1/) |
| 2 | Formulario personal | Formularios y tipos de entrada de HTML5 | [laboratorio2](https://juankspty.github.io/prog4/laboratorios/laboratorio2/) |
| 3 | Pokémon Lab | Hojas de estilo y JavaScript con PokeAPI | [laboratorio3](https://juankspty.github.io/prog4/laboratorios/laboratorio3/) |

Otras prácticas: [Hola mundo](https://juankspty.github.io/prog4/hello_world/hello_world.html).

## Estructura

```
index.html              Página principal con la lista de laboratorios
laboratorios/
  laboratorio1/         Cada laboratorio es independiente:
  laboratorio2/         solo enlaza a sus propios archivos
  laboratorio3/
hello_world/            Prácticas sueltas
```

## Agregar un laboratorio nuevo

1. Crea la carpeta `laboratorios/laboratorioN/` con su propio `index.html`. Todo lo que use el laboratorio (CSS, JS, imágenes) va dentro de esa carpeta y se enlaza con rutas relativas.
2. En `index.html` de la raíz, añade un bloque a la lista `laboratorios`:

   ```js
   {
       numero: 4,
       titulo: 'Título del laboratorio',
       carpeta: 'laboratorios/laboratorio4'
   }
   ```

   Si la página de entrada no se llama `index.html`, agrega `inicio: 'otra-pagina.html'`.
3. Agrega una fila a la tabla de este README, haz commit y push. GitHub Pages publica los cambios en uno o dos minutos.

## Ver en local

Abre `index.html` en el navegador. No hace falta instalar nada.

## Documentos

Las guías y los informes (`.pdf`, `.docx` y similares) se guardan solo de forma local y no se suben al repositorio; el `.gitignore` los excluye.
