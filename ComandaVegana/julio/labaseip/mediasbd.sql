-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2018 a las 21:03:05
-- Versión del servidor: 10.1.33-MariaDB
-- Versión de PHP: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mediasbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medias`
--

CREATE TABLE `medias` (
  `idmedias` int(11) NOT NULL,
  `color` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `marca` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` float NOT NULL,
  `talle` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `medias`
--

INSERT INTO `medias` (`idmedias`, `color`, `marca`, `precio`, `talle`, `foto`) VALUES
(3, 'azul', 'jerek', 200, 'l', 'jerekazull.jpg'),
(5, 'blanco', 'paso', 150, 'm', 'pasoblancom.jpg'),
(6, 'negro', 'jerek', 350, 's', 'jereknegros.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuarios` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `perfil` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `contrasena` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `perfil`, `contrasena`) VALUES
(1, 'Patricio', 'Empleado', 'Patricio'),
(2, 'Ernesto', 'Encargado', 'Ernesto'),
(3, 'Gaston', 'Dueño', 'Gaston'),
(4, 'Julia', 'Empleado', 'Julia'),
(5, 'Juan Pablo', 'Empleado', 'Juan Pablo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventamedia`
--

CREATE TABLE `ventamedia` (
  `idventamedia` int(11) NOT NULL,
  `idmedias` int(11) NOT NULL,
  `nombrecliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `importe` float NOT NULL,
  `foto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ventamedia`
--

INSERT INTO `ventamedia` (`idventamedia`, `idmedias`, `nombrecliente`, `fecha`, `importe`, `foto`) VALUES
(1, 6, 'Francisca', '15_05_2017', 600, '6Francisca15_05_2017.jpg'),
(2, 6, 'Francisca', '16_05_2017', 400, '6Francisca16_05_2017.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `medias`
--
ALTER TABLE `medias`
  ADD PRIMARY KEY (`idmedias`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuarios`);

--
-- Indices de la tabla `ventamedia`
--
ALTER TABLE `ventamedia`
  ADD PRIMARY KEY (`idventamedia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `medias`
--
ALTER TABLE `medias`
  MODIFY `idmedias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventamedia`
--
ALTER TABLE `ventamedia`
  MODIFY `idventamedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
