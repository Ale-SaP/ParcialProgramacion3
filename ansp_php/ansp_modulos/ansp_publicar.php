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
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);

        try {
            // Obtener el id_usuario del nombre del usuario que está autenticado
            $stmt = $pdo->prepare('SELECT id_usuario FROM usuario WHERE nombre = ?');
            $stmt->execute([$_SESSION['nombre']]);
            $id_usuario = $stmt->fetchColumn();

            if ($id_usuario) {
                // Insertar la nueva publicación asociada al usuario actual
                $stmt = $pdo->prepare('INSERT INTO publicacion (titulo, descripcion, eliminado, id_usuario) VALUES (?, ?, 0, ?)');
                $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
                $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(3, $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                header('Location: index.php?modulo=ansp_publicar&success=1');
                exit();
            } else {
                $error_message = "No se pudo encontrar el usuario.";
            }
        } catch (PDOException $e) {
            $error_message = "Ocurrió un error al realizar la publicación.";
        }
    } else {
        $error_message = "Completa todos los campos correctamente.";
    }
}
?>

<section class="flex flex-col">
    <h2 class="text-4xl mb-8">Crear publicaciones</h2>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div role="alert" class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Publicación realizada correctamente.</span>
        </div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div role="alert" class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>
    <form action="index.php?modulo=ansp_publicar" method="POST" class="flex flex-col input-primary">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" class="mb-4" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" class="mb-4 textarea-bordered" required></textarea>

        <button type="submit" class="mt-8 btn btn-primary">Realizar publicación</button>
    </form>
</section>
