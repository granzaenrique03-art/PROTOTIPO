<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Mi Boleta Transparente - Osinergmin</title>
    
    <!-- Estilos base - IMPORTANTE: Ruta corregida -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Font Awesome - URL actualizada -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💡</text></svg>">
    
    <!-- Meta tags -->
    <meta name="description" content="Herramienta pedagógica para entender tu recibo de luz eléctrica - Osinergmin">
    <meta name="keywords" content="Osinergmin, boleta de luz, recibo eléctrico, tarifa eléctrica, Perú">
    <meta name="author" content="Osinergmin">
    
    <?php if (isset($page_css)): ?>
    <style><?php echo $page_css; ?></style>
    <?php endif; ?>
</head>
