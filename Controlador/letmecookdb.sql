  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Servidor: 127.0.0.1
  -- Tiempo de generación: 11-06-2025 a las 06:46:24
  -- Versión del servidor: 10.4.32-MariaDB
  -- Versión de PHP: 8.2.12

  SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
  START TRANSACTION;
  USE s953_letmecookdb;
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
  (19, 'Pimienta', 6),
  (21, 'Masa para pizza', 7),
  (22, 'Puré de tomate', 3),
  (23, 'Papas', 7),
  (24, 'Pan rallado', 1),
  (25, 'Arroz', 1),
  (26, 'Canela', 6),
  (27, 'Carne picada', NULL),
  (28, 'Salsa de tomate', NULL);

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
  (53, 6, 1, 'Precalentar el horno a 180°C.'),
  (54, 6, 2, 'Pelar y cortar las manzanas en rodajas finas.'),
  (55, 6, 3, 'Mezclar las manzanas con azúcar y canela.'),
  (56, 6, 4, 'Preparar la masa con harina, huevos, leche y manteca derretida.'),
  (57, 6, 5, 'Colocar las manzanas sobre la masa en un molde.'),
  (58, 6, 6, 'Hornear durante 40 a 50 minutos hasta dorar.'),
  (68, 19, 1, 'Precalentar el horno a 200°C.'),
  (69, 19, 2, 'Estirar la masa sobre una bandeja enharinada.'),
  (70, 19, 3, 'Cubrir con puré de tomate y esparcir sal, orégano y pimienta.'),
  (71, 19, 4, 'Agregar el queso rallado por encima.'),
  (72, 19, 5, 'Hornear durante 20 a 30 minutos hasta que el queso se derrita y la masa esté dorada.'),
  (73, 19, 1, 'Precalentar el horno a 200°C.'),
  (76, 19, 2, 'Estirar la masa sobre una bandeja enharinada.'),
  (79, 19, 3, 'Cubrir con puré de tomate y esparcir sal, orégano y pimienta.'),
  (82, 19, 4, 'Agregar el queso rallado por encima.'),
  (85, 19, 5, 'Hornear durante 20 a 30 minutos hasta que el queso se derrita y la masa esté dorada.'),
  (93, 34, 1, 'Hervir los fideos en agua con sal hasta que estén al dente.'),
  (94, 34, 2, 'En una sartén, calentar el aceite y dorar la cebolla picada y el ajo.'),
  (95, 34, 3, 'Agregar la carne picada y cocinar hasta que se dore.'),
  (96, 34, 4, 'Incorporar el puré de tomate y condimentar. Cocinar a fuego medio por 15 minutos.'),
  (97, 34, 5, 'Servir los fideos con la salsa y espolvorear queso rallado si se desea.'),
  (98, 35, 1, 'Precalentar el horno a 200°C.'),
  (99, 35, 2, 'Estirar la masa en una bandeja enharinada.'),
  (100, 35, 3, 'Cubrir con puré de tomate, sal y orégano.'),
  (101, 35, 4, 'Añadir la mozzarella en trozos.'),
  (102, 35, 5, 'Hornear 20-25 minutos.'),
  (103, 35, 6, 'Agregar hojas de albahaca fresca al salir del horno.\\r\\n\\r\\n'),
  (104, 36, 1, 'Batir los huevos y salpimentar.'),
  (105, 36, 2, 'Pasar los bifes por huevo y luego pan rallado.'),
  (106, 36, 3, 'Freír en aceite caliente hasta dorar.\\r\\n\\r\\n'),
  (107, 36, 4, 'Colocar en bandeja, cubrir con salsa, jamón y queso.'),
  (108, 36, 5, 'Gratinar al horno por 10 minutos.'),
  (109, 37, 1, 'Picar el jamón y el queso.'),
  (110, 37, 2, 'Mezclar con orégano.'),
  (111, 37, 3, 'Rellenar las tapas y cerrar con repulgue.'),
  (112, 37, 4, 'Pincelar con huevo batido.'),
  (113, 37, 5, 'Hornear a 200°C por 20-25 minutos.'),
  (114, 38, 1, 'Romper el pan en trozos y remojar con leche.'),
  (115, 38, 2, 'Batir los huevos con el azúcar y la vainilla.'),
  (116, 38, 3, 'Mezclar todo y agregar pasas de uva si se quiere.'),
  (117, 38, 4, 'Volcar en molde acaramelado.'),
  (118, 38, 5, 'Hornear a baño María a 180°C por 50 minutos.'),
  (119, 38, 6, 'Dejar enfriar, desmoldar y servir.\\r\\n\\r\\n');

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
  (6, 'Tarta de Manzana', 'receta_tarta_manzana.jpg', 'Clásica tarta dulce con relleno de manzanas y un toque de canela.', 50, 'Media', 11, '2025-06-10 22:52:47'),
  (19, 'Pizza Casera', 'pizza_casera.jpg', 'Pizza al horno con salsa de tomate y queso derretido.', 60, 'Media', 11, '2025-06-10 23:14:33'),
  (34, 'Fideos con tuco', 'receta_68490301eb573.jpg', 'Fideos al dente con una salsa tuco casera a base de tomate, cebolla y carne picada.', 45, NULL, 11, '2025-06-11 01:16:01'),
  (35, 'Pizza Margarita', 'receta_684903dc35190.jpg', 'Pizza clásica con salsa de tomate, queso y albahaca.', 60, NULL, 11, '2025-06-11 01:19:40'),
  (36, 'Milanesa Napolitana', 'receta_6849048220f5e.jpg', 'Clásica milanesa con salsa, jamón y queso gratinado.', 60, NULL, 11, '2025-06-11 01:22:26'),
  (37, 'Empanadas de Jamón y Queso', 'receta_684904f6bc440.jpg', 'Empanadas rápidas con relleno derretido y sabroso.', 40, NULL, 11, '2025-06-11 01:24:22'),
  (38, 'Budín de pan', 'receta_68490581ec7be.jpg', 'Postre económico con pan duro, leche y azúcar.', 70, NULL, 11, '2025-06-11 01:26:41');

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
  (6, 1, 200.00, 'gramos'),
  (6, 2, 100.00, 'gramos'),
  (6, 5, 2.00, 'unidades'),
  (6, 6, 100.00, 'mililitros'),
  (6, 8, 50.00, 'gramos'),
  (19, 3, 5.00, 'gramos'),
  (19, 14, 150.00, 'gramos'),
  (19, 19, 1.00, 'gramo'),
  (19, 21, 1.00, 'unidad'),
  (19, 22, 100.00, 'gramos'),
  (34, 3, 5.00, 'gramos'),
  (34, 11, 1.00, 'unidades'),
  (34, 12, 1.00, 'unidades'),
  (34, 14, 35.00, 'gramos'),
  (34, 19, 1.00, 'cucharadas'),
  (34, 22, 250.00, ''),
  (34, 27, 150.00, ''),
  (35, 17, 200.00, 'gramos'),
  (35, 21, 1.00, ''),
  (35, 22, 150.00, ''),
  (36, 3, 5.00, 'gramos'),
  (36, 5, 2.00, 'unidades'),
  (36, 7, 2.00, 'cucharadas'),
  (36, 16, 50.00, 'gramos'),
  (36, 17, 100.00, 'gramos'),
  (36, 19, 1.00, 'cucharadas'),
  (36, 24, 100.00, ''),
  (36, 28, 100.00, ''),
  (37, 5, 1.00, 'unidades'),
  (37, 16, 150.00, 'gramos'),
  (37, 17, 150.00, 'gramos'),
  (38, 2, 150.00, 'gramos'),
  (38, 5, 3.00, 'unidades'),
  (38, 6, 500.00, 'mililitros'),
  (38, 18, 20.00, 'mililitros'),
  (38, 26, 1.00, '');

  -- --------------------------------------------------------

  --
  -- Estructura de tabla para la tabla `receta_tag`
  --

  CREATE TABLE `receta_tag` (
    `receta_id` int(11) NOT NULL,
    `tag_id` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  (11, 'Pedro Coppola', 'pedroicoppola@gmail.com', '$2y$10$oHUHvb9VDqo6sU6zxQfT2e2par42Df51AvOmYK08/aWB86L0gwU2m', '2025-06-03 19:39:02', 'usuario', 0),
  (12, 'Pedrous', 'asd@gmail.com', '$2y$10$fqSF2Y0WCTn9AylG6L368uBcZK7L6xbTA8gR9Mznhbpdr3gRQBP4a', '2025-06-10 22:01:16', 'usuario', 0);

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
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

  --
  -- AUTO_INCREMENT de la tabla `mediciones`
  --
  ALTER TABLE `mediciones`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

  --
  -- AUTO_INCREMENT de la tabla `pasos`
  --
  ALTER TABLE `pasos`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

  --
  -- AUTO_INCREMENT de la tabla `recetas`
  --
  ALTER TABLE `recetas`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

  --
  -- AUTO_INCREMENT de la tabla `tags`
  --
  ALTER TABLE `tags`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

  --
  -- AUTO_INCREMENT de la tabla `usuarios`
  --
  ALTER TABLE `usuarios`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
