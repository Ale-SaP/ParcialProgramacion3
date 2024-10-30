CREATE DATABASE IF NOT exists ansp_parcial_db;

CREATE TABLE IF NOT exists
    publicacion (
        id_publicacion INT PRIMARY KEY AUTO_INCREMENT,
        titulo varchar(200) NOT NULL,
        descripcion text NOT NULL,
        votos int DEFAULT 0,
        eliminado boolean
    );

CREATE TABLE IF NOT exists
    usuario (
        id_usuario INT PRIMARY KEY AUTO_INCREMENT,
        nombre varchar(50) NOT NULL,
        clave text NOT NULL
    );

CREATE TABLE IF NOT exists
    voto (
        id_voto INT PRIMARY KEY AUTO_INCREMENT,
        id_usuario int NOT NULL,
        id_publicacion int NOT NULL,
        FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
        FOREIGN KEY (id_usuario) REFERENCES publicacion(id_publicacion)
    );