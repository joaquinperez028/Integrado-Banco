<?php
   if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?controller=user&action=verLogin');
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=1.1">
    <title>Document</title>
</head>
<body>

<nav>
<a href="index.php?controller=user&action=logout">CERRAR SESION</a>
<a href="index.php?controller=user&action=transferir">TRANSFERIR</a>
<a href="view/privado/infoUsuario_view.php">VER MI INFORMACION</a>
</nav>



<div class="cuenta">
    <form action="index.php?controller=user&action=crearCuenta" method="post">
        <label for="nombre">Nombre de la cuenta a crear</label>
        <input type="text" name="nombreCuenta">
        <input type="submit" value="Crear">
    </form>

    </div>    
</body>
</html>