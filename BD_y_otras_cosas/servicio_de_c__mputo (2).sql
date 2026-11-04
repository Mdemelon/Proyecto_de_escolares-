-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2026 a las 01:35:43
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
-- Base de datos: `servicio_de_cómputo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes_horas`
--

CREATE TABLE `ajustes_horas` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `maestro_id` int(11) NOT NULL,
  `tipo` enum('abonar','descontar') NOT NULL,
  `horas` decimal(5,2) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `numero_control` varchar(12) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `carrera_id` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `dependencia_id` int(11) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `estatus` enum('activo','finalizado','suspendido') NOT NULL DEFAULT 'activo',
  `horas_acumuladas` decimal(6,2) NOT NULL DEFAULT 0.00,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `numero_control`, `nombre`, `carrera_id`, `telefono`, `dependencia_id`, `password_hash`, `estatus`, `horas_acumuladas`, `creado_en`) VALUES
(2, '23750151', 'Alumno de prueba', 1, NULL, NULL, '$2y$10$M182zyjoepJNGzacMmFRNu5zT1mjx9VvFAj/RMo8rhTqwqbOjz7lu', 'activo', 0.00, '2026-10-20 01:22:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones_servicio`
--

CREATE TABLE `asignaciones_servicio` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `institucion_id` int(11) DEFAULT NULL,
  `periodo` varchar(20) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `actor_tipo` enum('alumno','maestro') NOT NULL,
  `actor_id` int(11) NOT NULL,
  `accion` varchar(60) NOT NULL,
  `detalle` text DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `actor_tipo`, `actor_id`, `accion`, `detalle`, `creado_en`) VALUES
(1, 'alumno', 2, 'login', '', '2026-10-26 00:09:41'),
(2, 'maestro', 1, 'login', '', '2026-10-26 00:39:21'),
(3, 'maestro', 1, 'login', '', '2026-10-26 00:40:47'),
(4, 'alumno', 2, 'login', '', '2026-10-29 00:50:34'),
(5, 'alumno', 2, 'login', '', '2026-10-29 00:52:30'),
(6, 'alumno', 2, 'login', '', '2026-10-29 00:56:19'),
(7, 'alumno', 2, 'login', '', '2026-10-29 00:57:03'),
(8, 'maestro', 1, 'login', '', '2026-10-30 11:16:11'),
(9, 'alumno', 2, 'login', '', '2026-10-30 11:17:45'),
(10, 'alumno', 2, 'login', '', '2026-11-03 18:26:50'),
(11, 'maestro', 1, 'login', '', '2026-11-03 18:27:11'),
(12, 'maestro', 1, 'login', '', '2026-11-03 18:31:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id`, `nombre`) VALUES
(1, 'Ingeniería en Sistemas Computacionales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cartas_liberacion`
--

CREATE TABLE `cartas_liberacion` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `maestro_id` int(11) NOT NULL,
  `folio` varchar(30) NOT NULL,
  `fecha_emision` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencias`
--

CREATE TABLE `dependencias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `responsable` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituciones_liberacion`
--

CREATE TABLE `instituciones_liberacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `responsable` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestros`
--

CREATE TABLE `maestros` (
  `id` int(11) NOT NULL,
  `usuario` varchar(40) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestros`
--

INSERT INTO `maestros` (`id`, `usuario`, `nombre`, `password_hash`) VALUES
(1, 'admin', 'Administrador', '$2y$10$IfG1vXMSZlFxgper2r8EJekrFARfI69Jn4CnStn1/w7l4agsPP4jK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_horas`
--

CREATE TABLE `registro_horas` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` datetime NOT NULL,
  `hora_salida` datetime DEFAULT NULL,
  `horas` decimal(5,2) DEFAULT NULL,
  `estatus` enum('en_curso','completado') NOT NULL DEFAULT 'en_curso',
  `lat_entrada` decimal(10,7) DEFAULT NULL,
  `lng_entrada` decimal(10,7) DEFAULT NULL,
  `distancia_entrada` decimal(7,2) DEFAULT NULL,
  `lat_salida` decimal(10,7) DEFAULT NULL,
  `lng_salida` decimal(10,7) DEFAULT NULL,
  `distancia_salida` decimal(7,2) DEFAULT NULL,
  `validado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `periodo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes_horas`
--
ALTER TABLE `ajustes_horas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `maestro_id` (`maestro_id`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_control` (`numero_control`),
  ADD KEY `carrera_id` (`carrera_id`),
  ADD KEY `dependencia_id` (`dependencia_id`);

--
-- Indices de la tabla `asignaciones_servicio`
--
ALTER TABLE `asignaciones_servicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `turno_id` (`turno_id`),
  ADD KEY `institucion_id` (`institucion_id`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `cartas_liberacion`
--
ALTER TABLE `cartas_liberacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `maestro_id` (`maestro_id`);

--
-- Indices de la tabla `dependencias`
--
ALTER TABLE `dependencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `instituciones_liberacion`
--
ALTER TABLE `instituciones_liberacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maestros`
--
ALTER TABLE `maestros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `registro_horas`
--
ALTER TABLE `registro_horas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `validado_por` (`validado_por`),
  ADD KEY `alumno_id` (`alumno_id`,`fecha`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes_horas`
--
ALTER TABLE `ajustes_horas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `asignaciones_servicio`
--
ALTER TABLE `asignaciones_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cartas_liberacion`
--
ALTER TABLE `cartas_liberacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dependencias`
--
ALTER TABLE `dependencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instituciones_liberacion`
--
ALTER TABLE `instituciones_liberacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestros`
--
ALTER TABLE `maestros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registro_horas`
--
ALTER TABLE `registro_horas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajustes_horas`
--
ALTER TABLE `ajustes_horas`
  ADD CONSTRAINT `ajustes_horas_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `ajustes_horas_ibfk_2` FOREIGN KEY (`maestro_id`) REFERENCES `maestros` (`id`);

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`),
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`dependencia_id`) REFERENCES `dependencias` (`id`);

--
-- Filtros para la tabla `asignaciones_servicio`
--
ALTER TABLE `asignaciones_servicio`
  ADD CONSTRAINT `asignaciones_servicio_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `asignaciones_servicio_ibfk_2` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`),
  ADD CONSTRAINT `asignaciones_servicio_ibfk_3` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones_liberacion` (`id`);

--
-- Filtros para la tabla `cartas_liberacion`
--
ALTER TABLE `cartas_liberacion`
  ADD CONSTRAINT `cartas_liberacion_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `cartas_liberacion_ibfk_2` FOREIGN KEY (`maestro_id`) REFERENCES `maestros` (`id`);

--
-- Filtros para la tabla `registro_horas`
--
ALTER TABLE `registro_horas`
  ADD CONSTRAINT `registro_horas_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `registro_horas_ibfk_2` FOREIGN KEY (`validado_por`) REFERENCES `maestros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
