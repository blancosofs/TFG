DROP DATABASE IF EXISTS edunoly;
CREATE DATABASE IF NOT EXISTS edunoly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE edunoly;

-- 1. USUARIOS (La base que usa Breeze para el login)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, -- Nombre
    apellidos VARCHAR(60),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    colegio_id BIGINT UNSIGNED NULL, -- Para saber a qué colegio pertenece este usuario
    activo TINYINT(1) NOT NULL DEFAULT 1,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 2. COLEGIOS
CREATE TABLE colegios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    entidad VARCHAR(100),
    direccion VARCHAR(200),
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 3. COORDINADORES (Vincula un User con un Colegio)
CREATE TABLE coordinadores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    colegio_id BIGINT UNSIGNED NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (colegio_id) REFERENCES colegios(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. CURSOS (Ej: 1º ESO, 2º Primaria)
CREATE TABLE cursos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    colegio_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (colegio_id) REFERENCES colegios(id) ON DELETE CASCADE
);

-- 5. CLASES (Ej: Clase A, Clase B)
CREATE TABLE clases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(10) NOT NULL,
    codigo_acceso VARCHAR(10) UNIQUE NOT NULL,
    curso_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- 6. DOCENTES
CREATE TABLE docentes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    telefono VARCHAR(20),
    colegio_id BIGINT UNSIGNED NOT NULL,
    coordinador_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (colegio_id) REFERENCES colegios(id) ON DELETE CASCADE,
    FOREIGN KEY (coordinador_id) REFERENCES coordinadores(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. PIVOTE: DOCENTES_CLASES (Relación N:M)
-- Aquí anotamos qué profes dan clase en qué grupos.
CREATE TABLE docentes_clases (
    docente_id BIGINT UNSIGNED NOT NULL,
    clase_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (docente_id, clase_id),
    FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE CASCADE,
    FOREIGN KEY (clase_id) REFERENCES clases(id) ON DELETE CASCADE
);

-- 8. ALUMNOS (No tienen cuenta, solo son datos)
CREATE TABLE alumnos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(25) NOT NULL,
    apellidos VARCHAR(60) NOT NULL,
    colegio_id BIGINT UNSIGNED NOT NULL,
    curso_id BIGINT UNSIGNED NOT NULL,
    clase_id BIGINT UNSIGNED NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (colegio_id) REFERENCES colegios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT,
    FOREIGN KEY (clase_id) REFERENCES clases(id) ON DELETE RESTRICT
);

-- 9. TUTORES
CREATE TABLE tutores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    telefono VARCHAR(20),
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. PIVOTE: TUTORES_ALUMNOS (Relación N:M)
-- Un tutor puede tener varios hijos, un alumno varios tutores.
CREATE TABLE tutores_alumnos (
    tutor_id BIGINT UNSIGNED NOT NULL,
    alumno_id BIGINT UNSIGNED NOT NULL,
    parentesco VARCHAR(30), -- 'Padre', 'Madre', etc.
    PRIMARY KEY (tutor_id, alumno_id),
    FOREIGN KEY (tutor_id) REFERENCES tutores(id) ON DELETE CASCADE,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE
);

-- 11. HORARIOS
CREATE TABLE horarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dia_semana ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    docente_id BIGINT UNSIGNED NOT NULL,
    clase_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE CASCADE,
    FOREIGN KEY (clase_id) REFERENCES clases(id) ON DELETE CASCADE
);

-- 12. AUSENCIAS (Lo que el docente marca)
CREATE TABLE ausencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    tipo ENUM('falta','retraso') NOT NULL DEFAULT 'falta',
    justificada TINYINT(1) NOT NULL DEFAULT 0,
    justificacion TEXT, -- Lo que escribe el padre para explicar la falta
    alumno_id BIGINT UNSIGNED NOT NULL,
    docente_id BIGINT UNSIGNED NOT NULL,
    horario_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE CASCADE,
    FOREIGN KEY (horario_id) REFERENCES horarios(id) ON DELETE CASCADE
);

-- ÍNDICES (Para que la base de datos sea rápida al buscar)
CREATE INDEX idx_users_colegio ON users(colegio_id);
CREATE INDEX idx_alumnos_clase ON alumnos(clase_id);
CREATE INDEX idx_ausencias_fecha ON ausencias(fecha);

--
