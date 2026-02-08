<?php
/**
 * api/calcular.php - API para cálculo en tiempo real
 */

require_once '../includes/config.php';

header('Content-Type: application/json');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ]);
    exit();
}

// Obtener datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'error' => 'Datos inválidos'
    ]);
    exit();
}

// Función de cálculo simplificada (mismo que procesar.php)
function calcularAPI($datos) {
    $consumo = floatval($datos['consumo']);
    $total = floatval($datos['monto_total']);
    $tiene_fose = isset($datos['tiene_fose']) && $datos['tiene_fose'];
    
    // Cálculos básicos
    $subtotal_sin_igv = $total / 1.18;
    $monto_igv = $total - $subtotal_sin_igv;
    
    if ($tiene_fose) {
        $descuento_fose = $subtotal_sin_igv * 0.13;
        $subtotal_con_fose = $subtotal_sin_igv - $descuento_fose;
    } else {
        $descuento_fose = 0;
        $subtotal_con_fose = $subtotal_sin_igv;
    }
    
    // Cálculo simplificado para API
    $monto_energia = $subtotal_con_fose * 0.85;
    $monto_generacion = $monto_energia * 0.53;
    $monto_transmision = $monto_energia * 0.17;
    $monto_distribucion = $monto_energia * 0.30;
    
    return [
        'success' => true,
        'data' => [
            'totales' => [
                'consumo_kwh' => $consumo,
                'total_pagar' => $total,
                'costo_por_kwh' => $total / $consumo
            ],
            'desglose' => [
                'generacion' => round($monto_generacion, 2),
                'transmision' => round($monto_transmision, 2),
                'distribucion' => round($monto_distribucion, 2),
                'igv' => round($monto_igv, 2),
                'fose' => round($descuento_fose, 2)
            ]
        ]
    ];
}

try {
    $resultado = calcularAPI($data);
    echo json_encode($resultado);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
