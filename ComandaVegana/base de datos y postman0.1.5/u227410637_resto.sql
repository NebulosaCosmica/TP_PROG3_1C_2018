
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-10-2018 a las 13:53:43
-- Versión del servidor: 10.1.22-MariaDB
-- Versión de PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `u227410637_resto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bartenders`
--

CREATE TABLE IF NOT EXISTS `bartenders` (
  `idbartender` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idbartender`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

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

CREATE TABLE IF NOT EXISTS `cerveceros` (
  `idcervecero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcervecero`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3 ;

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

CREATE TABLE IF NOT EXISTS `clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `codigomesa` int(11) NOT NULL,
  `codigopedido` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `idpedido` int(11) NOT NULL,
  PRIMARY KEY (`idcliente`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idcliente`, `codigomesa`, `codigopedido`, `idpedido`) VALUES
(12, 10011, 'h6xv6', 12),
(13, 10012, 'znaur', 13),
(14, 10013, 'w8b34', 14),
(15, 10014, 'bj7gy', 15),
(16, 10015, 'uyfhm', 16),
(17, 10016, 'qydbp', 17),
(18, 10017, 'ydr1b', 18),
(19, 10018, 'wcdb6', 19),
(20, 10019, '49h3u', 20),
(21, 10020, '1xkql', 21),
(22, 10021, 'fe67o', 22),
(23, 10022, 'n1oc1', 23),
(24, 10023, 'gehts', 24),
(25, 10024, 'u63li', 25),
(26, 10025, '9v50w', 26),
(27, 10026, '44qsd', 27),
(28, 10027, 'h0lmc', 28),
(29, 10028, 'pkrxf', 29);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cocineros`
--

CREATE TABLE IF NOT EXISTS `cocineros` (
  `idcocinero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcocinero`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3 ;

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

CREATE TABLE IF NOT EXISTS `comandas` (
  `idcomanda` int(11) NOT NULL AUTO_INCREMENT,
  `idmozo` int(11) NOT NULL,
  `nombrecliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `idpedido` int(11) NOT NULL,
  `fotomesa` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horaini` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `importe` float NOT NULL,
  `horafin` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcomanda`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`idcomanda`, `idmozo`, `nombrecliente`, `idpedido`, `fotomesa`, `horaini`, `importe`, `horafin`, `fecha`) VALUES
(12, 4, 'Graciela', 12, 'Graciela.jpg', '10:46:08', 1400, '12:22:17', '09/10/2018'),
(13, 4, 'Lolo', 13, 'Lolo.jpg', '11:28:07', 1900, '12:22:23', '09/10/2018'),
(14, 4, 'Aida', 14, 'Aida.jpg', '11:30:18', 800, '12:22:28', '09/10/2018'),
(15, 4, 'Roberto', 15, 'Roberto.jpg', '08:43:08', 1200, '14:04:37', '10/10/2018'),
(16, 4, 'Alejandra', 16, 'Alejandra.png', '08:47:08', 850, '14:05:50', '10/10/2018'),
(17, 4, 'Patricio', 17, 'Patricio.jpg', '08:51:53', 750, '14:05:54', '10/10/2018'),
(18, 3, 'Patricia', 18, 'Patricia.jpg', '16:40:29', 1750, '19:50:54', '11/10/2018'),
(19, 2, 'German', 19, 'German.jpg', '19:15:42', 1600, '20:18:34', '11/10/2018'),
(20, 1, 'Adelaida', 20, 'Adelaida.jpg', '20:11:06', 1250, '21:32:45', '11/10/2018'),
(21, 1, 'Maria', 21, 'Maria.jpg', '20:36:25', 1750, '19:29:59', '11/10/2018'),
(22, 1, 'JoseMaria', 22, 'JoseMaria.jpg', '20:47:33', 1850, '19:29:35', '11/10/2018'),
(23, 1, 'Celeste', 23, 'Celeste.jpg', '21:09:55', 2000, '19:29:39', '11/10/2018'),
(24, 1, 'Franchesca', 24, 'Franchesca.jpg', '21:26:59', 1600, '19:29:42', '11/10/2018'),
(25, 1, 'Melisa', 25, 'Melisa.jpg', '21:29:21', 1100, '19:29:46', '11/10/2018'),
(26, 1, 'Tete', 26, 'Tete.jpg', '08:00:52', 1300, '19:39:34', '16/10/2018'),
(27, 2, 'CarlosFontana', 27, 'CarlosFontana.png', '19:02:01', 1800, '19:39:38', '16/10/2018'),
(28, 4, 'AdrianaAguayo', 28, 'AdrianaAguayo.png', '19:44:03', 1550, '20:11:54', '16/10/2018'),
(29, 4, 'PedroPerez', 29, 'PedroPerez.png', '20:07:52', 1450, '20:10:46', '16/10/2018');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipoempleado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `idempleado` int(11) NOT NULL,
  PRIMARY KEY (`idingreso`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=51 ;

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
(26, '09/10/2018', 'Pastelero', 2),
(27, '10/10/2018', 'Mozo', 4),
(28, '10/10/2018', 'Cervecero', 2),
(29, '10/10/2018', 'Cocinero', 2),
(30, '10/10/2018', 'Bartender', 3),
(31, '10/10/2018', 'Pastelero', 2),
(32, '11/10/2018', 'Mozo', 3),
(33, '11/10/2018', 'Mozo', 2),
(34, '11/10/2018', 'Cervecero', 2),
(35, '11/10/2018', 'Cocinero', 2),
(36, '11/10/2018', 'Bartender', 3),
(37, '11/10/2018', 'Pastelero', 2),
(38, '11/10/2018', 'Mozo', 1),
(50, '16/10/2018', 'Pastelero', 2),
(49, '16/10/2018', 'Bartender', 2),
(48, '16/10/2018', 'Cocinero', 2),
(47, '16/10/2018', 'Cervecero', 2),
(46, '16/10/2018', 'Mozo', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mozos`
--

CREATE TABLE IF NOT EXISTS `mozos` (
  `idmozo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idmozo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

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

CREATE TABLE IF NOT EXISTS `operaciones` (
  `idoperacion` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`idoperacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=45 ;

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
(20, 26, 4),
(21, 27, 9),
(22, 28, 3),
(23, 29, 3),
(24, 30, 3),
(25, 31, 3),
(26, 32, 3),
(27, 33, 12),
(28, 34, 13),
(29, 35, 11),
(30, 36, 11),
(31, 37, 11),
(32, 38, 6),
(33, 39, 1),
(34, 40, 2),
(35, 41, 12),
(36, 42, 3),
(37, 43, 6),
(38, 44, 6),
(39, 45, 6),
(40, 46, 6),
(41, 47, 4),
(42, 48, 4),
(43, 49, 4),
(44, 50, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasteleros`
--

CREATE TABLE IF NOT EXISTS `pasteleros` (
  `idpastelero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idpastelero`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3 ;

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

CREATE TABLE IF NOT EXISTS `pedidos` (
  `idpedido` int(11) NOT NULL AUTO_INCREMENT,
  `pbtv` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `pbcca` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `ppc` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `pbd` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idpedido`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idpedido`, `pbtv`, `pbcca`, `ppc`, `pbd`, `estado`) VALUES
(12, 'Vodka con limon', 'Heineken', 'Faina', 'brownie loco', 'Cerrado'),
(13, 'Speed con Vodka', 'IPA', 'Lentejas', 'brownie loco', 'Cerrado'),
(14, 'Agua sin gas', 'Honey', 'Faina', 'ensalada de frutas', 'Cerrado'),
(15, 'Tinto Dada', 'Stout', 'Salteados', 'ensalada de frutas', 'Cerrado'),
(16, 'Sprite zero', 'Honey', 'Roll', 'brownie loco', 'Cerrado'),
(17, 'Agua sin gas', 'IPA', 'Roll', 'torta de manzana', 'Cerrado'),
(18, 'Daikiri frutal', 'Quilmes', 'Seitan', 'ensalada de frutas', 'Cerrado'),
(19, 'Mojito', 'Scotish Red', 'Lentejas', 'Alfajor', 'Cerrado'),
(20, 'Agua sin gas', 'IPA', 'Lentejas', 'brownie', 'Cerrado'),
(21, 'Destornillador', 'Stout', 'Lentejas', 'ensalada de frutas', 'Cerrado'),
(22, 'Pantera Rosa', 'IPA', 'Lentejas', 'brownie', 'Cerrado'),
(23, 'Martini', 'Stout', 'Seitan', 'torta de manzana', 'Cerrado'),
(24, 'Vodka con Speed', 'IPA', 'Roll', 'ensalada de frutas', 'Cerrado'),
(25, 'Agua con gas', 'Camaleon', 'Faina', 'Pochoclos y garrapiñadas', 'Cerrado'),
(26, 'Sprite', 'Honey', 'Seitan', 'Helado de chocolate', 'Cerrado'),
(27, 'Destornillador', 'IPA', 'Seitan', 'Ensalada de frutas', 'Cerrado'),
(28, 'Ron con cola', 'Stout', 'Roll', 'Brownie', 'Cerrado'),
(29, 'Agua sin gas', 'IPA', 'Faina', 'Ensalada de frutas', 'Cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE IF NOT EXISTS `pendientes` (
  `idpendiente` int(11) NOT NULL AUTO_INCREMENT,
  `idpedido` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `tipoempleado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horainicio` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `horafin` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idpendiente`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=117 ;

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
(56, 14, 2, 'Pastelero', 'ensalada de frutas', '11:39:37', '11:44:37', 'Servido'),
(57, 15, 3, 'Bartender', 'Tinto Dada', '09:18:03', '09:23:03', 'Servido'),
(58, 15, 2, 'Cervecero', 'Stout', '09:08:49', '09:13:49', 'Servido'),
(59, 15, 2, 'Cocinero', 'Salteados', '09:11:48', '09:16:48', 'Servido'),
(60, 15, 2, 'Pastelero', 'ensalada de frutas', '09:15:12', '09:20:12', 'Servido'),
(61, 16, 3, 'Bartender', 'Sprite zero', '09:18:08', '09:23:08', 'Servido'),
(62, 16, 2, 'Cervecero', 'Honey', '09:09:32', '09:14:32', 'Servido'),
(63, 16, 2, 'Cocinero', 'Roll', '09:13:15', '09:18:15', 'Servido'),
(64, 16, 2, 'Pastelero', 'brownie loco', '09:15:16', '09:20:16', 'Servido'),
(65, 17, 3, 'Bartender', 'Agua sin gas', '09:18:19', '09:23:19', 'Servido'),
(66, 17, 2, 'Cervecero', 'IPA', '09:09:34', '09:14:34', 'Servido'),
(67, 17, 2, 'Cocinero', 'Roll', '09:13:20', '09:18:20', 'Servido'),
(68, 17, 2, 'Pastelero', 'torta de manzana', '09:15:20', '09:20:20', 'Servido'),
(71, 18, 2, 'Cocinero', 'Seitan', '19:26:26', '19:31:26', 'Servido'),
(70, 18, 2, 'Cervecero', 'Quilmes', '19:25:52', '19:30:52', 'Servido'),
(69, 18, 3, 'Bartender', 'Daikiri frutal', '19:26:52', '19:31:52', 'Servido'),
(72, 18, 2, 'Pastelero', 'ensalada de frutas', '19:27:13', '19:32:13', 'Servido'),
(73, 19, 3, 'Bartender', 'Mojito', '19:33:08', '19:35:08', 'Servido'),
(74, 19, 2, 'Cervecero', 'Scotish Red', '19:30:13', '19:32:13', 'Servido'),
(75, 19, 2, 'Cocinero', 'Lentejas', '19:33:04', '19:35:04', 'Servido'),
(76, 19, 2, 'Pastelero', 'Alfajor', '19:33:11', '19:35:11', 'Servido'),
(77, 20, 3, 'Bartender', 'Agua sin gas', '20:12:21', '20:14:21', 'Servido'),
(78, 20, 2, 'Cervecero', 'IPA', '20:11:39', '20:13:39', 'Servido'),
(79, 20, 2, 'Cocinero', 'Lentejas', '20:12:17', '20:14:17', 'Servido'),
(80, 20, 2, 'Pastelero', 'brownie', '20:12:23', '20:14:23', 'Servido'),
(102, 26, 2, 'Cervecero', 'Honey', '08:56:23', '19:10:06', 'Servido'),
(81, 21, 3, 'Bartender', 'Destornillador', '21:25:16', '21:25:21', 'Servido'),
(82, 21, 2, 'Cervecero', 'Stout', '20:36:56', '20:46:41', 'Servido'),
(83, 21, 2, 'Cocinero', 'Lentejas', '21:11:31', '21:18:10', 'Servido'),
(84, 21, 2, 'Pastelero', 'ensalada de frutas', '21:25:38', '21:25:57', 'Servido'),
(85, 22, 3, 'Bartender', 'Pantera Rosa', '21:27:49', '21:28:13', 'Servido'),
(86, 22, 2, 'Cervecero', 'IPA', '20:47:46', '20:47:52', 'Servido'),
(87, 22, 2, 'Cocinero', 'Lentejas', '21:17:03', '21:24:26', 'Servido'),
(88, 22, 2, 'Pastelero', 'brownie', '21:28:01', '21:28:31', 'Servido'),
(89, 23, 3, 'Bartender', 'Martini', '21:27:51', '21:28:17', 'Servido'),
(90, 23, 2, 'Cervecero', 'Stout', '21:10:23', '21:10:45', 'Servido'),
(91, 23, 2, 'Cocinero', 'Seitan', '21:17:51', '21:24:49', 'Servido'),
(92, 23, 2, 'Pastelero', 'torta de manzana', '21:28:03', '21:28:33', 'Servido'),
(93, 24, 3, 'Bartender', 'Vodka con Speed', '21:29:55', '21:29:58', 'Servido'),
(94, 24, 2, 'Cervecero', 'IPA', '21:29:39', '21:29:43', 'Servido'),
(95, 24, 2, 'Cocinero', 'Roll', '21:29:47', '21:29:50', 'Servido'),
(96, 24, 2, 'Pastelero', 'ensalada de frutas', '21:28:23', '21:31:37', 'Servido'),
(101, 26, 1, 'Bartender', 'Sprite', '19:37:40', '19:37:54', 'Servido'),
(97, 25, 1, 'Bartender', 'Agua con gas', '19:20:38', '19:21:03', 'Servido'),
(98, 25, 2, 'Cervecero', 'Camaleon', '21:54:09', '21:54:23', 'Servido'),
(99, 25, 1, 'Cocinero', 'Faina', '19:15:43', '19:17:34', 'Servido'),
(100, 25, 1, 'Pastelero', 'Pochoclos y garrapiñadas', '19:22:01', '19:22:38', 'Servido'),
(103, 26, 1, 'Cocinero', 'Seitan', '19:37:18', '19:37:32', 'Servido'),
(104, 26, 1, 'Pastelero', 'Helado de chocolate', '19:38:03', '19:38:13', 'Servido'),
(105, 27, 1, 'Bartender', 'Destornillador', '19:37:44', '19:37:56', 'Servido'),
(106, 27, 2, 'Cervecero', 'IPA', '19:09:29', '19:35:30', 'Servido'),
(107, 27, 1, 'Cocinero', 'Seitan', '19:37:22', '19:37:34', 'Servido'),
(108, 27, 1, 'Pastelero', 'Ensalada de frutas', '19:38:07', '19:38:15', 'Servido'),
(110, 28, 2, 'Cervecero', 'Stout', '19:52:35', '19:57:17', 'Servido'),
(109, 28, 2, 'Bartender', 'Ron con cola', '19:56:06', '19:58:26', 'Servido'),
(111, 28, 2, 'Cocinero', 'Roll', '19:54:38', '19:57:46', 'Servido'),
(112, 28, 2, 'Pastelero', 'Brownie', '19:59:08', '19:59:17', 'Servido'),
(113, 29, 2, 'Bartender', 'Agua sin gas', '20:09:44', '20:09:49', 'Servido'),
(114, 29, 2, 'Cervecero', 'IPA', '20:08:37', '20:09:00', 'Servido'),
(115, 29, 2, 'Cocinero', 'Faina', '20:09:32', '20:09:37', 'Servido'),
(116, 29, 2, 'Pastelero', 'Ensalada de frutas', '20:09:55', '20:10:01', 'Servido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE IF NOT EXISTS `socios` (
  `idsocio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idsocio`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`idsocio`, `nombre`, `pass`, `tipo`) VALUES
(1, 'admin', 'admin', 'Socio'),
(5, 'Mauro', 'Mauro', 'Socio'),
(6, 'Jose', 'Jose', 'Socio'),
(7, 'Juanjo', 'Juanjo', 'Socio');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
