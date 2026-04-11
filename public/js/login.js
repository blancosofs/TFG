/* ─────────────────────────────────────────
   Edunoly · Login — Scripts
   ───────────────────────────────────────── */

/**
 * Marca el tab de perfil seleccionado como activo.
 */
function selectTab(el) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

/**
 * Alterna la visibilidad del campo de contraseña.
 */
function togglePw() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5
                   12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0
                   0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0
                   01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65
                   3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0
                   10-4.243-4.243m4.242 4.242L9.88 9.88"/>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5
                   12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0
                   .639C20.577 16.49 16.64 19.5 12 19.5c-4.638
                   0-8.573-3.007-9.963-7.178z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
    }
}

/**
 * Muestra un mensaje de error en el formulario.
 */
function mostrarError(msg) {
    let errEl = document.getElementById('login-error');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.id = 'login-error';
        errEl.className = 'login-error';
        document.querySelector('.btn-primary').insertAdjacentElement('beforebegin', errEl);
    }
    errEl.textContent   = msg;
    errEl.style.display = 'block';
}

function ocultarError() {
    const errEl = document.getElementById('login-error');
    if (errEl) errEl.style.display = 'none';
}

/**
 * Envía las credenciales al backend.
 * Si el usuario es docente  → calendario.html
 * Otros perfiles            → sus páginas cuando existan
 */
async function doLogin() {
    const usuario  = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value;
    const btn      = document.querySelector('.btn-primary');

    ocultarError();

    if (!usuario || !password) {
        mostrarError('Introduce usuario y contraseña.');
        return;
    }

    btn.textContent = 'Accediendo…';
    btn.disabled    = true;

    try {
        const res  = await fetch('/api/login', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ usuario, password }),
        });

        const data = await res.json();

        if (data.error) {
            mostrarError(data.error);
        } else {
            // Redirige según el rol devuelto por el backend
            switch (data.usuario.rol) {
                case 'docente':
                    window.location.href = 'calendario.html';
                    break;
                case 'coordinador':
                    window.location.href = 'dashboard.html';   // pendiente
                    break;
                case 'familiar':
                    window.location.href = 'familiar.html';    // pendiente
                    break;
                default:
                    window.location.href = 'calendario.html';
            }
        }
    } catch (e) {
        mostrarError('Error de conexión. Inténtalo de nuevo.');
    }

    btn.textContent = 'Acceder';
    btn.disabled    = false;
}

// Enviar con la tecla Enter desde el campo contraseña
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('password')
        .addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });
});
