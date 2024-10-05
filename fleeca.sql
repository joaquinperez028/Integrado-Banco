-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2024 a las 02:15:29
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
-- Base de datos: `fleeca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `nombreCuenta` varchar(20) NOT NULL,
  `numeroCuenta` int(8) NOT NULL,
  `saldoCuenta` int(8) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`nombreCuenta`, `numeroCuenta`, `saldoCuenta`, `idUsuario`) VALUES
('3', 3362157, 55400, 1),
('prueba', 7490840, 34330, 1),
('AhorroDolares', 17320324, 53800, 1),
('6', 17504080, 35529, 1),
('hola', 19584229, 63510, 1),
('hola', 40443789, 503, 2),
('prueba2', 52183794, 3323, 2),
('4', 65681857, 32125, 2),
('prueba3 Cuenta', 71189489, 21950, 5),
('3', 75608777, 88759, 2),
('6', 85523707, 24748, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias`
--

CREATE TABLE `transferencias` (
  `id` int(11) NOT NULL,
  `numeroCuentaRemitente` char(8) NOT NULL,
  `numeroCuentaDestino` char(8) NOT NULL,
  `saldoEnviado` int(11) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transferencias`
--

INSERT INTO `transferencias` (`id`, `numeroCuentaRemitente`, `numeroCuentaDestino`, `saldoEnviado`, `concepto`, `fecha`) VALUES
(4, '19584229', '30488579', 5, 'a', '2024-09-17 05:48:05'),
(5, '19584229', '30488579', 10, 'prueba2', '2024-09-17 05:50:42'),
(6, '7490840', '30488579', 7, 'prueba3', '2024-09-17 05:51:07'),
(7, '17320324', '65681857', 2, 'prueba4', '2024-09-18 03:13:12'),
(8, '7490840', '65681857', 10, 'prueba6', '2024-09-18 04:28:41'),
(9, '3362157', '52183794', 38, 'prueba43', '2024-10-05 00:08:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `opcionSeguridad` varchar(50) NOT NULL,
  `respuestaSeguridad` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `name`, `password`, `opcionSeguridad`, `respuestaSeguridad`) VALUES
(1, 'admin@admin.com', 'admin', '$2y$10$MQbdmXXmfW3sSEaMRH4sr.auZwpxDS8S8eieqx8VyCd3Ljz4/d69i', 'Dibujo animado favorito', 'admin'),
(2, 'tortugaalpan@gmail.com', 'Tortu', '$2y$10$26kqybeHVdFKovdSkjdCvO50x.FNARMwd5ElXwG3gfLXlvLNIhSgG', 'Dibujo animado favorito', 'one piece'),
(3, 'asd@gmail.com', 'asd', '$2y$10$7EpVf9CGynh8FgUF6eRkuOs7YNoa19M2pD2X7S0QY5pqcbRKu3hBS', 'Dibujo animado favorito', 'onepiece'),
(4, 'admin2@admin.com', 'admin2', '$2y$10$w65gE7vHbn.BOBO9R1tPe.N4odigxBdVVpW.GmPZCNUCMdgtW/EqK', 'Dibujo animado favorito', 'one piece'),
(5, 'prueba3@gmail.com', 'prueba3', '$2y$10$RsD765s2iTiCw7XK3xjMKOaUMy0jW.auwETGp/Y2oGUcKckLbjXyy', 'Dibujo animado favorito', 'Hunter x Hunter');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`numeroCuenta`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numeroCuentaRemitente` (`numeroCuentaRemitente`),
  ADD KEY `numeroCuentaDestino` (`numeroCuentaDestino`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`,`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
