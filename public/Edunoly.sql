-- ══════════════════════════════════════════════════════════════
--  Edunoly · Schema MySQL completo
--  Basado en la propuesta TFG — Sofía Blanco, Ashley León, Jeremy Narváez
--  
--  Tablas incluidas:
--    colegio, coordinador, curso, clase, docente, alumno,
--    tutor, asignatura, horario, ausencia, material_repaso,
--    notificacion, auditoria,
--    + tablas de relación: docente_has_clase, docente_has_asignatura,
--      tutor_has_alumno, alumno_has_asignatura
--
--  AÑADIDOS respecto al diagrama original:
--    · Tabla 'usuario' centralizada para login (coordinador, docente, tutor)
--    · Tabla 'notificacion' para el sistema de avisos internos
--    · Tabla 'material_repaso' que aparece en los extras del tutor
--    · Tabla 'auditoria' para el algoritmo de auditoría y seguridad
--    · Campo 'justificada' y 'motivo' en ausencia (ya en diagrama, detallado)
--    · Índices en las claves foráneas más consultadas
-- ══════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS edunoly
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE edunoly;

-- ──────────────────────────────────────────────────────────────
--  1. COLEGIO  (entidad educativa)
--     El desarrollador/admin crea y gestiona los colegios.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS colegios (
    idColegio       INT             AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100)    NOT NULL,
    entidad         VARCHAR(100),                       -- nombre legal / titular
    tipoColegio     VARCHAR(50),                        -- público, concertado, privado…
    tipoEducacion   VARCHAR(100),                       -- infantil, primaria, ESO, bachillerato…
    direccion       VARCHAR(200),
    telefono        VARCHAR(20),
    email           VARCHAR(120),
    web             VARCHAR(200),
    activo          TINYINT(1)      NOT NULL DEFAULT 1,
    creado_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ──────────────────────────────────────────────────────────────
--  2. USUARIO  (tabla centralizada de login — AÑADIDO)
--     Gestiona la autenticación de todos los roles que acceden
--     al sistema: admin, coordinador, docente, tutor.
--     Los alumnos NO son usuarios del sistema (solo datos).
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario       INT             AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(25)     NOT NULL,
    apellidos       VARCHAR(60)     NOT NULL,
    email           VARCHAR(120)    NOT NULL UNIQUE,    -- también sirve de login
    password        VARCHAR(255)    NOT NULL,           -- hash bcrypt
    rol             ENUM('admin','coordinador','docente','tutor')
                                    NOT NULL DEFAULT 'docente',
    colegio_idColegio INT           DEFAULT NULL,       -- NULL para admin global
    activo          TINYINT(1)      NOT NULL DEFAULT 1,
    ultimo_acceso   DATETIME        DEFAULT NULL,
    creado_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE INDEX idx_usuario_colegio ON usuario(colegio_idColegio);
CREATE INDEX idx_usuario_rol     ON usuario(rol);


-- ──────────────────────────────────────────────────────────────
--  3. COORDINADOR
--     Un coordinador pertenece a UN único colegio.
--     Si el coordinador se da de baja → el colegio queda sin coordinador
--     (ver trigger al final).
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS coordinadores (
    idCoordinador       INT     AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(25)  NOT NULL,
    apellidos           VARCHAR(60)  NOT NULL,
    colegio_idColegio   INT          NOT NULL,
    usuario_idUsuario   INT          NOT NULL UNIQUE,   -- cuenta de acceso
    activo              TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (usuario_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_coord_colegio ON coordinadores(colegio_idColegio);


-- ──────────────────────────────────────────────────────────────
--  4. CURSO
--     Agrupa clases del mismo nivel (ej: 1º ESO, 2º Primaria).
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cursos (
    idCurso             INT     AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(30)  NOT NULL,          -- ej: "1º ESO"
    tipoCurso           VARCHAR(30),                    -- Primaria, ESO, Bachillerato…
    colegio_idColegio   INT          NOT NULL,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_curso_colegio ON curso(colegio_idColegio);


-- ──────────────────────────────────────────────────────────────
--  5. CLASE
--     Grupo concreto dentro de un curso (ej: 1ºA, 1ºB).
--     Código único por colegio para acceso por código (caso de uso).
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS clases (
    idClase             INT     AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(10)  NOT NULL,           -- ej: "A", "B"
    codigo_acceso       VARCHAR(10)  NOT NULL UNIQUE,    -- AÑADIDO: clases accesibles por código
    curso_idCurso       INT          NOT NULL,
    FOREIGN KEY (curso_idCurso)
        REFERENCES cursos(idCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_clase_curso ON clase(curso_idCurso);


-- ──────────────────────────────────────────────────────────────
--  6. DOCENTE
--     Pertenece a un colegio y está supervisado por un coordinador.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS docentes (
    idDocente               INT     AUTO_INCREMENT PRIMARY KEY,
    nombre                  VARCHAR(25)  NOT NULL,
    apellidos               VARCHAR(60)  NOT NULL,
    fechaNacimiento         DATE,
    email                   VARCHAR(120) NOT NULL,
    telefono                VARCHAR(20),
    colegio_idColegio       INT          NOT NULL,
    coordinador_idCoordinador INT        NOT NULL,
    usuario_idUsuario       INT          NOT NULL UNIQUE,
    activo                  TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (coordinador_idCoordinador)
        REFERENCES coordinadores(idCoordinador)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (usuario_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_docente_colegio ON docente(colegio_idColegio);
CREATE INDEX idx_docente_coord   ON docente(coordinador_idCoordinador);


-- ──────────────────────────────────────────────────────────────
--  7. ALUMNO  (datos, NO usuario del sistema)
--     Pertenece a un único colegio y una única clase.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS alumnos (
    idAlumno        INT     AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(25)  NOT NULL,
    apellidos       VARCHAR(60)  NOT NULL,
    fechaNacimiento DATE,
    curso_idCurso   INT          NOT NULL,
    clase_idClase   INT          NOT NULL,
    activo          TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (curso_idCurso)
        REFERENCES cursos(idCurso)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (clase_idClase)
        REFERENCES clases(idClase)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_alumno_clase ON alumnos(clase_idClase);
CREATE INDEX idx_alumno_curso ON alumnos(curso_idCurso);


-- ──────────────────────────────────────────────────────────────
--  8. TUTOR  (tutor legal)
--     Puede tener varios alumnos a cargo (relación N:M con alumno).
--     Pertenece a un único colegio pero puede estar en varias clases.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS tutores (
    idTutor             INT     AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(25)  NOT NULL,
    apellidos           VARCHAR(60)  NOT NULL,
    telefono            VARCHAR(20),
    email               VARCHAR(120) NOT NULL,
    colegio_idColegio   INT          NOT NULL,
    usuario_idUsuario   INT          NOT NULL UNIQUE,
    activo              TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (usuario_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_tutor_colegio ON tutor(colegio_idColegio);


-- ──────────────────────────────────────────────────────────────
--  9. TUTOR_HAS_ALUMNO  (relación N:M tutor ↔ alumno)
--     Incluye el parentesco del tutor con el alumno.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS tutores_has_alumnos (
    tutor_idTutor       INT         NOT NULL,
    alumno_idAlumno     INT         NOT NULL,
    parentesco          VARCHAR(30) NOT NULL,           -- padre, madre, abuelo…
    es_principal        TINYINT(1)  NOT NULL DEFAULT 1, -- AÑADIDO: tutor principal/secundario
    PRIMARY KEY (tutor_idTutor, alumno_idAlumno),
    FOREIGN KEY (tutor_idTutor)
        REFERENCES tutores(idTutor)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (alumno_idAlumno)
        REFERENCES alumnos(idAlumno)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ──────────────────────────────────────────────────────────────
--  10. ASIGNATURA
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS asignaturas (
    idAsignatura        INT     AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(60)  NOT NULL,
    descripcion         VARCHAR(200),
    colegio_idColegio   INT          NOT NULL,
    FOREIGN KEY (colegio_idColegio)
        REFERENCES colegios(idColegio)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_asignatura_colegio ON asignaturas(colegio_idColegio);


-- ──────────────────────────────────────────────────────────────
--  11. DOCENTE_HAS_ASIGNATURA  (qué asignaturas imparte cada docente)
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS docentes_has_asignaturas (
    docente_idDocente       INT NOT NULL,
    asignatura_idAsignatura INT NOT NULL,
    PRIMARY KEY (docente_idDocente, asignatura_idAsignatura),
    FOREIGN KEY (docente_idDocente)
        REFERENCES docentes(idDocente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (asignatura_idAsignatura)
        REFERENCES asignaturas(idAsignatura)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ──────────────────────────────────────────────────────────────
--  12. DOCENTE_HAS_CLASE  (qué clases tiene asignadas cada docente)
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS docentes_has_clases (
    docente_idDocente   INT NOT NULL,
    clase_idClase       INT NOT NULL,
    PRIMARY KEY (docente_idDocente, clase_idClase),
    FOREIGN KEY (docente_idDocente)
        REFERENCES docentes(idDocente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (clase_idClase)
        REFERENCES clases(idClase)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ──────────────────────────────────────────────────────────────
--  13. ALUMNO_HAS_ASIGNATURA  (AÑADIDO)
--      Extra del docente/tutor: asignaturas específicas de cada alumno.
--      Útil para alumnos con adaptaciones curriculares.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS alumnos_has_asignaturas (
    alumno_idAlumno         INT NOT NULL,
    asignatura_idAsignatura INT NOT NULL,
    PRIMARY KEY (alumno_idAlumno, asignatura_idAsignatura),
    FOREIGN KEY (alumno_idAlumno)
        REFERENCES alumnos(idAlumno)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (asignatura_idAsignatura)
        REFERENCES asignaturas(idAsignatura)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ──────────────────────────────────────────────────────────────
--  14. HORARIO
--      Franja horaria semanal recurrente.
--      diaSemana: 'Lunes','Martes','Miércoles','Jueves','Viernes'
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS horarios (
    idHorario               INT     AUTO_INCREMENT PRIMARY KEY,
    diaSemana               VARCHAR(10)  NOT NULL,
    horaInicio              TIME         NOT NULL,
    horaFin                 TIME         NOT NULL,
    docente_idDocente       INT          NOT NULL,
    asignatura_idAsignatura INT          NOT NULL,
    clase_idClase           INT          NOT NULL,
    curso_idCurso           INT          NOT NULL,
    fecha_inicio_vigencia   DATE         NOT NULL,      -- AÑADIDO: desde cuándo aplica
    fecha_fin_vigencia      DATE         NOT NULL,      -- AÑADIDO: hasta cuándo aplica
    FOREIGN KEY (docente_idDocente)
        REFERENCES docentes(idDocente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (asignatura_idAsignatura)
        REFERENCES asignaturas(idAsignatura)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (clase_idClase)
        REFERENCES clases(idClase)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (curso_idCurso)
        REFERENCES cursos(idCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_horario_docente ON horario(docente_idDocente);
CREATE INDEX idx_horario_clase   ON horario(clase_idClase);


-- ──────────────────────────────────────────────────────────────
--  15. AUSENCIA
--      Registro de faltas de asistencia de alumnos.
--      El docente la registra; el tutor puede justificarla.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS ausencias (
    idAusencia          INT         AUTO_INCREMENT PRIMARY KEY,
    fecha               DATE        NOT NULL,
    motivo              VARCHAR(200),
    justificada         TINYINT(1)  NOT NULL DEFAULT 0,
    justificacion       TEXT,                           -- AÑADIDO: texto de justificación
    tipo                ENUM('falta','retraso')         -- AÑADIDO: distingue falta total de retraso
                                    NOT NULL DEFAULT 'falta',
    alumno_idAlumno     INT         NOT NULL,
    docente_idDocente   INT         NOT NULL,           -- quién la registra
    tutor_idTutor       INT         DEFAULT NULL,       -- quién la justifica (si aplica)
    horario_idHorario   INT         DEFAULT NULL,       -- AÑADIDO: a qué franja corresponde
    FOREIGN KEY (alumno_idAlumno)
        REFERENCES alumnos(idAlumno)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (docente_idDocente)
        REFERENCES docentes(idDocente)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (tutor_idTutor)
        REFERENCES tutores(idTutor)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (horario_idHorario)
        REFERENCES horarios(idHorario)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE INDEX idx_ausencia_alumno ON ausencia(alumno_idAlumno);
CREATE INDEX idx_ausencia_fecha  ON ausencia(fecha);
CREATE INDEX idx_ausencia_docente ON ausencia(docente_idDocente);


-- ──────────────────────────────────────────────────────────────
--  16. AUSENCIA_DOCENTE  (AÑADIDO)
--      Control de asistencia de docentes, separado del de alumnos.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS ausencias_docente (
    idAusenciaDocente   INT         AUTO_INCREMENT PRIMARY KEY,
    fecha               DATE        NOT NULL,
    tipo                ENUM('falta','retraso') NOT NULL DEFAULT 'falta',
    motivo              VARCHAR(200),
    justificada         TINYINT(1)  NOT NULL DEFAULT 0,
    docente_idDocente   INT         NOT NULL,
    coordinador_idCoordinador INT   NOT NULL,           -- quién la registra
    FOREIGN KEY (docente_idDocente)
        REFERENCES docentes(idDocente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (coordinador_idCoordinador)
        REFERENCES coordinadores(idCoordinador)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_ausencia_doc_docente ON ausencia_docente(docente_idDocente);
CREATE INDEX idx_ausencia_doc_fecha   ON ausencia_docente(fecha);


-- ──────────────────────────────────────────────────────────────
--  17. NOTIFICACION  (AÑADIDO)
--      Sistema de avisos internos entre usuarios.
--      Cubre: notificar faltas, comunicar incidencias, avisos previos.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS notificaciones (
    idNotificacion      INT         AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(120) NOT NULL,
    mensaje             TEXT         NOT NULL,
    tipo                ENUM('falta','incidencia','aviso_ausencia','comunicado','otro')
                                     NOT NULL DEFAULT 'otro',
    leida               TINYINT(1)   NOT NULL DEFAULT 0,
    emisor_idUsuario    INT          NOT NULL,           -- quien envía
    receptor_idUsuario  INT          NOT NULL,           -- quien recibe
    alumno_idAlumno     INT          DEFAULT NULL,       -- alumno relacionado (si aplica)
    creado_en           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emisor_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (receptor_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (alumno_idAlumno)
        REFERENCES alumnos(idAlumno)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE INDEX idx_notif_receptor ON notificacion(receptor_idUsuario);
CREATE INDEX idx_notif_emisor   ON notificacion(emisor_idUsuario);


-- ──────────────────────────────────────────────────────────────
--  18. MATERIAL_REPASO  (AÑADIDO — extra del tutor legal)
--      El tutor puede añadir material de repaso asociado a un alumno.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS materiales_repaso (
    idMaterial          INT         AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(120) NOT NULL,
    descripcion         TEXT,
    url_archivo         VARCHAR(300),                   -- enlace o ruta del archivo
    tipo                ENUM('documento','enlace','imagen','video','otro')
                                     NOT NULL DEFAULT 'documento',
    alumno_idAlumno     INT          NOT NULL,
    tutor_idTutor       INT          NOT NULL,
    asignatura_idAsignatura INT      DEFAULT NULL,
    creado_en           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_idAlumno)
        REFERENCES alumnos(idAlumno)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (tutor_idTutor)
        REFERENCES tutores(idTutor)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (asignatura_idAsignatura)
        REFERENCES asignaturas(idAsignatura)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE INDEX idx_material_alumno ON material_repaso(alumno_idAlumno);



-- ──────────────────────────────────────────────────────────────
--  20. AUDITORIA  (AÑADIDO — algoritmo de auditoría y seguridad)
--      Registra acciones relevantes de todos los usuarios.
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS auditoria (
    idAuditoria     INT         AUTO_INCREMENT PRIMARY KEY,
    accion          VARCHAR(100) NOT NULL,              -- ej: 'LOGIN', 'CREAR_ALUMNO'…
    tabla_afectada  VARCHAR(60),                        -- tabla sobre la que actuó
    id_registro     INT          DEFAULT NULL,          -- ID del registro afectado
    datos_anteriores JSON        DEFAULT NULL,          -- AÑADIDO: snapshot antes del cambio
    datos_nuevos     JSON        DEFAULT NULL,          -- snapshot después del cambio
    ip_origen       VARCHAR(45),                        -- IPv4 o IPv6
    usuario_idUsuario INT        DEFAULT NULL,          -- NULL si acción del sistema
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_idUsuario)
        REFERENCES usuarios(idUsuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE INDEX idx_auditoria_usuario ON auditoria(usuario_idUsuario);
CREATE INDEX idx_auditoria_fecha   ON auditoria(creado_en);
CREATE INDEX idx_auditoria_accion  ON auditoria(accion);


-- ══════════════════════════════════════════════════════════════
--  TRIGGERS
-- ══════════════════════════════════════════════════════════════

-- Trigger: cuando un coordinador se da de baja (activo = 0),
-- marcar el colegio como inactivo automáticamente.
-- (Según restricciones de la propuesta: "Si el coordinador se da de baja,
--  se elimina la entidad asociada.")
DELIMITER $$

CREATE TRIGGER trg_coordinador_baja
AFTER UPDATE ON coordinadores
FOR EACH ROW
BEGIN
    IF NEW.activo = 0 AND OLD.activo = 1 THEN
        UPDATE colegios
        SET    activo = 0
        WHERE  idColegio = NEW.colegio_idColegio;
    END IF;
END$$

-- Trigger: registrar automáticamente en auditoría cada INSERT en alumno
CREATE TRIGGER trg_audit_alumno_insert
AFTER INSERT ON alumnos
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (accion, tabla_afectada, id_registro, datos_nuevos)
    VALUES (
        'CREAR_ALUMNO',
        'alumno',
        NEW.idAlumno,
        JSON_OBJECT(
            'nombre',    NEW.nombre,
            'apellidos', NEW.apellidos,
            'clase',     NEW.clase_idClase
        )
    );
END$$

DELIMITER ;
