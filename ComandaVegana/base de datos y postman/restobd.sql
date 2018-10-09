-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2018 a las 00:57:50
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
-- Base de datos: `restobd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bartenders`
--

CREATE TABLE `bartenders` (
  `idbartender` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `bartenders`
--

INSERT INTO `bartenders` (`idbartender`, `nombre`, `pass`, `tipo`) VALUES
(1, 'Agustina', 'Agustina', 'Bartender'),
(2, 'Javier', 'Javier', 'Bartender'),
(3, 'Luis', 'Luis', 'Bartender');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cerveceros`
--

CREATE TABLE `cerveceros` (
  `idcervecero` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cerveceros`
--

INSERT INTO `cerveceros` (`idcervecero`, `nombre`, `pass`, `tipo`) VALUES
(1, 'Junior', 'Junior', 'Cervecero'),
(2, 'Carlos', 'Carlos', 'Cervecero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL,
  `codigomesa` int(11) NOT NULL,
  `codigopedido` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `idpedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idcliente`, `codigomesa`, `codigopedido`, `idpedido`) VALUES
(1, 10000, 'f9w0p', 1),
(2, 10001, 'p6uqy', 2),
(3, 10002, 'nbrbq', 3),
(4, 10003, 'aw1oi', 4),
(5, 10004, '8dg45', 5),
(6, 10005, 'xwzds', 6),
(7, 10006, 'zb2x9', 7),
(8, 10007, 'k80lf', 8),
(9, 10008, 'a9aqq', 9),
(10, 10009, '0gs66', 10),
(11, 10010, 'obp6m', 11),
(12, 10011, 'h6xv6', 12),
(13, 10012, 'znaur', 13),
(14, 10013, 'w8b34', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cocineros`
--

CREATE TABLE `cocineros` (
  `idcocinero` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cocineros`
--

INSERT INTO `cocineros` (`idcocinero`, `nombre`, `pass`, `tipo`) VALUES
(1, 'Adriana', 'Adriana', 'Cocinero'),
(2, 'Mariana', 'Mariana', 'Cocinero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `idcomanda` int(11) NOT NULL,
  `idmozo` int(11) NOT NULL,
  `nombrecliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `idpedido` int(11) NOT NULL,
  `fotomesa` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horaini` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `importe` float NOT NULL,
  `horafin` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`idcomanda`, `idmozo`, `nombrecliente`, `idpedido`, `fotomesa`, `horaini`, `importe`, `horafin`, `fecha`) VALUES
(1, 1, 'Franco', 1, 'Franco.jpg', '18:36:18', 1500, '11:26:05', ''),
(2, 1, 'Luisa', 2, 'Luisa.jpg', '18:40:52', 1900, '19:24:47', ''),
(3, 1, 'Fede', 3, 'Fede.jpg', '14:17:12', 1400, '19:37:22', ''),
(4, 3, 'Marcos', 4, 'Marcos.jpg', '13:57:09', 750, '12:20:44', ''),
(5, 1, 'Mariano', 5, 'Mariano.jpg', '14:42:58', 900, '12:21:33', ''),
(6, 4, 'Elisa', 6, 'Elisa.JPG', '19:22:14', 950, '12:21:41', ''),
(7, 2, 'Martino', 7, 'Martino.jpg', '19:34:13', 900, '19:25:33', ''),
(8, 3, 'Felipe', 8, 'Felipe.jpg', '18:32:05', 850, '19:25:41', ''),
(9, 2, 'Eduardo', 9, 'Eduardo.jpg', '18:36:22', 1400, '19:42:04', ''),
(10, 2, 'Alicia', 10, 'Alicia.jpg', '19:39:36', 1200, '12:22:09', ''),
(11, 4, 'Jacinto', 11, 'Jacinto.jpg', '10:37:32', 1500, '12:22:13', ''),
(12, 4, 'Graciela', 12, 'Graciela.jpg', '10:46:08', 1400, '12:22:17', '09/10/2018'),
(13, 4, 'Lolo', 13, 'Lolo.jpg', '11:28:07', 1900, '12:22:23', '09/10/2018'),
(14, 4, 'Aida', 14, 'Aida.jpg', '11:30:18', 800, '12:22:28', '09/10/2018');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `idingreso` int(11) NOT NULL,
  `fecha` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipoempleado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `idempleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`idingreso`, `fecha`, `tipoempleado`, `idempleado`) VALUES
(7, '03/10/2018', 'Mozo', 2),
(8, '03/10/2018', 'Mozo', 2),
(9, '05/10/2018', 'Mozo', 4),
(10, '05/10/2018', 'Mozo', 2),
(11, '05/10/2018', 'Cervecero', 2),
(12, '05/10/2018', 'Cervecero', 1),
(13, '05/10/2018', 'Bartender', 1),
(14, '05/10/2018', 'Pastelero', 2),
(15, '05/10/2018', 'Mozo', 1),
(16, '08/10/2018', 'Mozo', 3),
(17, '08/10/2018', 'Mozo', 2),
(18, '08/10/2018', 'Cocinero', 1),
(19, '08/10/2018', 'Bartender', 3),
(20, '08/10/2018', 'Cervecero', 2),
(21, '08/10/2018', 'Pastelero', 1),
(22, '09/10/2018', 'Mozo', 4),
(23, '09/10/2018', 'Cocinero', 2),
(24, '09/10/2018', 'Bartender', 2),
(25, '09/10/2018', 'Cervecero', 2),
(26, '09/10/2018', 'Pastelero', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mozos`
--

CREATE TABLE `mozos` (
  `idmozo` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mozos`
--

INSERT INTO `mozos` (`idmozo`, `nombre`, `pass`, `tipo`) VALUES
(1, 'Fenicio', 'Fenicio', 'Mozo'),
(2, 'Elipides', 'Elipides', 'Mozo'),
(3, 'Karina', 'Karina', 'Mozo'),
(4, 'Alvaro', 'Alvaro', 'Mozo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `idoperacion` int(11) NOT NULL,
  `idingreso` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`idoperacion`, `idingreso`, `cantidad`) VALUES
(1, 7, 0),
(2, 8, 0),
(3, 9, 1),
(4, 10, 1),
(5, 11, 3),
(6, 12, 0),
(7, 13, 2),
(8, 14, 2),
(9, 15, 1),
(10, 16, 16),
(11, 17, 2),
(12, 18, 5),
(13, 19, 4),
(14, 20, 3),
(15, 21, 4),
(16, 22, 20),
(17, 23, 4),
(18, 24, 4),
(19, 25, 4),
(20, 26, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasteleros`
--

CREATE TABLE `pasteleros` (
  `idpastelero` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pasteleros`
--

INSERT INTO `pasteleros` (`idpastelero`, `nombre`, `pass`, `tipo`) VALUES
(1, 'Leopoldo', 'Leopoldo', 'Pastelero'),
(2, 'Aldo', 'Aldo', 'Pastelero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedido` int(11) NOT NULL,
  `pbtv` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `pbcca` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `ppc` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `pbd` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idpedido`, `pbtv`, `pbcca`, `ppc`, `pbd`, `estado`) VALUES
(1, 'Santa Julia Syrah', 'Stout', 'chorivegano con ensalada Cesar', 'Ensalada de frutas', 'Cerrado'),
(2, 'Los Arboles', 'IPA', 'Seitan a  la parrilla con morrones asados', 'Brownie loco', 'Cerrado'),
(3, 'Daikiri frutal', 'Honey', 'Brocoli al oliva con brotes de soja', 'Torta de manzana', 'Cerrado'),
(4, 'Agua sin gas', 'IPA', 'Brocoli al oliva con brotes de soja y salsa blanca', 'Torta de manzana', 'Cerrado'),
(5, 'Daikiri frutal', 'Quilmes cero', 'Empanadas de seitán', 'Ensalada de frutas', 'Cerrado'),
(6, 'Agua sin gas', 'IPA', 'Tortilla de papas con cheddar', 'Helado de chocolate', 'Cerrado'),
(7, 'Levite pera', 'Stout', 'Chorizaurio de Seitán', 'Alfajor Rincon Vegano', 'Cerrado'),
(8, 'Agua sin gas', 'Stout', 'Seitan', 'Alfajor Rincon Vegano', 'Cerrado'),
(9, 'Estancia de Mendoza Malbec', 'IPA', 'Seitan', 'torta de manzana acaramelada', 'Cerrado'),
(10, '7mo regimiento', 'Cerveza sin alcohol', 'Vegetales salteados con brotes de soja', 'ensalada de frutas', 'Cerrado'),
(11, 'Destornillador', 'Tirada', 'Lentejas', 'brownie loco', 'Cerrado'),
(12, 'Vodka con limon', 'Heineken', 'Faina', 'brownie loco', 'Cerrado'),
(13, 'Speed con Vodka', 'IPA', 'Lentejas', 'brownie loco', 'Cerrado'),
(14, 'Agua sin gas', 'Honey', 'Faina', 'ensalada de frutas', 'Cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE `pendientes` (
  `idpendiente` int(11) NOT NULL,
  `idpedido` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `tipoempleado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horainicio` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horafin` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pendientes`
--

INSERT INTO `pendientes` (`idpendiente`, `idpedido`, `idempleado`, `tipoempleado`, `descripcion`, `horainicio`, `horafin`, `estado`) VALUES
(1, 1, 1, 'Bartender', 'Santa Julia Syrah', '16:09:56', '16:14:56', 'Servido'),
(2, 1, 1, 'Cervecero', 'Stout', '14:09:25', '14:14:25', 'Servido'),
(3, 1, 1, 'Cocinero', 'chorivegano con ensalada Cesar', '15:43:57', '15:48:57', 'Servido'),
(4, 1, 2, 'Pastelero', 'Ensalada de frutas', '15:58:20', '16:03:20', 'Servido'),
(5, 2, 3, 'Bartender', 'Los Arboles', '20:02:56', '20:07:56', 'Servido'),
(6, 2, 1, 'Cervecero', 'IPA', '14:16:12', '14:21:12', 'Servido'),
(7, 2, 1, 'Cocinero', 'Seitan a  la parrilla con morr', '15:55:38', '16:00:38', 'Servido'),
(8, 2, 2, 'Pastelero', 'Brownie loco', '20:04:17', '20:09:17', 'Servido'),
(9, 3, 1, 'Bartender', 'Daikiri frutal', '20:10:04', '20:15:04', 'Servido'),
(10, 3, 1, 'Cervecero', 'Honey', '15:55:26', '16:00:26', 'Servido'),
(11, 3, 2, 'Cocinero', 'Brocoli al oliva con brotes de', '20:12:42', '20:17:42', 'Servido'),
(12, 3, 2, 'Pastelero', 'Torta de manzana', '20:09:29', '20:14:29', 'Servido'),
(13, 4, 1, 'Bartender', 'Agua sin gas', '20:34:18', '20:39:18', 'Servido'),
(14, 4, 1, 'Cervecero', 'IPA', '20:33:38', '20:38:38', 'Servido'),
(15, 4, 1, 'Cocinero', 'Brocoli al oliva con brotes de', '20:14:22', '20:19:22', 'Servido'),
(16, 4, 2, 'Pastelero', 'Torta de manzana', '20:34:08', '20:39:08', 'Servido'),
(17, 5, 1, 'Bartender', 'Daikiri frutal', '19:57:58', '20:02:58', 'Servido'),
(18, 5, 2, 'Cervecero', 'Quilmes cero', '19:55:09', '20:00:09', 'Servido'),
(19, 5, 1, 'Cocinero', 'Empanadas de seitán', '20:32:27', '20:37:27', 'Servido'),
(20, 5, 2, 'Pastelero', 'Ensalada de frutas', '19:59:18', '20:04:18', 'Servido'),
(21, 6, 1, 'Bartender', 'Agua sin gas', '20:10:13', '20:15:13', 'Servido'),
(22, 6, 2, 'Cervecero', 'IPA', '19:55:48', '20:00:48', 'Servido'),
(23, 6, 1, 'Cocinero', 'Tortilla de papas con cheddar', '18:43:52', '18:48:52', 'Servido'),
(24, 6, 2, 'Pastelero', 'Helado de chocolate', '20:10:33', '20:15:33', 'Servido'),
(25, 7, 3, 'Bartender', 'Levite pera', '18:47:38', '18:52:38', 'Servido'),
(26, 7, 2, 'Cervecero', 'Stout', '19:55:50', '20:00:50', 'Servido'),
(27, 7, 1, 'Cocinero', 'Chorizaurio de Seitán', '18:44:09', '18:49:09', 'Servido'),
(28, 7, 1, 'Pastelero', 'Alfajor Rincon Vegano', '18:52:25', '18:57:25', 'Servido'),
(29, 8, 3, 'Bartender', 'Agua sin gas', '18:47:46', '18:52:46', 'Servido'),
(30, 8, 2, 'Cervecero', 'Stout', '18:49:48', '18:54:48', 'Servido'),
(31, 8, 1, 'Cocinero', 'Seitan', '18:44:16', '18:49:16', 'Servido'),
(32, 8, 1, 'Pastelero', 'Alfajor Rincon Vegano', '18:52:33', '18:57:33', 'Servido'),
(33, 9, 3, 'Bartender', 'Estancia de Mendoza Malbec', '18:47:54', '18:52:54', 'Servido'),
(34, 9, 2, 'Cervecero', 'IPA', '18:50:02', '18:55:02', 'Servido'),
(35, 9, 1, 'Cocinero', 'Seitan', '18:44:25', '18:49:25', 'Servido'),
(36, 9, 1, 'Pastelero', 'torta de manzana acaramelada', '18:52:36', '18:57:36', 'Servido'),
(37, 10, 3, 'Bartender', '7mo regimiento', '19:40:15', '19:45:15', 'Servido'),
(38, 10, 2, 'Cervecero', 'Cerveza sin alcohol', '19:40:26', '19:45:26', 'Servido'),
(39, 10, 1, 'Cocinero', 'Vegetales salteados con brotes', '19:39:49', '19:44:49', 'Servido'),
(40, 10, 1, 'Pastelero', 'ensalada de frutas', '19:40:37', '19:45:37', 'Servido'),
(41, 11, 2, 'Bartender', 'Destornillador', '11:36:46', '11:41:46', 'Servido'),
(42, 11, 2, 'Cervecero', 'Tirada', '11:38:11', '11:43:11', 'Servido'),
(43, 11, 2, 'Cocinero', 'Lentejas', '11:30:45', '11:35:45', 'Servido'),
(44, 11, 2, 'Pastelero', 'brownie loco', '11:39:26', '11:44:26', 'Servido'),
(45, 12, 2, 'Bartender', 'Vodka con limon', '11:36:54', '11:41:54', 'Servido'),
(46, 12, 2, 'Cervecero', 'Heineken', '11:38:17', '11:43:17', 'Servido'),
(47, 12, 2, 'Cocinero', 'Faina', '11:30:54', '11:35:54', 'Servido'),
(48, 12, 2, 'Pastelero', 'brownie loco', '11:39:31', '11:44:31', 'Servido'),
(49, 13, 2, 'Bartender', 'Speed con Vodka', '11:36:56', '11:41:56', 'Servido'),
(50, 13, 2, 'Cervecero', 'IPA', '11:38:19', '11:43:19', 'Servido'),
(51, 13, 2, 'Cocinero', 'Lentejas', '11:30:58', '11:35:58', 'Servido'),
(52, 13, 2, 'Pastelero', 'brownie loco', '11:39:34', '11:44:34', 'Servido'),
(53, 14, 2, 'Bartender', 'Agua sin gas', '11:36:59', '11:41:59', 'Servido'),
(54, 14, 2, 'Cervecero', 'Honey', '11:38:21', '11:43:21', 'Servido'),
(55, 14, 2, 'Cocinero', 'Fugazza y Faina', '11:31:02', '11:36:02', 'Servido'),
(56, 14, 2, 'Pastelero', 'ensalada de frutas', '11:39:37', '11:44:37', 'Servido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE `socios` (
  `idsocio` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`idsocio`, `nombre`, `pass`, `tipo`) VALUES
(1, 'admin', 'admin', 'Socio'),
(5, 'Mauro', 'Mauro', 'Socio'),
(6, 'Jose', 'Jose', 'Socio'),
(7, 'Juanjo', 'Juanjo', 'Socio');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bartenders`
--
ALTER TABLE `bartenders`
  ADD PRIMARY KEY (`idbartender`);

--
-- Indices de la tabla `cerveceros`
--
ALTER TABLE `cerveceros`
  ADD PRIMARY KEY (`idcervecero`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `cocineros`
--
ALTER TABLE `cocineros`
  ADD PRIMARY KEY (`idcocinero`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`idcomanda`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`idingreso`);

--
-- Indices de la tabla `mozos`
--
ALTER TABLE `mozos`
  ADD PRIMARY KEY (`idmozo`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`idoperacion`);

--
-- Indices de la tabla `pasteleros`
--
ALTER TABLE `pasteleros`
  ADD PRIMARY KEY (`idpastelero`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idpedido`);

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`idpendiente`);

--
-- Indices de la tabla `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`idsocio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bartenders`
--
ALTER TABLE `bartenders`
  MODIFY `idbartender` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cerveceros`
--
ALTER TABLE `cerveceros`
  MODIFY `idcervecero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cocineros`
--
ALTER TABLE `cocineros`
  MODIFY `idcocinero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idcomanda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `idingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `mozos`
--
ALTER TABLE `mozos`
  MODIFY `idmozo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `idoperacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pasteleros`
--
ALTER TABLE `pasteleros`
  MODIFY `idpastelero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `idpendiente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `socios`
--
ALTER TABLE `socios`
  MODIFY `idsocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
