-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2018 a las 02:55:55
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
