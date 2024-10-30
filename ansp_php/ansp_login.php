<?php
$error = '';
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error = "Nombre de usuario o contraseña incorrectos.";
}
?>

<section id="login" class="section">
    <h2>Iniciar Sesión</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="index.php?modulo=procesar_login" method="POST">
        <label for="login-username">Nombre de Usuario:</label>
        <input type="text" id="login-username" name="login-username" required>

        <label for="login-password">Contraseña:</label>
        <input type="password" id="login-password" name="login-password" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
</section>