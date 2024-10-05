<?php    
if (!isset($_SESSION['user_id'])) {
header('Location: ../../index.php?controller=user&action=verLogin');
exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Mis Transferencias</title>
</head>
<body>

<nav>
<a href="index.php?controller=user&action=logout">CERRAR SESION</a> 
<a href="index.php?controller=user&action=transferir">TRANSFERIR</a> 
<a href="index.php?controller=user&action=consultarCuentas">VER MI INFORMACION</a>
</nav>    

<h2>Todas las Transferencias</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Remitente</th>
        <th>Destino</th>
        <th>Monto</th>
        <th>Concepto</th>
        <th>Fecha</th>
    </tr>
    <?php foreach ($transferencias as $transferencia) { ?>
    <tr>
        <td><?php echo $transferencia['id']; ?></td>
        <td><?php echo $transferencia['numeroCuentaRemitente']; ?></td>
        <td><?php echo $transferencia['numeroCuentaDestino']; ?></td>
        <td>$<?php echo $transferencia['saldoEnviado']; ?></td>
        <td><?php echo $transferencia['concepto']; ?></td>
        <td><?php echo $transferencia['fecha']; ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
