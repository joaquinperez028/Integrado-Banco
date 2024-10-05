<?php
    if (empty($_SESSION['cuentas'])) {
    header('Location: index.php?controller=user&action=crearCuenta');
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Document</title>
</head>
<body>
    <nav>
    <a href="index.php?controller=user&action=logout">CERRAR SESION</a>
    <a href="index.php?controller=user&action=transferir">TRANSFERIR</a>
    <a href="view/privado/infoUsuario_view.php">VER MI INFORMACION</a>
    </nav>

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
<div class="transfe">
    <form action="index.php?controller=user&action=transferir" method="post">
        <label for="cuentaRemitente">Seleccione la cuenta de la cual va a transferir</label>
        <select name="cuentaRemitente" id="">
            <?php foreach ($_SESSION['cuentas'] as $cuenta): ?>
            <?php echo '<option value=' .$cuenta['numeroCuenta'] .'> '.$cuenta['nombreCuenta'] .' </option>';?>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="saldoATransferir">Cantidad de saldo que desea transferir: </label>
        <input type="number" name="saldoTransferido" required>
        <br>
        <label for="cuentaDestino">Numero de cuenta del destinatario:</label>
        <input type="number" name="cuentaDestino" required>
        <br>
        <label for="concepto">Concepto de la transferencia:</label>
        <textarea name="conceptoTransferencia" required></textarea>
        <input type="submit" value="Enviar Transferencia">
    </form>
</div>
</body>
</html>