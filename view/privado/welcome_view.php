<?php
include '../general/cabecera_privada.php';
if(!isset($_SESSION['nombre']) || !isset($_SESSION['email'])){
    header('Location: ../../index.php?controller=user&action=consultarDatos');
  if(!isset($_SESSION['cuentas']))  {
        header('Location: ../../index.php?controller=user&action=consultarCuentas');
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Document</title>
</head>

<body id="we">
    <br>
<div class="bien">
    <nav>
    <a href="../../index.php?controller=user&action=logout">CERRAR SESION</a>
    <a href="../../index.php?controller=user&action=transferir">TRANSFERIR</a>
    <a href="../../index.php?controller=user&action=consultarCuentas">VER MI INFORMACION</a>
    </nav>
 </div>
 <div class="Well" >
    <h1>Bienvenido <?php echo $_SESSION['nombre']; ?> a:</h1>
    </div>
</body>
</html>