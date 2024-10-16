-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-10-2024 a las 05:29:51
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
-- Base de datos: `comandaapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(5, 'Carnes'),
(2, 'Ensaladas'),
(1, 'Entradas'),
(3, 'Pastas'),
(7, 'Postres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`) VALUES
(1, 'Cliente Final');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `id_detalle_factura` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_cajero` int(11) NOT NULL,
  `fecha_factura` date NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menuitem`
--

CREATE TABLE `menuitem` (
  `id_item` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menuitem`
--

INSERT INTO `menuitem` (`id_item`, `nombre`, `descripcion`, `precio`, `id_categoria`, `foto`, `estado`) VALUES
(1, 'Ensalada poke de atún y algas con aguacate', 'La gran contribución hawaiana es el famoso poke, popularizado en los últimos años como ejemplo de comida rápida pero sana. ', 8.60, 2, '../img/products/Ensalada poke de atún y algas con aguacate.jpg', 1),
(2, 'Panzanella italiana', 'Es una clásica receta de aprovechamiento, muy típica de la zona de Florencia, que a veces también se encuentra como panmolle.', 7.50, 2, '../img/products/Panzanella italiana.jpeg', 1),
(3, 'Lomo de cerdo', 'Lomo de cerdo a la parrilla, acompañado de papa al horno, vegetales al vapor, tortillas', 9.99, 5, '', 1),
(4, 'Tres Leches', 'Bizcocho bañado con tres tipos de lácteo: leche evaporada, media crema y leche condensada', 1.50, 7, '../img/products/Captura de pantalla 2024-06-04 201958.png', 1),
(5, 'Brownie Cookies and Cream', 'Exquisito brownie que combina lo mejor de Cokies and Cream', 5.99, 7, '../img/products/Brownie Cookies and Cream.png', 1),
(7, 'Prueba Prodcuto', 'POrueba', 1.00, 5, '../img/products/Prueba Prodcuto.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `num_mesa` int(11) NOT NULL,
  `cant_personas` int(11) NOT NULL,
  `estado` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`num_mesa`, `cant_personas`, `estado`) VALUES
(1, 5, 'D'),
(2, 10, 'D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `fecha_pedido` date NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL,
  `num_mesa` int(11) NOT NULL,
  `id_mesero` int(11) NOT NULL,
  `comentario_pedido` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `fecha_pedido`, `estado`, `num_mesa`, `id_mesero`, `comentario_pedido`) VALUES
(1, '2024-09-29', 'Nuevo', 1, 2, NULL),
(2, '2024-09-30', 'Nuevo', 1, 2, NULL),
(3, '2024-09-30', 'Nuevo', 2, 2, NULL),
(4, '2024-09-30', 'Nuevo', 1, 2, NULL),
(5, '2024-10-01', 'Nuevo', 1, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidodetalle`
--

CREATE TABLE `pedidodetalle` (
  `id_pedido_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedidodetalle`
--

INSERT INTO `pedidodetalle` (`id_pedido_detalle`, `id_pedido`, `id_item`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 5, 3, 5.99),
(2, 2, 3, 1, 9.99),
(3, 2, 4, 1, 1.50),
(4, 3, 2, 1, 7.50),
(5, 3, 5, 1, 5.99),
(6, 4, 5, 5, 5.99),
(7, 5, 3, 3, 9.99),
(8, 5, 4, 2, 1.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` int(11) NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL,
  `descripcion_tipo_usuario` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`, `descripcion_tipo_usuario`) VALUES
(1, 'Administrador', 'Administrador del sistema'),
(2, 'Mesero', 'Personal de atención a mesa'),
(3, 'Cocinero', 'Personal de atencion a cocina'),
(4, 'Cliente', 'Clientes del sistema'),
(20, 'Nuevo', 'Nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `apellido_usuario` varchar(50) NOT NULL,
  `mail` char(150) NOT NULL,
  `password` char(25) NOT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `fecha_creacion` date DEFAULT current_timestamp(),
  `estado_usuario` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `mail`, `password`, `id_tipo_usuario`, `fecha_creacion`, `estado_usuario`) VALUES
(1, 'Carlos', 'Orellana', 'corellana@sv.marytierra.com', '12345', 1, '2024-05-18', 1),
(2, 'Damaris', 'Hernandéz', 'dhernandez@sv.marytierra.com', '12345', 2, '2024-05-18', 1),
(3, 'Jorge', 'Franco', 'jfranco@sv.marytierra.com', '12345', 3, '2024-05-18', 1),
(4, 'Karina', 'Cárcamo', 'kcarcamo@sv.marytierra.com', '12345', 4, '2024-05-18', 1),
(5, 'Carlos', 'Prez', 'ccar@gmail.com', '12345', 3, '2024-05-30', 1),
(6, 'Sandra', 'Admin', 'sadmin@gmail.com', 'C0ntr4$eña', 1, '2024-10-06', 1),
(7, 'Sandra', 'Mesero', 'smesero@gmail.com', 'C0ntr4$eña', 2, '2024-10-06', 1),
(8, 'Sandra', 'Cocinera', 'scocinera@gmail.com', 'C0ntr4$eña', 3, '2024-10-06', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`id_detalle_factura`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_item` (`id_item`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_cajero` (`id_cajero`);

--
-- Indices de la tabla `menuitem`
--
ALTER TABLE `menuitem`
  ADD PRIMARY KEY (`id_item`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`num_mesa`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_mesero` (`id_mesero`),
  ADD KEY `num_mesa` (`num_mesa`);

--
-- Indices de la tabla `pedidodetalle`
--
ALTER TABLE `pedidodetalle`
  ADD PRIMARY KEY (`id_pedido_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_item` (`id_item`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id_tipo_usuario`),
  ADD KEY `tipo_usuario` (`tipo_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`mail`),
  ADD KEY `tipo_usuario` (`id_tipo_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `id_detalle_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menuitem`
--
ALTER TABLE `menuitem`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `num_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidodetalle`
--
ALTER TABLE `pedidodetalle`
  MODIFY `id_pedido_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `menuitem` (`id_item`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cajero`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `menuitem`
--
ALTER TABLE `menuitem`
  ADD CONSTRAINT `menuitem_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`num_mesa`) REFERENCES `mesa` (`num_mesa`),
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`id_mesero`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `pedidodetalle`
--
ALTER TABLE `pedidodetalle`
  ADD CONSTRAINT `pedidodetalle_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `menuitem` (`id_item`),
  ADD CONSTRAINT `pedidodetalle_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
