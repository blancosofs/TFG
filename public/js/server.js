/**
 * server.js — Backend Express para Calendario Profesores
 * -------------------------------------------------------
 * Rutas:
 *   POST /api/login          — Iniciar sesión
 *   POST /api/logout         — Cerrar sesión
 *   GET  /api/me             — Datos del profesor autenticado
 *   GET  /api/clases         — Clases del profesor (horario semanal)
 *   GET  /api/eventos        — Eventos puntuales del profesor
 *   POST /api/eventos        — Crear evento puntual
 *   DELETE /api/eventos/:id  — Eliminar evento
 *   GET  /api/materias       — Lista de materias
 *   GET  /api/aulas          — Lista de aulas
 */

require('dotenv').config();
const express  = require('express');
const session  = require('express-session');
const bcrypt   = require('bcrypt');
const mysql    = require('mysql2/promise');
const cors     = require('cors');
const path     = require('path');

const app  = express();
const PORT = process.env.PORT || 3000;

// ── Pool de conexiones MySQL ──────────────────────────────────
const pool = mysql.createPool({
  host    : process.env.DB_HOST     || 'localhost',
  port    : process.env.DB_PORT     || 3306,
  user    : process.env.DB_USER     || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME     || 'calendario_profesores',
  waitForConnections: true,
  connectionLimit   : 10,
});

// ── Middleware ────────────────────────────────────────────────
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(cors({
  origin: true,
  credentials: true,
}));

app.use(session({
  secret           : process.env.SESSION_SECRET || 'dev-secret-change-me',
  resave           : false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    maxAge  : 8 * 60 * 60 * 1000, // 8 horas
  },
}));

// Servir archivos estáticos (frontend)
app.use(express.static(path.join(__dirname, 'public')));

// ── Middleware de autenticación ───────────────────────────────
function requireAuth(req, res, next) {
  if (!req.session?.usuario) {
    return res.status(401).json({ error: 'No autenticado' });
  }
  next();
}

// ══════════════════════════════════════════════════════════════
//  AUTH
// ══════════════════════════════════════════════════════════════

// POST /api/login
app.post('/api/login', async (req, res) => {
  const { usuario, password } = req.body;
  if (!usuario || !password)
    return res.status(400).json({ error: 'Usuario y contraseña requeridos' });

  try {
    const [rows] = await pool.query(
      'SELECT * FROM profesores WHERE usuario = ? AND activo = 1',
      [usuario]
    );

    if (!rows.length)
      return res.status(401).json({ error: 'Usuario o contraseña incorrectos' });

    const profesor = rows[0];
    const match    = await bcrypt.compare(password, profesor.password);

    if (!match)
      return res.status(401).json({ error: 'Usuario o contraseña incorrectos' });

    req.session.usuario = {
      id       : profesor.id,
      nombre   : profesor.nombre,
      apellidos: profesor.apellidos,
      usuario  : profesor.usuario,
      email    : profesor.email,
      color    : profesor.color,
      rol      : profesor.rol,       // 'docente' | 'coordinador' | 'familiar'
    };

    res.json({ ok: true, usuario: req.session.usuario });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error del servidor' });
  }
});

// POST /api/logout
app.post('/api/logout', (req, res) => {
  req.session.destroy(() => res.json({ ok: true }));
});

// GET /api/me
app.get('/api/me', requireAuth, (req, res) => {
  res.json(req.session.usuario);
});

// ══════════════════════════════════════════════════════════════
//  CLASES (horario semanal recurrente)
// ══════════════════════════════════════════════════════════════

// GET /api/clases?desde=YYYY-MM-DD&hasta=YYYY-MM-DD
// Devuelve las clases del profesor expandidas en fechas concretas.
app.get('/api/clases', requireAuth, async (req, res) => {
  const profesorId = req.session.usuario.id;
  const { desde, hasta } = req.query;

  try {
    // Traer todas las clases activas del profesor en el período
    const [clases] = await pool.query(`
      SELECT c.id, c.dia_semana, c.hora_inicio, c.hora_fin,
             c.grupo, c.fecha_inicio, c.fecha_fin,
             m.nombre AS materia,
             a.nombre AS aula
      FROM   clases c
      JOIN   materias m ON m.id = c.materia_id
      LEFT JOIN aulas a ON a.id = c.aula_id
      WHERE  c.profesor_id = ?
        AND  c.activo = 1
        AND  c.fecha_inicio <= ?
        AND  c.fecha_fin    >= ?
    `, [profesorId, hasta || '2099-12-31', desde || '2000-01-01']);

    // Expandir recurrencia semanal en eventos concretos
    const eventos = [];
    const start = desde ? new Date(desde) : new Date();
    const end   = hasta ? new Date(hasta) : new Date(start.getFullYear(), start.getMonth() + 1, 0);

    clases.forEach(clase => {
      const cur = new Date(start);
      while (cur <= end) {
        // dia_semana: 1=Lunes…7=Domingo; getDay() 0=Dom,1=Lun…
        const dow = cur.getDay() === 0 ? 7 : cur.getDay();
        if (dow === clase.dia_semana) {
          const dateStr = cur.toISOString().slice(0, 10);
          if (dateStr >= clase.fecha_inicio && dateStr <= clase.fecha_fin) {
            eventos.push({
              id         : `clase-${clase.id}-${dateStr}`,
              clase_id   : clase.id,
              tipo       : 'clase',
              titulo     : clase.materia,
              grupo      : clase.grupo,
              aula       : clase.aula,
              fecha      : dateStr,
              hora_inicio: clase.hora_inicio,
              hora_fin   : clase.hora_fin,
              color      : req.session.profesor.color,
            });
          }
        }
        cur.setDate(cur.getDate() + 1);
      }
    });

    res.json(eventos);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al obtener clases' });
  }
});

// ══════════════════════════════════════════════════════════════
//  EVENTOS PUNTUALES
// ══════════════════════════════════════════════════════════════

// GET /api/eventos?desde=YYYY-MM-DD&hasta=YYYY-MM-DD
app.get('/api/eventos', requireAuth, async (req, res) => {
  const { desde, hasta } = req.query;
  try {
    const [rows] = await pool.query(`
      SELECT * FROM eventos
      WHERE profesor_id = ?
        AND fecha BETWEEN ? AND ?
      ORDER BY fecha, hora_inicio
    `, [req.session.usuario.id, desde || '2000-01-01', hasta || '2099-12-31']);
    res.json(rows);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al obtener eventos' });
  }
});

// POST /api/eventos
app.post('/api/eventos', requireAuth, async (req, res) => {
  const { titulo, descripcion, fecha, hora_inicio, hora_fin, tipo } = req.body;
  if (!titulo || !fecha)
    return res.status(400).json({ error: 'Título y fecha son obligatorios' });

  try {
    const [result] = await pool.query(`
      INSERT INTO eventos (profesor_id, titulo, descripcion, fecha, hora_inicio, hora_fin, tipo)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    `, [req.session.usuario.id, titulo, descripcion || null, fecha,
        hora_inicio || null, hora_fin || null, tipo || 'otro']);

    res.json({ ok: true, id: result.insertId });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al crear evento' });
  }
});

// DELETE /api/eventos/:id
app.delete('/api/eventos/:id', requireAuth, async (req, res) => {
  try {
    const [result] = await pool.query(
      'DELETE FROM eventos WHERE id = ? AND profesor_id = ?',
      [req.params.id, req.session.usuario.id]
    );
    if (!result.affectedRows)
      return res.status(404).json({ error: 'Evento no encontrado' });
    res.json({ ok: true });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al eliminar' });
  }
});

// ── Materias y Aulas (para los formularios) ──────────────────

app.get('/api/materias', requireAuth, async (_req, res) => {
  const [rows] = await pool.query('SELECT * FROM materias ORDER BY nombre');
  res.json(rows);
});

app.get('/api/aulas', requireAuth, async (_req, res) => {
  const [rows] = await pool.query('SELECT * FROM aulas ORDER BY nombre');
  res.json(rows);
});

// ══════════════════════════════════════════════════════════════
//  RUTAS DE ADMINISTRACIÓN
//  Solo accesibles para usuarios con rol = 'admin'
// ══════════════════════════════════════════════════════════════

function requireAdmin(req, res, next) {
  if (!req.session?.usuario) return res.status(401).json({ error: 'No autenticado' });
  if (req.session.usuario.rol !== 'admin') return res.status(403).json({ error: 'Acceso denegado' });
  next();
}

// GET /api/admin/colegios — lista todos los colegios con su coordinador
app.get('/api/admin/colegios', requireAdmin, async (_req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT c.*,
             u.id        AS coord_id,
             u.nombre    AS coord_nombre,
             u.apellidos AS coord_apellidos,
             u.email     AS coord_email,
             u.telefono  AS coord_telefono
      FROM   colegios c
      LEFT JOIN usuarios u ON u.colegio_id = c.id AND u.rol = 'coordinador' AND u.activo = 1
      ORDER BY c.nombre
    `);

    // Agrupar coordinador en objeto anidado
    const colegios = rows.map(r => ({
      id: r.id, nombre: r.nombre, tipo: r.tipo, etapas: r.etapas,
      calle: r.calle, ciudad: r.ciudad, comunidad: r.comunidad,
      cp: r.cp, telefono: r.telefono, email: r.email, web: r.web,
      alumnos: r.alumnos, notas: r.notas,
      coordinador: r.coord_id ? {
        id: r.coord_id, nombre: r.coord_nombre, apellidos: r.coord_apellidos,
        email: r.coord_email, telefono: r.coord_telefono
      } : null
    }));

    res.json(colegios);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al obtener colegios' });
  }
});

// POST /api/admin/colegios — crear nuevo colegio
app.post('/api/admin/colegios', requireAdmin, async (req, res) => {
  const { nombre, tipo, etapas, calle, ciudad, comunidad, cp, telefono, email, web, alumnos, notas } = req.body;
  if (!nombre || !calle || !ciudad || !cp || !telefono || !email)
    return res.status(400).json({ error: 'Faltan campos obligatorios' });

  try {
    const [result] = await pool.query(`
      INSERT INTO colegios (nombre, tipo, etapas, calle, ciudad, comunidad, cp, telefono, email, web, alumnos, notas)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [nombre, tipo||null, etapas||null, calle, ciudad, comunidad||null, cp, telefono, email, web||null, alumnos||null, notas||null]);

    res.json({ ok: true, id: result.insertId });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al crear colegio' });
  }
});

// POST /api/admin/colegios/:id/coordinador — añadir coordinador
app.post('/api/admin/colegios/:id/coordinador', requireAdmin, async (req, res) => {
  const { nombre, apellidos, email, telefono, notas, password } = req.body;
  const colegioId = req.params.id;

  if (!nombre || !apellidos || !email || !password)
    return res.status(400).json({ error: 'Nombre, apellidos, email y contraseña son obligatorios' });

  try {
    // Comprobar que no existe ya un coordinador activo en este colegio
    const [existe] = await pool.query(
      'SELECT id FROM usuarios WHERE colegio_id = ? AND rol = "coordinador" AND activo = 1',
      [colegioId]
    );
    if (existe.length)
      return res.status(400).json({ error: 'Este colegio ya tiene un coordinador. Modifícalo o elimínalo primero.' });

    const hash = await bcrypt.hash(password, 10);
    // El usuario de login es el email
    const [result] = await pool.query(`
      INSERT INTO usuarios (nombre, apellidos, usuario, password, email, telefono, rol, colegio_id, notas, activo)
      VALUES (?, ?, ?, ?, ?, ?, 'coordinador', ?, ?, 1)
    `, [nombre, apellidos, email, hash, email, telefono||null, colegioId, notas||null]);

    res.json({ ok: true, id: result.insertId });
  } catch (err) {
    console.error(err);
    if (err.code === 'ER_DUP_ENTRY') return res.status(400).json({ error: 'Ese email ya está registrado como usuario.' });
    res.status(500).json({ error: 'Error al crear coordinador' });
  }
});

// PUT /api/admin/colegios/:id/coordinador — modificar coordinador existente
app.put('/api/admin/colegios/:id/coordinador', requireAdmin, async (req, res) => {
  const { nombre, apellidos, email, telefono, notas, password } = req.body;
  const colegioId = req.params.id;

  try {
    const updates = ['nombre=?','apellidos=?','email=?','usuario=?','telefono=?','notas=?'];
    const values  = [nombre, apellidos, email, email, telefono||null, notas||null];

    if (password) {
      if (password.length < 8) return res.status(400).json({ error: 'La contraseña debe tener al menos 8 caracteres.' });
      updates.push('password=?');
      values.push(await bcrypt.hash(password, 10));
    }

    values.push(colegioId);
    const [result] = await pool.query(
      `UPDATE usuarios SET ${updates.join(',')} WHERE colegio_id=? AND rol='coordinador' AND activo=1`,
      values
    );

    if (!result.affectedRows) return res.status(404).json({ error: 'No se encontró coordinador para este colegio.' });
    res.json({ ok: true });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al actualizar coordinador' });
  }
});

// DELETE /api/admin/colegios/:id/coordinador — eliminar (desactivar) coordinador
app.delete('/api/admin/colegios/:id/coordinador', requireAdmin, async (req, res) => {
  try {
    const [result] = await pool.query(
      `UPDATE usuarios SET activo=0 WHERE colegio_id=? AND rol='coordinador' AND activo=1`,
      [req.params.id]
    );
    if (!result.affectedRows) return res.status(404).json({ error: 'No se encontró coordinador activo.' });
    res.json({ ok: true });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al eliminar coordinador' });
  }
});

// ── Catch-all → index.html (SPA) ─────────────────────────────
app.get('*', (_req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// ── Arrancar servidor ─────────────────────────────────────────
app.listen(PORT, () => {
  console.log(`✅ Servidor corriendo en http://localhost:${PORT}`);
});
