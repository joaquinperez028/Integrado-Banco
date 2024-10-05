<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/navBar.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/errores.css">
    <title>Recuperar Contraseña</title>
</head>
<body>

    <div class="login-container">
    <form action="index.php?controller=user&action=recuperar" method="post" class="login-form">
            <?php if (!empty($mensajeError)) : ?>
                <div class="mensajeError">
                    <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>
        <label for="correoElectronico">Ingrese el Correo de su cuenta</label>
        <input type="email" name="email" placeholder="email" required>
        <button type="submit">Enviar</button>
    </form>
    </div>
</body>
</html>