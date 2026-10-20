<?php
// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Conexion con la base de datos
require_once '../utils/database.php';

// Obtener el ID del curso
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: cursos.php');
    exit;
}

// Buscar el curso en la base de datos
$consulta = $conn->prepare("SELECT * FROM cursos WHERE id = :id");
$consulta->execute(['id' => $id]);
$curso = $consulta->fetch(PDO::FETCH_ASSOC);

// Verificar que el curso exista
if (!$curso) {
    header('Location: cursos.php');
    exit;
}

// Mostrar el encabezado
require_once 'common/header.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Curso</h2>

    <form action="../actions/editar_curso.php" method="POST">

        <input type="hidden" name="id"
               value="<?= (int) $curso['id'] ?>">

        <div class="form-group">
            <label>Nombre del curso</label>
            <input type="text" name="nombre" class="form-control"
                   maxlength="100"
                   value="<?= htmlspecialchars($curso['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label>Codigo del curso</label>
            <input type="text" name="codigo_curso" class="form-control"
                   maxlength="20"
                   value="<?= htmlspecialchars($curso['codigo_curso']) ?>" required>
        </div>

        <div class="form-group">
            <label>ID de la carrera</label>
            <input type="number" name="carrera_id" class="form-control"
                   min="1"
                   value="<?= (int) $curso['carrera_id'] ?>" required>
        </div>

        <div class="form-group">
            <label>Creditos</label>
            <input type="number" name="creditos" class="form-control"
                   min="0"
                   value="<?= (int) $curso['creditos'] ?>" required>
        </div>

        <div class="form-group">
            <label>Descripcion del curso</label>
            <textarea name="descripcion" class="form-control"
                      rows="3"><?= htmlspecialchars($curso['descripcion'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Guardar Cambios
        </button>

        <a href="cursos.php" class="btn btn-secondary">
            Cancelar
        </a>

    </form>
</div>

<?php require_once 'common/footer.php'; ?>