
<?php
// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
verificarSesion();

// Obtener el nombre del usuario
$username = $_SESSION['username'] ?? 'Usuario';

// Mostrar el encabezado
require_once 'common/header.php';
?>

<div class="container mt-5">

    <!-- Bienvenida -->
    <div class="jumbotron text-center bg-light border rounded">
        <h1 class="display-4">Sistema Academico</h1>

        <p class="lead">
            Bienvenido,
            <strong><?= htmlspecialchars($username) ?></strong>
        </p>

        <hr class="my-4">

        <p>
            Desde este sistema puedes administrar tu informacion
            personal y gestionar los cursos registrados.
        </p>

        <p class="text-muted mb-0">
            Utiliza el menu superior para navegar por el sistema.
        </p>
    </div>

    <!-- Informacion del sistema -->
    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Mi perfil</h4>
                    <p class="card-text">
                        Consulta tu informacion personal y actualiza
                        tus datos cuando lo necesites.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Gestion de cursos</h4>
                    <p class="card-text">
                        Consulta los cursos registrados, agrega nuevos
                        cursos y modifica o elimina los existentes.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<?php require_once 'common/footer.php'; ?>
