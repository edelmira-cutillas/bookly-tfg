<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header class="main-header header-user">
        <div class="header-left">
            <div class="menu-btn" id="menuToggle"><i class="fa-solid fa-bars"></i></div>
            <div class="logo"><span><img src="../recursos/img/logo.png" alt=""></span> Bookly</div>
            <div class="search-bar-desktop">
                <form action="buscar.php" method="GET">
                    <input type="text" name="q" placeholder="Buscar en mi biblioteca...">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>
        <div class="header-right">
            <span class="notification-icon"><i class="fa-solid fa-bell"></i></span>
            <div class="user-profile">
                <span class="user-name"></span>
                <i class="fa-solid fa-circle-user"></i>
            </div>
        </div>
    </header>