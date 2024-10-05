<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/errores.css">
    <title>Login</title>
</head>
<body>

    <div class="login-container">
        <form action="index.php?controller=user&action=login" method="POST" class="login-form">
            <?php if (!empty($mensajeError)) : ?>
                <div class="mensajeError">
                    <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>
            <h2>Login</h2>
            <label for="correoElectronico">Correo electrónico</label>
            <input type="email" name="email" id="correoElectronico" placeholder="email" required>
            
            <label for="contraseña">Contraseña</label>
            <input type="password" name="password" id="contraseña" placeholder="contraseña" required>
            
            <button type="submit">Login</button>
            
            <div class="login-links">
                <a href="index.php?controller=user&action=verRegistro">No tengo cuenta</a>
                <a href="index.php?controller=user&action=verRecuperarPassword">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

</body>
</html>
