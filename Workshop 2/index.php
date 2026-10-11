<?php require_once 'pages/common/header.php'; ?>
<body>
  <div class="container mt-5">
    <h1 class="text-center">Welcome to My Website</h1>
    <p class="text-center">This is a simple homepage created with PHP and Bootstrap.</p>
  </div>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">   
            <h4>Login</h4>
          </div>
          <div class="card-body">
            <?php
              if (isset($_GET['error'])) {
                  echo '<div class="alert alert-danger text-center">
                          Credenciales inválidas
                        </div>';
              }

              if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso') {
                  echo '<div class="alert alert-success text-center">
                          Usuario registrado correctamente
                        </div>';
              }
            ?>
            <form action="actions/login.php" method="POST">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block">
                Iniciar sesion
              </button>
              </form>

              <div class="text-center mt-3">
                  <p>No tienes cuenta?
                      <a href="registro.php">Registrate aqui</a>
                  </p>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 <?php require_once 'pages/common/footer.php'; ?>

