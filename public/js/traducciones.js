/* ══════════════════════════════════════════════════════════════
   Edunoly · traducciones.js
   Sistema de internacionalización (i18n).
   Incluir en TODAS las páginas después de temas.js.
   
   Uso: añade data-i18n="clave" a cualquier elemento HTML
   y este script lo traducirá automáticamente al idioma guardado.
   
   Ejemplo:
     <span data-i18n="nav.inicio">Inicio</span>
══════════════════════════════════════════════════════════════ */

const TRADUCCIONES = {

    es: {
        /* Navegación */
        'nav.inicio':        'Inicio',
        'nav.contacto':      'Contacto',
        'nav.configuracion': 'Configuración',
        'nav.iniciarSesion': 'Iniciar Sesión',
        'nav.miPerfil':      'Mi Perfil',
        'nav.cerrarSesion':  'Cerrar sesión',

        /* Login */
        'login.titulo':      'Acceso a la plataforma',
        'login.subtitulo':   'Selecciona tu perfil e introduce tus credenciales',
        'login.usuario':     'Usuario',
        'login.password':    'Contraseña',
        'login.mantener':    'Mantener sesión iniciada',
        'login.olvidaste':   '¿Olvidaste la contraseña?',
        'login.acceder':     'Acceder',
        'login.coordinador': 'Coordinador',
        'login.docente':     'Docente',
        'login.familiar':    'Familiar',

        /* Configuración */
        'config.titulo':          'Configuración',
        'config.subtitulo':       'Personaliza la apariencia de Edunoly según tus preferencias.',
        'config.estiloVisual':    '🎨 Estilo visual',
        'config.accesibilidad':   '♿ Accesibilidad',
        'config.idioma':          '🌐 Idioma',
        'config.notificaciones':  '🔔 Notificaciones',
        'config.guardar':         'Guardar cambios',
        'config.cancelar':        'Cancelar',
        'config.animaciones':     'Animaciones reducidas',
        'config.fuente':          'Tamaño de fuente',
        'config.contraste':       'Alto contraste',
        'config.enlaces':         'Subrayar enlaces',
        'config.recordatorio':    'Recordatorio de clases',
        'config.cambios':         'Cambios en el horario',
        'config.faltas':          'Nuevas faltas de asistencia',
        'config.sonido':          'Sonido de notificaciones',

        /* Perfil */
        'perfil.titulo':          'Mi Perfil',
        'perfil.datosPersonales': '👤 Datos personales',
        'perfil.infoProfesional': '🎓 Información profesional',
        'perfil.seguridad':       '🔒 Seguridad',
        'perfil.actividad':       '🕐 Actividad reciente',
        'perfil.editar':          '✏️ Editar',
        'perfil.guardar':         '💾 Guardar cambios',
        'perfil.cancelar':        'Cancelar',
        'perfil.nombre':          'Nombre',
        'perfil.apellidos':       'Apellidos',
        'perfil.email':           'Correo electrónico',
        'perfil.telefono':        'Teléfono',

        /* General */
        'general.cargando':  'Cargando…',
        'general.error':     'Error de conexión.',
        'general.guardado':  '✓ Guardado correctamente',
    },

    en: {
        'nav.inicio':        'Home',
        'nav.contacto':      'Contact',
        'nav.configuracion': 'Settings',
        'nav.iniciarSesion': 'Log in',
        'nav.miPerfil':      'My Profile',
        'nav.cerrarSesion':  'Log out',

        'login.titulo':      'Platform access',
        'login.subtitulo':   'Select your profile and enter your credentials',
        'login.usuario':     'Username',
        'login.password':    'Password',
        'login.mantener':    'Keep me logged in',
        'login.olvidaste':   'Forgot your password?',
        'login.acceder':     'Log in',
        'login.coordinador': 'Coordinator',
        'login.docente':     'Teacher',
        'login.familiar':    'Parent',

        'config.titulo':          'Settings',
        'config.subtitulo':       'Customize Edunoly to your preferences.',
        'config.estiloVisual':    '🎨 Visual style',
        'config.accesibilidad':   '♿ Accessibility',
        'config.idioma':          '🌐 Language',
        'config.notificaciones':  '🔔 Notifications',
        'config.guardar':         'Save changes',
        'config.cancelar':        'Cancel',
        'config.animaciones':     'Reduced motion',
        'config.fuente':          'Font size',
        'config.contraste':       'High contrast',
        'config.enlaces':         'Underline links',
        'config.recordatorio':    'Class reminders',
        'config.cambios':         'Schedule changes',
        'config.faltas':          'New absences',
        'config.sonido':          'Notification sounds',

        'perfil.titulo':          'My Profile',
        'perfil.datosPersonales': '👤 Personal data',
        'perfil.infoProfesional': '🎓 Professional info',
        'perfil.seguridad':       '🔒 Security',
        'perfil.actividad':       '🕐 Recent activity',
        'perfil.editar':          '✏️ Edit',
        'perfil.guardar':         '💾 Save changes',
        'perfil.cancelar':        'Cancel',
        'perfil.nombre':          'First name',
        'perfil.apellidos':       'Last name',
        'perfil.email':           'Email address',
        'perfil.telefono':        'Phone',

        'general.cargando':  'Loading…',
        'general.error':     'Connection error.',
        'general.guardado':  '✓ Saved successfully',
    },

    ca: {
        'nav.inicio':        'Inici',
        'nav.contacto':      'Contacte',
        'nav.configuracion': 'Configuració',
        'nav.iniciarSesion': 'Iniciar sessió',
        'nav.miPerfil':      'El meu perfil',
        'nav.cerrarSesion':  'Tancar sessió',

        'login.titulo':      'Accés a la plataforma',
        'login.subtitulo':   'Selecciona el teu perfil i introdueix les credencials',
        'login.usuario':     'Usuari',
        'login.password':    'Contrasenya',
        'login.mantener':    'Mantenir la sessió iniciada',
        'login.olvidaste':   'Has oblidat la contrasenya?',
        'login.acceder':     'Accedir',
        'login.coordinador': 'Coordinador',
        'login.docente':     'Docent',
        'login.familiar':    'Familiar',

        'config.titulo':          'Configuració',
        'config.subtitulo':       'Personalitza Edunoly segons les teves preferències.',
        'config.estiloVisual':    '🎨 Estil visual',
        'config.accesibilidad':   '♿ Accessibilitat',
        'config.idioma':          '🌐 Idioma',
        'config.notificaciones':  '🔔 Notificacions',
        'config.guardar':         'Desar canvis',
        'config.cancelar':        'Cancel·lar',
        'config.animaciones':     'Animacions reduïdes',
        'config.fuente':          'Mida de lletra',
        'config.contraste':       'Alt contrast',
        'config.enlaces':         'Subratllar enllaços',
        'config.recordatorio':    'Recordatori de classes',
        'config.cambios':         'Canvis a l\'horari',
        'config.faltas':          'Noves faltes',
        'config.sonido':          'So de notificacions',

        'perfil.titulo':          'El meu perfil',
        'perfil.datosPersonales': '👤 Dades personals',
        'perfil.infoProfesional': '🎓 Informació professional',
        'perfil.seguridad':       '🔒 Seguretat',
        'perfil.actividad':       '🕐 Activitat recent',
        'perfil.editar':          '✏️ Editar',
        'perfil.guardar':         '💾 Desar canvis',
        'perfil.cancelar':        'Cancel·lar',
        'perfil.nombre':          'Nom',
        'perfil.apellidos':       'Cognoms',
        'perfil.email':           'Correu electrònic',
        'perfil.telefono':        'Telèfon',

        'general.cargando':  'Carregant…',
        'general.error':     'Error de connexió.',
        'general.guardado':  '✓ Desat correctament',
    },

    eu: {
        'nav.inicio':        'Hasiera',
        'nav.contacto':      'Kontaktua',
        'nav.configuracion': 'Ezarpenak',
        'nav.iniciarSesion': 'Saioa hasi',
        'nav.miPerfil':      'Nire profila',
        'nav.cerrarSesion':  'Saioa itxi',

        'login.titulo':      'Plataformara sarrera',
        'login.subtitulo':   'Hautatu zure profila eta sartu zure kredentzialak',
        'login.usuario':     'Erabiltzailea',
        'login.password':    'Pasahitza',
        'login.mantener':    'Saioa aktibo mantendu',
        'login.olvidaste':   'Pasahitza ahaztu duzu?',
        'login.acceder':     'Sartu',
        'login.coordinador': 'Koordinatzailea',
        'login.docente':     'Irakaslea',
        'login.familiar':    'Familia',

        'config.titulo':          'Ezarpenak',
        'config.subtitulo':       'Pertsonalizatu Edunoly zure lehentasunen arabera.',
        'config.estiloVisual':    '🎨 Itxura',
        'config.accesibilidad':   '♿ Irisgarritasuna',
        'config.idioma':          '🌐 Hizkuntza',
        'config.notificaciones':  '🔔 Jakinarazpenak',
        'config.guardar':         'Aldaketak gorde',
        'config.cancelar':        'Utzi',
        'config.animaciones':     'Animazio gutxiago',
        'config.fuente':          'Letra tamaina',
        'config.contraste':       'Kontraste altua',
        'config.enlaces':         'Estekak azpimarratu',
        'config.recordatorio':    'Klase gogorarazlea',
        'config.cambios':         'Ordutegi aldaketak',
        'config.faltas':          'Falta berriak',
        'config.sonido':          'Jakinarazpen soinua',

        'perfil.titulo':          'Nire profila',
        'perfil.datosPersonales': '👤 Datu pertsonalak',
        'perfil.infoProfesional': '🎓 Informazio profesionala',
        'perfil.seguridad':       '🔒 Segurtasuna',
        'perfil.actividad':       '🕐 Azken jarduera',
        'perfil.editar':          '✏️ Editatu',
        'perfil.guardar':         '💾 Aldaketak gorde',
        'perfil.cancelar':        'Utzi',
        'perfil.nombre':          'Izena',
        'perfil.apellidos':       'Abizenak',
        'perfil.email':           'Posta elektronikoa',
        'perfil.telefono':        'Telefonoa',

        'general.cargando':  'Kargatzen…',
        'general.error':     'Konexio errorea.',
        'general.guardado':  '✓ Gordeta',
    },

    gl: {
        'nav.inicio':        'Inicio',
        'nav.contacto':      'Contacto',
        'nav.configuracion': 'Configuración',
        'nav.iniciarSesion': 'Iniciar sesión',
        'nav.miPerfil':      'O meu perfil',
        'nav.cerrarSesion':  'Pechar sesión',

        'login.titulo':      'Acceso á plataforma',
        'login.subtitulo':   'Selecciona o teu perfil e introduce as túas credenciais',
        'login.usuario':     'Usuario',
        'login.password':    'Contrasinal',
        'login.mantener':    'Manter a sesión iniciada',
        'login.olvidaste':   'Esqueciche o contrasinal?',
        'login.acceder':     'Acceder',
        'login.coordinador': 'Coordinador',
        'login.docente':     'Docente',
        'login.familiar':    'Familiar',

        'config.titulo':          'Configuración',
        'config.subtitulo':       'Personaliza Edunoly segundo as túas preferencias.',
        'config.estiloVisual':    '🎨 Estilo visual',
        'config.accesibilidad':   '♿ Accesibilidade',
        'config.idioma':          '🌐 Idioma',
        'config.notificaciones':  '🔔 Notificacións',
        'config.guardar':         'Gardar cambios',
        'config.cancelar':        'Cancelar',
        'config.animaciones':     'Animacións reducidas',
        'config.fuente':          'Tamaño de letra',
        'config.contraste':       'Alto contraste',
        'config.enlaces':         'Subliñar ligazóns',
        'config.recordatorio':    'Recordatorio de clases',
        'config.cambios':         'Cambios no horario',
        'config.faltas':          'Novas faltas',
        'config.sonido':          'Son de notificacións',

        'perfil.titulo':          'O meu perfil',
        'perfil.datosPersonales': '👤 Datos persoais',
        'perfil.infoProfesional': '🎓 Información profesional',
        'perfil.seguridad':       '🔒 Seguridade',
        'perfil.actividad':       '🕐 Actividade recente',
        'perfil.editar':          '✏️ Editar',
        'perfil.guardar':         '💾 Gardar cambios',
        'perfil.cancelar':        'Cancelar',
        'perfil.nombre':          'Nome',
        'perfil.apellidos':       'Apelidos',
        'perfil.email':           'Correo electrónico',
        'perfil.telefono':        'Teléfono',

        'general.cargando':  'Cargando…',
        'general.error':     'Erro de conexión.',
        'general.guardado':  '✓ Gardado correctamente',
    }
};

/* ══════════════════════════════════════════════════════════════
   MOTOR DE TRADUCCIÓN
══════════════════════════════════════════════════════════════ */

/**
 * Devuelve la traducción de una clave en el idioma dado.
 * Si no existe la clave, devuelve el texto original del elemento.
 */
function t(clave, idioma) {
    const lang = idioma || idiomaActual();
    return (TRADUCCIONES[lang] && TRADUCCIONES[lang][clave]) ||
           (TRADUCCIONES['es'][clave]) ||
           clave;
}

/** Devuelve el idioma guardado en localStorage */
function idiomaActual() {
    try {
        const opts = JSON.parse(localStorage.getItem('edunoly-config') || '{}');
        return opts.idioma || 'es';
    } catch { return 'es'; }
}

/**
 * Traduce todos los elementos de la página que tengan data-i18n.
 * Se llama automáticamente al cargar y cuando se cambia el idioma.
 */
function aplicarIdioma(idioma) {
    const lang = idioma || idiomaActual();
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const clave = el.getAttribute('data-i18n');
        const texto = t(clave, lang);
        if (texto) el.textContent = texto;
    });

    // Cambiar el atributo lang del HTML para accesibilidad
    document.documentElement.setAttribute('lang', lang);
}

/* Aplicar idioma en cuanto el DOM esté listo */
document.addEventListener('DOMContentLoaded', () => aplicarIdioma());
