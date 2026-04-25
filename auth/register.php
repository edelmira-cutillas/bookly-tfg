<?php 
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

        <form id="registerForm" action="procesar_registro.php" method="POST" class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>
                <span class="error-msg" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Mín. 8 caracteres" required>
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

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email').value;
    const pass = document.getElementById('password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    const emailRegEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Validar Email
    if (!emailRegEx.test(email)) {
        document.getElementById('emailError').innerText = "Email no válido.";
        isValid = false;
    } else {
        document.getElementById('emailError').innerText = "";
    }

    // Validar Coincidencia de Passwords
    if (pass !== confirmPass) {
        document.getElementById('passError').innerText = "Las contraseñas no coinciden.";
        isValid = false;
    } else {
        document.getElementById('passError').innerText = "";
    }

    if (!isValid) e.preventDefault();
});
</script>

<?php include '../includes/footer_log.php'; ?>