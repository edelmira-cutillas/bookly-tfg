<?php
session_start();
require_once '../utils/conexion.php';
require_once '../clases/Usuario.php';
require_once '../clases/Libro.php';
require_once '../clases/Estanteria.php';

require_once __DIR__ . '/../utils/conexion.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

$id_libro = (int)($_GET['id'] ?? 0);
$libroModel = new Libro($conexion);
$libro = $libroModel->obtenerPorId($id_libro);

if (!$libro) { die("Libro no encontrado"); }

// Comprobar si el usuario ya tiene este libro en su estantería
$estanteriaModel = new Estanteria($conexion);
$registroExistente = $estanteriaModel->obtenerPorUsuarioYLibro($_SESSION['user_id'], $id_libro);

$css = ["detalle_libro_style.css", "header_user_style.css", "footer_style.css"];
include '../includes/header_user.php';
?>

<main class="content-detalle">
    <div class="libro-container">
        <div class="libro-sidebar">
            <img src="../assets/img/portadas/<?php echo $libro->portada; ?>" class="portada-grande">
            
            <div class="acciones-estanteria">
                <h3>Mi Estantería</h3>
                <form action="gestionar_estanteria.php" method="POST">
                    <input type="hidden" name="libro_id" value="<?php echo $libro->id; ?>">
                    
                    <?php foreach (Estanteria::ESTADOS as $estado){ ?>
                        <label class="radio-container">
                            <input type="radio" name="estado" value="<?php echo $estado; ?>" 
                                <?php echo ($registroExistente && $registroExistente->estado === $estado) ? 'checked' : ($estado === 'Ninguno' ? 'checked' : ''); ?>>
                            <?php echo $estado; ?>
                        </label><br>
                    <?php } ?>
                    
                    <button type="submit" class="btn-guardar">Actualizar Estado</button>
                </form>
            </div>
        </div>

        <div class="libro-main">
            <h1><?php echo htmlspecialchars($libro->titulo); ?></h1>
            <p class="autor-nombre">por <span><?php echo htmlspecialchars($libro->autor->nombre); ?></span></p>
            
            <div class="meta-info">
                <span><i class="fa-solid fa-tag"></i> <?php echo $libro->genero->nombre; ?></span>
                <span><i class="fa-solid fa-file"></i> <?php echo $libro->paginas; ?> páginas</span>
            </div>

            <div class="sinopsis">
                <h3>Sinopsis</h3>
                <p><?php echo nl2br(htmlspecialchars($libro->descripcion)); ?></p>
            </div>

            <hr>

            <section class="social-section">
                <h3>Reseñas y Anotaciones de la comunidad</h3>
                <div class="comentario-vacio">
                    <p>No hay reseñas todavía. ¡Sé el primero en comentar!</p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include '../includes/footer_user.php'; ?>