<?php
/**
 * api/tarifas.php - API para obtener información de tarifas
 */

require_once '../includes/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Datos de ejemplo (en producción vendrían de una base de datos)
$tarifas = [
    'BT5B' => [
        'nombre' => 'Residencial Baja Tensión 5B',
        'descripcion' => 'Para uso residencial en zonas urbanas',
        'porcentajes' => [
            'generacion' => 0.53,
            'transmision' => 0.17,
            'distribucion' => 0.30
        ],
        'cargo_fijo' => 2.19,
        'ejemplo_consumo' => '30-200 kWh/mes'
    ],
    'BT5C' => [
        'nombre' => 'Comercial Baja Tensión 5C',
        'descripcion' => 'Para pequeños comercios y servicios',
        'porcentajes' => [
            'generacion' => 0.52,
            'transmision' => 0.18,
            'distribucion' => 0.30
        ],
        'cargo_fijo' => 4.38,
        'ejemplo_consumo' => '100-500 kWh/mes'
    ],
    'BT5H' => [
        'nombre' => 'Hogar Eficiente',
        'descripcion' => 'Para viviendas con equipos de alta eficiencia',
        'porcentajes' => [
            'generacion' => 0.54,
            'transmision' => 0.16,
            'distribucion' => 0.30
        ],
        'cargo_fijo' => 2.19,
        'ejemplo_consumo' => '20-150 kWh/mes'
    ]
];

// Permitir filtro por tarifa específica
$tarifa = $_GET['tarifa'] ?? '';

if ($tarifa && isset($tarifas[$tarifa])) {
    echo json_encode([
        'success' => true,
        'data' => $tarifas[$tarifa]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'data' => $tarifas
    ]);
}
?>
