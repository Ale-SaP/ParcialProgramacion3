<?php
session_start();
include './ansp_php/connection.php';
require_once ( 'ansp_php/login.php' );
require_once ( 'ansp_php/register.php' );
require_once ( 'ansp_php/homepage.php' );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Parcial</title>
    <link rel="stylesheet" href="ansp_styles.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <?php if (!isset($_SESSION['usuario'])): ?>
                <li><a href="index.php?modulo=registro">Registro</a></li>
                <li><a href="index.php?modulo=login">Iniciar Sesión</a></li>
            <?php else: ?>
                <li><a href="index.php?modulo=admin">Crear mi publicacion</a></li>
                <li><a href="index.php?modulo=logout">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <header>
        <h1>Ultimas publicaciones</h1>
    </header>
    <main>
        <?php
        if (!empty($_GET['ansp_php'])) {
            include('ansp_php/' . $_GET['modulo'] . '.php');
        }
        ?>
    </main>
    <script src="js/script.js"></script>
</body>

</html>
