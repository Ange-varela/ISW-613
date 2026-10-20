<?php
require_once '../utils/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$provincia_id = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

// Guardar los datos del formulario
$_SESSION['registro_datos'] = [
    'username' => is_string($username) ? $username : '',
    'provincia_id' => $provincia_id ?: ''
];

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

// Verificar la longitud de la contrasena
if (strlen($password) < 8) {
    header('Location: ../registro.php?error=longitud');
    exit;
}

// Verificar que las contrasenas coincidan
if ($password !== $confirm_password) {
    header('Location: ../registro.php?error=password');
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

    // Verificar que el nombre de usuario no este registrado
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

    // Limpiar los datos despues del registro exitoso
    unset($_SESSION['registro_datos']);

    // Regresar al registro y mostrar mensaje de exito
    header('Location: ../registro.php?registro=exitoso');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../registro.php?error=servidor');
    exit;
}