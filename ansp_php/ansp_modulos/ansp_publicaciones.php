<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['nombre'], $_POST['id_publicacion'])) {
        $id_publicacion = filter_var($_POST['id_publicacion'], FILTER_VALIDATE_INT);
        
        if ($id_publicacion) {
            if (isset($_POST['vote'])) {
                // Añadir el voto
                $stmt = $pdo->prepare('
                    INSERT INTO voto (id_usuario, id_publicacion) 
                    VALUES ((SELECT id_usuario FROM usuario WHERE nombre = ?), ?)
                ');
                $stmt->execute([$_SESSION['nombre'], $id_publicacion]);

                header('Location: index.php?&vote=success');
                exit();
                
            } elseif (isset($_POST['delete_vote'])) {
                // Borrar el voto si el usuario ya ha votado en esta publicación
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
    // Consulta para obtener publicaciones, el autor y contar votos por cada una
    $stmt = $pdo->query('
        SELECT p.id_publicacion, p.titulo, p.descripcion, p.id_usuario, 
               COALESCE(COUNT(v.id_voto), 0) AS votos, u.nombre AS autor
        FROM publicacion p
        LEFT JOIN voto v ON p.id_publicacion = v.id_publicacion
        LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
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
    <h1 class="text-4xl">Últimas publicaciones</h1>

    <?php if (isset($_GET['vote'])): ?>
        <?php if ($_GET['vote'] == 'success'): ?>
            <div role="alert" class="alert alert-success mb-4 alert-dismissible">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>¡Gracias por tu voto!</span>
            </div>
        <?php elseif ($_GET['vote'] == 'already_voted'): ?>
            <div role="alert" class="alert alert-error mb-4 alert-dismissible">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Ya has votado en esta publicación.</span>
            </div>
        <?php elseif ($_GET['vote'] == 'deleted'): ?>
            <div role="alert" class="alert alert-info mb-4 alert-dismissible">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-3m-4 4h16m-8-7v6a2 2 0 104 0V7a2 2 0 10-4 0z" />
                </svg>
                <span>Tu voto ha sido eliminado.</span>
            </div>
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
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($publicacion['autor']); ?></p>
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

<script>
    // Desaparecer alertas automáticamente después de 5 segundos
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                alert.style.transition = "opacity 1s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 1000); // Eliminar el elemento después de la transición
            });
        }, 5000);
    });
</script>
