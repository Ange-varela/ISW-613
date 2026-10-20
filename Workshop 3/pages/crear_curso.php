<?php
// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Mostrar el encabezado
require_once 'common/header.php';
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Crear Curso</h2>

  <form action="../actions/crear_curso.php" method="POST">

    <div class="form-group">
      <label>Nombre del curso</label>
      <input type="text" name="nombre" class="form-control"
             maxlength="100" required>
    </div>

    <div class="form-group">
      <label>Codigo del curso</label>
      <input type="text" name="codigo_curso" class="form-control"
             maxlength="20" required>
    </div>

    <div class="form-group">
      <label>ID de la carrera</label>
      <input type="number" name="carrera_id" class="form-control"
             min="1" required>
    </div>

    <div class="form-group">
      <label>Creditos</label>
      <input type="number" name="creditos" class="form-control"
             min="0" required>
    </div>

    <div class="form-group">
      <label>Descripcion del curso</label>
      <textarea name="descripcion" class="form-control"
                rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">
      Guardar Curso
    </button>

    <a href="cursos.php" class="btn btn-secondary">
      Cancelar
    </a>

  </form>
</div>

<?php require_once 'common/footer.php'; ?>