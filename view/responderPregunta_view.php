<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/navBar.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/errores.css">
    <title>Document</title>
</head>
<body>

    <div class="login-container">
        <form action="index.php?controller=user&action=responderPregunta" method="post" class="login-form">
            <?php if (!empty($mensajeError)) : ?>
                <div class="mensajeError">
                    <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>
            <label for="pregunta">
            <?php
            echo $_COOKIE["opcionSeguridad"];
            ?>
            :
            </label>
            <input type="text" name="respuestaSeguridad" placeholder="Respuesta" required>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>