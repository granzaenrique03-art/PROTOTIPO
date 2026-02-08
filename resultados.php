<?php
/**
 * resultados.php - Muestra el desglose pedagógico calculado
 */

require_once 'includes/config.php';
require_once 'includes/header.php';

// Verificar si hay datos en sesión
if (!isset($_SESSION['datos_boleta'])) {
    addFlashMessage('error', 'No hay datos para mostrar. Por favor, ingresa los datos de tu boleta primero.');
    redirect('index.php');
}

$datos = $_SESSION['datos_boleta'];

// Extraer datos para facilidad de uso
$totales = $datos['totales'];
$desglose = $datos['desglose_principal'];
$otros = $datos['otros_componentes'];
$impuestos = $datos['impuestos_beneficios'];
$graficos = $datos['datos_graficos'];
$info_adicional = $datos['informacion_adicional'];
$datos_usuario = $datos['datos_usuario'];

// Calcular totales para display
$total_desglose = $graficos['generacion'] + $graficos['transmision'] + $graficos['distribucion'];
$total_otros = 0;
foreach ($otros as $componente) {
    $total_otros += $componente['monto'];
}
?>

<!-- CSS adicional para resultados -->
<style>
    /* Estilos específicos para la página de resultados */
    .resultados-container {
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }
    
    /* Header de resultados */
    .header-resultados {
        width: 100%;
        margin-bottom: 30px;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    /* Resumen principal */
    .resumen-principal {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
        width: 100%;
        box-sizing: border-box;
    }
    
    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 25px;
        width: 100%;
    }
    
    .resumen-card {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 20px;
        text-align: center;
        transition: var(--transition);
        width: 100%;
        box-sizing: border-box;
    }
    
    .resumen-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .resumen-card.destacado {
        background: linear-gradient(135deg, var(--secondary-color), #2980b9);
        color: white;
    }
    
    .resumen-icon {
        font-size: 2em;
        margin-bottom: 15px;
    }
    
    .resumen-valor {
        font-size: 1.8em;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .resumen-detalle {
        font-size: 0.9em;
        color: var(--gray);
    }
    
    .resumen-card.destacado .resumen-detalle {
        color: rgba(255,255,255,0.9);
    }
    
    /* Clasificación de consumo */
    .clasificacion-consumo {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid var(--gray-light);
        width: 100%;
    }
    
    .clasificacion-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: white;
        border-radius: var(--border-radius);
        padding: 20px;
        border-left: 5px solid <?php echo $info_adicional['rango_consumo']['color']; ?>;
        margin-top: 15px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .clasificacion-icon {
        font-size: 2.5em;
        flex-shrink: 0;
    }
    
    /* Gráficos */
    .graficos-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 30px 0;
        width: 100%;
    }
    
    .grafico-wrapper {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        box-shadow: var(--box-shadow);
        width: 100%;
        box-sizing: border-box;
    }
    
    .grafico-wrapper h3 {
        margin-bottom: 20px;
        color: var(--primary-color);
        text-align: center;
        font-size: 1.3em;
    }
    
    .grafico-wrapper canvas {
        width: 100% !important;
        height: 300px !important;
        max-width: 100%;
    }
    
    .grafico-leyenda {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: var(--border-radius);
    }
    
    /* Desglose detallado */
    .desglose-detallado {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        margin: 30px 0;
        box-shadow: var(--box-shadow);
        width: 100%;
        box-sizing: border-box;
    }
    
    .categoria-desglose {
        margin-bottom: 40px;
        width: 100%;
    }
    
    .categoria-titulo {
        color: var(--primary-color);
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light-color);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.4em;
    }
    
    .categoria-descripcion {
        color: var(--gray);
        margin-bottom: 25px;
        font-size: 1.05em;
        line-height: 1.6;
    }
    
    .componente-detalle {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 20px;
        border-left: 4px solid var(--secondary-color);
        width: 100%;
        box-sizing: border-box;
    }
    
    .componente-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .componente-info {
        flex: 1;
        min-width: 300px;
    }
    
    .componente-info h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
        font-size: 1.3em;
    }
    
    .componente-descripcion {
        color: var(--gray);
        font-size: 0.95em;
        line-height: 1.5;
    }
    
    .componente-valores {
        text-align: right;
        min-width: 150px;
    }
    
    .componente-monto {
        font-size: 1.8em;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .componente-porcentaje {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .porcentaje-badge {
        background: var(--secondary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 1.1em;
    }
    
    .porcentaje-detalle {
        font-size: 0.85em;
        color: var(--gray);
        margin-top: 5px;
    }
    
    .componente-explicacion {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        margin: 20px 0;
        border-left: 3px solid var(--warning-color);
        width: 100%;
        box-sizing: border-box;
    }
    
    .componente-explicacion p {
        margin-bottom: 10px;
        line-height: 1.6;
        font-size: 1.05em;
    }
    
    .explicacion-detalle {
        background: #f8f9fa;
        padding: 15px;
        border-radius: var(--border-radius);
        margin-top: 15px;
        font-size: 0.95em;
    }
    
    .componente-acciones {
        text-align: center;
        margin-top: 15px;
    }
    
    .btn-info {
        background: transparent;
        border: 2px solid var(--secondary-color);
        color: var(--secondary-color);
        padding: 10px 20px;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95em;
    }
    
    .btn-info:hover {
        background: var(--secondary-color);
        color: white;
    }
    
    /* Componentes pequeños */
    .componentes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        width: 100%;
    }
    
    .componente-pequeno {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 20px;
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }
    
    .componente-pequeno-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .componente-pequeno-header h5 {
        color: var(--primary-color);
        margin: 0;
        font-size: 1.1em;
    }
    
    .componente-pequeno-monto {
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.2em;
    }
    
    .componente-pequeno-desc {
        color: var(--gray);
        font-size: 0.9em;
        margin-bottom: 10px;
        line-height: 1.5;
    }
    
    .btn-info-pequeno {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: var(--secondary-color);
        cursor: pointer;
        font-size: 1.2em;
    }
    
    /* Impuestos */
    .impuestos-container {
        margin-top: 20px;
        width: 100%;
    }
    
    .impuesto-item {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 20px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .impuesto-item.destacado {
        border-left: 4px solid var(--accent-color);
    }
    
    .impuesto-item.beneficio {
        border-left: 4px solid var(--success-color);
        background: #d5f4e6;
    }
    
    .impuesto-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .impuesto-info {
        flex: 1;
        min-width: 300px;
    }
    
    .impuesto-info h4 {
        color: var(--primary-color);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3em;
    }
    
    .impuesto-valor {
        text-align: right;
        min-width: 150px;
    }
    
    .impuesto-monto {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .impuesto-monto.beneficio {
        color: var(--success-color);
    }
    
    .impuesto-porcentaje {
        color: var(--gray);
        font-size: 0.9em;
    }
    
    .impuesto-porcentaje.beneficio {
        color: var(--success-color);
    }
    
    .impuesto-explicacion {
        color: var(--gray);
        font-size: 0.95em;
        line-height: 1.6;
    }
    
    /* Información educativa */
    .informacion-educativa {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        margin: 30px 0;
        box-shadow: var(--box-shadow);
        width: 100%;
        box-sizing: border-box;
    }
    
    .educacion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin: 25px 0;
        width: 100%;
    }
    
    .tarjeta-educativa {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 25px;
        transition: var(--transition);
        width: 100%;
        box-sizing: border-box;
        height: 100%;
    }
    
    .tarjeta-educativa:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .tarjeta-icono {
        font-size: 2.5em;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }
    
    .tarjeta-educativa h4 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1.2em;
    }
    
    .tarjeta-educativa ul {
        list-style: none;
        padding-left: 0;
    }
    
    .tarjeta-educativa li {
        padding: 8px 0;
        color: var(--dark-color);
        position: relative;
        padding-left: 25px;
        line-height: 1.5;
    }
    
    .tarjeta-educativa li:before {
        content: "•";
        color: var(--secondary-color);
        font-size: 1.5em;
        position: absolute;
        left: 0;
        top: 5px;
    }
    
    /* Comparativa */
    .comparativa-consumo {
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 25px;
        margin-top: 30px;
        border-left: 4px solid var(--secondary-color);
        width: 100%;
        box-sizing: border-box;
    }
    
    .equivalencias {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
        width: 100%;
    }
    
    .equivalencia {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .equivalencia-icono {
        font-size: 2em;
        color: var(--secondary-color);
        flex-shrink: 0;
    }
    
    .equivalencia-texto {
        line-height: 1.5;
        flex: 1;
    }
    
    /* Acciones */
    .acciones-finales {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin: 30px 0;
        width: 100%;
        box-sizing: border-box;
    }
    
    .acciones-botones {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin: 25px 0;
        flex-wrap: wrap;
        width: 100%;
    }
    
    .codigo-consulta {
        background: #f8f9fa;
        padding: 15px;
        border-radius: var(--border-radius);
        margin-top: 20px;
        border-left: 4px solid var(--warning-color);
        text-align: left;
    }
    
    .codigo-consulta code {
        background: #e9ecef;
        padding: 5px 10px;
        border-radius: 4px;
        font-family: monospace;
        color: var(--dark-color);
        font-size: 0.9em;
        display: inline-block;
        margin-top: 5px;
        word-break: break-all;
    }
    
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    
    .modal-contenido {
        background: white;
        border-radius: var(--border-radius);
        width: 90%;
        max-width: 600px;
        padding: 30px;
        position: relative;
        animation: modalAppear 0.3s ease;
    }
    
    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .cerrar-modal {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 28px;
        cursor: pointer;
        color: var(--gray);
        transition: var(--transition);
    }
    
    .cerrar-modal:hover {
        color: var(--accent-color);
    }
    
    /* Responsive para resultados */
    @media (max-width: 768px) {
        .resultados-container {
            padding: 0 15px;
        }
        
        .header-resultados > div:first-child {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        
        .resumen-grid {
            grid-template-columns: 1fr;
        }
        
        .graficos-container {
            grid-template-columns: 1fr;
        }
        
        .componentes-grid {
            grid-template-columns: 1fr;
        }
        
        .educacion-grid {
            grid-template-columns: 1fr;
        }
        
        .equivalencias {
            grid-template-columns: 1fr;
        }
        
        .acciones-botones {
            flex-direction: column;
        }
        
        .acciones-botones .btn {
            width: 100%;
        }
        
        .componente-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .componente-valores {
            text-align: left;
            width: 100%;
        }
        
        .componente-porcentaje {
            align-items: flex-start;
        }
        
        .impuesto-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .impuesto-valor {
            text-align: left;
            width: 100%;
        }
        
        .header-actions {
            justify-content: center;
            width: 100%;
        }
    }
    
    @media (max-width: 480px) {
        .resultados-container {
            padding: 0 10px;
        }
        
        .grafico-wrapper {
            padding: 15px;
        }
        
        .componente-detalle {
            padding: 20px;
        }
        
        .tarjeta-educativa {
            padding: 20px;
        }
        
        .resumen-principal,
        .desglose-detallado,
        .informacion-educativa,
        .acciones-finales {
            padding: 20px;
        }
    }
</style>

<div class="resultados-container">
    <!-- Header personalizado para resultados -->
    <div class="header-resultados">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <div style="flex: 1; min-width: 300px;">
                <h1 style="color: var(--primary-color); margin-bottom: 5px; font-size: 1.8em;">
                    <i class="fas fa-chart-pie"></i> Desglose de tu Recibo
                </h1>
                <p style="color: var(--gray); margin: 0; font-size: 1em;">Herramienta pedagógica - No constituye factura oficial</p>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Nueva Consulta
                </a>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
        
        <div class="advertencia" style="margin: 0; padding: 15px;">
            <i class="fas fa-exclamation-triangle"></i>
            <div style="line-height: 1.6;">
                <strong>Recordatorio importante:</strong> Esta es una herramienta pedagógica que utiliza porcentajes promedio. 
                Los valores mostrados son referenciales y estimados con fines educativos. 
                Para información exacta, consulte su factura oficial emitida por su empresa distribuidora.
            </div>
        </div>
    </div>

    <!-- Resumen Principal -->
    <section class="resumen-principal">
        <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.6em;">
            <i class="fas fa-file-invoice-dollar"></i> Resumen de tu Simulación
        </h2>
        
        <div class="resumen-grid">
            <div class="resumen-card destacado">
                <div class="resumen-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="resumen-content">
                    <h3 style="margin-bottom: 10px; font-size: 1.2em;">Consumo Energético</h3>
                    <div class="resumen-valor"><?php echo $totales['consumo_kwh']; ?> kWh</div>
                    <div class="resumen-detalle">
                        <?php echo $info_adicional['mes_actual']; ?> <?php echo $info_adicional['anio_actual']; ?>
                    </div>
                </div>
            </div>
            
            <div class="resumen-card destacado">
                <div class="resumen-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="resumen-content">
                    <h3 style="margin-bottom: 10px; font-size: 1.2em;">Total a Pagar</h3>
                    <div class="resumen-valor">S/ <?php echo number_format($totales['total_pagar'], 2); ?></div>
                    <div class="resumen-detalle">
                        Incluye todos los componentes
                    </div>
                </div>
            </div>
            
            <div class="resumen-card">
                <div class="resumen-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="resumen-content">
                    <h3 style="margin-bottom: 10px; font-size: 1.2em;">Costo por kWh</h3>
                    <div class="resumen-valor">S/ <?php echo $totales['costo_por_kwh']; ?></div>
                    <div class="resumen-detalle">
                        Promedio total incluido todo
                    </div>
                </div>
            </div>
            
            <div class="resumen-card">
                <div class="resumen-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="resumen-content">
                    <h3 style="margin-bottom: 10px; font-size: 1.2em;">Tarifa</h3>
                    <div class="resumen-valor"><?php echo $datos_usuario['tarifa']; ?></div>
                    <div class="resumen-detalle">
                        <?php 
                        $nombres_tarifas = [
                            'BT5B' => 'Residencial',
                            'BT5C' => 'Comercial', 
                            'BT5H' => 'Hogar eficiente',
                            'MT3' => 'Mediana tensión',
                            'MT4' => 'Industrial'
                        ];
                        echo $nombres_tarifas[$datos_usuario['tarifa']] ?? 'Tarifa eléctrica';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Clasificación del consumo -->
        <div class="clasificacion-consumo">
            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.3em;">
                <i class="fas fa-chart-bar"></i> Clasificación de tu Consumo
            </h4>
            <div class="clasificacion-card">
                <div class="clasificacion-icon" style="color: <?php echo $info_adicional['rango_consumo']['color']; ?>">
                    <i class="fas fa-<?php echo $info_adicional['rango_consumo']['icono']; ?>"></i>
                </div>
                <div class="clasificacion-content">
                    <h5 style="color: <?php echo $info_adicional['rango_consumo']['color']; ?>; margin-bottom: 8px; font-size: 1.2em;">
                        Consumo <?php echo $info_adicional['rango_consumo']['tipo']; ?>
                    </h5>
                    <p style="color: var(--gray-dark); line-height: 1.6;"><?php echo $info_adicional['recomendacion_ahorro']; ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Gráficos -->
    <section class="graficos-container">
        <div class="grafico-wrapper">
            <h3><i class="fas fa-chart-pie"></i> Distribución de tu Pago</h3>
            <canvas id="graficoDistribucion"></canvas>
            <div class="grafico-leyenda" id="leyendaDistribucion"></div>
        </div>
        
        <div class="grafico-wrapper">
            <h3><i class="fas fa-sitemap"></i> Cadena de Valor Eléctrico</h3>
            <canvas id="graficoCadenaValor"></canvas>
            <div class="grafico-leyenda" id="leyendaCadenaValor"></div>
        </div>
    </section>

    <!-- Desglose Detallado -->
    <section class="desglose-detallado">
        <h2 style="color: var(--primary-color); margin-bottom: 25px; font-size: 1.6em;">
            <i class="fas fa-search-dollar"></i> Desglose Detallado por Componente
        </h2>
        
        <!-- Componentes Principales -->
        <div class="categoria-desglose">
            <h3 class="categoria-titulo">
                <i class="fas fa-industry"></i> Componentes del Servicio Eléctrico
            </h3>
            <p class="categoria-descripcion">
                Estos son los tres componentes principales que conforman el costo de la energía que consumes.
            </p>
            
            <?php foreach ($desglose as $key => $componente): ?>
            <div class="componente-detalle" id="componente-<?php echo $key; ?>">
                <div class="componente-header">
                    <div class="componente-info">
                        <h4>
                            <?php 
                            $nombres = [
                                'generacion' => 'Generación de Energía',
                                'transmision' => 'Transmisión de Energía', 
                                'distribucion' => 'Distribución y Comercialización'
                            ];
                            echo $nombres[$key];
                            ?>
                        </h4>
                        <p class="componente-descripcion"><?php echo $componente['descripcion']; ?></p>
                    </div>
                    <div class="componente-valores">
                        <div class="componente-monto">S/ <?php echo number_format($componente['monto'], 2); ?></div>
                        <div class="componente-porcentaje">
                            <span class="porcentaje-badge"><?php echo $componente['porcentaje_total']; ?>%</span>
                            <span class="porcentaje-detalle">del total pagado</span>
                        </div>
                    </div>
                </div>
                
                <div class="componente-explicacion">
                    <p><?php echo $componente['explicacion']; ?></p>
                    <div class="explicacion-detalle">
                        <p><strong>De cada S/1 que pagas por energía, aproximadamente <?php echo round(($componente['porcentaje_total'] / 100) * ($totales['total_pagar'] / $totales['consumo_kwh']), 3); ?> centavos corresponden a este componente.</strong></p>
                    </div>
                </div>
                
                <div class="componente-acciones">
                    <button class="btn-info" onclick="mostrarInfoComponente('<?php echo $key; ?>')">
                        <i class="fas fa-info-circle"></i> Más información sobre <?php echo $nombres[$key]; ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Otros Componentes -->
        <div class="categoria-desglose">
            <h3 class="categoria-titulo">
                <i class="fas fa-list-alt"></i> Otros Cargos e Impuestos
            </h3>
            <p class="categoria-descripcion">
                Componentes adicionales que forman parte de tu factura eléctrica.
            </p>
            
            <div class="componentes-grid">
                <?php foreach ($otros as $key => $componente): ?>
                <div class="componente-pequeno">
                    <div class="componente-pequeno-header">
                        <h5>
                            <?php 
                            $nombres = [
                                'cargo_fijo' => 'Cargo Fijo',
                                'alumbrado_publico' => 'Alumbrado Público',
                                'reposicion' => 'Reposición de Red',
                                'aporte_ley' => 'Aporte Social'
                            ];
                            echo $nombres[$key] ?? ucfirst($key);
                            ?>
                        </h5>
                        <div class="componente-pequeno-monto">S/ <?php echo number_format($componente['monto'], 2); ?></div>
                    </div>
                    <p class="componente-pequeno-desc"><?php echo $componente['descripcion']; ?></p>
                    <button class="btn-info-pequeno" onclick="mostrarInfoComponente('<?php echo $key; ?>', 'otros')">
                        <i class="fas fa-question-circle"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Impuestos y Beneficios -->
        <div class="categoria-desglose">
            <h3 class="categoria-titulo">
                <i class="fas fa-percentage"></i> Impuestos y Beneficios
            </h3>
            
            <div class="impuestos-container">
                <!-- IGV -->
                <div class="impuesto-item destacado">
                    <div class="impuesto-header">
                        <div class="impuesto-info">
                            <h4><i class="fas fa-landmark"></i> Impuesto General a las Ventas (IGV)</h4>
                            <p><?php echo $impuestos['igv']['descripcion']; ?></p>
                        </div>
                        <div class="impuesto-valor">
                            <div class="impuesto-monto">S/ <?php echo number_format($impuestos['igv']['monto'], 2); ?></div>
                            <div class="impuesto-porcentaje"><?php echo $impuestos['igv']['porcentaje']; ?>% del total</div>
                        </div>
                    </div>
                    <p class="impuesto-explicacion">
                        El IGV es un impuesto nacional que se aplica a la venta de bienes y servicios en el Perú. 
                        En el caso de la energía eléctrica, se aplica sobre el valor total del servicio.
                    </p>
                </div>
                
                <!-- FOSE -->
                <?php if ($impuestos['descuento_fose']['activo']): ?>
                <div class="impuesto-item beneficio">
                    <div class="impuesto-header">
                        <div class="impuesto-info">
                            <h4><i class="fas fa-hand-holding-heart"></i> Descuento FOSE</h4>
                            <p><?php echo $impuestos['descuento_fose']['descripcion']; ?></p>
                        </div>
                        <div class="impuesto-valor">
                            <div class="impuesto-monto beneficio">- S/ <?php echo number_format($impuestos['descuento_fose']['monto'], 2); ?></div>
                            <div class="impuesto-porcentaje beneficio">Ahorro del <?php echo round(($impuestos['descuento_fose']['monto'] / $totales['subtotal_sin_igv']) * 100, 1); ?>%</div>
                        </div>
                    </div>
                    <p class="impuesto-explicacion">
                        El Fondo de Compensación Social Eléctrica (FOSE) es un subsidio del Estado Peruano 
                        destinado a usuarios residenciales de bajos consumos para hacer la electricidad más accesible.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Información Educativa -->
    <section class="informacion-educativa">
        <h2 style="color: var(--primary-color); margin-bottom: 25px; font-size: 1.6em;">
            <i class="fas fa-graduation-cap"></i> Aprendamos Más sobre tu Recibo
        </h2>
        
        <div class="educacion-grid">
            <div class="tarjeta-educativa">
                <div class="tarjeta-icono">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h4>¿Por qué varía mi recibo?</h4>
                <ul>
                    <li><strong>Cambios en tu consumo:</strong> Usar más electrodomésticos o por más tiempo.</li>
                    <li><strong>Ajustes tarifarios:</strong> Osinergmin revisa trimestralmente algunos componentes.</li>
                    <li><strong>Variaciones en generación:</strong> Los costos de producir energía cambian según disponibilidad de recursos.</li>
                    <li><strong>Factores estacionales:</strong> En época de lluvias, la generación hidroeléctrica es más barata.</li>
                </ul>
            </div>
            
            <div class="tarjeta-educativa">
                <div class="tarjeta-icono">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Consejos para ahorrar energía</h4>
                <ul>
                    <li><strong>Iluminación:</strong> Usa focos LED en lugar de incandescentes (ahorra hasta 80%).</li>
                    <li><strong>Electrodomésticos:</strong> Desconéctalos cuando no los uses (evita consumo fantasma).</li>
                    <li><strong>Refrigerador:</strong> Mantenlo a 4-5°C y límpialo regularmente.</li>
                    <li><strong>Clima:</strong> Usa ventiladores en lugar de aire acondicionado cuando sea posible.</li>
                </ul>
            </div>
            
            <div class="tarjeta-educativa">
                <div class="tarjeta-icono">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h4>Tus derechos como usuario</h4>
                <ul>
                    <li><strong>Factura clara:</strong> Derecho a recibir una factura detallada y comprensible.</li>
                    <li><strong>Información previa:</strong> Ser notificado sobre cortes programados con anticipación.</li>
                    <li><strong>Reclamos:</strong> Derecho a presentar reclamos ante tu distribuidora y Osinergmin.</li>
                    <li><strong>Subsidios:</strong> Acceder al FOSE si cumples los requisitos establecidos.</li>
                </ul>
            </div>
        </div>
        
        <!-- Comparativa -->
        <div class="comparativa-consumo">
            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.3em;">
                <i class="fas fa-chart-line"></i> Comparativa de Consumo
            </h4>
            <p style="color: var(--gray-dark); margin-bottom: 15px; line-height: 1.6;">
                Tu consumo de <strong style="color: var(--primary-color);"><?php echo $totales['consumo_kwh']; ?> kWh</strong> equivale aproximadamente a:
            </p>
            <div class="equivalencias">
                <div class="equivalencia">
                    <div class="equivalencia-icono">
                        <i class="fas fa-tv"></i>
                    </div>
                    <div class="equivalencia-texto">
                        Tener un televisor LED de 50" encendido
                        <strong style="color: var(--primary-color); display: block; margin-top: 5px;">4 horas diarias durante todo el mes</strong>
                    </div>
                </div>
                <div class="equivalencia">
                    <div class="equivalencia-icono">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="equivalencia-texto">
                        Cargar tu teléfono celular
                        <strong style="color: var(--primary-color); display: block; margin-top: 5px;">todos los días durante 3 años</strong>
                    </div>
                </div>
                <div class="equivalencia">
                    <div class="equivalencia-icono">
                        <i class="fas fa-snowflake"></i>
                    </div>
                    <div class="equivalencia-texto">
                        Usar un refrigerador eficiente (A++)
                        <strong style="color: var(--primary-color); display: block; margin-top: 5px;">durante 45 días continuos</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Acciones -->
    <section class="acciones-finales">
        <h3 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.5em;">
            <i class="fas fa-share-alt"></i> Comparte este conocimiento
        </h3>
        <div class="acciones-botones">
            <button class="btn btn-primary" onclick="compartirResultados()">
                <i class="fas fa-share"></i> Compartir resultados
            </button>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir desglose
            </button>
            <a href="index.php" class="btn btn-outline">
                <i class="fas fa-redo"></i> Nueva simulación
            </a>
        </div>
        
        <div class="codigo-consulta">
            <p style="margin: 0 0 5px 0; color: var(--gray-dark);">
                <i class="fas fa-fingerprint"></i> <strong>Código de esta consulta:</strong>
            </p>
            <code><?php echo $datos['hash_calculo']; ?></code>
            <p style="margin: 10px 0 0 0; color: var(--gray); font-size: 0.9em;">
                <i class="fas fa-info-circle"></i> Guarda este código si necesitas referencia futura de esta simulación.
            </p>
        </div>
    </section>
</div>

<!-- Modal para información adicional -->
<div id="infoModal" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <div id="modalContenido"></div>
    </div>
</div>

<!-- JavaScript para gráficos y funcionalidades -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para gráficos
const datosGraficos = <?php echo json_encode($graficos); ?>;
const datosDesglose = <?php echo json_encode($desglose); ?>;
const datosTotales = <?php echo json_encode($totales); ?>;

// Información para modales
const informacionComponentes = {
    generacion: {
        titulo: "Generación de Energía Eléctrica",
        contenido: `<p>La generación es el primer eslabón de la cadena eléctrica. Corresponde al costo de producir la energía en diferentes tipos de centrales:</p>
                  <ul style="margin: 15px 0; padding-left: 20px; list-style-type: disc;">
                    <li><strong>Hidroeléctricas:</strong> Usan la fuerza del agua (más económica en época de lluvias)</li>
                    <li><strong>Térmicas:</strong> Usan gas natural, diesel o carbón (varían con precios internacionales)</li>
                    <li><strong>Renovables:</strong> Solar, eólica, biomasa (costo en desarrollo)</li>
                  </ul>
                  <p><strong>¿Por qué varía este costo?</strong> Depende de la disponibilidad de recursos naturales y los precios de combustibles.</p>`
    },
    transmision: {
        titulo: "Transmisión de Energía",
        contenido: `<p>La transmisión transporta la energía desde las centrales generadoras hasta las ciudades:</p>
                  <ul style="margin: 15px 0; padding-left: 20px; list-style-type: disc;">
                    <li><strong>Redes de alta tensión:</strong> Líneas de 220kV, 138kV que cruzan el país</li>
                    <li><strong>Subestaciones:</strong> Transforman el voltaje para su distribución</li>
                    <li><strong>Pérdidas técnicas:</strong> Energía que se disipa en el transporte</li>
                  </ul>
                  <p>Este costo cubre mantenimiento de torres, líneas y sistemas de control nacional.</p>`
    },
    distribucion: {
        titulo: "Distribución y Comercialización",
        contenido: `<p>La distribución lleva la energía hasta tu hogar y el servicio comercial:</p>
                  <ul style="margin: 15px 0; padding-left: 20px; list-style-type: disc;">
                    <li><strong>Red local:</strong> Postes, cables y transformadores de tu zona</li>
                    <li><strong>Lectura de medidores:</strong> Personal que registra tu consumo</li>
                    <li><strong>Atención al cliente:</strong> Call centers, oficinas, atención de reclamos</li>
                    <li><strong>Mantenimiento:</strong> Reparación de fallas en tu área</li>
                  </ul>
                  <p>Este componente incluye el margen regulatorio de tu empresa distribuidora.</p>`
    },
    otros: {
        cargo_fijo: {
            titulo: "Cargo Fijo",
            contenido: "Costo mínimo por tener el servicio disponible, incluso si no consumes energía. Cubre gastos administrativos, lectura del medidor y disponibilidad de la infraestructura eléctrica."
        },
        alumbrado_publico: {
            titulo: "Alumbrado Público",
            contenido: "Contribución que se transfiere directamente a tu municipalidad para el mantenimiento del alumbrado de calles, parques, plazas y espacios públicos. Este monto no es retenido por la empresa eléctrica."
        },
        reposicion: {
            titulo: "Reposición y Mantenimiento",
            contenido: "Fondo destinado a la reposición periódica de la infraestructura eléctrica (postes, cables, transformadores) y mantenimiento preventivo de la red de distribución."
        },
        aporte_ley: {
            titulo: "Aporte Ley N° 28749",
            contenido: "Contribución establecida por ley para financiar programas sociales del Estado Peruano, específicamente relacionados con el sector eléctrico y energía."
        }
    }
};

// Inicializar gráficos cuando la página cargue
document.addEventListener('DOMContentLoaded', function() {
    inicializarGraficos();
    crearLeyendas();
});

function inicializarGraficos() {
    // Gráfico de distribución (doughnut)
    const ctxDistribucion = document.getElementById('graficoDistribucion');
    if (ctxDistribucion) {
        const chartDistribucion = new Chart(ctxDistribucion, {
            type: 'doughnut',
            data: {
                labels: ['Generación', 'Transmisión', 'Distribución', 'Otros Cargos', 'IGV'],
                datasets: [{
                    data: [
                        datosGraficos.generacion,
                        datosGraficos.transmision,
                        datosGraficos.distribucion,
                        datosGraficos.otros,
                        datosGraficos.impuestos
                    ],
                    backgroundColor: [
                        '#3498db', // Azul - Generación
                        '#2ecc71', // Verde - Transmisión
                        '#e74c3c', // Rojo - Distribución
                        '#f39c12', // Naranja - Otros
                        '#9b59b6'  // Púrpura - IGV
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return `${context.label}: S/ ${context.raw.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de cadena de valor (barras)
    const ctxCadenaValor = document.getElementById('graficoCadenaValor');
    if (ctxCadenaValor) {
        const chartCadenaValor = new Chart(ctxCadenaValor, {
            type: 'bar',
            data: {
                labels: ['Generación', 'Transmisión', 'Distribución'],
                datasets: [{
                    label: 'Costo (S/)',
                    data: [
                        datosGraficos.generacion,
                        datosGraficos.transmision,
                        datosGraficos.distribucion
                    ],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.8)',
                        'rgba(46, 204, 113, 0.8)',
                        'rgba(231, 76, 60, 0.8)'
                    ],
                    borderColor: [
                        'rgb(52, 152, 219)',
                        'rgb(46, 204, 113)',
                        'rgb(231, 76, 60)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value.toFixed(2);
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Costo: S/ ${context.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    }
}

function crearLeyendas() {
    // Leyenda para gráfico de distribución
    const leyendaDistribucion = document.getElementById('leyendaDistribucion');
    if (leyendaDistribucion) {
        const total = Object.values(datosGraficos).reduce((a, b) => a + b, 0);
        const items = [
            { label: 'Generación', color: '#3498db', valor: datosGraficos.generacion },
            { label: 'Transmisión', color: '#2ecc71', valor: datosGraficos.transmision },
            { label: 'Distribución', color: '#e74c3c', valor: datosGraficos.distribucion },
            { label: 'Otros cargos', color: '#f39c12', valor: datosGraficos.otros },
            { label: 'IGV', color: '#9b59b6', valor: datosGraficos.impuestos }
        ];
        
        leyendaDistribucion.innerHTML = items.map(item => {
            const porcentaje = ((item.valor / total) * 100).toFixed(1);
            return `
                <div style="display: flex; align-items: center; gap: 12px; margin: 10px 0; padding: 10px; border-radius: 6px; background: #f8f9fa; border-left: 4px solid ${item.color};">
                    <div style="width: 20px; height: 20px; background-color: ${item.color}; border-radius: 4px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #2c3e50;">${item.label}</div>
                        <div style="font-size: 0.9em; color: #7f8c8d;">${porcentaje}% del total</div>
                    </div>
                    <div style="font-weight: bold; color: #2c3e50;">S/ ${item.valor.toFixed(2)}</div>
                </div>
            `;
        }).join('');
    }
    
    // Leyenda para gráfico de cadena de valor
    const leyendaCadenaValor = document.getElementById('leyendaCadenaValor');
    if (leyendaCadenaValor) {
        const items = [
            { label: 'Generación', color: 'rgba(52, 152, 219, 0.8)' },
            { label: 'Transmisión', color: 'rgba(46, 204, 113, 0.8)' },
            { label: 'Distribución', color: 'rgba(231, 76, 60, 0.8)' }
        ];
        
        leyendaCadenaValor.innerHTML = items.map(item => `
            <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0;">
                <div style="width: 20px; height: 20px; background-color: ${item.color}; border-radius: 3px;"></div>
                <span style="color: #2c3e50; font-weight: 500;">${item.label}</span>
            </div>
        `).join('');
    }
}

function mostrarInfoComponente(componente, tipo = 'principal') {
    let info;
    
    if (tipo === 'principal') {
        info = informacionComponentes[componente];
    } else if (tipo === 'otros') {
        info = informacionComponentes.otros[componente];
    }
    
    if (!info) return;
    
    const modal = document.getElementById('infoModal');
    const contenido = document.getElementById('modalContenido');
    
    contenido.innerHTML = `
        <h3 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.4em;">${info.titulo}</h3>
        <div style="line-height: 1.6; color: #5d6d7e; font-size: 1.05em;">
            ${info.contenido}
        </div>
        <div style="text-align: center; margin-top: 25px;">
            <button onclick="cerrarModal()" class="btn btn-primary" style="padding: 12px 30px; font-size: 1em;">
                <i class="fas fa-check"></i> Entendido
            </button>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('infoModal').style.display = 'none';
}

function compartirResultados() {
    if (navigator.share) {
        navigator.share({
            title: 'Mi Desglose de Recibo de Luz - Osinergmin',
            text: 'Acabo de entender cómo se compone mi recibo de luz usando la herramienta pedagógica de Osinergmin',
            url: window.location.href
        });
    } else {
        // Copiar enlace al portapapeles
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                alert('¡Enlace copiado al portapapeles! Puedes compartirlo con otras personas.');
            })
            .catch(() => {
                alert('Enlace para compartir:\n' + window.location.href);
            });
    }
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('infoModal');
    if (event.target === modal) {
        cerrarModal();
    }
}

// Inicializar tooltips para botones de información
document.querySelectorAll('.btn-info, .btn-info-pequeno').forEach(button => {
    button.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
    });
    button.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>

<?php
require_once 'includes/footer.php';
?>