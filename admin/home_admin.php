<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/footer_style.css">
    
    <title>Document</title>
    
</head>
<body>
    <?php
session_start();
// Si no está logueado O no es admin, fuera
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../auth/login.php");
    exit;
}
?>
    <h1>admin</h1>
    <?php include "../includes/footer_admin.php" ?>

</html>