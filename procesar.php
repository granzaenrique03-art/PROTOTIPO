<?php
/**
 * procesar.php - Procesa el formulario y calcula el desglose
 */

require_once 'includes/config.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    addFlashMessage('error', 'Acceso no autorizado.');
    redirect('index.php');
}

// ============================================
// 1. VALIDACIÓN DE DATOS
// ============================================
$errores = [];
$datos = [];

// Validar consumo
$consumo = filter_input(INPUT_POST, 'consumo', FILTER_VALIDATE_FLOAT);
if (!$consumo || $consumo < MIN_CONSUMO_KWH || $consumo > MAX_CONSUMO_KWH) {
    $errores[] = "El consumo debe ser un número entre " . MIN_CONSUMO_KWH . " y " . MAX_CONSUMO_KWH . " kWh";
} else {
    $datos['consumo_kwh'] = round($consumo, 2);
}

// Validar monto total
$monto_total = filter_input(INPUT_POST, 'monto_total', FILTER_VALIDATE_FLOAT);
if (!$monto_total || $monto_total < MIN_MONTO_TOTAL || $monto_total > MAX_MONTO_TOTAL) {
    $errores[] = "El monto total debe ser un número válido entre S/ " . MIN_MONTO_TOTAL . " y S/ " . MAX_MONTO_TOTAL;
} else {
    $datos['monto_total'] = round($monto_total, 2);
}

// Validar tarifa
$tarifa = sanitizeInput($_POST['tarifa'] ?? '');
$tarifas_validas = ['BT5B', 'BT5C', 'BT5H', 'MT3', 'MT4'];
if (empty($tarifa) || !in_array($tarifa, $tarifas_validas)) {
    $errores[] = "Selecciona una tarifa válida";
} else {
    $datos['tarifa'] = $tarifa;
}

// Validar distribuidora
$distribuidora = sanitizeInput($_POST['distribuidora'] ?? '');
$distribuidoras_validas = ['ENEL', 'LUZ_SUR', 'ELECTROPERU', 'HIDRANDINA', 'OTRA'];
if (empty($distribuidora) || !in_array($distribuidora, $distribuidoras_validas)) {
    $errores[] = "Selecciona una empresa distribuidora válida";
} else {
    $datos['distribuidora'] = $distribuidora;
}

// Validar FOSE
$datos['tiene_fose'] = isset($_POST['tiene_fose']) && $_POST['tiene_fose'] === '1';

// Validar términos
if (!isset($_POST['acepto_terminos']) || $_POST['acepto_terminos'] !== '1') {
    $errores[] = "Debes aceptar los términos de uso de la herramienta pedagógica";
}

// Si hay errores, redirigir al inicio
if (!empty($errores)) {
    foreach ($errores as $error) {
        addFlashMessage('error', $error);
    }
    // Guardar datos anteriores para repoblar formulario
    $_SESSION['form_data'] = $_POST;
    redirect('index.php');
}

// ============================================
// 2. CÁLCULO DEL DESGLOSE PEDAGÓGICO
// ============================================
function calcularDesglose($datos) {
    $consumo = $datos['consumo_kwh'];
    $total = $datos['monto_total'];
    $tiene_fose = $datos['tiene_fose'];
    
    // Calcular IGV (18%)
    $subtotal_sin_igv = $total / (1 + PORCENTAJE_IGV);
    $monto_igv = $total - $subtotal_sin_igv;
    
    // Aplicar descuento FOSE si corresponde
    $descuento_fose = 0;
    if ($tiene_fose) {
        $descuento_fose = $subtotal_sin_igv * PORCENTAJE_FOSE;
        $subtotal_con_fose = $subtotal_sin_igv - $descuento_fose;
    } else {
        $subtotal_con_fose = $subtotal_sin_igv;
    }
    
    // Calcular costos fijos estimados
    $cargo_fijo = 2.19; // Valor fijo para BT5B
    $alumbrado_publico = $subtotal_con_fose * PORCENTAJE_ALUMBRADO;
    $reposicion = 1.63;
    $aporte_ley = 0.59;
    
    // Calcular monto para energía (subtotal - costos fijos)
    $costos_fijos = $cargo_fijo + $alumbrado_publico + $reposicion + $aporte_ley;
    $monto_energia = max(0, $subtotal_con_fose - $costos_fijos);
    
    // Distribuir monto de energía según porcentajes pedagógicos
    $monto_generacion = $monto_energia * PORCENTAJE_GENERACION;
    $monto_transmision = $monto_energia * PORCENTAJE_TRANSMISION;
    $monto_distribucion = $monto_energia * PORCENTAJE_DISTRIBUCION;
    
    // Calcular porcentajes respecto al total
    $porcentaje_generacion = ($monto_generacion / $total) * 100;
    $porcentaje_transmision = ($monto_transmision / $total) * 100;
    $porcentaje_distribucion = ($monto_distribucion / $total) * 100;
    
    // Costo por kWh
    $costo_por_kwh = $total / $consumo;
    
    // Preparar resultado
    return [
        'datos_usuario' => $datos,
        'fecha_calculo' => date('d/m/Y H:i:s'),
        'hash_calculo' => md5(json_encode($datos) . time()),
        
        'totales' => [
            'consumo_kwh' => round($consumo, 2),
            'total_pagar' => round($total, 2),
            'costo_por_kwh' => round($costo_por_kwh, 3),
            'subtotal_sin_igv' => round($subtotal_sin_igv, 2),
            'subtotal_con_fose' => round($subtotal_con_fose, 2)
        ],
        
        'desglose_principal' => [
            'generacion' => [
                'monto' => round($monto_generacion, 2),
                'porcentaje_total' => round($porcentaje_generacion, 1),
                'descripcion' => 'Producción de energía en centrales eléctricas',
                'explicacion' => 'Costo de generar la energía en centrales hidroeléctricas, térmicas, solares y eólicas.'
            ],
            'transmision' => [
                'monto' => round($monto_transmision, 2),
                'porcentaje_total' => round($porcentaje_transmision, 1),
                'descripcion' => 'Transporte de energía por redes de alta tensión',
                'explicacion' => 'Costo de transportar la energía desde las centrales hasta las ciudades.'
            ],
            'distribucion' => [
                'monto' => round($monto_distribucion, 2),
                'porcentaje_total' => round($porcentaje_distribucion, 1),
                'descripcion' => 'Distribución local y servicio comercial',
                'explicacion' => 'Costo de la red local, mantenimiento y servicio de tu empresa distribuidora.'
            ]
        ],
        
        'otros_componentes' => [
            'cargo_fijo' => [
                'monto' => round($cargo_fijo, 2),
                'descripcion' => 'Cargo fijo por disponibilidad del servicio'
            ],
            'alumbrado_publico' => [
                'monto' => round($alumbrado_publico, 2),
                'descripcion' => 'Contribución al alumbrado público municipal'
            ],
            'reposicion' => [
                'monto' => round($reposicion, 2),
                'descripcion' => 'Reposición y mantenimiento de la red'
            ],
            'aporte_ley' => [
                'monto' => round($aporte_ley, 2),
                'descripcion' => 'Aporte Ley N° 28749 (programas sociales)'
            ]
        ],
        
        'impuestos_beneficios' => [
            'igv' => [
                'monto' => round($monto_igv, 2),
                'porcentaje' => round(($monto_igv / $total) * 100, 1),
                'descripcion' => 'Impuesto General a las Ventas (18%)'
            ],
            'descuento_fose' => [
                'monto' => round($descuento_fose, 2),
                'descripcion' => 'Descuento FOSE (subsidio estatal)',
                'activo' => $tiene_fose
            ]
        ],
        
        'datos_graficos' => [
            'generacion' => round($monto_generacion, 2),
            'transmision' => round($monto_transmision, 2),
            'distribucion' => round($monto_distribucion, 2),
            'otros' => round($costos_fijos, 2),
            'impuestos' => round($monto_igv, 2)
        ],
        
        'informacion_adicional' => [
            'mensaje_importante' => 'Los valores mostrados son estimaciones pedagógicas basadas en porcentajes promedio de Osinergmin.',
            'recomendacion_ahorro' => obtenerRecomendacionAhorro($consumo),
            'rango_consumo' => clasificarConsumo($consumo),
            'mes_actual' => obtenerNombreMes(date('n')),
            'anio_actual' => date('Y')
        ]
    ];
}

// Funciones auxiliares
function obtenerRecomendacionAhorro($consumo) {
    if ($consumo <= 100) {
        return 'Tu consumo es eficiente. Continúa con buenas prácticas como apagar luces innecesarias y desconectar equipos.';
    } elseif ($consumo <= 200) {
        return 'Considera revisar electrodomésticos de alto consumo y usar focos LED para mayor eficiencia.';
    } else {
        return 'Te recomendamos revisar tus hábitos de consumo y considerar una auditoría energética.';
    }
}

function clasificarConsumo($consumo) {
    if ($consumo <= 30) return ['tipo' => 'MUY BAJO', 'color' => '#27ae60', 'icono' => 'leaf'];
    if ($consumo <= 100) return ['tipo' => 'BAJO', 'color' => '#2ecc71', 'icono' => 'thumbs-up'];
    if ($consumo <= 200) return ['tipo' => 'MODERADO', 'color' => '#f39c12', 'icono' => 'balance-scale'];
    return ['tipo' => 'ALTO', 'color' => '#e74c3c', 'icono' => 'exclamation-triangle'];
}

function obtenerNombreMes($numero) {
    $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
              7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
    return $meses[$numero] ?? 'Desconocido';
}

// ============================================
// 3. EJECUTAR CÁLCULO Y GUARDAR RESULTADO
// ============================================
try {
    $resultado = calcularDesglose($datos);
    
    // Guardar en sesión
    $_SESSION['datos_boleta'] = $resultado;
    
    // Limpiar datos de formulario anteriores
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    
    // Redirigir a resultados
    redirect('resultados.php');
    
} catch (Exception $e) {
    // Manejo de errores
    error_log("Error en procesar.php: " . $e->getMessage());
    addFlashMessage('error', 'Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente.');
    redirect('index.php');
}
?>