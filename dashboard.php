<?php
session_start();

// Seguridad: si no ha iniciado sesión, redirigir
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tienda</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="header">
        <h1>IMPORTACIONES EL HUECO</h1>
        <img src="img/logo.png" alt="Logo Empresa" class="logo">
    </div>

    <div class="navbar">
        <div class="navbar-logo">
            <img src="img/logo.png" alt="Logo Empresa">
            <span><?php echo isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Invitado'; ?></span>
        </div>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
        <ul class="navbar-menu">
            <li><a href="productos/listar.php">Productos</a></li>
            <li><a href="usuarios/listar_usuarios.php">Usuarios</a></li>
            <li><a href="ventas/listar_ventas.php">Ver lista de ventas</a></li>
            <li><a href="historial_ventas.php">Historial de ventas</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
        <?php endif; ?>

        <?php if ($_SESSION['rol'] === 'vendedor'): ?>
        <ul class="navbar-menu">
            <li><a href="productos/listar.php">Productos</a></li>
            <li><a href="ventas/crear_venta.php">Ventas</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
        <?php endif; ?>
    </div>
            <!-- SLIDER DE IMÁGENES -->
   
    <div class="slider-container">
    <div class="slider-wrapper">
        <div class="slider" id="slider">
        <div class="slide"><img src="img/img1.png" alt="Imagen 1"></div>
        <div class="slide"><img src="img/img2.png" alt="Imagen 2"></div>
        <div class="slide"><img src="img/img3.png" alt="Imagen 3"></div>
        <div class="slide"><img src="img/img4.png" alt="Imagen 4"></div>
        <div class="slide"><img src="img/img5.png" alt="Imagen 5"></div>
        </div>
    </div>
    <div class="slider-controls">
        <span class="prev" onclick="prevSlide()">&#10094;</span>
        <span class="next" onclick="nextSlide()">&#10095;</span>
    </div>
        <div class="slider-dots" id="dots"></div>
        </div>

    <div class="vision-mision">
        <div class="bloque">
            <h3>Visión</h3>
            <p>
                Brindar productos importados de calidad a precios accesibles, satisfaciendo las necesidades de los
                clientes en Cerro de Pasco y zonas aledañas, con un servicio rápido, confiable y personalizado. 
            </p>
        </div>
        <div class="bloque">
            <h3>Misión</h3>
            <p>
                Brindar productos importados de calidad a precios accesibles, satisfaciendo las necesidades de los
                clientes en Cerro de Pasco y zonas aledañas, con un servicio rápido, confiable y personalizado.
            </p>
        </div>
    </div>

 <script>
  const slider = document.getElementById('slider');
  const totalSlides = slider.children.length;
  const dotsContainer = document.getElementById('dots');
  let currentSlide = 0;

  // Crear los puntos de navegación
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement('span');
    dot.onclick = () => goToSlide(i);
    dotsContainer.appendChild(dot);
  }

  const dots = dotsContainer.querySelectorAll('span');

  function updateSlider() {
    const slideWidth = slider.querySelector('.slide').offsetWidth;
    slider.style.transform = `translateX(-${currentSlide * slideWidth}px)`;

    dots.forEach(dot => dot.classList.remove('active'));
    dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlider();
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSlider();
  }

  function goToSlide(index) {
    currentSlide = index;
    updateSlider();
  }

  // Inicializar slider
  updateSlider();

  // Cambio automático cada 5 segundos
  setInterval(() => {
    nextSlide();
  }, 5000);

  // Reajustar si el tamaño cambia (responsive)
  window.addEventListener('resize', updateSlider);
</script>


</body>

</html>
