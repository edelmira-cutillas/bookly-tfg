<?php 
$css = ["index_style.css", "footer_style.css", "variables.css"];
include 'includes/header_base.php'; 

$dir = "assets/img/portadas/";
$images = array_diff(scandir($dir), array('.', '..'));
$images = array_filter($images, function($file) {
    return preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $file);
});
$images = array_values($images);
shuffle($images);
?>

<body>
<div class="app-container">

    <aside class="sidebar" id="sidebar">
        <h3>DESCUBRE</h3><br>
        <ul>
            <li><i class="fa-solid fa-fire"></i> Tendencias</li>
        </ul>
    </aside>

    <main class="content">

        <section class="welcome-card hero-section">
            <h2>Bienvenido a Bookly</h2>
            <p>Explora nuestro catálogo público.</p>
        </section>

        <section class="carousel-section">
            <div class="f-carousel" id="myCarousel">
                <?php foreach ($images as $img): ?>
                    <div class="f-carousel__slide">
                        <img src="<?php echo $dir . $img; ?>" alt="imagen">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>
</div>

<script src="assets/js/slideshow.js"></script>

<?php 
include 'includes/footer_base.php'; 
?>