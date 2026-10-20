
<?php
session_start();
require_once 'utils/database.php';

// Obtener las provincias
$consulta = $conn->query("SELECT id, nombre FROM provincias ORDER BY nombre");
$provincias = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Recuperar los datos del formulario
$datosRegistro = $_SESSION['registro_datos'] ?? [];
unset($_SESSION['registro_datos']);

$usernameAnterior = $datosRegistro['username'] ?? '';
$provinciaAnterior = $datosRegistro['provincia_id'] ?? '';

// Preparar los mensajes de error
$errores = [
    'password' => 'Las contraseñas no coinciden.',
    'longitud' => 'La contraseña debe tener al menos 8 caracteres.',
    'datos' => 'Complete correctamente todos los campos.',
    'provincia' => 'La provincia seleccionada no es valida.',
    'existe' => 'El nombre de usuario ya esta registrado.',
    'servidor' => 'Ocurrio un error al registrar el usuario.'
];

// Obtener el mensaje de error
$codigoError = $_GET['error'] ?? '';

require_once 'pages/common/header.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Registro de usuarios</h4>
                </div>
                <div class="card-body">
                    <!-- Mostrar el mensaje de registro exitoso -->
                    <?php if (($_GET['registro'] ?? '') === 'exitoso'): ?>
                        <div class="alert alert-success text-center">
                            Usuario registrado correctamente.
                        </div>
                    <?php endif; ?>
                    <!-- Mostrar los mensajes de error -->
                    <?php if (is_string($codigoError) && isset($errores[$codigoError])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($errores[$codigoError], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Formulario de registro -->
                    <form action="actions/registro.php" method="POST" onsubmit="return validarContrasenas()">
                        <!-- Nombre de usuario -->
                        <div class="form-group">
                            <label for="username">Nombre de usuario</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= htmlspecialchars((string) $usernameAnterior, ENT_QUOTES, 'UTF-8') ?>"
                                   maxlength="50" required>
                        </div>
                        <!-- Contrasena -->
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   minlength="8" required>
                        </div>
                        <!-- Confirmar contrasena -->
                        <div class="form-group">
                            <label for="confirm_password">Confirmar contraseña</label>
                            <input type="password" class="form-control" id="confirm_password"
                                   name="confirm_password" minlength="8" required>
                        </div>
                        <!-- Mostrar error de contrasenas -->
                        <div id="errorPassword" class="alert alert-danger d-none">
                            Las contraseñas no coinciden.
                        </div>
                        <!-- Seleccionar provincia -->
                        <div class="form-group">
                            <label for="provincia_id">Provincia</label>
                            <select name="provincia_id" id="provincia_id" class="form-control" required>
                                <option value="">Seleccione una provincia</option>
                                <?php foreach ($provincias as $provincia): ?>
                                    <option value="<?= (int) $provincia['id'] ?>"
                                            <?= (string) $provinciaAnterior === (string) $provincia['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($provincia['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Boton de registro -->
                        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                    </form>
                    <!-- Boton para cancelar -->
                    <div class="mt-2">
                        <a href="index.php" class="btn btn-danger btn-block">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function validarContrasenas() {
    // Obtener las contrasenas
    const password = document.querySelector('[name="password"]').value;
    const confirmar = document.querySelector('[name="confirm_password"]').value;
    const mensaje = document.getElementById('errorPassword');

    // Verificar que sean iguales
    if (password !== confirmar) {
        mensaje.classList.remove('d-none');
        return false;
    }

    mensaje.classList.add('d-none');
    return true;
}
</script>
<?php require_once 'pages/common/footer.php'; ?>
