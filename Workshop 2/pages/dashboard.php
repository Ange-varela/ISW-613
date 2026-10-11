
<?php
session_start();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../utils/database.php';

// Consultar los datos del usuario
$consulta = $conn->prepare(
    "SELECT u.id, u.username, u.provincia_id, p.nombre AS provincia
     FROM usuarios u
     LEFT JOIN provincias p ON u.provincia_id = p.id
     WHERE u.id = :id"
);
$consulta->execute(['id' => $_SESSION['user_id']]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

// Verificar que el usuario exista
if (!$usuario) {
    header('Location: logout.php');
    exit;
}

// Obtener las provincias para el formulario
$consulta = $conn->query("SELECT id, nombre FROM provincias ORDER BY nombre");
$provincias = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'common/header.php'; ?>
<body>
<div class="container mt-5">
    <h1 class="text-center">Perfil de usuario</h1>
    <p class="text-center">Consulta y administra tu información personal.</p>

    <div class="row justify-content-center mt-4">
    <div class="col-md-6">
    <div class="card">

        <div class="card-header text-center">
            <h4>Mi perfil</h4>
        </div>

        <div class="card-body">
            <h5 class="text-center mb-4">
                Bienvenido, <?= htmlspecialchars($usuario['username']) ?>
            </h5>

            <!-- Información actual -->
            <div class="form-group">
                <label><strong>Nombre de usuario:</strong></label>
                <p class="form-control"><?= htmlspecialchars($usuario['username']) ?></p>
            </div>

            <div class="form-group">
                <label><strong>Provincia:</strong></label>
                <p class="form-control"><?= htmlspecialchars($usuario['provincia'] ?? 'Sin asignar') ?></p>
            </div>

            <hr>

            <!-- Mensajes de actualización -->
            <?php if (isset($_GET['actualizado'])): ?>
                <div class="alert alert-success">Datos actualizados correctamente.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">No se pudieron actualizar los datos.</div>
            <?php endif; ?>

            <h5 class="text-center mb-3">Actualizar mis datos</h5>

            <!-- Formulario para modificar el perfil -->
            <form action="../actions/actualizar.php" method="POST">
                <div class="form-group">
                    <label>Nombre de usuario</label>
                    <input type="text" name="username" class="form-control"
                           maxlength="50" value="<?= htmlspecialchars($usuario['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Provincia</label>
                    <select name="provincia_id" class="form-control" required>
                        <option value="">Seleccione una provincia</option>
                        <?php foreach ($provincias as $provincia): ?>
                            <option value="<?= (int)$provincia['id'] ?>"
                                <?= $usuario['provincia_id'] == $provincia['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($provincia['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nueva contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Dejar vacío para mantener la actual">
                </div>

                <button type="submit" class="btn btn-primary btn-block mb-3">
                    Guardar cambios
                </button>
            </form>

            <hr>

            <!-- Cerrar sesión -->
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger btn-block">Cerrar sesión</button>
            </form>
        </div>

    </div>
    </div>
    </div>
</div>
<?php require_once 'common/footer.php'; ?>
