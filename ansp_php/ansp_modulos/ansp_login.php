<?php
$error = '';
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error = "Nombre de usuario o contraseña incorrectos.";
}
?>

<section id="login" class="section">
    <h2 class="text-4xl">Iniciar Sesión</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="index.php?modulo=ansp_procesar_login" method="POST" class="flex flex-col">
        <label for="nombre" class="mt-8">Nombre de Usuario:</label>
        <input type="text" id="nombre" name="nombre" class="input-primary" required>

        <label for="clave" class="mt-8">Contraseña:</label>
        <input type="password" id="clave" name="clave" class="input-primary" required>

        <button type="submit" class="mt-8 btn btn-primary">Iniciar Sesión</button>
    </form>
</section>