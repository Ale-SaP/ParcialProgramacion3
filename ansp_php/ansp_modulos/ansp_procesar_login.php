<?php
include './ansp_php/ansp_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['clave'])) {
        $username = trim($_POST['nombre']);
        $password = trim($_POST['clave']);

        $stmt = $pdo->prepare('SELECT id_usuario, clave FROM usuario WHERE nombre = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && $password === $user['clave']) {
            $_SESSION['nombre'] = $username;
            header('Location: index.php');
            exit();
        } else {
            header('Location: index.php?modulo=ansp_login&error=1');
            exit();
        }
    }
}
?>
