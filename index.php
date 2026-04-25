<?php 
//session_start(); 

$css = ["index_style.css", "footer_style.css", "variables.css"];
include 'includes/header_base.php'; 
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
        </main>
    </div>

<?php 
include 'includes/footer_base.php'; 
?>