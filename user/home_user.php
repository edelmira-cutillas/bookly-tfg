<?php
session_start();

require_once '../utils/conexion.php';
require_once '../clases/Usuario.php';
require_once '../clases/Estanteria.php';
require_once '../clases/Autor.php';
require_once '../clases/Genero.php';

require_once __DIR__ . '/../utils/conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cargar datos del usuario
$usuarioModel = new Usuario($conexion);
$usuarioActual = $usuarioModel->obtenerPorId($_SESSION['user_id']);

// Cargar libros de la estantería del usuario
$estanteriaModel = new Estanteria($conexion);
$librosLeyendo = $estanteriaModel->obtenerPorUsuario($usuarioActual->id, 'Actualmente leyendo');
$librosQuieroLeer = $estanteriaModel->obtenerPorUsuario($usuarioActual->id, 'Quiero leer');
$librosLeidos = $estanteriaModel->obtenerPorUsuario($usuarioActual->id, 'Leídos');

// Libro que está leyendo
$libroDestacado = !empty($librosLeyendo) ? $librosLeyendo[0] : null;

$css = ["home_user_style.css", "header_user_style.css", "footer_style.css", "variables.css"];
include '../includes/header_user.php';
?>

        <main class="content">
            <section class="welcome-card">
                <h2>¡Bienvenid@ <?php echo htmlspecialchars($usuarioActual->nombre); ?>!</h2>
                <p>Tu cuenta está activa desde: <?php echo date("d/m/Y", strtotime($usuarioActual->fecha_registro)); ?></p>
            </section>

            <section class="stats-grid">
                <div class="stat-box"><span>Actualmente leyendo</span> <strong><?php echo count($librosLeyendo); ?></strong></div>
                <div class="stat-box"><span>Quiero leer</span> <strong><?php echo count($librosQuieroLeer); ?></strong></div>
                <div class="stat-box"><span>Leídos</span> <strong><?php echo count($librosLeidos); ?></strong></div>
            </section>

            <section class="card-section">
                <div class="section-header"><i class="fa-solid fa-rotate-right"></i> Continuar leyendo</div><br>
                
                <?php if ($libroDestacado){ ?>
                <div class="reading-now">
                    <div class="book-info">
                        <div class="book-cover-placeholder">
                            <img src="../assets/img/portadas/<?php echo $libroDestacado->libro->portada; ?>" alt="Portada">
                        </div>
                        <div class="book-details">
                            <h3><strong>Título: </strong><?php echo htmlspecialchars($libroDestacado->libro->titulo); ?></h3>
                            <p><strong>Autor/a: </strong><?php echo htmlspecialchars($libroDestacado->libro->autor->nombre); ?></p>
                            <p><strong>Páginas: </strong><?php echo $libroDestacado->pagina_actual; ?> / <?php echo $libroDestacado->libro->paginas; ?></p>
                        </div>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="fill" style="width: <?php echo $libroDestacado->progreso_porcentaje; ?>%;"></div>
                        </div>
                        <span><?php echo $libroDestacado->progreso_porcentaje; ?>%</span>
                    </div>
                </div>
                <?php }else{ ?>
                    <p>No tienes libros en lectura actualmente. ¡Busca uno nuevo!</p>
                <?php } ?>
            </section>

            <section class="card-section">
                <div class="section-header"><i class="fa-solid fa-star"></i> Recomendaciones</div><br>
                <div class="recommendations-grid">
                    <?php foreach($librosQuieroLeer as $item){ ?>
                    <div class="rec-item">
                        <div class="thumb"><img src="../assets/img/portadas/<?php echo $item->libro->portada; ?>" alt=""></div>
                        <div class="details">
                            <p><strong><?php echo htmlspecialchars($item->libro->titulo); ?></strong></p>
                            <p><?php echo htmlspecialchars($item->libro->autor->nombre); ?></p>
                            <p><?php echo $item->libro->paginas; ?> páginas</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

<?php
include '../includes/footer_user.php';
?>