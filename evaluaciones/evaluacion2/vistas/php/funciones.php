<?php
// Funciones del Consultorio ABC: lectura de datos, cálculos, diagnósticos, mediciones y presentación.

require_once __DIR__ . '/auth.php';

const CARPETA_IMAGENES = __DIR__ . '/../../img/';

/* --------------------------------------------------------------------------
   Lectura y salida de datos
   -------------------------------------------------------------------------- */

function leer($campo) {
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

// Lee un número del formulario; acepta coma o punto decimal. Devuelve null si no es un número
function leer_numero($campo) {
    $texto = str_replace(',', '.', leer($campo));
    return is_numeric($texto) ? (float) $texto : null;
}

// htmlspecialchars evita que lo escrito en el formulario se ejecute como HTML
function mostrar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function formato_numero($numero, $decimales = 2) {
    return number_format($numero, $decimales, '.', ',');
}

function iniciales($nombre) {
    $partes = preg_split('/\s+/', trim($nombre));
    $letras = mb_substr($partes[0], 0, 1);
    if (count($partes) > 1) {
        $letras .= mb_substr(end($partes), 0, 1);
    }
    return mb_strtoupper($letras);
}

/* --------------------------------------------------------------------------
   Índice de masa corporal
   -------------------------------------------------------------------------- */

function categorias_imc() {
    return [
        'bajo' => [
            'nombre' => 'Bajo peso',
            'rango' => 'Menos de 18.5',
            'tono' => 'tono-celeste',
            'imagen' => 'imc-bajo',
            'consejo' => 'Tu peso está por debajo de lo recomendado. Consulta con un nutricionista para planificar una alimentación que te ayude a ganar peso de forma saludable.',
        ],
        'normal' => [
            'nombre' => 'Normal',
            'rango' => '18.5 a 24.9',
            'tono' => 'tono-verde',
            'imagen' => 'imc-normal',
            'consejo' => '¡Muy bien! Tu peso es adecuado para tu estatura. Mantén una dieta equilibrada y actividad física regular.',
        ],
        'sobrepeso' => [
            'nombre' => 'Sobrepeso',
            'rango' => '25 a 29.9',
            'tono' => 'tono-amarillo',
            'imagen' => 'imc-sobrepeso',
            'consejo' => 'Tienes algo de peso extra. Aumentar la actividad física y reducir azúcares y harinas refinadas puede ayudarte.',
        ],
        'obesidad' => [
            'nombre' => 'Obesidad',
            'rango' => '30 a 39.9',
            'tono' => 'tono-naranja',
            'imagen' => 'imc-obesidad',
            'consejo' => 'Tu IMC indica obesidad, lo que aumenta el riesgo de diabetes e hipertensión. Te recomendamos una cita con tu médico.',
        ],
        'extrema' => [
            'nombre' => 'Obesidad extrema',
            'rango' => '40 o más',
            'tono' => 'tono-rojo',
            'imagen' => 'imc-extrema',
            'consejo' => 'Tu IMC está en un nivel de riesgo alto para tu salud. Agenda una consulta médica lo antes posible.',
        ],
    ];
}

// IMC = peso (kg) / estatura² (m), con 2 decimales: se clasifica el mismo valor que se muestra
function calcular_imc($peso, $estatura) {
    return round($peso / ($estatura * $estatura), 2);
}

// Devuelve los errores del formulario de IMC (vacío si los datos son válidos)
function validar_imc($peso, $estatura) {
    $errores = [];
    if ($peso === null || $peso < 1 || $peso > 500) {
        $errores['peso'] = 'Escribe tu peso en kilogramos, entre 1 y 500.';
    }
    if ($estatura === null || $estatura < 0.5 || $estatura > 2.5) {
        $errores['estatura'] = $estatura !== null && $estatura >= 50 && $estatura <= 250
            ? 'Parece que escribiste centímetros. Escríbela en metros, por ejemplo ' . formato_numero($estatura / 100) . '.'
            : 'Escribe tu estatura en metros, entre 0.50 y 2.50.';
    }
    return $errores;
}

function clasificar_imc($imc) {
    if ($imc < 18.5) {
        return 'bajo';
    }
    if ($imc < 25) {
        return 'normal';
    }
    if ($imc < 30) {
        return 'sobrepeso';
    }
    if ($imc < 40) {
        return 'obesidad';
    }
    return 'extrema';
}

/* --------------------------------------------------------------------------
   Glucosa en sangre
   -------------------------------------------------------------------------- */

function momentos_glucosa() {
    return [
        'ayunas' => 'En ayunas',
        'posprandial' => 'Posprandial (2 horas después de comer)',
    ];
}

function categorias_glucosa($momento) {
    $rangos = $momento === 'ayunas'
        ? ['bajo' => 'Menos de 70', 'normal' => '70 a 100', 'pre' => '100 a 125', 'diabetes' => '126 o más']
        : ['bajo' => 'Menos de 70', 'normal' => 'Menos de 140', 'pre' => '140 a 199', 'diabetes' => '200 o más'];

    return [
        'bajo' => [
            'nombre' => 'Glucosa baja',
            'rango' => $rangos['bajo'],
            'tono' => 'tono-celeste',
            'imagen' => 'glucosa-baja',
            'consejo' => 'Tu glucosa está por debajo de lo normal (hipoglucemia). Come algo con azúcar, como un jugo, y vuelve a medirte en 15 minutos. Si se repite, consulta a tu médico.',
        ],
        'normal' => [
            'nombre' => 'Sin diabetes',
            'rango' => $rangos['normal'],
            'tono' => 'tono-teal',
            'imagen' => 'glucosa-normal',
            'consejo' => 'Tu nivel de glucosa es normal. Sigue con tus hábitos saludables y haz controles periódicos.',
        ],
        'pre' => [
            'nombre' => 'Prediabetes',
            'rango' => $rangos['pre'],
            'tono' => 'tono-ambar',
            'imagen' => 'glucosa-prediabetes',
            'consejo' => 'Tu glucosa está elevada. Con cambios en la alimentación y más actividad física se puede evitar la diabetes. Coméntalo con tu médico.',
        ],
        'diabetes' => [
            'nombre' => 'Diabetes',
            'rango' => $rangos['diabetes'],
            'tono' => 'tono-vino',
            'imagen' => 'glucosa-diabetes',
            'consejo' => 'Tu lectura está en el rango de diabetes. Agenda una cita para confirmar el diagnóstico con exámenes de laboratorio.',
        ],
    ];
}

function clasificar_glucosa($lectura, $momento) {
    if ($lectura < 70) {
        return 'bajo';
    }

    if ($momento === 'ayunas') {
        if ($lectura <= 100) {
            return 'normal';
        }
        if ($lectura < 126) {
            return 'pre';
        }
        return 'diabetes';
    }

    if ($lectura < 140) {
        return 'normal';
    }
    if ($lectura < 200) {
        return 'pre';
    }
    return 'diabetes';
}

function validar_glucosa($lectura, $momento) {
    $errores = [];
    if ($lectura === null || $lectura < 10 || $lectura > 1000) {
        $errores['lectura'] = 'Escribe la lectura del glucómetro en mg/dl, entre 10 y 1000.';
    }
    if (!isset(momentos_glucosa()[$momento])) {
        $errores['momento'] = 'Indica si tomaste la lectura en ayunas o después de comer.';
    }
    return $errores;
}

/* --------------------------------------------------------------------------
   Presión arterial
   -------------------------------------------------------------------------- */

function categorias_presion() {
    return [
        'normal' => [
            'nombre' => 'Normal',
            'rango' => 'Menos de 120 / 80',
            'tono' => 'tono-lima',
            'imagen' => 'presion-normal',
            'consejo' => 'Tu presión arterial es normal. Sigue con una dieta baja en sal y actividad física regular.',
        ],
        'elevada' => [
            'nombre' => 'Elevada',
            'rango' => '120-129 / menos de 80',
            'tono' => 'tono-limon',
            'imagen' => 'presion-elevada',
            'consejo' => 'Tu presión está un poco elevada. Reduce la sal, el alcohol y el estrés, y vuelve a medirla en unos días.',
        ],
        'nivel1' => [
            'nombre' => 'Hipertensión nivel 1',
            'rango' => '130-139 / 80-89',
            'tono' => 'tono-ocre',
            'imagen' => 'presion-nivel1',
            'consejo' => 'Tienes presión arterial alta (nivel 1). Consulta con tu médico; puede recomendarte cambios de hábitos o tratamiento.',
        ],
        'nivel2' => [
            'nombre' => 'Hipertensión nivel 2',
            'rango' => '140 o más / 90 o más',
            'tono' => 'tono-ladrillo',
            'imagen' => 'presion-nivel2',
            'consejo' => 'Tienes presión arterial alta (nivel 2). Es importante que te vea un médico pronto para iniciar tratamiento.',
        ],
        'crisis' => [
            'nombre' => 'Crisis de hipertensión',
            'rango' => 'Más de 180 / más de 120',
            'tono' => 'tono-granate',
            'imagen' => 'presion-crisis',
            'consejo' => 'Consulta a tu médico de inmediato. Si tienes dolor de pecho, falta de aire o visión borrosa, acude a urgencias.',
        ],
    ];
}

// Se revisa de la categoría más grave a la más leve, como indica la tabla de referencia
function clasificar_presion($sistolica, $diastolica) {
    if ($sistolica > 180 || $diastolica > 120) {
        return 'crisis';
    }
    if ($sistolica >= 140 || $diastolica >= 90) {
        return 'nivel2';
    }
    if ($sistolica >= 130 || $diastolica >= 80) {
        return 'nivel1';
    }
    if ($sistolica >= 120) {
        return 'elevada';
    }
    return 'normal';
}

function validar_presion($sistolica, $diastolica) {
    $errores = [];
    if ($sistolica === null || $sistolica < 50 || $sistolica > 300) {
        $errores['sistolica'] = 'Escribe la sistólica (el número de arriba), entre 50 y 300.';
    }
    if ($diastolica === null || $diastolica < 30 || $diastolica > 200) {
        $errores['diastolica'] = 'Escribe la diastólica (el número de abajo), entre 30 y 200.';
    }
    if (!$errores && $sistolica <= $diastolica) {
        $errores['sistolica'] = 'La sistólica debe ser mayor que la diastólica. Revisa que no estén invertidas.';
    }
    return $errores;
}


/* --------------------------------------------------------------------------
   Indicadores y bandas de color
   -------------------------------------------------------------------------- */

function indicadores() {
    return [
        'imc' => [
            'nombre' => 'IMC',
            'titulo' => 'Índice de masa corporal',
            'pagina' => 'vistas/imc.php',
            'imagen' => 'menu-imc',
            'datos' => 'Peso y estatura',
        ],
        'glucosa' => [
            'nombre' => 'Glucosa',
            'titulo' => 'Glucosa en sangre',
            'pagina' => 'vistas/glucosa.php',
            'imagen' => 'menu-glucosa',
            'datos' => 'Lectura del glucómetro, en ayunas o después de comer',
        ],
        'presion' => [
            'nombre' => 'Presión',
            'titulo' => 'Presión arterial',
            'pagina' => 'vistas/presion.php',
            'imagen' => 'menu-presion',
            'datos' => 'Presión sistólica y diastólica',
        ],
    ];
}

// Valores mínimo y máximo de cada tramo, para ubicar el marcador dentro de la banda
function limites_banda($tipo, $momento = 'ayunas') {
    if ($tipo === 'imc') {
        return ['bajo' => [12, 18.5], 'normal' => [18.5, 25], 'sobrepeso' => [25, 30], 'obesidad' => [30, 40], 'extrema' => [40, 55]];
    }
    if ($tipo === 'glucosa') {
        return $momento === 'ayunas'
            ? ['bajo' => [40, 70], 'normal' => [70, 100], 'pre' => [100, 126], 'diabetes' => [126, 260]]
            : ['bajo' => [40, 70], 'normal' => [70, 140], 'pre' => [140, 200], 'diabetes' => [200, 360]];
    }
    return null;
}

// Posición del marcador (0 a 100) según el valor; la presión se marca en el centro de su tramo
function posicion_en_banda($tipo, $categoria, $valor = null, $momento = 'ayunas') {
    $categorias = array_keys(categorias_de($tipo, $momento));
    $indice = array_search($categoria, $categorias, true);
    $fraccion = 0.5;

    $limites = limites_banda($tipo, $momento);
    if ($limites !== null && $valor !== null) {
        [$minimo, $maximo] = $limites[$categoria];
        $fraccion = ($valor - $minimo) / ($maximo - $minimo);
    }

    $posicion = ($indice + max(0.06, min(0.94, $fraccion))) / count($categorias) * 100;
    return round($posicion, 1);
}

function categorias_de($tipo, $momento = 'ayunas') {
    if ($tipo === 'imc') {
        return categorias_imc();
    }
    if ($tipo === 'glucosa') {
        return categorias_glucosa($momento);
    }
    return categorias_presion();
}

/* --------------------------------------------------------------------------
   Usuarios
   -------------------------------------------------------------------------- */

function buscar_usuario_por_correo($correo) {
    $consulta = conectar()->prepare('SELECT id, nombre, correo, clave_hash FROM usuarios WHERE correo = ?');
    $consulta->execute([$correo]);
    return $consulta->fetch() ?: null;
}

function buscar_usuario($id) {
    $consulta = conectar()->prepare('SELECT id, nombre, correo, clave_hash, creado_en FROM usuarios WHERE id = ?');
    $consulta->execute([$id]);
    return $consulta->fetch() ?: null;
}

function crear_usuario($nombre, $correo, $clave) {
    $consulta = conectar()->prepare('INSERT INTO usuarios (nombre, correo, clave_hash, creado_en) VALUES (?, ?, ?, ?)');
    $consulta->execute([$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
    return (int) conectar()->lastInsertId();
}

function eliminar_usuario($id) {
    $consulta = conectar()->prepare('DELETE FROM usuarios WHERE id = ?');
    $consulta->execute([$id]);
}

/* --------------------------------------------------------------------------
   Mediciones
   -------------------------------------------------------------------------- */

function guardar_medicion($usuario_id, $tipo, $categoria, $datos) {
    $permitidas = ['peso', 'estatura', 'imc', 'glucosa', 'momento', 'sistolica', 'diastolica'];
    $columnas = [
        'usuario_id' => $usuario_id,
        'tipo' => $tipo,
        'categoria' => $categoria,
        'creado_en' => date('Y-m-d H:i:s'),
    ] + array_intersect_key($datos, array_flip($permitidas));

    $nombres = array_keys($columnas);
    $sql = 'INSERT INTO mediciones (' . implode(', ', $nombres) . ') VALUES (:' . implode(', :', $nombres) . ')';
    conectar()->prepare($sql)->execute($columnas);
    return (int) conectar()->lastInsertId();
}

// Solo devuelve la medición si pertenece al usuario
function buscar_medicion($id, $usuario_id, $tipo) {
    $consulta = conectar()->prepare('SELECT * FROM mediciones WHERE id = ? AND usuario_id = ? AND tipo = ?');
    $consulta->execute([$id, $usuario_id, $tipo]);
    return $consulta->fetch() ?: null;
}

function mediciones_recientes($usuario_id, $limite = 8, $tipo = null) {
    $sql = 'SELECT * FROM mediciones WHERE usuario_id = ?' . ($tipo ? ' AND tipo = ?' : '') . ' ORDER BY creado_en DESC, id DESC LIMIT ' . (int) $limite;
    $consulta = conectar()->prepare($sql);
    $consulta->execute($tipo ? [$usuario_id, $tipo] : [$usuario_id]);
    return $consulta->fetchAll();
}

function ultima_por_tipo($usuario_id) {
    $ultimas = [];
    foreach (array_keys(indicadores()) as $tipo) {
        $ultimas[$tipo] = mediciones_recientes($usuario_id, 1, $tipo)[0] ?? null;
    }
    return $ultimas;
}

function total_mediciones($usuario_id) {
    $consulta = conectar()->prepare('SELECT COUNT(*) FROM mediciones WHERE usuario_id = ?');
    $consulta->execute([$usuario_id]);
    return (int) $consulta->fetchColumn();
}

function categoria_de_medicion($medicion) {
    return categorias_de($medicion['tipo'], $medicion['momento'] ?? 'ayunas')[$medicion['categoria']];
}

// Valor principal, unidad y detalle de una medición guardada, listos para mostrar
function lectura_de_medicion($medicion) {
    if ($medicion['tipo'] === 'imc') {
        return [
            'valor' => formato_numero((float) $medicion['imc']),
            'unidad' => 'kg/m²',
            'detalle' => formato_numero((float) $medicion['peso'], 1) . ' kg y ' . formato_numero((float) $medicion['estatura']) . ' m',
            'numero' => (float) $medicion['imc'],
        ];
    }
    if ($medicion['tipo'] === 'glucosa') {
        $glucosa = (float) $medicion['glucosa'];
        return [
            'valor' => formato_numero($glucosa, floor($glucosa) == $glucosa ? 0 : 1),
            'unidad' => 'mg/dl',
            'detalle' => $medicion['momento'] === 'ayunas' ? 'En ayunas' : 'Después de comer',
            'numero' => $glucosa,
        ];
    }
    return [
        'valor' => (int) $medicion['sistolica'] . '/' . (int) $medicion['diastolica'],
        'unidad' => 'mm Hg',
        'detalle' => 'Sistólica / diastólica',
        'numero' => null,
    ];
}

function posicion_de_medicion($medicion) {
    $lectura = lectura_de_medicion($medicion);
    return posicion_en_banda($medicion['tipo'], $medicion['categoria'], $lectura['numero'], $medicion['momento'] ?? 'ayunas');
}

/* --------------------------------------------------------------------------
   Presentación
   -------------------------------------------------------------------------- */

function fecha_relativa($fecha) {
    $momento = new DateTime($fecha);
    $dia = (clone $momento)->setTime(0, 0);
    $dias = (int) $dia->diff(new DateTime('today'))->format('%r%a');
    // Espacios que no se cortan, para que la hora no quede partida en dos líneas
    $hora = $momento->format('g:i') . ($momento->format('A') === 'AM' ? "\u{a0}a.\u{a0}m." : "\u{a0}p.\u{a0}m.");

    if ($dias <= 0) {
        return 'Hoy, ' . $hora;
    }
    if ($dias === 1) {
        return 'Ayer, ' . $hora;
    }
    if ($dias < 7) {
        return "Hace $dias días";
    }
    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $texto = $momento->format('j') . ' ' . $meses[$momento->format('n') - 1];
    return $momento->format('Y') === date('Y') ? $texto : $texto . ' ' . $momento->format('Y');
}

function fecha_de_hoy() {
    $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return ucfirst($dias[date('w')]) . ' ' . date('j') . ' de ' . $meses[date('n') - 1];
}

function icono($nombre, $clase = 'icono') {
    $trazos = [
        'inicio' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>',
        'imc' => '<rect x="3" y="3" width="18" height="18" rx="5"/><path d="M7.5 11.5a4.5 4.5 0 0 1 9 0"/><path d="m12 11.5 2-2.5"/>',
        'glucosa' => '<path d="M12 2.7S6 9.3 6 14a6 6 0 0 0 12 0c0-4.7-6-11.3-6-11.3z"/>',
        'presion' => '<path d="M19.5 12.6 12 20l-7.5-7.4a4.8 4.8 0 0 1 7.5-6 4.8 4.8 0 0 1 7.5 6z"/><path d="M3 12h4.5l1.5-2.5 2.5 5 1.5-2.5H21"/>',
        'figura' => '<circle cx="12" cy="4.5" r="2.5"/><path d="M9.5 21.5v-6l-2-1V10a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v4.5l-2 1v6"/>',
        'salir' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'usuario' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'abajo' => '<path d="m6 9 6 6 6-6"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 8h.01"/><path d="M11 12h1v4h1"/>',
        'alerta' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v6"/><path d="M12 16.5h.01"/>',
        'reloj' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    ];
    return '<svg class="' . $clase . '" viewBox="0 0 24 24" aria-hidden="true">' . $trazos[$nombre] . '</svg>';
}

// Muestra la ilustración si está en img/; si no, un ícono del mismo tono como respaldo
function ilustracion($imagen, $icono, $texto_alternativo = '', $clase = '') {
    if (is_file(CARPETA_IMAGENES . $imagen . '.webp')) {
        return '<img class="' . $clase . '" src="' . url('img/' . $imagen . '.webp') . '" alt="' . mostrar($texto_alternativo) . '" width="640" height="640">';
    }
    return '<span class="arte-respaldo ' . $clase . '" aria-hidden="true">' . icono($icono, '') . '</span>';
}

// Banda de colores con todas las categorías y un marcador en la del paciente
function banda($categorias, $actual, $posicion, $con_leyenda = true) {
    $html = '<div class="banda">';
    $html .= '<div class="banda-pista">';
    foreach ($categorias as $clave => $categoria) {
        $html .= '<span class="banda-tramo ' . $categoria['tono'] . ($clave === $actual ? ' es-actual' : '') . '"></span>';
    }
    if ($actual !== null) {
        $html .= '<span class="banda-marcador" style="left: ' . $posicion . '%"></span>';
    }
    $html .= '</div>';

    if ($con_leyenda) {
        $html .= '<ol class="banda-leyenda">';
        foreach ($categorias as $clave => $categoria) {
            $es_actual = $clave === $actual;
            $html .= '<li class="banda-categoria ' . $categoria['tono'] . ($es_actual ? ' es-actual' : '') . '"' . ($es_actual ? ' aria-current="true"' : '') . '>';
            $html .= '<span class="banda-nombre">' . mostrar($categoria['nombre']) . '</span>';
            $html .= '<span class="banda-rango">' . mostrar($categoria['rango']) . '</span>';
            $html .= '</li>';
        }
        $html .= '</ol>';
    }

    return $html . '</div>';
}

function insignia($categoria) {
    return '<span class="insignia ' . $categoria['tono'] . '">' . mostrar($categoria['nombre']) . '</span>';
}
