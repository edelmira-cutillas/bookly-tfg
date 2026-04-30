<?php
session_start();

require_once '../utils/conexion.php';
require_once '../clases/Usuario.php';
require_once '../clases/Libro.php';
require_once '../clases/Genero.php';

require_once __DIR__ . '/../utils/conexion.php';

// Redirigir si no hay sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuarioModel  = new Usuario($conexion);
$usuarioActual = $usuarioModel->obtenerPorId($_SESSION['user_id']);

$generoModel = new Genero($conexion);
$generos     = $generoModel->obtenerTodos();

// Paginación
$librosPorPagina = 8; // Número de tarjetas por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
$offset = ($paginaActual - 1) * $librosPorPagina;

$libroModel  = new Libro($conexion);
$resultados  = [];
$totalLibros = 0;
$totalPaginas = 0;
$buscado     = false;

$termino  = trim($_GET['termino'] ?? '');
$generoId = (int) ($_GET['genero_id'] ?? 0);

if (isset($_GET['buscar']) || isset($_GET['pagina'])) {
    $resultados = $libroModel->buscarConPaginacion($termino, $generoId, $librosPorPagina, $offset);
    $totalLibros = $libroModel->contarResultadosBusqueda($termino, $generoId);
    
    $totalPaginas = ceil($totalLibros / $librosPorPagina);
    $buscado = true;
}

$css = ["variables.css", "header_user_style.css", "search_user_style.css", "footer_style.css"];
include '../includes/header_user.php';
?>

<main class="content">

    <section class="welcome-card">
        <h2>Descubrir nuevas lecturas</h2>
        <p>Busca por título, autor o filtra por género en nuestra biblioteca global.</p>
    </section>

    <section class="search-section">
        <form method="GET" action="search_user.php" class="search-form">
            <div class="search-bar">
                <input
                    type="text"
                    name="termino"
                    placeholder="Título o autor..."
                    value="<?php echo htmlspecialchars($termino); ?>"
                >
                <select name="genero_id">
                    <option value="0">Todos los géneros</option>
                    <?php foreach ($generos as $g){ ?>
                        <option value="<?php echo $g->id; ?>"
                            <?php echo ($generoId === (int)$g->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($g->nombre); ?>
                        </option>
                    <?php } ?>
                </select>
                <button type="submit" name="buscar">
                    <i class="fa-solid fa-magnifying-glass"></i> BUSCAR
                </button>
            </div>
        </form>
    </section>

    <?php if ($buscado){ ?>
        <section class="results-container">
            <div class="section-header">
                <i class="fa-solid fa-book"></i>
                <span>Se han encontrado <strong><?php echo $totalLibros; ?></strong> libros</span>
            </div>

            <?php if (!empty($resultados)){ ?>
                <div class="books-grid">
                    <?php foreach ($resultados as $libro){ ?>
                        <a href="detalle_libro.php?id=<?php echo $libro->id; ?>" class="book-card-link">
                            <article class="book-card">
                                <div class="book-cover">
                                    <img src="../assets/img/portadas/<?php echo htmlspecialchars($libro->portada ?? 'default.jpg'); ?>" alt="Portada de <?php echo htmlspecialchars($libro->titulo); ?>">
                                </div>
                                <div class="book-info">
                                    <h4><?php echo htmlspecialchars($libro->titulo); ?></h4>
                                    <p class="autor"><?php echo htmlspecialchars($libro->autor->nombre ?? 'Autor desconocido'); ?></p>
                                    <p class="paginas"><i class="fa-regular fa-file"></i> <?php echo $libro->paginas ?? '—'; ?> páginas</p>
                                    <span class="genero"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($libro->genero->nombre ?? 'General'); ?></span>
                                </div>
                            </article>
                        </a>
                    <?php } ?>
                </div>

                <?php if ($totalPaginas > 1){ ?>
                    <nav class="pagination">
                        <?php 
                        $params = "&termino=" . urlencode($termino) . "&genero_id=" . $generoId . "&buscar=";
                        ?>

                        <?php if ($paginaActual > 1){ ?>
                            <a href="?pagina=<?php echo $paginaActual - 1 . $params; ?>" class="page-link prev">
                                <i class="fa-solid fa-arrow-left"></i> Anterior
                            </a>
                        <?php } ?>

                        <div class="page-numbers">
                            <?php for ($i = 1; $i <= $totalPaginas; $i++){ ?>
                                <a href="?pagina=<?php echo $i . $params; ?>" 
                                   class="page-number <?php echo ($i === $paginaActual) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php } ?>
                        </div>

                        <?php if ($paginaActual < $totalPaginas){ ?>
                            <a href="?pagina=<?php echo $paginaActual + 1 . $params; ?>" class="page-link next">
                                Siguiente <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        <?php } ?>
                    </nav>
                <?php } ?>

            <?php }else{ ?>
                <div class="no-results">
                    <p><i class="fa-solid fa-circle-exclamation"></i> No se han encontrado libros que coincidan con tu búsqueda.</p>
                </div>
            <?php } ?>
        </section>
    <?php } ?>

</main>

<div id="sidebarOverlay" class="sidebar-overlay"></div>

<?php include '../includes/footer_user.php'; ?>