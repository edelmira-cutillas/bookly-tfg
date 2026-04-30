<?php
session_start();
require_once '../utils/conexion.php';
require_once '../clases/Usuario.php';

require_once __DIR__ . '/../utils/conexion.php'; 

$mensaje_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = $_POST['nombre'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } else {
        $usuarioModel = new Usuario($conexion);

        if ($usuarioModel->obtenerPorEmail($email)) {
            $mensaje_error = "Este correo ya está registrado.";
        } else {
            $usuarioModel->nombre = $nombre;
            $usuarioModel->email = $email;
            $usuarioModel->password = $password;
            $usuarioModel->rol = 'usuario'; // Rol por defecto

            if ($usuarioModel->crear()) {
                header("Location: login.php?register=success");
                exit;
            } else {
                $mensaje_error = "Hubo un error al crear la cuenta. Inténtalo de nuevo.";
            }
        }
    }
}

$css = ["login_style.css", "header_login_style.css", "footer_style.css", "variables.css"];
include '../includes/header_log.php'; 
?>

<main class="content-center">
    <div class="register-card">
        <div class="register-header">
            <i class="fa-solid fa-user-plus"></i>
            <h2>Crea tu cuenta</h2>
            <p>Únete a la comunidad de lectores de Bookly</p>
        </div>

        <?php if ($mensaje_error){ ?>
            <div class="error-banner">
                <?php echo $mensaje_error; ?>
            </div>
        <?php } ?>

        <form id="registerForm" action="register.php" method="POST" class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <span class="error-msg" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña (Debe contener letras y números)</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Mín. 4 caracteres" required>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Repetir Contraseña</label>
                <div class="input-icon">
                    <i class="fa-solid fa-circle-check"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite tu contraseña" required>
                </div>
                <span class="error-msg" id="passError"></span>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-submit">Registrarme ahora</button>
                <br><br>
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
                <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
            </div>
        </form>
    </div>
</main>

<script src="../assets/js/validaciones_auth.js"></script>

<?php include '../includes/footer_log.php'; ?>