
<?php

// Verificar que el usuario haya iniciado sesion
require_once '../utils/auth.php';
require_once '../utils/profile_pictures.php';
verificarSesion();

require_once '../utils/database.php';

// Consultar los datos del usuario
$consulta = $conn->prepare(
    "SELECT u.id, u.username, u.provincia_id, u.profile_picture,
            p.nombre AS provincia
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
    <p class="text-center">Consulta y administra tu informacion personal.</p>

    <div class="row justify-content-center mt-4">
    <div class="col-md-6">
    <div class="card">

        <div class="card-header text-center">
            <h4>Mi perfil</h4>
        </div>

        <div class="card-body">

            <!-- Mostrar la fotografia de perfil -->
            <div class="text-center mb-4">
                <?php if (!empty($usuario['profile_picture'])): ?>
                    <img
                        src="<?= htmlspecialchars(profilePictureUrl($usuario['profile_picture']), ENT_QUOTES, 'UTF-8') ?>"
                        alt="Foto de perfil"
                        class="rounded-circle"
                        style="width: 130px; height: 130px; object-fit: cover;"
                    >
                <?php else: ?>

                    <!-- Mostrar la inicial si no tiene fotografia -->
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                         style="width: 130px; height: 130px; font-size: 55px;">
                        <?= htmlspecialchars(strtoupper(substr($usuario['username'], 0, 1))) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mostrar el mensaje de bienvenida -->
            <h5 class="text-center mb-4">
                Bienvenido, <?= htmlspecialchars($usuario['username']) ?>
            </h5>

            <!-- Mostrar el nombre de usuario actual -->
            <div class="form-group">
                <label><strong>Nombre de usuario:</strong></label>
                <p class="form-control"><?= htmlspecialchars($usuario['username']) ?></p>
            </div>

            <!-- Mostrar la provincia actual -->
            <div class="form-group">
                <label><strong>Provincia:</strong></label>
                <p class="form-control"><?= htmlspecialchars($usuario['provincia'] ?? 'Sin asignar') ?></p>
            </div>

            <hr>

            <!-- Mostrar mensaje de actualizacion exitosa -->
            <?php if (isset($_GET['actualizado'])): ?>
                <div class="alert alert-success">
                    Datos actualizados correctamente.
                </div>
            <?php endif; ?>

            
            <?php
            // Preparar los mensajes de error
            $errores = [
                'datos' => 'Complete correctamente los datos.',
                'provincia' => 'Seleccione una provincia valida.',
                'existe' => 'El nombre de usuario ya esta registrado.',
                'password_incomplete' => 'Complete los tres campos de contraseña.',
                'password_too_short' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'password_mismatch' => 'Las nuevas contraseñas no coinciden.',
                'wrong_password' => 'La contraseña actual es incorrecta.',
                'invalid_picture' => 'Seleccione una imagen JPG, PNG, GIF o WEBP.',
                'picture_too_large' => 'La fotografia no puede superar los 2 MB.',
                'upload_failed' => 'No se pudo subir la fotografia.',
                'servidor' => 'Ocurrio un error al actualizar el perfil.'
            ];

            $codigoError = $_GET['error'] ?? '';

            if (is_string($codigoError) && isset($errores[$codigoError])):
            ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errores[$codigoError], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>


            <h5 class="text-center mb-3">Actualizar mis datos</h5>

            <!-- Formulario para actualizar el perfil -->
            <form action="../actions/actualizar_perfil.php"
                  method="POST"
                  enctype="multipart/form-data">

                <!-- Seleccionar una nueva fotografia -->
                <div class="form-group">
                    <label>Fotografia de perfil (opcional)</label>
                    <input type="file"
                           name="profile_picture"
                           class="form-control-file"
                           accept=".jpg,.jpeg,.png,.gif,.webp">
                    <small class="form-text text-muted">
                        Formatos permitidos: JPG, PNG, GIF y WEBP. Maximo 2 MB.
                    </small>
                </div>

                <!-- Modificar el nombre de usuario -->
                <div class="form-group">
                    <label>Nombre de usuario</label>
                    <input type="text"
                           name="username"
                           class="form-control"
                           maxlength="50"
                           value="<?= htmlspecialchars($usuario['username']) ?>"
                           required>
                </div>

                <!-- Seleccionar la provincia -->
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

                
                <!-- Cambiar la contrasena de forma opcional -->
                <hr>
                <h5 class="text-center mb-3">Cambiar contraseña</h5>

                <!-- Ingresar la contrasena actual -->
                <div class="form-group">
                    <label>Contraseña actual</label>
                    <input type="password"
                        name="current_password"
                        class="form-control"
                        autocomplete="current-password"
                        placeholder="Ingrese su contraseña actual">
                </div>

                <!-- Ingresar la nueva contrasena -->
                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password"
                        name="new_password"
                        class="form-control"
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Minimo 8 caracteres">
                </div>

                <!-- Confirmar la nueva contrasena -->
                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password"
                        name="confirm_password"
                        class="form-control"
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Repita la nueva contraseña">
                </div>

                <small class="form-text text-muted mb-3">
                    Deje los tres campos vacios si no desea cambiar su contraseña.
                </small>


                <!-- Guardar los cambios del perfil -->
                <button type="submit" class="btn btn-primary btn-block mb-3">
                    Guardar cambios
                </button>
            </form>

            <hr>

            <!-- Cerrar la sesion del usuario -->
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger btn-block">
                    Cerrar sesion
                </button>
            </form>

        </div>
    </div>
    </div>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>
