-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2025 a las 13:28:13
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
-- Base de datos: datos_pasteleria
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla carrito
--

CREATE TABLE carrito (
  id_carrito int(11) NOT NULL,
  cantidad decimal(10,2) NOT NULL,
  id_usuario int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla categoria
--

CREATE TABLE categoria (
  id_categoria int(11) NOT NULL,
  nombre varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla categoria
--

INSERT INTO categoria (id_categoria, nombre) VALUES
(1, 'Pasteles'),
(2, 'Cafés'),
(3, 'Bocadillos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla control_inventario
--

CREATE TABLE control_inventario (
  id_inventario int(11) NOT NULL,
  cantidad int(11) NOT NULL,
  movimiento varchar(50) NOT NULL,
  fecha date NOT NULL,
  id_producto int(11) NOT NULL,
  id_ingrediente int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla detalle_pedido
--

CREATE TABLE detalle_pedido (
  id_detalle int(11) NOT NULL,
  id_pedido int(11) NOT NULL,
  id_producto int(11) NOT NULL,
  nombre_producto varchar(100) NOT NULL,
  cantidad int(11) NOT NULL,
  precio_unitario decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla detalle_venta
--

CREATE TABLE detalle_venta (
  id_detalle int(11) NOT NULL,
  id_venta int(11) NOT NULL,
  id_producto int(11) NOT NULL,
  cantidad int(11) NOT NULL,
  precio_unitario decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla empleados
--

CREATE TABLE empleados (
  id_empleado int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  rol varchar(50) NOT NULL,
  correo varchar(50) NOT NULL,
  contraseña varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla empleados
--

INSERT INTO empleados (id_empleado, nombre, rol, correo, contraseña) VALUES
(6, 'Anderson', 'pastelero', 'anderson123@gmail.com', ''),
(7, 'Anderson', 'pastelero', 'anderson123@gmail.com', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla ingredientes
--

CREATE TABLE ingredientes (
  id_ingrediente int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  cantidad_disponible decimal(10,2) NOT NULL,
  unidad varchar(50) NOT NULL,
  stock decimal(10,2) NOT NULL,
  id_proveedor int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla kardex
--

CREATE TABLE kardex (
  id_kardex int(11) NOT NULL,
  id_producto int(11) NOT NULL,
  tipo_movimiento enum('entrada','salida') NOT NULL,
  cantidad int(11) NOT NULL,
  fecha timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla pagos
--

CREATE TABLE pagos (
  id_pago int(11) NOT NULL,
  metodo_pago varchar(50) NOT NULL,
  fecha_pago date NOT NULL,
  monto decimal(10,2) NOT NULL,
  id_carrito int(11) NOT NULL,
  direccion varchar(200) NOT NULL,
  telefono varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla pedidos
--

CREATE TABLE pedidos (
  id_pedido int(11) NOT NULL,
  fecha_pedido date NOT NULL,
  fecha_entrega date NOT NULL,
  estado varchar(50) NOT NULL,
  total decimal(10,2) NOT NULL,
  metodo_pago varchar(20) DEFAULT NULL,
  direccion_entrega text DEFAULT NULL,
  telefono_contacto varchar(20) DEFAULT NULL,
  id_usuario int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla pedidos
--

INSERT INTO pedidos (id_pedido, fecha_pedido, fecha_entrega, estado, total, metodo_pago, direccion_entrega, telefono_contacto, id_usuario) VALUES
(32, '2025-07-07', '2025-07-09', 'Pendiente', 7.00, 'efectivo', 'hj', '75772207', 5),
(33, '2025-07-07', '2025-07-09', 'Pendiente', 12.00, 'tarjeta', 'San pablo', '75772207', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla productos
--

CREATE TABLE productos (
  id_producto int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  descripcion varchar(100) NOT NULL,
  precio decimal(10,2) NOT NULL,
  tipo varchar(100) NOT NULL,
  imagen varchar(100) NOT NULL,
  id_categoria int(11) DEFAULT NULL,
  stock int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla productos
--

INSERT INTO productos (id_producto, nombre, descripcion, precio, tipo, imagen, id_categoria, stock) VALUES
(2, 'Café caliente', 'Café sabroso para tomar', 7.00, 'Bebida', 'imagen_685ebbd97706e.jpeg', 2, 85),
(3, 'Pastel de fresa', 'Bocadilos sabrosos', 6.00, 'Postre', 'Crepas.jpeg', 1, 98),
(5, 'Cachos', 'Cachos rellenos de manjar', 2.99, 'Bocadillo', 'prod_685e9d475e43c.png', 3, 43),
(6, 'Cheescake ', 'Cheescakes pequeños para comer', 98.34, 'Postre', 'cheescake.jpeg', 3, 0),
(7, 'Cupcakes', 'Cupcakes de bocadillos', 56.34, 'Postre', 'Cupcakes.jpeg', 3, 0),
(8, 'Limonada', 'Limonada de limon', 8.34, 'Bebida', 'Limonada.jpeg', 2, 0),
(9, 'Café elado', 'Café delicioso elado', 54.89, 'Bebida', 'Cafe_helado.jpeg', 2, 0),
(10, 'Crepas', 'Crepas jugosas', 52.97, 'Bocadillo', 'Crepas.jpeg', 3, 0),
(11, 'Tres leches', 'Pastel grande y pequeño de tres leches', 87.54, 'Postre', 'Tres_leches.jpeg', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla proveedores
--

CREATE TABLE proveedores (
  id_proveedor int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  producto varchar(100) NOT NULL,
  telefono int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla proveedores
--

INSERT INTO proveedores (id_proveedor, nombre, producto, telefono) VALUES
(1, 'Fernando palomo', 'Harina', 66789955),
(2, 'Alfonso Cardinal', 'Leche', 98234576),
(3, 'Rosa Maurillo', 'Huevos', 23456434),
(4, 'Margarito Molina', 'Azúcar', 98654323);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla usuarios
--

CREATE TABLE usuarios (
  id_usuario int(11) NOT NULL,
  nombre varchar(200) NOT NULL,
  correo varchar(200) NOT NULL,
  contrasena varchar(200) NOT NULL,
  telefono int(10) NOT NULL,
  direccion varchar(11) NOT NULL,
  rol varchar(20) DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla usuarios
--

INSERT INTO usuarios (id_usuario, nombre, correo, contrasena, telefono, direccion, rol) VALUES
(3, 'Anderson', 'ovidio@gmail.com', '$2y$10$pNRCiSQiSA5NXHvCJPZxueFk6mWxCZSHSaeondvojMjNUCySGlydG', 75772207, 'San pablo', 'admin'),
(4, 'Melames', 'usuario@gmail.com', '$2y$10$p7sHmBzbx3pYxitHt2Ipz.phGS7Z78hVCwJy6NaCELywu/yDOJWX6', 8787878, 'aya', 'usuario'),
(5, 'Anderson', 'mel396@gmail.com', '$2y$10$BsCGKH5b0qu9VLc1Ov26Oeb9XWNGFfYfL8WqmhJnVHDdilAJV224S', 75772207, 'San pablo', 'usuario'),
(6, 'Chele', 'chele@gmail.com', '$2y$10$yDsp0BtT5R7/6VZ4/xD4suhSHvJ6TKF3hwc/IpyMDoLOuQc6BNVqC', 75772207, 'San pablo', 'usuario'),
(7, 'Anderson', 'mel2@gmail.com', '$2y$10$OX2.59mQYcoohC63SvdJd.myE73QKLca4xUZeA9Cj7y6Egk2YLz6i', 75772207, 'San pablo', 'usuario'),
(8, 'Anderson', 'mel2@gmail.com', '$2y$10$OX2.59mQYcoohC63SvdJd.myE73QKLca4xUZeA9Cj7y6Egk2YLz6i', 75772207, 'San pablo', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla ventas
--

CREATE TABLE ventas (
  id_venta int(11) NOT NULL,
  id_usuario int(11) NOT NULL,
  fecha timestamp NOT NULL DEFAULT current_timestamp(),
  total decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla carrito
--
ALTER TABLE carrito
  ADD PRIMARY KEY (id_carrito),
  ADD KEY id_usuario (id_usuario);

--
-- Indices de la tabla categoria
--
ALTER TABLE categoria
  ADD PRIMARY KEY (id_categoria);

--
-- Indices de la tabla control_inventario
--
ALTER TABLE control_inventario
  ADD PRIMARY KEY (id_inventario),
  ADD KEY id_producto (id_producto),
  ADD KEY id_ingrediente (id_ingrediente);

--
-- Indices de la tabla detalle_pedido
--
ALTER TABLE detalle_pedido
  ADD PRIMARY KEY (id_detalle),
  ADD KEY fk_detalle_pedido (id_pedido),
  ADD KEY fk_detalle_producto (id_producto);

--
-- Indices de la tabla detalle_venta
--
ALTER TABLE detalle_venta
  ADD PRIMARY KEY (id_detalle),
  ADD KEY id_venta (id_venta),
  ADD KEY id_producto (id_producto);

--
-- Indices de la tabla empleados
--
ALTER TABLE empleados
  ADD PRIMARY KEY (id_empleado);

--
-- Indices de la tabla ingredientes
--
ALTER TABLE ingredientes
  ADD PRIMARY KEY (id_ingrediente),
  ADD KEY id_proveedor (id_proveedor);

--
-- Indices de la tabla kardex
--
ALTER TABLE kardex
  ADD PRIMARY KEY (id_kardex),
  ADD KEY id_producto (id_producto);

--
-- Indices de la tabla pagos
--
ALTER TABLE pagos
  ADD PRIMARY KEY (id_pago);

--
-- Indices de la tabla pedidos
--
ALTER TABLE pedidos
  ADD PRIMARY KEY (id_pedido),
  ADD KEY fk_pedidos_usuario (id_usuario);

--
-- Indices de la tabla productos
--
ALTER TABLE productos
  ADD PRIMARY KEY (id_producto),
  ADD KEY fk_categoria_producto (id_categoria);

--
-- Indices de la tabla proveedores
--
ALTER TABLE proveedores
  ADD PRIMARY KEY (id_proveedor);

--
-- Indices de la tabla usuarios
--
ALTER TABLE usuarios
  ADD PRIMARY KEY (id_usuario);

--
-- Indices de la tabla ventas
--
ALTER TABLE ventas
  ADD PRIMARY KEY (id_venta),
  ADD KEY id_usuario (id_usuario);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla carrito
--
ALTER TABLE carrito
  MODIFY id_carrito int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla control_inventario
--
ALTER TABLE control_inventario
  MODIFY id_inventario int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla detalle_pedido
--
ALTER TABLE detalle_pedido
  MODIFY id_detalle int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla detalle_venta
--
ALTER TABLE detalle_venta
  MODIFY id_detalle int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla empleados
--
ALTER TABLE empleados
  MODIFY id_empleado int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla ingredientes
--
ALTER TABLE ingredientes
  MODIFY id_ingrediente int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla kardex
--
ALTER TABLE kardex
  MODIFY id_kardex int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla pagos
--
ALTER TABLE pagos
  MODIFY id_pago int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla pedidos
--
ALTER TABLE pedidos
  MODIFY id_pedido int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla productos
--
ALTER TABLE productos
  MODIFY id_producto int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla proveedores
--
ALTER TABLE proveedores
  MODIFY id_proveedor int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla usuarios
--
ALTER TABLE usuarios
  MODIFY id_usuario int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla ventas
--
ALTER TABLE ventas
  MODIFY id_venta int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla carrito
--
ALTER TABLE carrito
  ADD CONSTRAINT id_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario);

--
-- Filtros para la tabla control_inventario
--
ALTER TABLE control_inventario
  ADD CONSTRAINT id_ingrediente FOREIGN KEY (id_ingrediente) REFERENCES ingredientes (id_ingrediente),
  ADD CONSTRAINT id_producto FOREIGN KEY (id_producto) REFERENCES productos (id_producto);

--
-- Filtros para la tabla detalle_pedido
--
ALTER TABLE detalle_pedido
  ADD CONSTRAINT fk_detalle_pedido FOREIGN KEY (id_pedido) REFERENCES pedidos (id_pedido) ON DELETE CASCADE,
  ADD CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto) REFERENCES productos (id_producto) ON DELETE CASCADE;

--
-- Filtros para la tabla detalle_venta
--
ALTER TABLE detalle_venta
  ADD CONSTRAINT detalle_venta_ibfk_1 FOREIGN KEY (id_venta) REFERENCES ventas (id_venta),
  ADD CONSTRAINT detalle_venta_ibfk_2 FOREIGN KEY (id_producto) REFERENCES productos (id_producto);

--
-- Filtros para la tabla ingredientes
--
ALTER TABLE ingredientes
  ADD CONSTRAINT id_proveedor FOREIGN KEY (id_proveedor) REFERENCES proveedores (id_proveedor);

--
-- Filtros para la tabla kardex
--
ALTER TABLE kardex
  ADD CONSTRAINT kardex_ibfk_1 FOREIGN KEY (id_producto) REFERENCES productos (id_producto);

--
-- Filtros para la tabla pedidos
--
ALTER TABLE pedidos
  ADD CONSTRAINT fk_pedidos_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario);

--
-- Filtros para la tabla productos
--
ALTER TABLE productos
  ADD CONSTRAINT fk_categoria_producto FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria);

--
-- Filtros para la tabla ventas
--
ALTER TABLE ventas
  ADD CONSTRAINT ventas_ibfk_1 FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
