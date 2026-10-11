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
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];


header('Location: ../pages/dashboard.php');
?>
