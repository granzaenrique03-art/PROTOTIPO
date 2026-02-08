<?php
/**
 * asistencia.php - Información sobre quejas y asistencia
 */

require_once 'includes/config.php';
$page_title = "Asistencia y Quejas";
require_once 'includes/header.php';
?>

<style>
    .assistance-container {
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }
    
    .assistance-hero {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border-radius: var(--border-radius);
        padding: 50px 30px;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .steps-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
    }
    
    .steps-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0;
    }
    
    .steps-timeline:before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gray-light);
        z-index: 1;
    }
    
    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        background: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2em;
        margin: 0 auto 15px;
    }
    
    .step-content {
        padding: 0 10px;
    }
    
    .step-content h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .when-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }
    
    .when-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 30px;
        box-shadow: var(--box-shadow);
    }
    
    .when-card.yes {
        border-top: 5px solid #27ae60;
    }
    
    .when-card.no {
        border-top: 5px solid #e74c3c;
    }
    
    .when-card h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .when-card.yes h3 {
        color: #27ae60;
    }
    
    .when-card.no h3 {
        color: #e74c3c;
    }
    
    .when-list {
        list-style: none;
        padding-left: 0;
    }
    
    .when-list li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .when-list.yes li:before {
        content: "✓";
        color: #27ae60;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .when-list.no li:before {
        content: "✗";
        color: #e74c3c;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .channels-section {
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 35px;
        margin: 40px 0;
    }
    
    .channels-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }
    
    .channel-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        text-align: center;
        transition: var(--transition);
        border: 1px solid #e9ecef;
    }
    
    .channel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .channel-icon {
        font-size: 2.5em;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }
    
    .channel-card h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .osinergmin-section {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        border-radius: var(--border-radius);
        padding: 40px;
        margin: 40px 0;
    }
    
    .osinergmin-section h3 {
        color: white;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .faq-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 35px;
        margin: 40px 0;
        box-shadow: var(--box-shadow);
    }
    
    .faq-item {
        border-bottom: 1px solid #eee;
        padding: 20px 0;
    }
    
    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    
    .faq-question h4 {
        color: var(--primary-color);
        margin: 0;
        flex: 1;
    }
    
    .faq-answer {
        padding-top: 15px;
        color: var(--gray-dark);
        line-height: 1.6;
        display: none;
    }
    
    .faq-item.active .faq-answer {
        display: block;
    }
    
    @media (max-width: 768px) {
        .steps-timeline {
            flex-direction: column;
            gap: 30px;
        }
        
        .steps-timeline:before {
            display: none;
        }
        
        .step {
            text-align: left;
            display: flex;
            gap: 20px;
        }
        
        .step-number {
            margin: 0;
            flex-shrink: 0;
        }
        
        .when-section {
            grid-template-columns: 1fr;
        }
        
        .channels-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="assistance-container">
    <!-- Hero Section -->
    <div class="assistance-hero">
        <h1 style="font-size: 2.2em; margin-bottom: 20px;">
            <i class="fas fa-headset"></i> Asistencia y Quejas
        </h1>
        <p style="font-size: 1.1em; max-width: 800px; margin: 0 auto 25px; line-height: 1.6;">
            Información clara sobre cómo presentar quejas, canales de atención y el proceso regulatorio de Osinergmin.
        </p>
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: var(--border-radius); display: inline-block;">
            <strong>¿Problemas con el servicio eléctrico?</strong> Te guiamos paso a paso.
        </div>
    </div>
    
    <!-- Proceso paso a paso -->
    <div class="steps-section">
        <h2 style="color: var(--primary-color); margin-bottom: 30px; font-size: 1.8em;">
            <i class="fas fa-directions"></i> Proceso Paso a Paso para Quejas
        </h2>
        
        <div class="steps-timeline">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Contacta a tu Distribuidora</h4>
                    <p>Primero comunícate con tu empresa distribuidora. Ellos tienen la obligación de atender tu reclamo.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Documenta tu Reclamo</h4>
                    <p>Guarda tu número de reclamo, fecha y nombre del atendente. Tienes derecho a una respuesta en 30 días.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Espera la Respuesta</h4>
                    <p>La distribuidora tiene 30 días calendario para responderte por escrito con una solución.</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h4>Si no estás conforme</h4>
                    <p>Si no te responden o la solución no te satisface, acude a Osinergmin como segunda instancia.</p>
                </div>
            </div>
        </div>
        
        <div style="background: #e8f4fc; padding: 20px; border-radius: var(--border-radius); margin-top: 30px;">
            <p style="margin: 0; color: var(--dark-color); display: flex; align-items: flex-start; gap: 15px;">
                <i class="fas fa-info-circle" style="color: var(--secondary-color); font-size: 1.2em; flex-shrink: 0; margin-top: 3px;"></i>
                <span><strong>Importante:</strong> Osinergmin es la segunda instancia regulatoria. Primero debes agotar el reclamo con tu empresa distribuidora. Saltarse este paso puede retrasar la resolución de tu caso.</span>
            </p>
        </div>
    </div>
    
    <!-- ¿Cuándo quejarse? ¿Cuándo NO? -->
    <div class="when-section">
        <div class="when-card yes">
            <h3>
                <i class="fas fa-check-circle"></i> ¿CUÁNDO SÍ debes quejarte?
            </h3>
            <ul class="when-list yes">
                <li>Cortes de luz frecuentes o prolongados sin aviso</li>
                <li>Variaciones de voltaje que dañan tus electrodomésticos</li>
                <li>Facturación incorrecta o cobros no correspondientes</li>
                <li>Maltrato o negligencia en la atención al cliente</li>
                <li>No recibir respuesta a reclamos previos</li>
                <li>Instalaciones eléctricas inseguras en tu zona</li>
                <li>Incumplimiento de plazos de reparación acordados</li>
            </ul>
        </div>
        
        <div class="when-card no">
            <h3>
                <i class="fas fa-times-circle"></i> ¿Cuándo NO debes quejarte?
            </h3>
            <ul class="when-list no">
                <li>Cortes programados con 24 horas de anticipación</li>
                <li>Problemas dentro de tu instalación interna (casa)</li>
                <li>Falta de pago y suspensión por morosidad</li>
                <li>Consumo alto por uso de nuevos electrodomésticos</li>
                <li>Ajustes tarifarios autorizados por Osinergmin</li>
                <li>Problemas por fenómenos naturales (tormentas, lluvias)</li>
                <li>Sin haber contactado primero a tu distribuidora</li>
            </ul>
        </div>
    </div>
    
    <!-- Canales de atención -->
    <div class="channels-section">
        <h2 style="color: var(--primary-color); margin-bottom: 25px; font-size: 1.8em;">
            <i class="fas fa-phone-alt"></i> Canales de Atención
        </h2>
        
        <div class="channels-grid">
            <div class="channel-card">
                <div class="channel-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h4>Línea Telefónica</h4>
                <p style="color: var(--gray); margin: 10px 0;">
                    <strong>0800-XXXXX</strong><br>
                    Atención 24/7 para emergencias
                </p>
                <div style="background: #e8f4fc; padding: 10px; border-radius: 8px; font-size: 0.9em;">
                    Lunes a Viernes: 8:00 - 18:00
                </div>
            </div>
            
            <div class="channel-card">
                <div class="channel-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h4>Chat en Línea</h4>
                <p style="color: var(--gray); margin: 10px 0;">
                    Atención instantánea por chat
                </p>
                <div style="background: #e8f4fc; padding: 10px; border-radius: 8px; font-size: 0.9em;">
                    Disponible en portal web
                </div>
            </div>
            
            <div class="channel-card">
                <div class="channel-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Correo Electrónico</h4>
                <p style="color: var(--gray); margin: 10px 0;">
                    <strong>reclamos@distribuidora.pe</strong><br>
                    Respuesta en 48 horas hábiles
                </p>
            </div>
            
            <div class="channel-card">
                <div class="channel-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Agencias Presenciales</h4>
                <p style="color: var(--gray); margin: 10px 0;">
                    Atención personalizada en oficinas
                </p>
                <div style="background: #e8f4fc; padding: 10px; border-radius: 8px; font-size: 0.9em;">
                    Con cita previa recomendada
                </div>
            </div>
        </div>
    </div>
    
    <!-- Osinergmin como segunda instancia -->
    <div class="osinergmin-section">
        <h3>
            <i class="fas fa-balance-scale"></i> Osinergmin como Segunda Instancia
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 25px;">
            <div>
                <h4 style="color: white; margin-bottom: 15px; font-size: 1.2em;">Tu rol como regulador</h4>
                <p style="opacity: 0.9; line-height: 1.6;">
                    Osinergmin supervisa que las empresas distribuidoras cumplan con las normas y atiendan adecuadamente a los usuarios. No es primera instancia para reclamos individuales.
                </p>
            </div>
            
            <div>
                <h4 style="color: white; margin-bottom: 15px; font-size: 1.2em;">¿Cuándo acudir a Osinergmin?</h4>
                <p style="opacity: 0.9; line-height: 1.6;">
                    Solo después de haber agotado el reclamo con tu distribuidora y no haber recibido respuesta satisfactoria en 30 días.
                </p>
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: var(--border-radius); margin-top: 30px;">
            <p style="margin: 0; display: flex; align-items: flex-start; gap: 15px;">
                <i class="fas fa-exclamation-triangle" style="flex-shrink: 0; font-size: 1.2em;"></i>
                <span><strong>Contacto Osinergmin:</strong> Para presentar reclamo como segunda instancia, visita <a href="https://apps.osinergmin.gob.pe/reclamos/" style="color: white; text-decoration: underline;">apps.osinergmin.gob.pe/reclamos/</a> o llama al (01) 219-3410.</span>
            </p>
        </div>
    </div>
    
    <!-- Preguntas frecuentes -->
    <div class="faq-section">
        <h2 style="color: var(--primary-color); margin-bottom: 30px; font-size: 1.8em;">
            <i class="fas fa-question-circle"></i> Preguntas Frecuentes
        </h2>
        
        <div class="faq-item active">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h4>¿Cuánto tiempo tiene mi distribuidora para responderme?</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Tu empresa distribuidora tiene un plazo máximo de 30 días calendario para responder por escrito a tu reclamo. Si no lo hace, puedes acudir a Osinergmin como segunda instancia.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h4>¿Qué documentos necesito para presentar un reclamo?</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Necesitas: 1) Copia de tu DNI, 2) Última boleta de luz, 3) Número de suministro, 4) Detalle escrito del problema, 5) Número de reclamo anterior (si aplica), 6) Evidencia fotográfica si hay daños.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h4>¿Puedo reclamar por daños a mis electrodomésticos?</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Sí, si los daños fueron causados por variaciones de voltaje de la red pública. Debes presentar: factura del equipo, certificado técnico del daño y reclamo formal a la distribuidora.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h4>¿Qué hago si hay un poste o cable caído en mi calle?</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Llama inmediatamente al número de emergencias de tu distribuidora (24/7). No te acerques a los cables caídos y alerta a tus vecinos. Esto es considerado emergencia y debe atenderse de inmediato.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h4>¿Osinergmin puede obligar a la empresa a indemnizarme?</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Osinergmin puede ordenar a la empresa que cumpla con la normativa y repare los daños causados. En casos de incumplimiento reiterado, puede imponer multas, pero las indemnizaciones civiles se gestionan por vía judicial.</p>
            </div>
        </div>
    </div>
    
    <!-- Enlaces importantes -->
    <div style="background: #f8f9fa; border-radius: var(--border-radius); padding: 30px; margin-top: 40px;">
        <h3 style="color: var(--primary-color); margin-bottom: 25px;">
            <i class="fas fa-external-link-alt"></i> Enlaces y Recursos Importantes
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <a href="https://apps.osinergmin.gob.pe/reclamos/" target="_blank" class="link-card" style="background: white;">
                <i class="fas fa-file-contract"></i>
                <span>Sistema de Reclamos en Línea - Osinergmin</span>
            </a>
            
            <a href="https://www.osinergmin.gob.pe/seccion/atencion-al-ciudadano/derechos-y-obligaciones" target="_blank" class="link-card" style="background: white;">
                <i class="fas fa-gavel"></i>
                <span>Derechos y Obligaciones del Usuario</span>
            </a>
            
            <a href="https://www.osinergmin.gob.pe/seccion/tarifas-electricas" target="_blank" class="link-card" style="background: white;">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Tarifas Eléctricas Vigentes</span>
            </a>
        </div>
    </div>
</div>

<script>
// Funcionalidad FAQ
function toggleFAQ(element) {
    const faqItem = element.closest('.faq-item');
    faqItem.classList.toggle('active');
    
    // Cerrar otros FAQs (opcional)
    const allFaqs = document.querySelectorAll('.faq-item');
    allFaqs.forEach(item => {
        if (item !== faqItem) {
            item.classList.remove('active');
        }
    });
}

// Inicializar FAQ
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        toggleFAQ(this);
    });
});

// Simular datos de contactos por distribuidora
const contactosDistribuidoras = {
    'ENEL': {
        telefono: '0800-10100',
        correo: 'reclamos@enel.pe',
        emergencias: '0800-10100'
    },
    'LUZ_SUR': {
        telefono: '0800-13120',
        correo: 'reclamos@luzdelsur.com.pe',
        emergencias: '0800-13120'
    },
    'ELECTROPERU': {
        telefono: '(01) 618-6565',
        correo: 'reclamos@electroperu.com.pe',
        emergencias: '(01) 618-6565'
    }
};

function actualizarContactos(distribuidora) {
    const contactos = contactosDistribuidoras[distribuidora] || contactosDistribuidoras.ENEL;
    // Aquí actualizarías los números en la página
    console.log('Contactos para', distribuidora, ':', contactos);
}
</script>

<?php
require_once 'includes/footer.php';
?>