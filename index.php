<?php
/**
 * index.php - Portal Principal de Servicios Eléctricos
 */

require_once 'includes/config.php';
$page_title = "Portal de Servicios Eléctricos";
require_once 'includes/header.php';
?>

<!-- CSS específico para el portal -->
<style>
    .portal-hero {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: white;
        border-radius: var(--border-radius);
        padding: 50px 30px;
        margin-bottom: 40px;
        text-align: center;
        box-shadow: var(--box-shadow);
    }
    
    .portal-hero h1 {
        font-size: 2.5em;
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .portal-hero p {
        font-size: 1.2em;
        max-width: 800px;
        margin: 0 auto 25px;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }
    
    .service-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
        border-top: 5px solid var(--secondary-color);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .service-icon {
        font-size: 3.5em;
        color: var(--secondary-color);
        margin-bottom: 20px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-card h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1.4em;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-card p {
        color: var(--gray);
        line-height: 1.6;
        margin-bottom: 25px;
        flex-grow: 1;
    }
    
    .service-features {
        text-align: left;
        margin: 20px 0;
        padding-left: 0;
    }
    
    .service-features li {
        padding: 8px 0;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .service-features li:before {
        content: "✓";
        color: var(--success-color);
        font-weight: bold;
    }
    
    .btn-service {
        margin-top: auto;
        padding: 12px 25px;
        width: 100%;
        justify-content: center;
    }
    
    .quick-access {
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 30px;
        margin: 40px 0;
        border-left: 5px solid var(--warning-color);
    }
    
    .status-alert {
        background: #fff3cd;
        border-radius: var(--border-radius);
        padding: 20px;
        margin: 30px 0;
        border-left: 4px solid var(--warning-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .status-alert i {
        font-size: 1.5em;
        color: var(--warning-color);
        flex-shrink: 0;
    }
    
    .distribution-selector {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        margin: 30px 0;
        box-shadow: var(--box-shadow);
    }
    
    @media (max-width: 768px) {
        .portal-hero {
            padding: 30px 20px;
        }
        
        .portal-hero h1 {
            font-size: 2em;
        }
        
        .services-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .service-card {
            padding: 25px 20px;
        }
    }
</style>

<div class="portal-hero">
    <h1><i class="fas fa-bolt"></i> Portal de Servicios Eléctricos</h1>
    <p>Accede a todos los servicios de tu distribuidora eléctrica en un solo lugar. Información clara, trámites en línea y herramientas educativas.</p>
    
    <div class="distribution-selector">
        <h3 style="margin-bottom: 20px; color: var(--primary-color);">
            <i class="fas fa-building"></i> Selecciona tu empresa distribuidora
        </h3>
        <select id="distribuidoraSelect" style="width: 100%; padding: 15px; border-radius: var(--border-radius); border: 2px solid var(--gray-light); font-size: 1.1em;">
            <option value="">-- Elige tu distribuidora --</option>
            <option value="ENEL">Enel Distribución Perú</option>
            <option value="LUZ_SUR">Luz del Sur</option>
            <option value="ELECTROPERU">Electroperú</option>
            <option value="HIDRANDINA">Hidrandina</option>
            <option value="ELECTROCENTRO">Electrocentro</option>
            <option value="ELECTRONORTE">Electronorte</option>
        </select>
        <p style="margin-top: 15px; color: var(--gray); font-size: 0.9em;">
            <i class="fas fa-info-circle"></i> La selección de tu distribuidora personalizará toda la información mostrada
        </p>
    </div>
</div>

<!-- Alerta de estado del servicio -->
<div class="status-alert">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Estado del servicio:</strong> Actualmente no hay cortes programados en tu zona. 
        <a href="#" style="color: var(--secondary-color); margin-left: 10px;">Ver estado por distrito →</a>
    </div>
</div>

<!-- Grid de servicios -->
<div class="services-grid">
    <!-- 1. CONSULTAS -->
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-search"></i>
        </div>
        <h3>Consultas en Línea</h3>
        <p>Accede a tu información de cuenta, consumo histórico y estado de pagos sin necesidad de ir a una oficina.</p>
        
        <ul class="service-features">
            <li>Consulta tu saldo y débitos</li>
            <li>Historial de consumo detallado</li>
            <li>Descarga de recibos anteriores</li>
            <li>Estado de solicitudes y trámites</li>
        </ul>
        
        <button class="btn btn-primary btn-service" onclick="irAConsultas()">
            <i class="fas fa-sign-in-alt"></i> Ingresar a Consultas
        </button>
    </div>
    
    <!-- 2. AGENCIAS -->
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3>Agencias y Oficinas</h3>
        <p>Encuentra la agencia más cercana, horarios de atención y programa tu visita para evitar filas.</p>
        
        <ul class="service-features">
            <li>Ubicación de todas las agencias</li>
            <li>Horarios de atención actualizados</li>
            <li>Turnos virtuales programables</li>
            <li>Servicios por agencia</li>
        </ul>
        
        <button class="btn btn-secondary btn-service" onclick="irAAgencias()">
            <i class="fas fa-map"></i> Ver Agencias
        </button>
    </div>
    
    <!-- 3. MANTENIMIENTO -->
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-tools"></i>
        </div>
        <h3>Mantenimiento Programado</h3>
        <p>Información actualizada sobre cortes programados para mantenimiento preventivo de la red eléctrica.</p>
        
        <ul class="service-features">
            <li>Calendario de cortes programados</li>
            <li>Zonas afectadas por distrito</li>
            <li>Estado de trabajos en tiempo real</li>
            <li>Notificaciones personalizadas</li>
        </ul>
        
        <button class="btn btn-primary btn-service" onclick="irAMantenimiento()">
            <i class="fas fa-calendar-alt"></i> Ver Mantenimiento
        </button>
    </div>
    
    <!-- 4. NOTICIAS -->
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-newspaper"></i>
        </div>
        <h3>Noticias y Avisos</h3>
        <p>Mantente informado sobre cambios tarifarios, mejoras en el servicio y comunicados oficiales.</p>
        
        <ul class="service-features">
            <li>Avisos oficiales de tu distribuidora</li>
            <li>Cambios en tarifas eléctricas</li>
            <li>Proyectos de mejora del servicio</li>
            <li>Información del sector energético</li>
        </ul>
        
        <button class="btn btn-secondary btn-service" onclick="irANoticias()">
            <i class="fas fa-bullhorn"></i> Ver Noticias
        </button>
    </div>
    
    <!-- 5. SIMULADOR (TU PROYECTO) -->
    <div class="service-card" style="border-top-color: #27ae60;">
        <div class="service-icon">
            <i class="fas fa-calculator"></i>
        </div>
        <h3>Mi Boleta Transparente</h3>
        <p>Herramienta pedagógica para entender cada componente de tu recibo de luz y cómo se calcula tu pago.</p>
        
        <ul class="service-features">
            <li>Desglose detallado de tu recibo</li>
            <li>Explicación de cada componente</li>
            <li>Gráficos interactivos educativos</li>
            <li>Consejos para ahorrar energía</li>
        </ul>
        
        <a href="simulador/" class="btn btn-primary btn-service" style="background: linear-gradient(135deg, #27ae60, #2ecc71); border: none;">
            <i class="fas fa-chart-pie"></i> Usar Simulador
        </a>
    </div>
    
    <!-- 6. ASISTENCIA Y QUEJAS -->
    <div class="service-card" style="border-top-color: #e74c3c;">
        <div class="service-icon">
            <i class="fas fa-headset"></i>
        </div>
        <h3>Asistencia y Quejas</h3>
        <p>Información clara sobre cómo y cuándo presentar reclamos, y los canales de atención disponibles.</p>
        
        <ul class="service-features">
            <li>¿Cuándo presentar una queja?</li>
            <li>Canales de atención disponibles</li>
            <li>Proceso paso a paso de reclamos</li>
            <li>Osinergmin como segunda instancia</li>
        </ul>
        
        <button class="btn btn-primary btn-service" style="background: linear-gradient(135deg, #e74c3c, #c0392b); border: none;" onclick="irAAsistencia()">
            <i class="fas fa-comments"></i> Ver Asistencia
        </button>
    </div>
</div>

<!-- Acceso rápido -->
<div class="quick-access">
    <h3 style="color: var(--primary-color); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-bolt"></i> Acceso Rápido
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <a href="#" class="link-card" style="background: #e8f4fc;">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Pagar Recibo en Línea</span>
        </a>
        <a href="#" class="link-card" style="background: #f0f7ff;">
            <i class="fas fa-exclamation-circle"></i>
            <span>Reportar Corte de Luz</span>
        </a>
        <a href="#" class="link-card" style="background: #f8f9fa;">
            <i class="fas fa-question-circle"></i>
            <span>Preguntas Frecuentes</span>
        </a>
        <a href="#" class="link-card" style="background: #fff3cd;">
            <i class="fas fa-shield-alt"></i>
            <span>Seguridad Eléctrica</span>
        </a>
    </div>
</div>

<script>
// Funciones de navegación
function irAConsultas() {
    const distribuidora = document.getElementById('distribuidoraSelect').value;
    if (distribuidora) {
        alert('Redirigiendo a consultas de ' + distribuidora);
        // window.location.href = 'consultas/?dist=' + distribuidora;
    } else {
        alert('Por favor, selecciona tu empresa distribuidora primero');
        document.getElementById('distribuidoraSelect').focus();
    }
}

function irAAgencias() {
    const distribuidora = document.getElementById('distribuidoraSelect').value;
    if (distribuidora) {
        alert('Mostrando agencias de ' + distribuidora);
        // window.location.href = 'agencias/?dist=' + distribuidora;
    } else {
        alert('Por favor, selecciona tu empresa distribuidora primero');
    }
}

function irAMantenimiento() {
    const distribuidora = document.getElementById('distribuidoraSelect').value;
    if (distribuidora) {
        // window.location.href = 'mantenimiento/?dist=' + distribuidora;
        // Por ahora, redirigir al archivo que crearemos
        window.location.href = 'mantenimiento.php?dist=' + distribuidora;
    } else {
        alert('Por favor, selecciona tu empresa distribuidora primero');
    }
}

function irANoticias() {
    const distribuidora = document.getElementById('distribuidoraSelect').value;
    if (distribuidora) {
        alert('Mostrando noticias de ' + distribuidora);
        // window.location.href = 'noticias/?dist=' + distribuidora;
    } else {
        alert('Por favor, selecciona tu empresa distribuidora primero');
    }
}

function irAAsistencia() {
    // window.location.href = 'asistencia/';
    window.location.href = 'asistencia.php';
}

// Guardar selección en localStorage
document.getElementById('distribuidoraSelect').addEventListener('change', function() {
    localStorage.setItem('distribuidora_seleccionada', this.value);
    actualizarContenidoDistribuidora(this.value);
});

// Cargar selección anterior
window.addEventListener('DOMContentLoaded', function() {
    const distribuidoraGuardada = localStorage.getItem('distribuidora_seleccionada');
    if (distribuidoraGuardada) {
        document.getElementById('distribuidoraSelect').value = distribuidoraGuardada;
        actualizarContenidoDistribuidora(distribuidoraGuardada);
    }
});

function actualizarContenidoDistribuidora(distribuidora) {
    // Actualizar contenido basado en la distribuidora seleccionada
    console.log('Distribuidora seleccionada:', distribuidora);
    // Aquí podrías actualizar dinámicamente el contenido de la página
}
</script>

<?php
require_once 'includes/footer.php';
?>