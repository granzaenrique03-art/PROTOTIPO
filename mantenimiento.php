<?php
/**
 * mantenimiento.php - Calendario de cortes programados
 */

require_once 'includes/config.php';
$page_title = "Mantenimiento Programado";
require_once 'includes/header.php';

// Obtener distribuidora de parámetro GET o localStorage
$distribuidora = $_GET['dist'] ?? 'ENEL';
?>

<style>
    .maintenance-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }
    
    .maintenance-hero {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
        border-radius: var(--border-radius);
        padding: 40px 30px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .filters-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .calendar-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        margin-bottom: 30px;
    }
    
    .calendar-day {
        padding: 15px 10px;
        text-align: center;
        border-radius: 8px;
        background: #f8f9fa;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .calendar-date {
        padding: 20px 10px;
        text-align: center;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        cursor: pointer;
        transition: var(--transition);
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .calendar-date:hover {
        background: #e9ecef;
    }
    
    .calendar-date.has-maintenance {
        background: #fff3cd;
        border-color: #ffc107;
        position: relative;
    }
    
    .calendar-date.has-maintenance:after {
        content: "🔧";
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 0.8em;
    }
    
    .calendar-date.current-day {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }
    
    .maintenance-list {
        margin-top: 30px;
    }
    
    .maintenance-item {
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 20px;
        border-left: 5px solid #f39c12;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .maintenance-info h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
        font-size: 1.2em;
    }
    
    .maintenance-meta {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 0.9em;
    }
    
    .maintenance-status {
        background: #d4edda;
        color: #155724;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 600;
    }
    
    .maintenance-status.cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .maintenance-status.scheduled {
        background: #fff3cd;
        color: #856404;
    }
    
    .zone-affected {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        margin-top: 30px;
        box-shadow: var(--box-shadow);
    }
    
    .zone-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .zone-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid var(--secondary-color);
    }
    
    @media (max-width: 768px) {
        .calendar-grid {
            grid-template-columns: repeat(7, 1fr);
            font-size: 0.9em;
        }
        
        .calendar-date {
            padding: 15px 5px;
            min-height: 60px;
        }
        
        .maintenance-item {
            flex-direction: column;
        }
    }
</style>

<div class="maintenance-container">
    <!-- Hero Section -->
    <div class="maintenance-hero">
        <h1 style="margin-bottom: 15px; font-size: 2em;">
            <i class="fas fa-tools"></i> Mantenimiento Programado
        </h1>
        <p style="font-size: 1.1em; opacity: 0.9; max-width: 800px; margin: 0 auto;">
            Información actualizada sobre cortes programados para mantenimiento preventivo de la red eléctrica.
            Planifica tus actividades con anticipación.
        </p>
        
        <div style="margin-top: 25px; display: inline-flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
            <div style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 20px;">
                <strong>Distribuidora:</strong> 
                <span id="currentDistributor"><?php echo htmlspecialchars($distribuidora); ?></span>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 20px;">
                <strong>Región:</strong> Lima Metropolitana
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 20px;">
                <strong>Actualizado:</strong> <?php echo date('d/m/Y'); ?>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="filters-section">
        <h3 style="color: var(--primary-color); margin-bottom: 20px;">
            <i class="fas fa-filter"></i> Filtrar por zona y fecha
        </h3>
        
        <div class="filter-grid">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-color);">
                    <i class="fas fa-map-marker-alt"></i> Distrito
                </label>
                <select style="width: 100%; padding: 12px; border-radius: var(--border-radius); border: 2px solid var(--gray-light);">
                    <option value="">Todos los distritos</option>
                    <option value="LIMA">Lima</option>
                    <option value="MIRAFLORES">Miraflores</option>
                    <option value="SAN_ISIDRO">San Isidro</option>
                    <option value="SURCO">Santiago de Surco</option>
                    <option value="LA_MOLINA">La Molina</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-color);">
                    <i class="fas fa-calendar-alt"></i> Mes
                </label>
                <select style="width: 100%; padding: 12px; border-radius: var(--border-radius); border: 2px solid var(--gray-light);">
                    <option value="<?php echo date('Y-m'); ?>" selected><?php echo date('F Y'); ?></option>
                    <option value="<?php echo date('Y-m', strtotime('+1 month')); ?>"><?php echo date('F Y', strtotime('+1 month')); ?></option>
                    <option value="<?php echo date('Y-m', strtotime('+2 month')); ?>"><?php echo date('F Y', strtotime('+2 month')); ?></option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-color);">
                    <i class="fas fa-bolt"></i> Tipo de mantenimiento
                </label>
                <select style="width: 100%; padding: 12px; border-radius: var(--border-radius); border: 2px solid var(--gray-light);">
                    <option value="">Todos los tipos</option>
                    <option value="PREVENTIVO">Preventivo</option>
                    <option value="CORRECTIVO">Correctivo</option>
                    <option value="MEJORA">Mejora de red</option>
                    <option value="EMERGENCIA">Emergencia</option>
                </select>
            </div>
        </div>
        
        <button style="margin-top: 25px; padding: 12px 30px; background: var(--secondary-color); color: white; border: none; border-radius: var(--border-radius); font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; margin-left: auto;">
            <i class="fas fa-search"></i> Aplicar Filtros
        </button>
    </div>
    
    <!-- Calendario -->
    <div class="calendar-section">
        <div class="calendar-header">
            <h3 style="color: var(--primary-color); margin: 0;">
                <i class="fas fa-calendar-alt"></i> Calendario de Mantenimiento - Enero 2024
            </h3>
            <div style="display: flex; gap: 10px;">
                <button style="padding: 8px 15px; background: var(--light-color); border: 1px solid var(--gray-light); border-radius: var(--border-radius); cursor: pointer;">
                    <i class="fas fa-chevron-left"></i> Mes anterior
                </button>
                <button style="padding: 8px 15px; background: var(--secondary-color); color: white; border: none; border-radius: var(--border-radius); cursor: pointer;">
                    Hoy
                </button>
                <button style="padding: 8px 15px; background: var(--light-color); border: 1px solid var(--gray-light); border-radius: var(--border-radius); cursor: pointer;">
                    Siguiente mes <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Días de la semana -->
        <div class="calendar-grid">
            <div class="calendar-day">Lun</div>
            <div class="calendar-day">Mar</div>
            <div class="calendar-day">Mié</div>
            <div class="calendar-day">Jue</div>
            <div class="calendar-day">Vie</div>
            <div class="calendar-day">Sáb</div>
            <div class="calendar-day">Dom</div>
            
            <!-- Ejemplo de fechas (simplificado) -->
            <?php for ($i = 1; $i <= 31; $i++): ?>
                <?php 
                $hasMaintenance = in_array($i, [5, 12, 19, 26]); // Ejemplo: mantenimiento los viernes
                $isToday = $i == date('j');
                ?>
                <div class="calendar-date <?php echo $hasMaintenance ? 'has-maintenance' : ''; ?> <?php echo $isToday ? 'current-day' : ''; ?>">
                    <span><?php echo $i; ?></span>
                    <?php if ($hasMaintenance): ?>
                    <small style="font-size: 0.7em; color: #f39c12; margin-top: 5px;">Mantenimiento</small>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
        
        <!-- Leyenda -->
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 20px; height: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 4px;"></div>
                <span style="font-size: 0.9em;">Día con mantenimiento programado</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 20px; height: 20px; background: var(--secondary-color); border-radius: 4px;"></div>
                <span style="font-size: 0.9em;">Día actual</span>
            </div>
        </div>
    </div>
    
    <!-- Próximos mantenimientos -->
    <div class="maintenance-list">
        <h3 style="color: var(--primary-color); margin-bottom: 25px;">
            <i class="fas fa-list-alt"></i> Próximos Mantenimientos Programados
        </h3>
        
        <!-- Ejemplo de mantenimientos -->
        <div class="maintenance-item">
            <div class="maintenance-info">
                <h4>Mantenimiento Preventivo - Red Primaria</h4>
                <p style="color: var(--gray); margin: 10px 0;">Reemplazo de aisladores y limpieza de línea de transmisión en zona de Miraflores.</p>
                <div class="maintenance-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Viernes, 5 de Enero 2024</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>10:00 AM - 4:00 PM</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Miraflores - Zona Comercial</span>
                    </div>
                </div>
            </div>
            <div class="maintenance-status scheduled">
                Programado
            </div>
        </div>
        
        <div class="maintenance-item">
            <div class="maintenance-info">
                <h4>Mejora de Transformadores - Sector Residencial</h4>
                <p style="color: var(--gray); margin: 10px 0;">Instalación de transformadores de mayor capacidad en La Molina.</p>
                <div class="maintenance-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Viernes, 12 de Enero 2024</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>8:00 AM - 6:00 PM</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>La Molina - Urbanización Las Lagunas</span>
                    </div>
                </div>
            </div>
            <div class="maintenance-status scheduled">
                Programado
            </div>
        </div>
    </div>
    
    <!-- Zonas afectadas -->
    <div class="zone-affected">
        <h3 style="color: var(--primary-color); margin-bottom: 20px;">
            <i class="fas fa-map"></i> Zonas Afectadas en los Próximos 7 Días
        </h3>
        
        <div class="zone-list">
            <div class="zone-item">
                <h5 style="margin: 0 0 10px 0; color: var(--primary-color);">Miraflores</h5>
                <p style="margin: 0; color: var(--gray); font-size: 0.9em;">Av. Larco, Av. Benavides, Zona Comercial</p>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <span style="background: #fff3cd; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">5 Ene</span>
                    <span style="background: #d4edda; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">10:00-16:00</span>
                </div>
            </div>
            
            <div class="zone-item">
                <h5 style="margin: 0 0 10px 0; color: var(--primary-color);">La Molina</h5>
                <p style="margin: 0; color: var(--gray); font-size: 0.9em;">Urbanización Las Lagunas, Campus UNALM</p>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <span style="background: #fff3cd; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">12 Ene</span>
                    <span style="background: #d4edda; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">8:00-18:00</span>
                </div>
            </div>
            
            <div class="zone-item">
                <h5 style="margin: 0 0 10px 0; color: var(--primary-color);">San Isidro</h5>
                <p style="margin: 0; color: var(--gray); font-size: 0.9em;">Av. Javier Prado, Zona Bancaria</p>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <span style="background: #fff3cd; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">19 Ene</span>
                    <span style="background: #d4edda; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;">9:00-15:00</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Información importante -->
    <div style="background: #e8f4fc; border-radius: var(--border-radius); padding: 25px; margin-top: 30px; border-left: 5px solid var(--secondary-color);">
        <h4 style="color: var(--primary-color); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle"></i> Información Importante
        </h4>
        <ul style="color: var(--gray-dark); line-height: 1.6; padding-left: 20px;">
            <li>Los cortes programados son necesarios para garantizar la calidad y seguridad del servicio eléctrico.</li>
            <li>Se recomienda desconectar electrodomésticos sensibles antes del corte programado.</li>
            <li>El horario estimado puede variar según las condiciones de trabajo.</li>
            <li>Para emergencias durante el mantenimiento, comunicarse al <strong>0800-XXXXX</strong>.</li>
            <li>Los clientes con equipos médicos esenciales deben contactar a su distribuidora con anticipación.</li>
        </ul>
    </div>
</div>

<script>
// Simulación de datos de mantenimiento
const maintenanceData = {
    'ENEL': [
        { date: '2024-01-05', zone: 'Miraflores', type: 'PREVENTIVO', hours: '10:00-16:00' },
        { date: '2024-01-12', zone: 'La Molina', type: 'MEJORA', hours: '8:00-18:00' },
        { date: '2024-01-19', zone: 'San Isidro', type: 'PREVENTIVO', hours: '9:00-15:00' }
    ],
    'LUZ_SUR': [
        { date: '2024-01-08', zone: 'Surco', type: 'CORRECTIVO', hours: '13:00-17:00' },
        { date: '2024-01-15', zone: 'Barranco', type: 'PREVENTIVO', hours: '10:00-14:00' }
    ]
};

// Actualizar contenido según distribuidora
function updateMaintenanceContent(distributor) {
    const distributorName = {
        'ENEL': 'Enel Distribución Perú',
        'LUZ_SUR': 'Luz del Sur',
        'ELECTROPERU': 'Electroperú'
    }[distributor] || distributor;
    
    document.getElementById('currentDistributor').textContent = distributorName;
    
    // Aquí se cargarían los datos reales de la API
    console.log('Cargando mantenimientos para:', distributor);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const distributor = urlParams.get('dist') || 'ENEL';
    updateMaintenanceContent(distributor);
});
</script>

<?php
require_once 'includes/footer.php';
?>