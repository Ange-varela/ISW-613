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
$nombre = trim($_POST['nombre'] ?? '');
$codigo_curso = trim($_POST['codigo_curso'] ?? '');
$carrera_id = filter_input(INPUT_POST, 'carrera_id', FILTER_VALIDATE_INT);
$creditos = filter_input(INPUT_POST, 'creditos', FILTER_VALIDATE_INT);
$descripcion = trim($_POST['descripcion'] ?? '');

// Validar los datos
if ($nombre === '' || strlen($nombre) > 100 ||
    $codigo_curso === '' || strlen($codigo_curso) > 20 ||
    !$carrera_id || $creditos === false || $creditos === null ||
    $creditos < 0) {
    header('Location: ../pages/crear_curso.php?error=datos');
    exit;
}

try {
    // Verificar que el codigo no exista
    $consulta = $conn->prepare(
        "SELECT id FROM cursos WHERE codigo_curso = :codigo"
    );
    $consulta->execute(['codigo' => $codigo_curso]);

    if ($consulta->fetch()) {
        header('Location: ../pages/crear_curso.php?error=existe');
        exit;
    }

    // Guardar el curso
    $consulta = $conn->prepare(
        "INSERT INTO cursos
         (nombre, codigo_curso, carrera_id, creditos, descripcion)
         VALUES (:nombre, :codigo, :carrera, :creditos, :descripcion)"
    );

    $consulta->execute([
        'nombre' => $nombre,
        'codigo' => $codigo_curso,
        'carrera' => $carrera_id,
        'creditos' => $creditos,
        'descripcion' => $descripcion
    ]);

    // Regresar a la lista de cursos
    header('Location: ../pages/cursos.php?creado=1');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../pages/crear_curso.php?error=servidor');
    exit;
}