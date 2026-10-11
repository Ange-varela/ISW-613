<?php
session_start();

// Eliminar los datos de la sesion
$_SESSION = [];

// Eliminar la cookie de sesion
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destruir la sesinn
session_destroy();

// Regresar al inicio de sesion
header('Location: ../index.php');
exit;