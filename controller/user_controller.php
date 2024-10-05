<?php
// Incluir el archivo de configuración de la base de datos y la clase User


require 'model/user_model.php';
function verRegistro ()
{   
   include 'view/register_view.php';
}

function verLogin ()
{   
   include 'view/login_view.php';
}

function verRecuperarPassword ()
{
    include 'view/forgotPassword_view.php';
}

function registrar(){

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crear una instancia de la clase User, pasando la conexión PDO como argumento
        $user = new User();
        // Sanitizar la entrada del usuario y lo registra

        $resultado = $user->register(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password2']), htmlspecialchars($_POST['opcionSeguridad']), htmlspecialchars($_POST['respuestaSeguridad']));
       // Redirigir al usuario a la página de inicio de sesión después del registro
       if ($resultado['status'] === false) {

        $mensajeError = $resultado['error'];
        include 'view/register_view.php';

        } else {
            $mensajeError = $resultado['error'];
            include 'view/login_view.php';
            echo '<script> alert("Registro completado con exito;."); </script>';
        }
    }

}

function login() {
    
    // Verificar si se recibió una solicitud POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crear una instancia de la clase User
        $user = new User();
        
        // Sanitizar la entrada del usuario e intenta iniciar sesión
        $resultado = $user->login(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']));

        if ($resultado['status'] === true) {
            // Si el login es exitoso, iniciar la sesión
            session_start();

            $_SESSION['user_id'] = $resultado['user']['id'];
            
            // Redirigir al usuario a la página de bienvenida
            header('Location: index.php?controller=user&action=consultarDatos');
            exit();
        } else {
            // Mostrar un mensaje de error si las credenciales de inicio de sesión son incorrectas
            $mensajeError = $resultado['error'];
            include 'view/login_view.php';
        }
    }
}



function recuperar() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $user = new User();

        $recuperarContraseña = $user->recuperarContraseña(htmlspecialchars($_POST['email']));
        
        if($recuperarContraseña['status'] === true){
            include 'view/responderPregunta_view.php';
        }
        else {
            // Mostrar el mensaje de error
            $mensajeError = $recuperarContraseña['error'];
            include 'view/forgotPassword_view.php';
            echo '<meta http-equiv="refresh" content="3;url=index.php?controller=user&action=verLogin">';
            exit();
        }
        
    }

}

function responderPregunta() {


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $user = new User();


        $compararContraseñas = $user -> buscarRespuesta(htmlspecialchars($_POST['respuestaSeguridad']));

        if($compararContraseñas['status'] === true){

            include 'view/cambiarPassword_view.php';

        }
        else {
            $mensajeError = $compararContraseñas['error'];
            include 'view/responderPregunta_view.php';
            echo '<meta http-equiv="refresh" content="3;url=index.php?controller=user&action=verLogin">';
            exit();

        }
    }    
}

function cambiarPassword () {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $user = new User();
        
        // Llamar a la función cambiarPassword y obtener el resultado
        $resultado = $user->cambiarPassword(htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password2']));

        // Si la contraseña fue cambiada con éxito
        if($resultado['status'] === true) {
            echo 'Contraseña cambiada con éxito. Serás redirigido en 3 segundos.';
            echo '<meta http-equiv="refresh" content="3;url=index.php?controller=user&action=verLogin">';
            exit();
        } else {
            
            $mensajeError = $resultado['error'];
            include 'view/cambiarPassword_view.php';
        }
    }
}

function consultarDatos() {

    if(session_status() === PHP_SESSION_NONE) session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){

        $user = new User();

        $datosUsuario = $user -> consultarDatos();

        if($datosUsuario){
            header('Location: view/privado/welcome_view.php');
        }
        else{
            header('Location: view/privado/infoUsuario_view.php');
            echo 'no pudimos obtener los datos del usuario';
        }
    }
}

function consultarCuentas() {

    if(session_status() === PHP_SESSION_NONE) session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){

        $user = new User();

        $datosUsuario = $user -> consultarDatos();
        $cuentasUsuario = $user -> consultarCuentas();

        if($cuentasUsuario || $datosUsuario){

            header('Location: view/privado/infoUsuario_view.php');

        }
        else{
            header('Location: view/privado/welcome_view.php');
            echo 'no pudimos obtener las cuentas del usuario';
        }
    }
}


function transferir(){

    if(session_status() === PHP_SESSION_NONE) session_start();

    include 'view/privado/menuTransferencia_view.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        $user = new User();

        $transferencia = $user->realizarTransferencia(htmlspecialchars($_POST['cuentaRemitente']),htmlspecialchars($_POST['saldoTransferido']),htmlspecialchars($_POST['cuentaDestino']),htmlspecialchars($_POST['conceptoTransferencia']));

        if($transferencia){
            echo 'transferencia realizada con exito. Serás redirigido en 3 segundos.';
            echo '<meta http-equiv="refresh" content="3;url=index.php?controller=user&action=consultarCuentas">';
            exit();
        
        }
        else{
            echo ' La transferencia se cancelo.';
        }
    }
}

function crearCuenta(){

    if(session_status() === PHP_SESSION_NONE) session_start();

    include 'view/privado/crearCuenta_view.php';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $user = new User();
        
        $cuentaNueva = $user->crearCuenta(htmlspecialchars($_POST['nombreCuenta']));

        if($cuentaNueva){
            header('Location: index.php?controller=user&action=consultarCuentas');
        } else {
            echo 'no se pudo crear la cuenta';
        }
    }
     
}

function logout() {
    session_start();
    session_destroy();
    echo '<script> alert("Session Cerrada con exito") </script> ';
    include 'view/login_view.php';
}

?>