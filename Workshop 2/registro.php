<?php
require_once 'utils/database.php';

$consulta = $conn->query("SELECT id, nombre FROM provincias ORDER BY nombre");
$provincias = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'pages/common/header.php'; ?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Registro de usuarios</h4>
                    </div>

                    <div class="card-body">
                        <form action="actions/registro.php" method="POST">

                            <div class="form-group">
                                <label>Nombre de usuario</label>
                                <input type="text" name="username"
                                       class="form-control" maxlength="50" required>
                            </div>

                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password"
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Provincia</label>
                                <select name="provincia_id" class="form-control" required>
                                    <option value="">Seleccione una provincia</option>

                                    <?php foreach ($provincias as $provincia): ?>
                                        <option value="<?php echo (int) $provincia['id']; ?>">
                                            <?php echo htmlspecialchars($provincia['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                Registrarse
                            </button>

                        </form>

                        <div class="text-center mt-3">
                            <a href="index.php">Volver al inicio de sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'pages/common/footer.php'; ?>