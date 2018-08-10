-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-08-2018 a las 19:47:20
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
(2, 'Javier', 'Javier', 'Bartender');

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
(1, 'Junior', 'Junior', 'Cervecero');

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
(3, 10002, 'nbrbq', 3);

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
(1, 'Adriana', 'Adriana', 'Cocinero');

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
  `horafin` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`idcomanda`, `idmozo`, `nombrecliente`, `idpedido`, `fotomesa`, `horaini`, `importe`, `horafin`) VALUES
(1, 1, 'Franco', 1, 'Franco.jpg', '18:36:18', 0, ''),
(2, 1, 'Luisa', 2, 'Luisa.jpg', '18:40:52', 0, ''),
(3, 1, 'Fede', 3, 'Fede.jpg', '14:17:12', 0, '');

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
(3, 'Karina', 'Karina', 'Mozo');

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
(1, 'Leopoldo', 'Leopoldo', 'Pastelero');

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
(1, 'Santa Julia Syrah', 'Stout', 'chorivegano con ensalada Cesar', 'Ensalada de frutas', 'Pendiente'),
(2, 'Los Arboles', 'IPA', 'Seitan a  la parrilla con morrones asados', 'Brownie loco', 'Pendiente'),
(3, 'Daikiri frutal', 'Honey', 'Brocoli al oliva con brotes de soja', 'Torta de manzana', 'Pendiente');

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
(1, 1, 0, 'Bartender', 'Santa Julia Syrah', '00:00', '00:00', 'Pendiente'),
(2, 1, 0, 'Cervecero', 'Stout', '00:00', '00:00', 'Pendiente'),
(3, 1, 0, 'Cocinero', 'chorivegano con ensalada Cesar', '00:00', '00:00', 'Pendiente'),
(4, 1, 0, 'Pastelero', 'Ensalada de frutas', '00:00', '00:00', 'Pendiente'),
(5, 2, 0, 'Bartender', 'Los Arboles', '00:00', '00:00', 'Pendiente'),
(6, 2, 0, 'Cervecero', 'IPA', '00:00', '00:00', 'Pendiente'),
(7, 2, 0, 'Cocinero', 'Seitan a  la parrilla con morr', '00:00', '00:00', 'Pendiente'),
(8, 2, 0, 'Pastelero', 'Brownie loco', '00:00', '00:00', 'Pendiente'),
(9, 3, 0, 'Bartender', 'Daikiri frutal', '00:00', '00:00', 'Pendiente'),
(10, 3, 0, 'Cervecero', 'Honey', '00:00', '00:00', 'Pendiente'),
(11, 3, 0, 'Cocinero', 'Brocoli al oliva con brotes de', '00:00', '00:00', 'Pendiente'),
(12, 3, 0, 'Pastelero', 'Torta de manzana', '00:00', '00:00', 'Pendiente');

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
(3, 'Alicia', 'Alicia', 'Socio'),
(5, 'Lucas', 'Lucas', 'Socio'),
(6, 'Jose', 'Jose', 'Socio');

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
-- Indices de la tabla `mozos`
--
ALTER TABLE `mozos`
  ADD PRIMARY KEY (`idmozo`);

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
  MODIFY `idbartender` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cerveceros`
--
ALTER TABLE `cerveceros`
  MODIFY `idcervecero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cocineros`
--
ALTER TABLE `cocineros`
  MODIFY `idcocinero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idcomanda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mozos`
--
ALTER TABLE `mozos`
  MODIFY `idmozo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pasteleros`
--
ALTER TABLE `pasteleros`
  MODIFY `idpastelero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `idpendiente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `socios`
--
ALTER TABLE `socios`
  MODIFY `idsocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
