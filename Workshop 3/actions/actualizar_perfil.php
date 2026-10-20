
<?php

// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Permitir solamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/perfil.php');
    exit;
}

require_once '../utils/database.php';
require_once '../utils/profile_pictures.php';

// Obtener los datos del formulario
$username = $_POST['username'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$provincia_id = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

// Validar los tipos de datos
if (
    !is_string($username) ||
    !is_string($current_password) ||
    !is_string($new_password) ||
    !is_string($confirm_password)
) {
    header('Location: ../pages/perfil.php?error=datos');
    exit;
}

$username = trim($username);

// Validar el nombre de usuario y la provincia
if ($username === '' || strlen($username) > 50 || !$provincia_id) {
    header('Location: ../pages/perfil.php?error=datos');
    exit;
}

// Preparar las variables de la fotografia
$nuevaFoto = null;
$fotoAnterior = null;

try {

    // Verificar que la provincia exista
    $consulta = $conn->prepare(
        "SELECT id FROM provincias WHERE id = :id"
    );
    $consulta->execute(['id' => $provincia_id]);

    if (!$consulta->fetch()) {
        header('Location: ../pages/perfil.php?error=provincia');
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
        header('Location: ../pages/perfil.php?error=existe');
        exit;
    }

    // Consultar la contrasena y la fotografia actuales
    $consulta = $conn->prepare(
        "SELECT password_hash, profile_picture
         FROM usuarios
         WHERE id = :id"
    );
    $consulta->execute(['id' => $_SESSION['user_id']]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    // Verificar que el usuario exista
    if (!$usuario) {
        header('Location: ../pages/perfil.php?error=datos');
        exit;
    }

    $fotoAnterior = $usuario['profile_picture'];

    // Preparar los datos para actualizar
    $campos = [
        'username = :username',
        'provincia_id = :provincia_id'
    ];

    $parametros = [
        'username' => $username,
        'provincia_id' => $provincia_id,
        'id' => $_SESSION['user_id']
    ];

    // Verificar si el usuario desea cambiar la contrasena
    if (
        $current_password !== '' ||
        $new_password !== '' ||
        $confirm_password !== ''
    ) {

        // Verificar que los tres campos esten completos
        if (
            $current_password === '' ||
            $new_password === '' ||
            $confirm_password === ''
        ) {
            header('Location: ../pages/perfil.php?error=password_incomplete');
            exit;
        }

        // Verificar la longitud de la nueva contrasena
        if (strlen($new_password) < 8) {
            header('Location: ../pages/perfil.php?error=password_too_short');
            exit;
        }

        // Verificar que las nuevas contrasenas coincidan
        if ($new_password !== $confirm_password) {
            header('Location: ../pages/perfil.php?error=password_mismatch');
            exit;
        }

        // Verificar que la contrasena actual sea correcta
        if (!password_verify($current_password, $usuario['password_hash'])) {
            header('Location: ../pages/perfil.php?error=wrong_password');
            exit;
        }

        // Preparar la nueva contrasena
        $campos[] = 'password_hash = :password_hash';
        $parametros['password_hash'] = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );
    }

    // Procesar la nueva fotografia si fue seleccionada
    if (
        isset($_FILES['profile_picture']) &&
        ($_FILES['profile_picture']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE
    ) {
        $resultado = storeProfilePicture($_FILES['profile_picture']);

        // Verificar si ocurrio un error al subir la fotografia
        if (isset($resultado['error'])) {
            header('Location: ../pages/perfil.php?error=' . $resultado['error']);
            exit;
        }

        $nuevaFoto = $resultado['filename'];
    }

    // Actualizar la fotografia si se subio una nueva
    if ($nuevaFoto !== null) {
        $campos[] = 'profile_picture = :profile_picture';
        $parametros['profile_picture'] = $nuevaFoto;
    }

    // Guardar los cambios en la base de datos
    $sql = "UPDATE usuarios SET " . implode(', ', $campos) .
           " WHERE id = :id";

    $consulta = $conn->prepare($sql);
    $consulta->execute($parametros);

    // Eliminar la fotografia anterior despues de guardar la nueva
    if ($nuevaFoto !== null && !empty($fotoAnterior)) {
        deleteProfilePicture($fotoAnterior);
    }

    // Actualizar los datos de la sesion
    $_SESSION['username'] = $username;

    if ($nuevaFoto !== null) {
        $_SESSION['profile_picture'] = $nuevaFoto;
    }

    // Regresar al perfil con mensaje de exito
    header('Location: ../pages/perfil.php?actualizado=1');
    exit;

} catch (Throwable $e) {

    // Eliminar la nueva fotografia si ocurre un error
    if ($nuevaFoto !== null) {
        deleteProfilePicture($nuevaFoto);
    }

    // Registrar el error en el servidor
    error_log('Error al actualizar perfil: ' . $e->getMessage());

    header('Location: ../pages/perfil.php?error=servidor');
    exit;
}
