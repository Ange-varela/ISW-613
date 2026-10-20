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

// Obtener el ID del curso
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// Validar el ID
if (!$id) {
    header('Location: ../pages/cursos.php?error=datos');
    exit;
}

try {
    // Eliminar el curso seleccionado
    $consulta = $conn->prepare(
        "DELETE FROM cursos WHERE id = :id"
    );

    $consulta->execute([
        'id' => $id
    ]);

    // Regresar a la lista de cursos
    header('Location: ../pages/cursos.php?eliminado=1');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../pages/cursos.php?error=servidor');
    exit;
}