// frontend/js/login.js

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    console.log("Intentando iniciar sesión...");
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const mensajeError = document.getElementById('mensajeError');

    try {
        const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=login', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        const resultado = await resp.json();

        if (resultado.status === 'success') {
            // Redirige al dashboard correctamente
            window.location.href = '/control-patrimonial/frontend/pages/dashboard.html';
        } else {
            mensajeError.textContent = resultado.message;
            mensajeError.style.display = 'block';
        }
    } catch (error) {
        console.error("Error en login:", error);
    }
});