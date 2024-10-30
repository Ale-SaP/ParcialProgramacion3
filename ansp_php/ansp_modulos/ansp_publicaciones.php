<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['nombre'], $_POST['id_publicacion'])) {
        $id_publicacion = filter_var($_POST['id_publicacion'], FILTER_VALIDATE_INT);
        if ($id_publicacion) {
            $stmt = $pdo->prepare('INSERT INTO voto (id_usuario, id_publicacion) VALUES ((SELECT id FROM usuario WHERE nombre = ?), ?)');
            $stmt->execute([$_SESSION['nombre'], $id_publicacion]);

            header('Location: index.php?&vote=success');
            exit();
        }
    }
}

try {
    $stmt = $pdo->query('SELECT titulo, descripcion FROM publicacion WHERE eliminado = 0');
    $publicaciones_lista = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar publicaciones.";
    exit();
}
?>

<section id="publicaciones" class="section">
    <?php if (isset($_GET['vote']) && $_GET['vote'] == 'success'): ?>
        <p style="color:green;">¡Gracias por tu voto!</p>
    <?php endif; ?>
    <?php if (empty($publicaciones_lista)): ?>
        <p>No hay publicaciones disponibles.</p>
    <?php else: ?>
        <?php foreach ($publicaciones_lista as $publicacion): ?>
            <div class="publicacion">
                <h3><?php echo htmlspecialchars($publicacion['titulo']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($publicacion['descripcion'])); ?></p>
                <p>Votos: <?php echo htmlspecialchars($publicacion['votos']); ?></p>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <form method="POST" action="index.php?modulo=ansp_votar">
                        <input type="hidden" name="id_publicacion" value="<?php echo htmlspecialchars($publicacion['id']); ?>">
                        <button type="submit" name="vote" class="votar">Votar</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
