<?php
// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Conexion con la base de datos
require_once '../utils/database.php';

// Obtener todos los cursos
$consulta = $conn->query("SELECT * FROM cursos ORDER BY nombre");
$cursos = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Mostrar el encabezado
require_once 'common/header.php';
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Lista de cursos</h2>

  <a href="crear_curso.php" class="btn btn-primary mb-3">
    Crear Curso
  </a>

  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Nombre</th>
        <th>Codigo</th>
        <th>ID Carrera</th>
        <th>Creditos</th>
        <th>Descripcion</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($cursos as $curso): ?>
        <tr>
          <td><?= htmlspecialchars($curso['nombre']) ?></td>
          <td><?= htmlspecialchars($curso['codigo_curso']) ?></td>
          <td><?= (int) $curso['carrera_id'] ?></td>
          <td><?= (int) $curso['creditos'] ?></td>
          <td><?= htmlspecialchars($curso['descripcion'] ?? '') ?></td>
          <td>
            <a href="editar_curso.php?id=<?= (int) $curso['id'] ?>"
               class="btn btn-warning btn-sm">
              Editar
            </a>

            <form action="../actions/eliminar_curso.php"
                  method="POST" class="d-inline">
              <input type="hidden" name="id"
                     value="<?= (int) $curso['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm"
                      onclick="return confirm('Desea eliminar este curso?')">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'common/footer.php'; ?>