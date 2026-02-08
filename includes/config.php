<?php
/**
 * config.php - Configuración básica del proyecto
 */

// Configuración de sesión
session_start();

// Configuración del sitio
define('SITE_NAME', 'Mi Boleta Transparente');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
define('SITE_EMAIL', 'transparencia@osinergmin.gob.pe');

// Configuración de entorno
define('ENVIRONMENT', 'development'); // 'development' o 'production'

// Porcentajes pedagógicos (valores por defecto)
define('PORCENTAJE_GENERACION', 0.53);   // 53%
define('PORCENTAJE_TRANSMISION', 0.17);  // 17%
define('PORCENTAJE_DISTRIBUCION', 0.30); // 30%
define('PORCENTAJE_IGV', 0.18);          // 18%
define('PORCENTAJE_FOSE', 0.13);         // 13%
define('PORCENTAJE_ALUMBRADO', 0.08);    // 8%

// Límites de validación
define('MAX_CONSUMO_KWH', 10000);
define('MAX_MONTO_TOTAL', 10000);
define('MIN_CONSUMO_KWH', 1);
define('MIN_MONTO_TOTAL', 0.01);

// Rutas de archivos
define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data/');
define('LOG_PATH', ROOT_PATH . '/logs/');

// Funciones de utilidad
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function redirect($url, $statusCode = 303) {
    if (!headers_sent()) {
        header('Location: ' . $url, true, $statusCode);
    } else {
        echo '<script>window.location.href="' . $url . '";</script>';
    }
    exit();
}

function jsonResponse($data, $success = true, $error = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error,
        'timestamp' => time()
    ]);
    exit();
}

// Manejo de errores según entorno
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Crear directorios necesarios si no existen
$directorios_necesarios = [DATA_PATH, LOG_PATH];
foreach ($directorios_necesarios as $directorio) {
    if (!file_exists($directorio)) {
        @mkdir($directorio, 0755, true);
    }
}

// Inicializar sesión para mensajes flash
if (!isset($_SESSION['flash_messages'])) {
    $_SESSION['flash_messages'] = [];
}

function addFlashMessage($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message,
        'time' => time()
    ];
}

function getFlashMessages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    $_SESSION['flash_messages'] = [];
    return $messages;
}
?>
