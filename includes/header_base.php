<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Bookly - Tu biblioteca personal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.arrows.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.dots.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.autoscroll.css" />
    <?php if (isset($css)){ ?>
        <?php foreach ($css as $file){ ?>
            <link rel="stylesheet" href="assets/css/<?php echo $file; ?>">
        <?php } ?>
    <?php } ?>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <div class="menu-btn" id="menuToggle"><i class="fa-solid fa-bars"></i></div>
            <div class="logo"><span><img src="assets/img/logo.png" alt=""></span> Bookly</div>
        </div>
        <div class="header-right">
            <a href="auth/login.php" class="btn-login">Iniciar Sesión</a>
            <a href="auth/register.php" class="btn-register">Registrarse</a>
        </div>
    </header>