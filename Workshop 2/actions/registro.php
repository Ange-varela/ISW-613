<?php
require_once '../utils/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$provincia_id = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

if (
    $username === '' ||
    strlen($username) > 50 ||
    !is_string($password) ||
    $password === '' ||
    !$provincia_id
) {
    header('Location: ../registro.php?error=datos');
    exit;
}

try {
    // Verificar que la provincia exista
    $consulta = $conn->prepare(
        'SELECT id FROM provincias WHERE id = :id'
    );
    $consulta->execute(['id' => $provincia_id]);

    if (!$consulta->fetch()) {
        header('Location: ../registro.php?error=provincia');
        exit;
    }

    // Verificar que el nombre de usuario no esté registrado
    $consulta = $conn->prepare(
        'SELECT id FROM usuarios WHERE username = :username'
    );
    $consulta->execute(['username' => $username]);

    if ($consulta->fetch()) {
        header('Location: ../registro.php?error=existe');
        exit;
    }

    // Proteger la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Guardar el nuevo usuario
    $consulta = $conn->prepare(
        'INSERT INTO usuarios (username, password_hash, provincia_id)
         VALUES (:username, :password_hash, :provincia_id)'
    );

    $consulta->execute([
        'username' => $username,
        'password_hash' => $password_hash,
        'provincia_id' => $provincia_id
    ]);

    header('Location: ../index.php?registro=exitoso');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../registro.php?error=servidor');
    exit;
}