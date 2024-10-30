<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['clave'])) {
        $username = trim($_POST['nombre']);
        $password = trim($_POST['clave']);
        try {
            $stmt = $pdo->prepare('INSERT INTO usuario (nombre, clave) VALUES (?, ?)');
            $stmt->execute([$username, $password]);
            header('Location: index.php?modulo=ansp_login&registered=1');
            exit();
        } catch (PDOException $e) {
            $error_message = "Ocurrió un error al registrar el usuario.";
        }
    } else {
        $error_message = "Completa todos los campos correctamente.";
    }
}
?>

<section id="registro" class="section">
    <h2>Registro</h2>   
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="index.php?modulo=ansp_registro" method="POST">
        <label for="nombre">Nombre de Usuario:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>

        <button type="submit">Registrarse</button>
    </form>
</section>
