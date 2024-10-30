CREATE DATABASE IF NOT EXISTS ansp_parcial_db;
USE ansp_parcial_db;

CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    clave TEXT NOT NULL
);

CREATE TABLE publicacion (
    id_publicacion INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    votos INT DEFAULT 0,
    eliminado BOOLEAN,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE voto (
    id_voto INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_publicacion INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
);
