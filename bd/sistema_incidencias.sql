-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2026 a las 05:51:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_incidencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `grado` int(11) NOT NULL,
  `grupo` varchar(10) NOT NULL,
  `correo_alumno` varchar(100) NOT NULL,
  `datos_tutor` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canalizaciones_psicologia`
--

CREATE TABLE `canalizaciones_psicologia` (
  `id_canalizacion` int(11) NOT NULL,
  `id_incidencia` int(11) NOT NULL,
  `id_psicologo` int(11) NOT NULL,
  `estado_psicologia` enum('no_iniciado','en_proceso','terminado') NOT NULL DEFAULT 'no_iniciado',
  `notas_sesion` text DEFAULT NULL,
  `fecha_cita` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id_incidencia` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `folio` varchar(10) NOT NULL,
  `id_creador` int(11) NOT NULL,
  `tipo_incidencia` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `evidencia_url` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','en_proceso_se','canalizado_psicologia','resuelto') NOT NULL DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_escolar`
--

CREATE TABLE `seguimiento_escolar` (
  `id_seguimiento` int(11) NOT NULL,
  `id_incidencia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentarios_tutor` text NOT NULL,
  `canalizado_a_psicologia` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `identificador` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `rol` enum('maestro','prefecto','servicios_escolares','psicologia','administrador') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `identificador`, `nombre`, `correo`, `pass`, `rol`, `created_at`) VALUES
(1, 'MAT9999', 'Profesor de Prueba', 'test_profe@sigi.com', '$2y$10$WkNahkpTSEOB2JnZgg1MeOpT0BPxMiDgZo7pxZVBknVkccBXV7Lw.', 'maestro', '2026-06-23 02:19:19'),
(2, '12345', 'Jonathan', 'gosthn1@gmail.com', '$2y$10$Zjsss9x3pTkWto9Aigbqj.cex6o3uWY1kL7dsuXwnh3fa/wxE.O4.', 'maestro', '2026-06-23 02:20:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Indices de la tabla `canalizaciones_psicologia`
--
ALTER TABLE `canalizaciones_psicologia`
  ADD PRIMARY KEY (`id_canalizacion`),
  ADD KEY `fk_psicologia_incidencia` (`id_incidencia`),
  ADD KEY `fk_psicologia_psicologo` (`id_psicologo`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `fk_incidencias_alumno` (`id_alumno`),
  ADD KEY `fk_incidencias_creador` (`id_creador`);

--
-- Indices de la tabla `seguimiento_escolar`
--
ALTER TABLE `seguimiento_escolar`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `fk_seguimiento_incidencia` (`id_incidencia`),
  ADD KEY `fk_seguimiento_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `canalizaciones_psicologia`
--
ALTER TABLE `canalizaciones_psicologia`
  MODIFY `id_canalizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguimiento_escolar`
--
ALTER TABLE `seguimiento_escolar`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `canalizaciones_psicologia`
--
ALTER TABLE `canalizaciones_psicologia`
  ADD CONSTRAINT `fk_psicologia_incidencia` FOREIGN KEY (`id_incidencia`) REFERENCES `incidencias` (`id_incidencia`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_psicologia_psicologo` FOREIGN KEY (`id_psicologo`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `fk_incidencias_alumno` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_incidencias_creador` FOREIGN KEY (`id_creador`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `seguimiento_escolar`
--
ALTER TABLE `seguimiento_escolar`
  ADD CONSTRAINT `fk_seguimiento_incidencia` FOREIGN KEY (`id_incidencia`) REFERENCES `incidencias` (`id_incidencia`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_seguimiento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
