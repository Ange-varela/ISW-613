<?php
// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Permitir solamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/cursos.php');
    exit;
}

// Conexion con la base de datos
require_once '../utils/database.php';

// Obtener los datos del formulario
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre'] ?? '');
$codigo_curso = trim($_POST['codigo_curso'] ?? '');
$carrera_id = filter_input(INPUT_POST, 'carrera_id', FILTER_VALIDATE_INT);
$creditos = filter_input(INPUT_POST, 'creditos', FILTER_VALIDATE_INT);
$descripcion = trim($_POST['descripcion'] ?? '');

// Validar los datos
if (!$id || $nombre === '' || strlen($nombre) > 100 ||
    $codigo_curso === '' || strlen($codigo_curso) > 20 ||
    !$carrera_id || $creditos === false || $creditos === null ||
    $creditos < 0) {
    header('Location: ../pages/cursos.php?error=datos');
    exit;
}

try {
    // Verificar que el codigo no pertenezca a otro curso
    $consulta = $conn->prepare(
        "SELECT id FROM cursos
         WHERE codigo_curso = :codigo AND id != :id"
    );

    $consulta->execute([
        'codigo' => $codigo_curso,
        'id' => $id
    ]);

    if ($consulta->fetch()) {
        header('Location: ../pages/editar_curso.php?id=' . $id . '&error=existe');
        exit;
    }

    // Actualizar los datos del curso
    $consulta = $conn->prepare(
        "UPDATE cursos
         SET nombre = :nombre,
             codigo_curso = :codigo,
             carrera_id = :carrera,
             creditos = :creditos,
             descripcion = :descripcion
         WHERE id = :id"
    );

    $consulta->execute([
        'nombre' => $nombre,
        'codigo' => $codigo_curso,
        'carrera' => $carrera_id,
        'creditos' => $creditos,
        'descripcion' => $descripcion,
        'id' => $id
    ]);

    // Regresar a la lista de cursos
    header('Location: ../pages/cursos.php?actualizado=1');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../pages/cursos.php?error=servidor');
    exit;
}