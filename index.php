<?php
session_start();
include './ansp_php/ansp_connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Parcial</title>
    <link rel="stylesheet" href="ansp_styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <nav class="navbar">
        <ul class="flex flex-row bg-base-100">
            <li><a class="btn btn-ghost text-xl" href="index.php">Inicio</a></li>
            <?php if (!isset($_SESSION['nombre'])): ?>
                <li><a class="btn btn-neutral ml-4 mr-4 text-xl" href="index.php?modulo=ansp_registro">Registro</a></li>
                <li><a class="btn btn-primary text-xl" href="index.php?modulo=ansp_login">Iniciar Sesión</a></li>
            <?php else: ?>
                <li><a class="btn btn-success ml-4 mr-4 text-xl" href="index.php?modulo=ansp_publicar">Crear mi publicacion</a></li>
                <li><a class="btn btn-error ml-4 mr-4 text-xl" href="index.php?modulo=ansp_logout">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <header>
        <h1>Ultimas publicaciones</h1>
    </header>
    <main>
        <?php
        if (!empty($_GET['modulo'])) {
            include('ansp_php/ansp_modulos/' . $_GET['modulo'] . '.php');
        }
        else {
            include('ansp_php/ansp_modulos/ansp_publicaciones.php');
        }
        ?>
    </main>
    <script src="js/script.js"></script>
</body>

</html>