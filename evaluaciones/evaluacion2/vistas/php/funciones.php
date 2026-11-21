<?php
// Funciones compartidas del Consultorio ABC: sesión, lectura de datos, cálculos y diagnósticos.

session_start();

const RUTA_LOGIN = '../../index.html';
const CARPETA_IMAGENES = __DIR__ . '/../../img/';

/* --------------------------------------------------------------------------
   Sesión y lectura de datos
   -------------------------------------------------------------------------- */

// Devuelve el nombre del paciente; si no inició sesión, lo manda al login
function requerir_sesion() {
    if (empty($_SESSION['nombre'])) {
        header('Location: ' . RUTA_LOGIN);
        exit;
    }
    return $_SESSION['nombre'];
}

// Las páginas de resultado solo responden al envío de su formulario
function solo_post($formulario) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . $formulario);
        exit;
    }
}

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

// IMC = peso (kg) / estatura² (m)
function calcular_imc($peso, $estatura) {
    return $peso / ($estatura * $estatura);
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
        ? ['bajo' => 'Menos de 70', 'normal' => '70 a 100', 'pre' => '101 a 125', 'diabetes' => '126 o más']
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
        if ($lectura <= 125) {
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

/* --------------------------------------------------------------------------
   Presentación
   -------------------------------------------------------------------------- */

function icono($nombre, $clase = 'icono') {
    $trazos = [
        'menu' => '<rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/>',
        'imc' => '<rect x="3" y="3" width="18" height="18" rx="5"/><path d="M7.5 11.5a4.5 4.5 0 0 1 9 0"/><path d="m12 11.5 2-2.5"/>',
        'glucosa' => '<path d="M12 2.7S6 9.3 6 14a6 6 0 0 0 12 0c0-4.7-6-11.3-6-11.3z"/>',
        'presion' => '<path d="M19.5 12.6 12 20l-7.5-7.4a4.8 4.8 0 0 1 7.5-6 4.8 4.8 0 0 1 7.5 6z"/><path d="M3 12h4.5l1.5-2.5 2.5 5 1.5-2.5H21"/>',
        'figura' => '<circle cx="12" cy="4.5" r="2.5"/><path d="M9.5 21.5v-6l-2-1V10a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v4.5l-2 1v6"/>',
        'salir' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'flecha' => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 8h.01"/><path d="M11 12h1v4h1"/>',
    ];
    return '<svg class="' . $clase . '" viewBox="0 0 24 24" aria-hidden="true">' . $trazos[$nombre] . '</svg>';
}

// Muestra la ilustración si ya está en img/; si no, un ícono del mismo tono como respaldo
function ilustracion($imagen, $icono, $texto_alternativo = '') {
    if (is_file(CARPETA_IMAGENES . $imagen . '.webp')) {
        return '<img src="../../img/' . $imagen . '.webp" alt="' . mostrar($texto_alternativo) . '" width="640" height="640">';
    }
    return '<span class="arte-respaldo"' . ($texto_alternativo === '' ? ' aria-hidden="true"' : ' role="img" aria-label="' . mostrar($texto_alternativo) . '"') . '>' . icono($icono, '') . '</span>';
}

// Barra con todas las categorías y un marcador en la del paciente
function escala($categorias, $actual, $titulo) {
    $html = '<section class="escala" aria-labelledby="escala-titulo">';
    $html .= '<h2 class="escala-titulo" id="escala-titulo">' . mostrar($titulo) . '</h2>';
    $html .= '<ol class="escala-lista">';
    foreach ($categorias as $clave => $categoria) {
        $es_actual = $clave === $actual;
        $html .= '<li class="escala-tramo ' . $categoria['tono'] . ($es_actual ? ' es-actual' : '') . '"' . ($es_actual ? ' aria-current="true"' : '') . '>';
        if ($es_actual) {
            $html .= '<span class="escala-marcador">Tú</span>';
        }
        $html .= '<span class="escala-barra"></span>';
        $html .= '<span class="escala-nombre">' . mostrar($categoria['nombre']) . '</span>';
        $html .= '<span class="escala-rango">' . mostrar($categoria['rango']) . '</span>';
        $html .= '</li>';
    }
    return $html . '</ol></section>';
}
