# Workshop 2 - Registro y autenticacion de usuarios

## Descripcion
Aplicacion web desarrollada en PHP que permite registrar usuarios, iniciar sesion y administrar su perfil mediante una base de datos MySQL.

## Funcionalidades
- Registro de usuarios con username, password y provincia.
- Inicio de sesion y validacion de credenciales.
- Proteccion del perfil mediante sesiones PHP.
- Visualizacion de los datos del usuario.
- Actualizacion del username, provincia y password.
- Cierre de sesion.

## Tecnologias utilizadas
- PHP
- MySQL
- HTML y Bootstrap
- XAMPP

## Base de datos
La base de datos se llama `isw613` y contiene las tablas `usuarios` y `provincias`.

El archivo `isw613.sql` permite importar la estructura de las tablas y las siete provincias de Costa Rica.

## Ejecucion
1. Iniciar Apache y MySQL desde XAMPP.
2. Importar `isw613.sql` desde phpMyAdmin.
3. Abrir `http://localhost/Workshops/Workshop%202/index.php`.
4. Registrar un usuario e iniciar sesion.