<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['nombre'], $_POST['id_publicacion'])) {
        $id_publicacion = filter_var($_POST['id_publicacion'], FILTER_VALIDATE_INT);
        
        if ($id_publicacion) {
            if (isset($_POST['vote'])) {
                $stmt = $pdo->prepare('
                    INSERT INTO voto (id_usuario, id_publicacion) 
                    VALUES ((SELECT id_usuario FROM usuario WHERE nombre = ?), ?)
                ');
                $stmt->execute([$_SESSION['nombre'], $id_publicacion]);

                header('Location: index.php?&vote=success');
                exit();
                
            } elseif (isset($_POST['delete_vote'])) {
                $stmt = $pdo->prepare('
                    DELETE FROM voto 
                    WHERE id_usuario = (SELECT id_usuario FROM usuario WHERE nombre = ?) 
                    AND id_publicacion = ?
                ');
                $stmt->execute([$_SESSION['nombre'], $id_publicacion]);

                header('Location: index.php?&vote=deleted');
                exit();
            }
        }
    }
}

try {
    $stmt = $pdo->query('
        SELECT p.id_publicacion, p.titulo, p.descripcion, 
               COALESCE(COUNT(v.id_voto), 0) AS votos 
        FROM publicacion p
        LEFT JOIN voto v ON p.id_publicacion = v.id_publicacion
        WHERE p.eliminado = 0
        GROUP BY p.id_publicacion
    ');
    $publicaciones_lista = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar publicaciones.";
    exit();
}
?>

<section id="publicaciones" class="section">
    <h1 class="text-4xl">Ultimas publicaciones</h1>
    <?php if (isset($_GET['vote'])): ?>
        <?php if ($_GET['vote'] == 'success'): ?>
            <p style="color:green;">¡Gracias por tu voto!</p>
        <?php elseif ($_GET['vote'] == 'already_voted'): ?>
            <p style="color:red;">Ya has votado en esta publicación.</p>
        <?php elseif ($_GET['vote'] == 'deleted'): ?>
            <p style="color:blue;">Tu voto ha sido eliminado.</p>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (empty($publicaciones_lista)): ?>
        <p>No hay publicaciones disponibles.</p>
    <?php else: ?>
        <?php foreach ($publicaciones_lista as $publicacion): ?>
            <div class="card bg-base-100 w-96 shadow-xl m-4">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($publicacion['titulo']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($publicacion['descripcion'])); ?></p>
                    <p>Votos: <?php echo htmlspecialchars($publicacion['votos']); ?></p>
                    <div class="card-actions justify-end">
                        <?php
                        // Verificar si el usuario ya ha votado en esta publicación
                        if (isset($_SESSION['nombre'])) {
                            $stmt = $pdo->prepare('
                                SELECT COUNT(*) 
                                FROM voto 
                                WHERE id_usuario = (SELECT id_usuario FROM usuario WHERE nombre = ?) 
                                AND id_publicacion = ?
                            ');
                            $stmt->execute([$_SESSION['nombre'], $publicacion['id_publicacion']]);
                            $voto_existente = $stmt->fetchColumn();
                            if ($voto_existente == 0): ?>
                                <form method="POST" action="index.php?modulo=ansp_publicaciones">
                                    <input type="hidden" name="id_publicacion" value="<?php echo htmlspecialchars($publicacion['id_publicacion']); ?>">
                                    <button type="submit" name="vote" class="btn btn-primary">Votar</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="index.php?modulo=ansp_publicaciones">
                                    <input type="hidden" name="id_publicacion" value="<?php echo htmlspecialchars($publicacion['id_publicacion']); ?>">
                                    <button type="submit" name="delete_vote" class="btn btn-secondary">Eliminar Voto</button>
                                </form>
                            <?php endif; 
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
