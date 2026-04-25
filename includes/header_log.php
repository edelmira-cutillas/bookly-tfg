<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Bookly - Tu biblioteca personal</title>
    <?php if (isset($css)){ ?>
        <?php foreach ($css as $file){ ?>
            <link rel="stylesheet" href="../assets/css/<?php echo $file; ?>">
        <?php } ?>
    <?php } ?>
</head>
<body>
    <header class="main-header header-minimal">
        <div class="header-center" style="margin: 0 auto;">
            <div class="logo">
                <a href="../index.php" style="text-decoration: none; color: inherit;">
                    <span><img src="../assets/img/logo2.png" alt=""></span>
                </a>
            </div>
        </div>
    </header>