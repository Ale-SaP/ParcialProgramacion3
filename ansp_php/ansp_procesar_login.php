<?php
session_start();
include './php/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['clave'])) {
        $username = trim($_POST['nombre']);
        $password = trim($_POST['clave']);

        $stmt = $pdo->prepare('SELECT id, clave FROM usuarios WHERE nombre = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && $password === $user['clave']) {
            $_SESSION['usuario'] = $username;
            header('Location: index.php');
            exit();
        } else {
            header('Location: index.php?modulo=ansp_login&error=1');
            exit();
        }
    }
}
?>
