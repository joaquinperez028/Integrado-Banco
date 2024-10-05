<?php
include '../general/cabecera_privada.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssPriv.css">
    <title>Document</title>
</head>
<body>
    <br>

    <nav>
    <a href="../../index.php?controller=user&action=logout">CERRAR SESION</a> 
    <a href="../../index.php?controller=user&action=transferir">TRANSFERIR</a> 
    <a href="infoUsuario_view.php">VER MI INFORMACION</a>
    </nav>


    <br>
   <p> Nombre de usuario: <?php echo $_SESSION['nombre']; ?></p>
    <br>
    <p> Email: <?php echo $_SESSION['email']; ?></p>
    <br>
    <h3>Cuentas</h3>
    <table border=1>
    <tr>
        <th>Nombre</th>
        <th>Número</th>
        <th>Saldo disponible</th>
    </tr>
    <?php if (!empty($_SESSION['cuentas'])): ?>
        <?php foreach ($_SESSION['cuentas'] as $cuenta): ?>
            <tr>
                <td><?php echo $cuenta['nombreCuenta']; ?></td>
                <td><?php echo $cuenta['numeroCuenta']; ?></td>
                <td><?php echo $cuenta['saldoCuenta']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No tiene cuentas creadas</td>
        </tr>
    <?php endif; ?>
    </table>
    <br>
    <form action="../../index.php?controller=transacciones&action=verTransacciones" method="POST">
        <button type="submit" name="mostrarMovimientos">Mostrar movimientos</button>
    </form>
    <?php
        if(empty($cuenta['numeroCuenta'])){
            echo '<h3>No tienes cuentas creadas?<a href="../../index.php?controller=user&action=crearCuenta">haz click aqui!</a></h3>';
        }
        else{
            echo '<a href="../../index.php?controller=user&action=crearCuenta" class="btnCrearCuenta">Crear otra cuenta</a>';
        }
    ?>

</body>
</html>
