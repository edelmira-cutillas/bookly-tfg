<?php
session_start();
require_once '../utils/conexion.php'; 
require_once '../clases/Usuario.php';

require_once __DIR__ . '/../utils/conexion.php'; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $usuarioModel = new Usuario($conexion);
    $usuario = $usuarioModel->obtenerPorEmail($email);

    if ($usuario && $usuario->verificarPassword($password)) {
        // Guardar datos en la sesión
        $_SESSION['user_id'] = $usuario->id;
        $_SESSION['user_nombre'] = $usuario->nombre;
        $_SESSION['user_rol'] = $usuario->rol; // 'usuario' o 'administrador'

        if ($usuario->rol === 'administrador') {
            header("Location: ../admin/home_admin.php");
        } else {
            header("Location: ../user/home_user.php");
        }
        exit;
    } else {
        $error = "Credenciales no válidas. Inténtalo de nuevo.";
    }
}

$css = ["login_style.css", "header_login_style.css", "footer_style.css", "variables.css"];
include '../includes/header_log.php'; 
?>

<main class="content-center">
    <div class="login-card">
        <div class="login-header">
            <i class="fa-solid fa-circle-user"></i>
            <h2>Identifícate</h2>
            <p>Accede a tu panel de Bookly</p>
        </div>

        <?php if ($error){ ?>
            <div class="error-banner">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php } ?>

        <form id="loginForm" action="login.php" method="POST" class="form-bookly">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Entrar ahora</button>
        </form>

        <div class="login-footer">
            <p>¿Eres nuevo? <a href="register.php">Crea tu cuenta</a></p>
            <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
        </div>
    </div>
</main>

<script src="../assets/js/validaciones_auth.js"></script>

<?php include '../includes/footer_log.php'; ?>