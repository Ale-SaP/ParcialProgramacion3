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
    <form action="index.php?modulo=ansp_procesar_login" method="POST">
        <label for="nombre">Nombre de Usuario:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
</section>