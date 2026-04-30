<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookly - Mi Biblioteca</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php if (isset($css)){ ?>
        <?php foreach ($css as $file){ ?>
            <link rel="stylesheet" href="../assets/css/<?php echo $file; ?>">
        <?php } ?>
    <?php } ?>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <div class="menu-btn" id="menuToggle"><i class="fa-solid fa-bars"></i></div>
            <div class="logo">
                <span><img src="../assets/img/logo.png" alt="Bookly"></span> 
                Bookly
            </div>
            <div class="search-bar-desktop">
                <form action="search_user.php">
                    <input id="descubrir" type="submit" value="Descubrir nuevas lecturas">
                </form>
            </div>
        </div>
        <div class="header-right">
            <a href="home_user.php" class="home-icon"><i class="fa-solid fa-house"></i></a>
            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            
            <div class="user-profile" id="userMenuTrigger">
    <i class="fa-solid fa-user-circle" style="font-size: 1.4rem;"></i> 
    <span class="user-name">
        <?php echo htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario'); ?>
    </span>

    <div class="user-dropdown" id="userDropdown">
        <ul>
            <li>
                <a href="perfil_user.php">
                    <i class="fa-solid fa-id-card"></i> Mi Perfil
                </a>
            </li>
            <li class="logout-item">
                <a href="../auth/logout.php">
                    <i class="fa-solid fa-power-off"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</div>
        </div>
    </header>

    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <h3>ESTANTERÍAS</h3><br>
            <ul>
                <li><i class="fa-solid fa-folder"></i> Leyendo</li>
                <li><i class="fa-solid fa-folder"></i> Quiero leer</li>
                <li><i class="fa-solid fa-folder"></i> Leídos</li>
            </ul>
            <hr>
            <ul>
                <li><i class="fa-solid fa-circle"></i> Anotaciones</li>
                <li><i class="fa-solid fa-circle"></i> Reseñas</li>
            </ul>
            <ul class="logoFinal">
                <img src="../recursos/img/logo2.png" alt="Logo Bookly">
            </ul>
        </aside>

<script src="../assets/js/desplegable_header_user.js"></script>