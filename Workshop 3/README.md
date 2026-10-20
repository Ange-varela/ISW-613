# Workshop 3 - Gestion de cursos

## Descripcion
Aplicacion web desarrollada en PHP y MySQL que permite registrar usuarios, iniciar sesion, administrar su perfil y gestionar cursos mediante operaciones CRUD.

## Funcionalidades
- Registro de usuarios con nombre de usuario, contraseña y provincia.
- Inicio de sesion y validacion de credenciales.
- Menu de navegacion con acceso al perfil, cursos y cierre de sesion.
- Visualizacion y actualizacion del perfil del usuario.
- Cambio de contraseña y fotografia de perfil.
- Listado de cursos con nombre, codigo, ID de carrera, creditos y descripcion.
- Creacion de cursos nuevos.
- Edicion de cursos existentes.
- Eliminacion de cursos mediante POST.
- Validacion de datos y codigos de curso duplicados.
- Proteccion de paginas mediante sesiones PHP.

## Tecnologias utilizadas
- PHP
- MySQL
- HTML
- JavaScript
- Bootstrap 4
- XAMPP

## Base de datos
La base de datos se llama `workshop3` y contiene las siguientes tablas:

- `usuarios`
- `provincias`
- `cursos`

El archivo `workshop3.sql` permite importar la base de datos.

## Ejecucion
1. Iniciar Apache y MySQL desde XAMPP.
2. Importar `workshop3.sql` desde phpMyAdmin.
3. Configurar la conexion MySQL en `utils/database.php` (este archivo no se incluye en el repositorio).
4. Abrir `http://localhost/Workshops/Workshop%203/index.php`.
5. Registrar un usuario e iniciar sesion.
6. Acceder a la seccion Cursos para crear, editar, consultar y eliminar cursos.
