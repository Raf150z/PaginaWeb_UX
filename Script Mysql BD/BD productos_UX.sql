DROP DATABASE productos_UX;
CREATE DATABASE productos_UX;
USE productos_UX;


CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion_corta TEXT,
    categoria ENUM('Electrónica', 'Ropa y calzado', 'Hogar', 'Juguetes', 'Otros') NOT NULL
);


CREATE TABLE detalles_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    unidades_stock INT NOT NULL,
    fecha_ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);


CREATE TABLE colores_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    color_hex VARCHAR(7) NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);


-- INSERT INTO productos (nombre, descripcion_corta, categoria) VALUES
-- ('Silla ergonómica', 'Silla cómoda para oficina', 'Hogar');

-- INSERT INTO detalles_producto (producto_id, precio_unitario, unidades_stock) VALUES
-- (3, 150.00, 10);


-- INSERT INTO colores_producto (producto_id, color_hex) VALUES
-- (3, '#FF20aa');

-- SELECT * FROM productos;




-- INSERT INTO productos (nombre, descripcion_corta, categoria) VALUES
-- ('Zapatos', 'Zapatos de piel marca ABC', 'Ropa y calzado');

-- INSERT INTO detalles_producto (producto_id, precio_unitario, unidades_stock) VALUES
-- (4, 450.00, 10);


-- INSERT INTO colores_producto (producto_id, color_hex) VALUES
-- (4, '#4a9998');

-- SELECT * FROM productos;
