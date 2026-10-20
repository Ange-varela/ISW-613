<?php
// Iniciar la sesion si todavia no esta iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario inicio sesion
$sesionIniciada = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Workshop 3</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>

  <!-- Menu de navegacion -->
  <ul class="nav nav-tabs" id="navId">

    <?php if ($sesionIniciada): ?>

      <!-- Opciones para usuarios autenticados -->
      <li class="nav-item">
        <a href="/Workshops/Workshop%203/pages/dashboard.php" class="nav-link">
          Inicio
        </a>
      </li>

      <li class="nav-item">
        <a href="/Workshops/Workshop%203/pages/perfil.php" class="nav-link">
          Mi perfil
        </a>
      </li>

      <li class="nav-item">
        <a href="/Workshops/Workshop%203/pages/cursos.php" class="nav-link">
          Cursos
        </a>
      </li>

      <li class="nav-item">
        <form action="/Workshops/Workshop%203/pages/logout.php" method="POST" class="m-0">
          <button type="submit" class="nav-link btn btn-link">
            Cerrar sesion
          </button>
        </form>
      </li>

    <?php else: ?>

      <!-- Opciones para visitantes -->
      <li class="nav-item">
        <a href="/Workshops/Workshop%203/index.php" class="nav-link">
          Inicio
        </a>
      </li>

      <li class="nav-item">
        <a href="/Workshops/Workshop%203/index.php" class="nav-link">
          Iniciar sesion
        </a>
      </li>

      <!-- Enlace al registro -->
      <li class="nav-item">
          <a href="/Workshops/Workshop%203/registro.php" class="nav-link">
              Registrarse
          </a>
      </li>

    <?php endif; ?>

  </ul>