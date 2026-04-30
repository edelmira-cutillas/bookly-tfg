// VALIDAR EMAIL
function validarEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
}

// VALIDAR CONTRASEÑA
function validarPassword(password) {
    const passRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}$/;
    return passRegex.test(password);
}

// LOGIN
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        let isValid = true;

        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;

        if (!validarEmail(email)) {
            alert("Email no válido");
            isValid = false;
        }

        if (pass.length < 4) {
            alert("La contraseña debe tener al menos 4 caracteres");
            isValid = false;
        }

        if (!isValid) e.preventDefault();
    });
}

// REGISTER
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        let isValid = true;

        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;
        const confirmPass = document.getElementById('confirm_password').value;

        if (!validarEmail(email)) {
            document.getElementById('emailError').innerText = "Email no válido.";
            isValid = false;
        }

        if (!validarPassword(pass)) {
            document.getElementById('passError').innerText = 
                "Mínimo 4 caracteres con letras y números.";
            isValid = false;
        }

        if (pass !== confirmPass) {
            document.getElementById('passError').innerText = "No coinciden.";
            isValid = false;
        }

        if (!isValid) e.preventDefault();
    });
}