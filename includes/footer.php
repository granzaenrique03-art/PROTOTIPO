<?php
/**
 * footer.php - Pie de página común
 */
?>
        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-university"></i> Osinergmin</h5>
                    <p>Organismo Supervisor de la Inversión en Energía y Minería</p>
                    <p>Entidad técnica especializada adscrita al Ministerio de Energía y Minas</p>
                </div>
                
                <div class="footer-section">
                    <h5><i class="fas fa-info-circle"></i> Información legal</h5>
                    <p>Av. Luis Aldana 320, Lima 15036, Perú</p>
                    <p>Central telefónica: (01) 219-3410</p>
                    <p>Horario de atención: L-V 8:30 a.m. - 5:00 p.m.</p>
                </div>
                
                <div class="footer-section">
                    <h5><i class="fas fa-link"></i> Enlaces rápidos</h5>
                    <div class="footer-links">
                        <a href="https://www.osinergmin.gob.pe/seccion/transparencia" target="_blank">
                            <i class="fas fa-eye"></i> Portal de Transparencia
                        </a>
                        <a href="https://apps.osinergmin.gob.pe/reclamos/" target="_blank">
                            <i class="fas fa-comments"></i> Sistema de reclamos
                        </a>
                        <a href="https://www.osinergmin.gob.pe/seccion/atencion-al-ciudadano/preguntas-frecuentes" target="_blank">
                            <i class="fas fa-question-circle"></i> Preguntas Frecuentes
                        </a>
                        <a href="https://www.osinergmin.gob.pe/seccion/normatividad" target="_blank">
                            <i class="fas fa-gavel"></i> Normatividad
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Osinergmin - Organismo Supervisor de la Inversión en Energía y Minería. Todos los derechos reservados.</p>
                <p class="footer-version">Mi Boleta Transparente v1.0 - Herramienta pedagógica desarrollada para promover la transparencia y educación en el sector eléctrico.</p>
                <div class="footer-legal">
                    <a href="#" onclick="alert('Términos de uso: Esta herramienta es de carácter pedagógico. Los resultados son estimaciones basadas en porcentajes promedio.');">
                        Términos de uso
                    </a> | 
                    <a href="#" onclick="alert('Política de privacidad: No almacenamos información personal. Los cálculos se realizan en tiempo real.');">
                        Política de privacidad
                    </a> | 
                    <a href="#" onclick="alert('Declaración de accesibilidad: Esta herramienta sigue estándares de accesibilidad web.');">
                        Declaración de accesibilidad
                    </a>
                </div>
            </div>
        </footer>
    </div> <!-- Cierre del container -->

    <!-- Scripts comunes -->
    <script>
        // Manejo de mensajes flash
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-ocultar mensajes flash después de 5 segundos
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 300);
                }, 5000);
            });
            
            // Validación básica de formularios
            const forms = document.querySelectorAll('form[novalidate]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    let firstInvalidField = null;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                            field.style.borderColor = '#e74c3c';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Por favor, completa todos los campos obligatorios.');
                        if (firstInvalidField) {
                            firstInvalidField.focus();
                        }
                    }
                });
            });
        });
        
        // Función para mostrar/ocultar elementos
        function toggleElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = element.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
    
    <?php if (isset($page_scripts)): ?>
    <script><?php echo $page_scripts; ?></script>
    <?php endif; ?>
    
</body>
</html>
