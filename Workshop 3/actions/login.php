<?php
session_start(); 
require_once '../utils/database.php';

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

// validates the input to ensure that the username and password are strings, not empty, and within reasonable length limits
if (
    !is_string($username)
    || !is_string($password)
    || trim($username) === ''
    || strlen($username) > 50
    || $password === ''
) {
    //redirect to login page with error message
    header('Location: ../index.php?error=invalid_input');
    exit;
}

$user = false;
try {
    // connect to the database and fetch the user record
    $statement = $conn->prepare(
        'SELECT id, username, password_hash FROM usuarios WHERE username = :username LIMIT 1'
    );
    $statement->execute(['username' => trim($username)]);
    $user = $statement->fetch();
} catch (PDOException $exception) {
    error_log('Authentication database error: ' . $exception->getMessage());
}

// check if the user exists and verify the password
if ($user === false || !password_verify($password, $user['password_hash'])) {
    // Invalid credentials redirect to login page with error message
    header('Location: ../index.php?error=invalid_credentials');
    exit;
}

// Set session variables and redirect to dashboard
// Iniciar sesion
session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];

// Crear una sesion segura
session_regenerate_id(true);

// Guardar los datos del usuario
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];

// Registrar la ultima actividad
$_SESSION['last_activity'] = time();

// Configuracion de la cookie
$cookieOptions = [
    'expires' => time() + (30 * 24 * 60 * 60),
    'path' => '/Workshops/Workshop%203/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
];

// Recordar el usuario por 30 dias
if (isset($_POST['remember_me'])) {
    setcookie('remembered_username', $user['username'], $cookieOptions);
} else {
    // Eliminar el usuario recordado
    $cookieOptions['expires'] = time() - 3600;
    setcookie('remembered_username', '', $cookieOptions);
}

// Ir al dashboard
header('Location: ../pages/dashboard.php');
exit;
