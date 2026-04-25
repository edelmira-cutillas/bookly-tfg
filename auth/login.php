<?php 
$css = ["login_style.css", "header_login_style.css", "footer_style.css", "variables.css"];
include '../includes/header_log.php'; 
?>

<main class="content-center">
    <div class="login-card">
        <div class="login-header">
            <i class="fa-solid fa-lock"></i>
            <h2>Identifícate</h2>
            <p>Accede a tu biblioteca personal</p>
        </div>

        <form id="loginForm" action="procesar_login.php" method="POST" class="form-bookly">
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>
                <span class="error-msg" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <span class="error-msg" id="passError"></span>
            </div>

            <button type="submit" class="btn-submit">Entrar a Bookly</button>
        </form>

        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
            <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
        </div>
    </div>
</main>

<?php include '../includes/footer_log.php'; ?>