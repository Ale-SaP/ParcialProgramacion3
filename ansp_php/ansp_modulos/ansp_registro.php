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
    <h2 class="text-4xl">Registro</h2>
    <?php if (isset($error_message)): ?>
        <div role="alert" class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>
    <form action="index.php?modulo=ansp_registro" method="POST" class="flex flex-col input-primary">
        <label for="nombre" class="mt-8">Nombre de Usuario:</label>
        <input type="text" id="nombre" name="nombre" class="input-primary mb-4" required>

        <label for="clave" class="mt-8">Contraseña:</label>
        <input type="password" id="clave" name="clave" class="input-primary mb-4" required>

        <button type="submit" class="mt-8 btn btn-primary">Registrarse</button>
    </form>
</section>