
<?php

// Iniciar la sesion si no esta activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tiempo maximo de inactividad: 30 minutos
const SESSION_TIMEOUT_SECONDS = 30 * 60;

// Verificar que el usuario haya iniciado sesion
function verificarSesion()
{
    // Comprobar que exista una sesion
    if (!isset($_SESSION['user_id'])) {
        header('Location: /Workshops/Workshop%203/index.php?error=login_required');
        exit;
    }

    // Registrar la actividad inicial si no existe
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    }

    // Comprobar si la sesion ha expirado
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT_SECONDS) {

        // Eliminar los datos de la sesion
        $_SESSION = [];

        // Eliminar la cookie de la sesion
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 3600,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Destruir la sesion
        session_destroy();

        // Redirigir al inicio de sesion
        header('Location: /Workshops/Workshop%203/index.php?error=session_expired');
        exit;
    }

    // Actualizar la ultima actividad
    $_SESSION['last_activity'] = time();
}
