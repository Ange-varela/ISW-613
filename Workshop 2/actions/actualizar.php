
<?php
session_start();

// Verificar que el usuario haya iniciado sesion
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Permitir solamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/dashboard.php');
    exit;
}

require_once '../utils/database.php';

// Obtener los datos del formulario
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$provincia_id = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

// Validar los datos
if (!is_string($username) || !is_string($password)) {
    header('Location: ../pages/dashboard.php?error=datos');
    exit;
}

$username = trim($username);

if ($username === '' || strlen($username) > 50 || !$provincia_id) {
    header('Location: ../pages/dashboard.php?error=datos');
    exit;
}

try {
    // Verificar que la provincia exista
    $consulta = $conn->prepare("SELECT id FROM provincias WHERE id = :id");
    $consulta->execute(['id' => $provincia_id]);

    if (!$consulta->fetch()) {
        header('Location: ../pages/dashboard.php?error=provincia');
        exit;
    }

    // Verificar que el username no pertenezca a otro usuario
    $consulta = $conn->prepare(
        "SELECT id FROM usuarios WHERE username = :username AND id != :id"
    );
    $consulta->execute([
        'username' => $username,
        'id' => $_SESSION['user_id']
    ]);

    if ($consulta->fetch()) {
        header('Location: ../pages/dashboard.php?error=existe');
        exit;
    }

    // Actualizar los datos del usuario
    if ($password !== '') {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $consulta = $conn->prepare(
            "UPDATE usuarios
             SET username = :username, provincia_id = :provincia_id,
                 password_hash = :password_hash
             WHERE id = :id"
        );

        $consulta->execute([
            'username' => $username,
            'provincia_id' => $provincia_id,
            'password_hash' => $password_hash,
            'id' => $_SESSION['user_id']
        ]);
    } else {
        $consulta = $conn->prepare(
            "UPDATE usuarios
             SET username = :username, provincia_id = :provincia_id
             WHERE id = :id"
        );

        $consulta->execute([
            'username' => $username,
            'provincia_id' => $provincia_id,
            'id' => $_SESSION['user_id']
        ]);
    }

    // Actualizar el username de la sesion
    $_SESSION['username'] = $username;

    header('Location: ../pages/dashboard.php?actualizado=1');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../pages/dashboard.php?error=servidor');
    exit;
}
