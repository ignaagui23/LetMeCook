-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2025 a las 05:44:00
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
-- Base de datos: `letmecookdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `usuario_id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `medicion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`id`, `nombre`, `medicion_id`) VALUES
(1, 'Harina', 1),
(2, 'Azúcar', 1),
(3, 'Sal', 1),
(4, 'Levadura seca', 7),
(5, 'Huevos', 7),
(6, 'Leche', 3),
(7, 'Aceite de oliva', 6),
(8, 'Manteca', 1),
(9, 'Agua', 3),
(10, 'Pimienta negra', 1),
(11, 'Cebolla', 7),
(12, 'Ajo', 7),
(13, 'Tomate', 7),
(14, 'Queso rallado', 1),
(15, 'Perejil fresco', 7),
(16, 'Jamón cocido', 1),
(17, 'Queso', 1),
(18, 'Crema de leche', 3),
(19, 'Pimienta', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mediciones`
--

CREATE TABLE `mediciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('peso','volumen','cantidad') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mediciones`
--

INSERT INTO `mediciones` (`id`, `nombre`, `tipo`) VALUES
(1, 'gramos', 'peso'),
(2, 'kilogramos', 'peso'),
(3, 'mililitros', 'volumen'),
(4, 'litros', 'volumen'),
(5, 'tazas', 'volumen'),
(6, 'cucharadas', 'volumen'),
(7, 'unidades', 'cantidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasos`
--

CREATE TABLE `pasos` (
  `id` int(11) NOT NULL,
  `receta_id` int(11) DEFAULT NULL,
  `numero_paso` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pasos`
--

INSERT INTO `pasos` (`id`, `receta_id`, `numero_paso`, `descripcion`) VALUES
(1, 1, 1, 'Pelar y cortar las manzanas.'),
(2, 1, 2, 'Preparar la masa mezclando harina, manteca y huevos.'),
(3, 1, 3, 'Colocar la masa en el molde y agregar manzanas.'),
(4, 1, 4, 'Espolvorear azúcar por encima.'),
(5, 1, 5, 'Hornear por 40 minutos.'),
(6, 2, 1, 'Precalentar el horno a 180°C.\\r\\n\\r\\n'),
(7, 2, 2, 'Estirar la masa en un molde para tartas.\\r\\n\\r\\n'),
(8, 2, 3, 'Colocar el jamón y el queso sobre la masa.\\r\\n\\r\\n'),
(9, 2, 4, 'Batir los huevos con la crema y salpimentar.\\r\\n\\r\\n'),
(10, 2, 5, 'Verter la mezcla sobre el jamón y queso.\\r\\n\\r\\n'),
(11, 2, 6, 'Hornear durante 30 minutos o hasta que esté dorada.\\r\\n\\r\\n'),
(12, 2, 7, 'Dejar enfriar unos minutos antes de servir.\\r\\n\\r\\n'),
(13, 3, 1, 'Precalentar el horno a 180°C'),
(14, 3, 2, 'Estirar la masa en un molde para tartas.\\r\\n\\r\\n'),
(15, 3, 3, 'Cortar el queso y el jamón en trozos pequeños'),
(16, 3, 4, 'Colocar el jamón y el queso sobre la masa.\\r\\n\\r\\n'),
(17, 3, 5, 'Batir los huevos con la crema y salpimentar.\\r\\n\\r\\n'),
(18, 3, 6, 'Verter la mezcla sobre el jamón y queso.\\r\\n\\r\\n'),
(19, 3, 7, 'Hornear durante 30 minutos o hasta que esté dorada.\\r\\n\\r\\n'),
(20, 3, 8, 'Dejar enfriar unos minutos antes de servir.\\r\\n\\r\\n'),
(21, 4, 1, 'En un bowl, mezclá la harina, los huevos, la leche y la sal hasta que no haya grumos.\\r\\n\\r\\n'),
(22, 4, 2, 'Si querés, agregá una cucharada de manteca derretida para que queden más suaves.\\r\\n\\r\\n'),
(23, 4, 3, 'En una sartén caliente con un poco de aceite, cociná los panqueques uno por uno (poné un poco de mezcla, girá para que cubra toda la sartén, cociná hasta que los bordes se despeguen y das vuelta).\\r\\n\\r\\n'),
(24, 4, 4, 'Mientras hacés los panqueques, salteá la cebolla picada en aceite hasta que quede dorada.\\r\\n\\r\\n'),
(25, 4, 5, 'Para armar: rellená cada panqueque con cebolla salteada y queso, enrollalo o doblalo en forma de triángulo.\\r\\n\\r\\n'),
(26, 4, 6, 'Podés gratinarlos unos minutos al horno o microondas si querés que el queso se derrita bien.\\r\\n\\r\\n'),
(27, 5, 1, 'En un bowl, mezclá la harina, los huevos, la leche y la sal hasta que no haya grumos.\\r\\n\\r\\n'),
(28, 5, 2, 'Si querés, agregá una cucharada de manteca derretida para que queden más suaves.\\r\\n\\r\\n'),
(29, 5, 3, 'En una sartén caliente con un poco de aceite, cociná los panqueques uno por uno (poné un poco de mezcla, girá para que cubra toda la sartén, cociná hasta que los bordes se despeguen y das vuelta).\\r\\n\\r\\n'),
(30, 5, 4, 'Mientras hacés los panqueques, salteá la cebolla picada en aceite hasta que quede dorada.\\r\\n\\r\\n'),
(31, 5, 5, 'Para armar: rellená cada panqueque con cebolla salteada y queso, enrollalo o doblalo en forma de triángulo.\\r\\n\\r\\n'),
(32, 5, 6, 'Podés gratinarlos unos minutos al horno o microondas si querés que el queso se derrita bien.\\r\\n\\r\\n'),
(33, 6, 1, 'En un bowl, mezclá la harina, los huevos, la leche y la sal hasta que no haya grumos.\\r\\n\\r\\n'),
(34, 6, 2, 'Si querés, agregá una cucharada de manteca derretida para que queden más suaves.\\r\\n\\r\\n'),
(35, 6, 3, 'En una sartén caliente con un poco de aceite, cociná los panqueques uno por uno (poné un poco de mezcla, girá para que cubra toda la sartén, cociná hasta que los bordes se despeguen y das vuelta).\\r\\n\\r\\n'),
(36, 6, 4, 'Mientras hacés los panqueques, salteá la cebolla picada en aceite hasta que quede dorada.\\r\\n\\r\\n'),
(37, 6, 5, 'Para armar: rellená cada panqueque con cebolla salteada y queso, enrollalo o doblalo en forma de triángulo.\\r\\n\\r\\n'),
(38, 6, 6, 'Podés gratinarlos unos minutos al horno o microondas si querés que el queso se derrita bien.\\r\\n\\r\\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntuaciones`
--

CREATE TABLE `puntuaciones` (
  `usuario_id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `puntuacion` int(11) DEFAULT NULL CHECK (`puntuacion` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tiempo_preparacion` int(11) DEFAULT NULL,
  `dificultad` enum('Fácil','Media','Difícil') DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `titulo`, `imagen`, `descripcion`, `tiempo_preparacion`, `dificultad`, `usuario_id`, `fecha_creacion`) VALUES
(1, 'Tarta de Manzana', 'tarta_manzana.jpg', 'Una tarta clásica con manzanas y masa casera.', 60, 'Media', 11, '2025-06-03 20:25:40'),
(2, 'Tarta de Jamón y Queso', 'receta_683f8d02e218d.jpg', 'Una tarta clásica, fácil y rápida de hacer, ideal para una merienda o cena liviana.', 40, 'Fácil', 11, '2025-06-03 21:02:10'),
(3, 'Tarta de Jamón y Queso', 'receta_683f8dce6c0a4.jpg', 'Una tarta clásica, fácil y rápida de hacer, ideal para una merienda o cena liviana.', 40, 'Fácil', 11, '2025-06-03 21:05:34'),
(4, 'Panqueques Salados Rellenos', 'receta_683fa1d0039bd.jpg', 'Una base de panqueque clásico, relleno con queso y cebolla salteada. Ideal para un almuerzo o cena rápida.\\r\\n\\r\\n', 80, '', 11, '2025-06-03 22:30:56'),
(5, 'Panqueques Salados Rellenos', 'receta_683fa2c2035a8.jpg', 'Una base de panqueque clásico, relleno con queso y cebolla salteada. Ideal para un almuerzo o cena rápida.\\r\\n\\r\\n', 80, NULL, 11, '2025-06-03 22:34:58'),
(6, 'Panqueques Salados Rellenos', 'receta_683fad864be9b.jpg', 'Una base de panqueque clásico, relleno con queso y cebolla salteada. Ideal para un almuerzo o cena rápida.\\r\\n\\r\\n', 80, NULL, 11, '2025-06-03 23:20:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_ingrediente`
--

CREATE TABLE `receta_ingrediente` (
  `receta_id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `cantidad` decimal(5,2) NOT NULL,
  `unidad` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_ingrediente`
--

INSERT INTO `receta_ingrediente` (`receta_id`, `ingrediente_id`, `cantidad`, `unidad`) VALUES
(1, 1, 4.00, 'unidades'),
(1, 2, 150.00, 'gramos'),
(1, 3, 250.00, 'gramos'),
(1, 4, 100.00, 'gramos'),
(1, 5, 2.00, 'unidades'),
(3, 1, 300.00, 'gramos'),
(3, 3, 5.00, 'gramos'),
(3, 5, 3.00, 'unidades'),
(3, 16, 150.00, 'gramos'),
(3, 17, 150.00, 'gramos'),
(3, 18, 200.00, 'mililitros'),
(3, 19, 1.00, 'cucharadas'),
(6, 1, 120.00, 'gramos'),
(6, 3, 5.00, 'gramos'),
(6, 5, 2.00, 'unidades'),
(6, 6, 250.00, 'mililitros'),
(6, 7, 1.00, 'cucharadas'),
(6, 8, 40.00, 'gramos'),
(6, 11, 1.00, 'unidades'),
(6, 17, 100.00, 'gramos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_tag`
--

CREATE TABLE `receta_tag` (
  `receta_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_tag`
--

INSERT INTO `receta_tag` (`receta_id`, `tag_id`) VALUES
(1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `nombre`) VALUES
(13, 'Aperitivo'),
(3, 'Celíaca'),
(12, 'Difícil'),
(10, 'Fácil'),
(6, 'Italiana'),
(11, 'Media'),
(7, 'Mexicana'),
(14, 'Plato principal'),
(8, 'Postre'),
(9, 'Rápida'),
(4, 'Sin azúcar'),
(5, 'Sin gluten'),
(15, 'Sopa'),
(1, 'Vegana'),
(2, 'Vegetariana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `rol` varchar(20) DEFAULT 'usuario',
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password`, `fecha_creacion`, `rol`, `is_admin`) VALUES
(11, 'Pedro Coppola', 'pedroicoppola@gmail.com', '$2y$10$oHUHvb9VDqo6sU6zxQfT2e2par42Df51AvOmYK08/aWB86L0gwU2m', '2025-06-03 19:39:02', 'usuario', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`usuario_id`,`receta_id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `fk_ingrediente_medicion` (`medicion_id`);

--
-- Indices de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasos`
--
ALTER TABLE `pasos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `puntuaciones`
--
ALTER TABLE `puntuaciones`
  ADD PRIMARY KEY (`usuario_id`,`receta_id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD PRIMARY KEY (`receta_id`,`ingrediente_id`),
  ADD KEY `ingrediente_id` (`ingrediente_id`);

--
-- Indices de la tabla `receta_tag`
--
ALTER TABLE `receta_tag`
  ADD PRIMARY KEY (`receta_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pasos`
--
ALTER TABLE `pasos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `fk_ingrediente_medicion` FOREIGN KEY (`medicion_id`) REFERENCES `mediciones` (`id`);

--
-- Filtros para la tabla `pasos`
--
ALTER TABLE `pasos`
  ADD CONSTRAINT `pasos_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `puntuaciones`
--
ALTER TABLE `puntuaciones`
  ADD CONSTRAINT `puntuaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `puntuaciones_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD CONSTRAINT `receta_ingrediente_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receta_ingrediente_ibfk_2` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`);

--
-- Filtros para la tabla `receta_tag`
--
ALTER TABLE `receta_tag`
  ADD CONSTRAINT `receta_tag_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receta_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
