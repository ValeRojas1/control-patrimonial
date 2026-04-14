-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2026 a las 02:25:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_patrimonial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bienes`
--

CREATE TABLE `bienes` (
  `id` int(11) NOT NULL,
  `codigo_patrimonial` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desplazamientos`
--

CREATE TABLE `desplazamientos` (
  `id` int(11) NOT NULL,
  `numero_desplazamiento` varchar(50) NOT NULL,
  `persona_origen_id` int(11) DEFAULT NULL,
  `persona_destino_id` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_desplazamiento`
--

CREATE TABLE `detalle_desplazamiento` (
  `id` int(11) NOT NULL,
  `desplazamiento_id` int(11) DEFAULT NULL,
  `bien_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `id` int(11) NOT NULL,
  `bien_id` int(11) DEFAULT NULL,
  `persona_anterior_id` int(11) DEFAULT NULL,
  `persona_nueva_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `accion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `area`, `estado`) VALUES
(1, 'Juan Perez', 'Sistemas', 1),
(2, 'Maria Lopez', 'Administracion', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT 'usuario',
  `estado` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `estado`, `fecha_creacion`) VALUES
(1, 'Admin', 'admin@demo.com', '123456', 'usuario', 1, '2026-04-14 00:25:27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_patrimonial` (`codigo_patrimonial`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `desplazamientos`
--
ALTER TABLE `desplazamientos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_desplazamiento` (`numero_desplazamiento`),
  ADD KEY `persona_origen_id` (`persona_origen_id`),
  ADD KEY `persona_destino_id` (`persona_destino_id`);

--
-- Indices de la tabla `detalle_desplazamiento`
--
ALTER TABLE `detalle_desplazamiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desplazamiento_id` (`desplazamiento_id`),
  ADD KEY `bien_id` (`bien_id`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bien_id` (`bien_id`),
  ADD KEY `persona_anterior_id` (`persona_anterior_id`),
  ADD KEY `persona_nueva_id` (`persona_nueva_id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bienes`
--
ALTER TABLE `bienes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `desplazamientos`
--
ALTER TABLE `desplazamientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_desplazamiento`
--
ALTER TABLE `detalle_desplazamiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD CONSTRAINT `bienes_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `desplazamientos`
--
ALTER TABLE `desplazamientos`
  ADD CONSTRAINT `desplazamientos_ibfk_1` FOREIGN KEY (`persona_origen_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `desplazamientos_ibfk_2` FOREIGN KEY (`persona_destino_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `detalle_desplazamiento`
--
ALTER TABLE `detalle_desplazamiento`
  ADD CONSTRAINT `detalle_desplazamiento_ibfk_1` FOREIGN KEY (`desplazamiento_id`) REFERENCES `desplazamientos` (`id`),
  ADD CONSTRAINT `detalle_desplazamiento_ibfk_2` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id`);

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id`),
  ADD CONSTRAINT `historial_ibfk_2` FOREIGN KEY (`persona_anterior_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `historial_ibfk_3` FOREIGN KEY (`persona_nueva_id`) REFERENCES `personas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
