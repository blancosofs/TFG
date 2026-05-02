/* ─────────────────────────────────────────
   Edunoly · Login — Scripts
   ───────────────────────────────────────── */

/* ── Lógica de tabs (incluye Admin) ── */
function selectTab(el) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    const rol     = el.dataset.rol;
    const isAdmin = rol === 'admin';

    document.getElementById('notice-normal').style.display = isAdmin ? 'none' : 'flex';
    document.getElementById('notice-admin').style.display  = isAdmin ? 'flex' : 'none';

    const inputUsuario = document.getElementById('usuario');
    inputUsuario.placeholder = isAdmin ? 'admin@edunoly.es' : 'Tu nombre de usuario';

    document.getElementById('hint-usuario').textContent = isAdmin
        ? 'Acceso restringido — solo administradores del sistema'
        : 'Proporcionado por el centro educativo';

    document.getElementById('row-recuerda').style.display = isAdmin ? 'none' : 'flex';
    document.querySelector('.card').classList.toggle('card-admin-mode', isAdmin);
    document.getElementById('login-error-wrap').innerHTML = '';
}

/* ── Toggle visibilidad contraseña ── */
function togglePw() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>';
    }
}

/* ── Mostrar error en formulario ── */
function mostrarError(msg) {
    const wrap = document.getElementById('login-error-wrap');
    wrap.innerHTML = '<div id="login-error" class="login-error">' + msg + '</div>';
}

/* ── Login principal ── */
async function doLogin() {
    const usuario   = document.getElementById('usuario').value.trim();
    const password  = document.getElementById('password').value;
    const btn       = document.getElementById('btn-acceder');
    const activeTab = document.querySelector('.tab.active');
    const rol       = activeTab ? activeTab.dataset.rol : 'coordinador';

    document.getElementById('login-error-wrap').innerHTML = '';

    if (!usuario || !password) {
        mostrarError('Introduce usuario y contraseña.');
        return;
    }

    const textoOrig = btn.innerHTML;
    btn.textContent = 'Accediendo…';
    btn.disabled    = true;

    try {
        const res  = await fetch('/api/login', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
            },
            body: JSON.stringify({ usuario, password }),
        });

        const data = await res.json();

        if (data.error) {
            mostrarError(data.error);
        } else {
            const rolBack = data.usuario ? data.usuario.rol : rol;

            if (rol === 'admin' && rolBack !== 'admin') {
                mostrarError('Esta cuenta no tiene permisos de administrador.');
            } else {
                switch (rolBack) {
                    case 'admin':       window.location.href = '/perfil-admin';  break;
                    case 'docente':     window.location.href = '/calendario';    break;
                    case 'coordinador': window.location.href = '/dashboard';     break;
                    case 'familiar':    window.location.href = '/perfil-familia'; break;
                    default:            window.location.href = '/calendario';
                }
            }
        }
    } catch (e) {
        mostrarError('Error de conexión. Inténtalo de nuevo.');
    }

    btn.innerHTML = textoOrig;
    btn.disabled  = false;
}

/* ── Enviar con Enter desde el campo contraseña ── */
document.addEventListener('DOMContentLoaded', function () {
    const pw = document.getElementById('password');
    if (pw) pw.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doLogin();
    });
});
