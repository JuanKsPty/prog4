# Revisión de la evaluación 2 (Consultorio ABC)

_Auditoría del 2026-11-21 contra el enunciado de Programación IV. Las líneas citadas corresponden al código antes de las correcciones._


## 1. Resumen
El sitio cumple los 8 requisitos en lo esencial: HTML5 semántico, CSS por clases, cálculos en funciones PHP, login con `password_hash`, sesión y CSRF, menú de 3 opciones, footer con nombres. Hay **dos fallos que pueden costar puntos**: la frase del IMC no coincide con la del enunciado y el IMC mostrado (redondeado) puede contradecir la categoría (p. ej. "25.00 … Normal"). Además, glucosa en ayunas 125.5 sale "Diabetes", falta un README para XAMPP y la validación de los `*_post.php` está como código suelto, no en funciones.
**Nota estimada: 88–92 / 100** (≈ 96–98 con las correcciones críticas e importantes).

## 2. Requisitos (A)
| # | Requisito | Estado | Evidencia |
|---|---|---|---|
| 1 | HTML5 semántico | Cumple | `plantilla.php:60-96` (header, nav, main), `:37` footer; `panel.php:16,28,34` (header, section, article); `acceso.php:7-9` (main, aside) |
| 2 | CSS3 con clases/ID | Cumple | `css/styles.css` (1610 líneas, todo por clase). Inline solo en `funciones.php:483` (valor dinámico) y `db.php:77` (página de error) |
| 3 | Todo en funciones PHP | Parcial | Cálculos y diagnósticos en funciones (`funciones.php:86,90,154,223`); JS sin cálculos. Pero la validación es código suelto en `imc_post.php:10-18`, `glucosa_post.php:10-16`, `presion_post.php:10-19` |
| 4 | "Bienvenido <Nombre>" y luego menú | Cumple | `panel.php:17` → `Bienvenido <?= mostrar($usuario['nombre']) ?>` |
| 5 | Menú IMC / Glucosa en sangre / Presión arterial | Cumple | Tarjetas creativas con ilustración y última medición `panel.php:31-58` (títulos de `funciones.php:247-262`). La barra usa rótulos cortos "Glucosa"/"Presión" (`plantilla.php:54-55`) |
| 6 | Página propia con formulario y diagnóstico | Cumple | `vistas/imc.php`, `glucosa.php`, `presion.php` + `indicador.php:26-48` |
| 7 | Footer con integrantes | Cumple | `plantilla.php:39` "Hecho por Juan Castillo y Agustín Traverso" |
| 8 | Entrega local (XAMPP) | Parcial | Rutas relativas (`auth.php:26-39`), config con valores de XAMPP (`config.php:9-13`), BD se crea sola (`db.php:21-33`), `sql/consultorio_abc.sql`. Falta README; sobran `Dockerfile` y `.dockerignore` |

## 3. Hallazgos

**Crítico**
1. **Frase del IMC distinta a la exigida** — `indicador.php:7`. Muestra "Tu IMC es 24.98 y se considera **Normal**." El enunciado exige "Su IMC es ##.## y se categoriza como Normal". → Cambiar a `'Su IMC es <strong>' . valor . '</strong> y se categoriza como ' . $nombre . '.'`.
2. **IMC mostrado contradice la categoría** — `imc_post.php:25-29`. Se clasifica el valor sin redondear y se guarda/muestra redondeado a 2 decimales. Casos reales con datos válidos del formulario:
   - 57 kg, 1.51 m → IMC 24.9989 → muestra **25.00 … Normal**
   - 68.4 kg, 1.51 m → **30.00 … Sobrepeso**
   - 91.2 kg, 1.51 m → **40.00 … Obesidad**
   - 43.3 kg, 1.53 m → **18.50 … Bajo peso**
   - 96.1 kg, 1.55 m → `96.1/1.55²` da 39.99999… por coma flotante → **40.00 … Obesidad**
   → Redondear primero y clasificar el valor que se muestra: `$imc = round(calcular_imc($peso, $estatura), 2);` y `clasificar_imc($imc)`. Mejor aún, que `calcular_imc()` ya devuelva `round(..., 2)` (el enunciado pide 2 decimales).

**Importante**
3. **Glucosa en ayunas 125.5 → "Diabetes"** — `funciones.php:163` usa `<= 125`, así que de 125.01 a 125.99 sale Diabetes aunque la guía dice "≥ 126 diabetes". → Usar `if ($lectura < 126) return 'pre';`. (Posprandial ya usa `< 200` y está bien.)
4. **Texto de prediabetes en ayunas "101 a 125"** — `funciones.php:119`. La guía dice "100 a 125" y el código manda 100.5 a prediabetes, así que "101" tampoco describe bien el rango. → Usar el texto de la guía: `'normal' => '70 a 100', 'pre' => '100 a 125'` (igual que la tabla del enunciado; el código decide que el 100 exacto es "sin diabetes").
5. **Validación como código suelto (requisito 3)** — `*_post.php`. → Extraer `validar_imc($peso, $estatura)`, `validar_glucosa($lectura, $momento)` y `validar_presion($s, $d)` a `funciones.php` (devuelven el arreglo `$errores`); los `*_post.php` quedan como llamadas a funciones.
6. **Falta README con instrucciones de XAMPP** — raíz del proyecto. → Crear `README.md`: copiar la carpeta a `C:\xampp\htdocs\`, arrancar Apache y MySQL, abrir `http://localhost/evaluacion2/`, BD se crea sola (o importar `sql/consultorio_abc.sql`), credenciales en `vistas/php/config.php`, PHP ≥ 8.0 con `pdo_mysql`, integrantes.
7. **Dockerfile y .dockerignore sobran en la entrega** — no aportan nada en XAMPP y pueden confundir al profesor. → Excluirlos del .zip (no borrarlos del repo si se usan para el despliegue en Dokploy); listar en el README qué carpeta se entrega.

**Menor**
8. **Nombre "Crisis de hipertensión"** — `funciones.php:213`. La guía dice "Crisis hipertensiva". → Renombrar. El consejo ya empieza con "Consulta a tu médico de inmediato" (`:217`).
9. **Glucosa < 70 (ayunas y posprandial) → "Glucosa baja"** — `funciones.php:155`. La tabla no cubre ese caso; el sitio agrega una fila "Glucosa baja (hipoglucemia)" con consejo. Es una extensión razonable y la tabla de referencia la muestra; conviene mencionarlo en el README como decisión del grupo.
10. **Estilo inline del marcador** — `funciones.php:483` `style="left: X%"`. Es aceptable (valor calculado por fila; no es el mecanismo principal). Para dejar la regla en el CSS: `style="--posicion: X%"` y en `styles.css:475` `.banda-marcador { left: var(--posicion); }`. Sigue siendo un atributo `style`, pero solo pasa un dato; la presentación queda en la hoja.
11. **Inline en la página de error de BD** — `db.php:77`. → Enlazar `css/styles.css` y usar una clase (`.error-bd`), o dejarlo (solo aparece si MySQL está caído).
12. **Mensaje de "escribiste centímetros" inalcanzable desde el navegador** — `imc.php:22` tiene `max="2.5"`, así que el navegador bloquea 170 con su mensaje genérico antes de llegar a `imc_post.php:15`. → Aceptable; opcional quitar `max` del input para que el mensaje propio aparezca.
13. **Usuario borrado con sesión abierta** — `cuenta.php:8-10` lee `$registro['creado_en']` sin comprobar null (warning en PHP 8) y `guardar_medicion` fallaría por la FK. → En `requerir_sesion()`/`usuario_actual()` verificar que el usuario exista; si no, `cerrar_sesion()` y redirigir al login.
14. **Rótulos de la barra** — `plantilla.php:54-55` dicen "Glucosa" y "Presión". → Opcional: usar `title`/`aria-label` con el nombre completo, o "Glucosa en sangre"/"Presión arterial" si caben.
15. **Salida sin `mostrar()` de textos internos** — p. ej. `indicador.php:113,126,155`, `panel.php:38`. Son constantes del código, sin riesgo de XSS; solo por consistencia.

**Verificado sin problemas:** `password_hash`/`password_verify` (`funciones.php:327`, `login_post.php:12`); consultas preparadas en todo, columnas dinámicas en lista blanca (`funciones.php:341-351`); `htmlspecialchars` en `mostrar()` (`:24-26`) usado en todos los datos del usuario; todas las vistas y `*_post.php` llaman `requerir_sesion()`; logout por POST con CSRF (`salir.php`, `plantilla.php:87-90`); `session_regenerate_id` al iniciar (`auth.php:67`); campos vacíos/texto/negativos/cero rechazados por `leer_numero()` + rangos (`imc_post.php:11-18`), estatura < 0.5 evita división por cero; sistólica ≤ diastólica rechazada (`presion_post.php:17`); `js/clave.js` (mostrar contraseña) y `js/menu.js` (cerrar menú) sin ningún cálculo; credenciales: solo `root` sin clave por defecto de XAMPP, sin secretos en el repo; labels en todos los campos, `alt` en imágenes (vacío si son decorativas), enlace "Saltar al contenido", responsive con 5 `@media`. Contraste: no medido con herramienta.

## 4. Casos de prueba (B) — "actual" = ejecutado con PHP 8.3

**clasificar_imc**
| Entrada | Esperado | Actual |
|---|---|---|
| 18.4999 | Bajo peso | Bajo peso ✔ (pero se muestra "18.50", ver hallazgo 2) |
| 18.5 | Normal | Normal ✔ |
| 24.9 / 24.95 / 24.999 | Normal | Normal ✔ (24.999 se muestra "25.00" ✘) |
| 25 | Sobrepeso | Sobrepeso ✔ |
| 29.95 | Sobrepeso | Sobrepeso ✔ |
| 30 | Obesidad | Obesidad ✔ |
| 39.95 | Obesidad | Obesidad ✔ |
| 40 | Obesidad extrema | Obesidad extrema ✔ |
| 96.1 kg / 1.55 m (= 40.00) | Obesidad extrema | Obesidad ✘ |
| 72.2 kg / 1.70 m | 24.98, Normal | 24.98, Normal ✔ |

**clasificar_glucosa**
| Entrada | Esperado | Actual |
|---|---|---|
| 69.9 ayunas | (fuera de tabla) | Glucosa baja |
| 70 ayunas | Sin diabetes | Sin diabetes ✔ |
| 100 ayunas | Sin diabetes | Sin diabetes ✔ |
| 100.5 ayunas | Prediabetes | Prediabetes ✔ |
| 125 ayunas | Prediabetes | Prediabetes ✔ |
| 125.5 ayunas | Prediabetes | **Diabetes ✘** |
| 126 ayunas | Diabetes | Diabetes ✔ |
| 60 posprandial | Sin diabetes (tabla: < 140) | Glucosa baja (extensión) |
| 139.9 posprandial | Sin diabetes | Sin diabetes ✔ |
| 140 posprandial | Prediabetes | Prediabetes ✔ |
| 199.5 posprandial | Prediabetes | Prediabetes ✔ |
| 200 posprandial | Diabetes | Diabetes ✔ |

**clasificar_presion** (orden: crisis → nivel 2 → nivel 1 → elevada → normal, con O; correcto)
| Entrada | Esperado | Actual |
|---|---|---|
| 119/79 | Normal | Normal ✔ |
| 120/79, 129/79 | Elevada | Elevada ✔ |
| 120/80 | Nivel 1 | Nivel 1 ✔ |
| 130/70, 139/89, 110/85 | Nivel 1 | Nivel 1 ✔ |
| 140/70, 110/90 | Nivel 2 | Nivel 2 ✔ |
| 180/110 | Nivel 2 (180 no es > 180) | Nivel 2 ✔ |
| 150/120 | Nivel 2 (120 no es > 120) | Nivel 2 ✔ |
| 181/80, 150/121, 185/125 | Crisis | Crisis ✔ |

**Validación (servidor)**
| Entrada | Esperado | Actual |
|---|---|---|
| peso/estatura vacíos o texto ("abc") | Error | Error ✔ (`leer_numero` → null) |
| estatura 0 o negativa | Error, sin división por cero | Error ✔ (mín. 0.5) |
| estatura 170 | Aviso de cm | Aviso de cm ✔ (solo si el navegador no lo bloquea antes) |
| "1,70" con coma | 1.70 | 1.70 ✔ |
| glucosa sin momento | Error | Error ✔ |
| 80/120 (invertida) | Error | Error ✔ |

## 5. Estado de las correcciones (rama `revision-evaluacion2`)

| # | Hallazgo | Estado |
|---|---|---|
| 1 | Frase del IMC | Aplicado: "Su IMC es ##.## y se categoriza como X" |
| 2 | IMC mostrado vs. categoría | Aplicado: `calcular_imc()` redondea a 2 decimales antes de clasificar |
| 3 | Glucosa en ayunas 125.5 | Aplicado: prediabetes hasta `< 126` |
| 4 | Texto "101 a 125" | Aplicado: "100 a 125" |
| 5 | Validación en funciones | Aplicado: `validar_imc`, `validar_glucosa`, `validar_presion` |
| 6 | README para XAMPP | Aplicado: `README.md` |
| 7 | Dockerfile en la entrega | Documentado en el README (se excluye del .zip; se conserva para el despliegue) |
| 8 | "Crisis hipertensiva" | Aplicado |
| 9 | Glucosa < 70 | Documentado en el README como decisión del grupo |
| 10 | Estilo inline del marcador | Aplicado: `--posicion` + `left: var(--posicion)` en el CSS |
| 11 | Inline en la página de error de BD | Sin cambiar: cambiaría el aspecto de esa página |
| 12 | Aviso de centímetros | Aplicado: sin `max` en el campo de estatura |
| 13 | Usuario borrado con sesión abierta | Aplicado: `requerir_sesion()` cierra la sesión |
| 14 | Rótulos cortos de la barra | Sin cambiar: alteraría el diseño de la barra |
| 15 | `mostrar()` en textos internos | Aplicado |

`php -l`: 22 archivos .php sin errores de sintaxis (PHP 8.3.36 de XAMPP).
