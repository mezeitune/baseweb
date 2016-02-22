-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-10-2014 a las 11:17:28
-- Versión del servidor: 5.1.46-community
-- Versión de PHP: 5.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `proy_base`
--
CREATE DATABASE IF NOT EXISTS `proy_base` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `proy_base`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `borrarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `borrarUsuario`(IN p_id INT(11))
BEGIN
	
	UPDATE usuarios SET estado = 'X' WHERE id = p_id;

END$$

DROP PROCEDURE IF EXISTS `cambiarPass`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambiarPass`(IN p_id INT(11), IN p_pass VARCHAR(45))
BEGIN
	
	UPDATE usuarios
	SET pass = p_pass,
		cambiar_pass = 'N'
	WHERE id = p_id;

END$$

DROP PROCEDURE IF EXISTS `confirmarLogin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `confirmarLogin`(p_id INTEGER(11),
								p_hash VARCHAR(100))
BEGIN

	UPDATE usuarios
	SET hash = p_hash
	WHERE id = p_id;

END$$

DROP PROCEDURE IF EXISTS `login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `login`(p_username VARCHAR(100),
							   p_pass VARCHAR(100))
BEGIN

	SELECT *
	FROM usuarios
	WHERE username = p_username
	  AND pass = p_pass
	-- Filtro los eliminados
	  AND estado != 'X';

END$$

DROP PROCEDURE IF EXISTS `logout`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `logout`(p_id INTEGER(11),
						  p_hash VARCHAR(100))
BEGIN

	UPDATE usuarios
	SET hash = null
	WHERE id = p_id
      AND hash = p_hash;

END$$

DROP PROCEDURE IF EXISTS `obtOpcionesMenu`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtOpcionesMenu`()
BEGIN
	SELECT *
	FROM opciones_menu
	ORDER BY orden IS NULL, orden;
END$$

DROP PROCEDURE IF EXISTS `obtOpcionMenuClave`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtOpcionMenuClave`(
	IN p_clave VARCHAR(100))
BEGIN
	SELECT *
	FROM opciones_menu
	WHERE clave = p_clave;
END$$

DROP PROCEDURE IF EXISTS `obtParametros`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtParametros`()
BEGIN
	  SELECT *
	  FROM parametros;
END$$

DROP PROCEDURE IF EXISTS `obtUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtUsuario`(IN p_id INT(11))
BEGIN
	
	SELECT *
	FROM usuarios
	WHERE id = p_id  AND estado != 'X';

END$$

DROP PROCEDURE IF EXISTS `obtUsuariosNivel`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtUsuariosNivel`(IN p_nivel VARCHAR(10))
BEGIN
	
	SELECT *
	FROM usuarios
	WHERE SUBSTRING_INDEX(p_nivel, nivel, 1) != p_nivel AND estado != 'X';

END$$

DROP PROCEDURE IF EXISTS `resetPass`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `resetPass`(IN p_username VARCHAR(45), IN p_pass VARCHAR(45))
BEGIN
	
	UPDATE usuarios
	SET pass  = p_pass,
		cambiar_pass = 'S'
	WHERE username = p_username
		AND estado != 'X';

	SELECT *
	FROM usuarios
	WHERE username = p_username
		AND estado != 'X';

END$$

DROP PROCEDURE IF EXISTS `verificarLogin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verificarLogin`(p_id INTEGER(11),
							   p_hash VARCHAR(100))
BEGIN

	SELECT *
	FROM usuarios
	WHERE id = p_id
	  AND estado != 'X'
	  AND hash = p_hash;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_menu`
--

DROP TABLE IF EXISTS `opciones_menu`;
CREATE TABLE IF NOT EXISTS `opciones_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(45) DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `texto` varchar(100) DEFAULT NULL,
  `url` varchar(450) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `nivel` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `opciones_menu`
--

INSERT INTO `opciones_menu` (`id`, `clave`, `padre_id`, `texto`, `url`, `orden`, `nivel`) VALUES
(1, 'HOME', NULL, 'Inicio', 'Home', 1, 'AO'),
(2, 'OTRO', NULL, 'Otro', 'Otro', 2, 'A'),
(3, 'otro a', 2, 'A', 'Otro', NULL, 'A'),
(4, 'otro b', 2, 'B', 'Otro', NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

DROP TABLE IF EXISTS `parametros`;
CREATE TABLE IF NOT EXISTS `parametros` (
  `id` varchar(30) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `valor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id`, `descripcion`, `valor`) VALUES
('FROM_MAILS', 'Email del que se envian los correos electronicos', 'Sistema <sistema@sys.com>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `pass` varchar(45) NOT NULL,
  `cambiar_pass` varchar(1) DEFAULT 'S',
  `hash` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `nivel` varchar(45) NOT NULL DEFAULT 'A',
  `alta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `pass`, `cambiar_pass`, `hash`, `email`, `nivel`, `alta`, `estado`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 'N', '23f86f86b0339228ff043ed7c49cc104', 'ariasagustin1@gmail.com', 'A', '2013-09-23 05:26:35', 'A');
