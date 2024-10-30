<?php
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php?modulo=ansp_login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['titulo'], $_POST['descripcion']) &&
        !empty(trim($_POST['titulo'])) &&
        !empty(trim($_POST['descripcion']))
    ) {
        $nombre = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        try {
            $stmt = $pdo->prepare('INSERT INTO publicacion (titulo, descripcion, eliminado) VALUES (?, ?, 0)');
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
            $stmt->execute();

            header('Location: index.php?modulo=ansp_publicar&success=1');
            exit();
        } catch (PDOException $e) {
            $error_message = "Ocurrió un error al realizar la publicación.";
        }
    } else {
        $error_message = "Tipo de archivo no permitido.";
    }
} else {
    $error_message = "Completa todos los campos correctamente.";
}
?>

<section>
    <h2>Crear publicaciones</h2>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color:green;">Publicacion realizada correctamente.</p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="index.php?modulo=ansp_publicar" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <button type="submit">Realizar publicación</button>
    </form>
</section>