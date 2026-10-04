<?php

$conexion = new mysqli("localhost", "root", "", "workshop1");

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    header("Location: usuario.php");
    exit();
} else {
    header("Location: index.php?error=1");
    exit();
}