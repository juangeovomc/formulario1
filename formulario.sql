-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2024 a las 04:58:06
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
-- Base de datos: `formulario`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `enviar_notificacion` (IN `id_estudiante` INT, IN `mensaje` VARCHAR(255))   BEGIN
    -- Lógica para enviar notificación, por ejemplo, usando un sistema de correos
    INSERT INTO notificaciones (id_estudiante, mensaje, fecha)
    VALUES (id_estudiante, mensaje, NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

CREATE TABLE `alertas` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alertas`
--

INSERT INTO `alertas` (`id`, `id_estudiante`, `mensaje`, `fecha`) VALUES
(1, 1, 'Inscripción incompleta', '2024-12-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `calificacion_final` decimal(5,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `calificacion` decimal(5,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `evaluaciones`
--
DELIMITER $$
CREATE TRIGGER `registrar_calificacion_final` AFTER INSERT ON `evaluaciones` FOR EACH ROW BEGIN
    DECLARE calificacion_final DECIMAL(5,2);
    -- Cálculo de la calificación final (puede variar según tus reglas)
    SET calificacion_final = (SELECT AVG(calificacion) FROM evaluaciones WHERE id_estudiante = NEW.id_estudiante);
    INSERT INTO calificaciones (id_estudiante, calificacion_final) VALUES (NEW.id_estudiante, calificacion_final);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `curso` varchar(255) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `horarios`
--
DELIMITER $$
CREATE TRIGGER `notificar_cambio_horario` AFTER UPDATE ON `horarios` FOR EACH ROW BEGIN
    -- Aquí puedes agregar un procedimiento para enviar un correo o una notificación
    CALL enviar_notificacion(NEW.id_estudiante, 'Cambio en el horario del curso');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `completada` int(11) DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `id_estudiante`, `completada`, `fecha_inscripcion`) VALUES
(1, 1, 0, '2024-12-09');

--
-- Disparadores `inscripciones`
--
DELIMITER $$
CREATE TRIGGER `alertar_inscripcion_incompleta` AFTER INSERT ON `inscripciones` FOR EACH ROW BEGIN
    IF NEW.completada = 0 THEN
        -- Insertar alerta para el estudiante con inscripción incompleta
        INSERT INTO alertas (id_estudiante, mensaje, fecha)
        VALUES (NEW.id_estudiante, 'Inscripción incompleta', NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `alertar_inscripcion_incompleta_update` AFTER UPDATE ON `inscripciones` FOR EACH ROW BEGIN
    IF NEW.completada = 0 AND OLD.completada != 0 THEN
        -- Insertar alerta cuando una inscripción previamente completa se marca como incompleta
        INSERT INTO alertas (id_estudiante, mensaje, fecha)
        VALUES (NEW.id_estudiante, 'Inscripción marcada como incompleta', NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `edad` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `genero` enum('Masculino','Femenino','Otro') NOT NULL,
  `intereses` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
