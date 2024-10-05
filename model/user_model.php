<?php
// Definición de la clase User
require "config.php";

class User {
     // Propiedad privada para almacenar la conexión PDO a la base de datos
    
    private $pdo;
    //PDO (PHP Data Objects) es una extensión de PHP que proporciona una interfaz uniforme para acceder a bases de datos desde PHP
    // Constructor que recibe la conexión PDO como argumento

    public $mensajeError= '';

    public function __construct() {
        $this->pdo = getConnection();
    }

    // Método para registrar un nuevo usuario en la base de datos
    public function register($name, $email, $password, $password2, $opcionSeguridad, $respuestaSeguridad) {

        $mensajeError = '';

        if (empty($name) || empty($email) || empty($password) || empty($password2) || empty($opcionSeguridad) || empty($respuestaSeguridad)) {
            $mensajeError = 'Ingresaste un campo vacío.';
        }
    
        elseif (strlen($name) > 15 || strlen($name) < 3) {
            $mensajeError = 'El nombre de usuario debe tener entre 3 y 15 caracteres.';
        }
    
        elseif (!self::corroborarUsuario($name)) {
            $mensajeError = 'Ingresaste caracteres especiales en el campo de usuario.';
        }
        
        elseif (self::buscarEmail($email)) {
            $mensajeError = 'El email ya está registrado.';
        }

        elseif (strlen($email) > 50) {
            $mensajeError = 'El email ingresado es demasiado largo.';
        }
    
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensajeError = 'El email ingresado no cumple con los estándares.';
        }
    
        elseif (strlen($password) > 20 || strlen($password) < 8) {
            $mensajeError = 'La contraseña debe tener entre 8 y 20 caracteres.';
        }
    
        elseif (!self::corroborarPassword($password)) {
            $mensajeError ='La contraseña debe contener una letra minúscula, una mayúscula, un número y un carácter especial (ej: !, ?, #, @).';
        }
    
        elseif ($password !== $password2) {
            $mensajeError = 'Las contraseñas ingresadas no coinciden.';
        }
    
        elseif (strlen($respuestaSeguridad) >=30) {
            $mensajeError = 'La respuesta de seguridad es demasiado larga.';
        }

        if ($mensajeError !== '') {
            return ['error' => $mensajeError, 'status' => false];
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (name, email, password, opcionSeguridad, respuestaSeguridad) VALUES (:name, :email, :password, :opcionSeguridad, :respuestaSeguridad)');
        
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword, 'opcionSeguridad' => $opcionSeguridad, 'respuestaSeguridad' => $respuestaSeguridad]);
        
        return ['error' => '', 'status' => true];

    }
    

    public function crearCuenta($nombreCuenta) {

        $stmt = $this->pdo->prepare('SELECT COUNT(*) as totalCuentas FROM cuentas WHERE idUsuario = :idUsuario'); //usamos el count para obtener la cantidad de tablas que hay en cuentas con la id del usuario
        $stmt->execute(['idUsuario' => $_SESSION['user_id']]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado['totalCuentas'] >= 5) {
            echo '<script>alert("Llego al limite de cuentas creadas, comuniquese con soporte para poder ayudarlo");</script>';
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM cuentas WHERE idUsuario = :idUsuario');
        $stmt->execute(['idUsuario' => $_SESSION['user_id']]);
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC); // usamos fetchAll porque agarra todo y no solo el primero y lo convierte en un array con todo el contenido
        
        foreach ($cuentas as $cuenta) {
            if ($cuenta['nombreCuenta'] === $nombreCuenta) {
                echo '<script>alert("Ya tiene una cuenta creada con ese nombre.");</script>';
                return false;
            }
        }
    
        // Si no existe una cuenta con ese nombre, crear una nueva
        if (!empty($nombreCuenta)) {
            $numeroCuenta = $this->generarNumeroCuenta();
            $saldoCuenta = mt_rand(100, 99999);
    
            $stmt = $this->pdo->prepare('INSERT INTO cuentas (nombreCuenta, numeroCuenta, saldoCuenta, idUsuario) VALUES (:nombreCuenta, :numeroCuenta, :saldoCuenta, :idUsuario)');
            $stmt->execute([
                'nombreCuenta' => $nombreCuenta,
                'numeroCuenta' => $numeroCuenta,
                'saldoCuenta' => $saldoCuenta,
                'idUsuario' => $_SESSION['user_id']
            ]);
    
            return true;
        } else {
            echo '<script>alert("No ingresó un nombre a la cuenta.");</script>';
            return false;
        }
    }
    
    private function generarNumeroCuenta() {
        return str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function corroborarUsuario($name) {

        $validacion = "/^[a-zA-Z\dñáéíóúÁÉÍÓÚ]+$/"; // \d busca si tiene numeros del 0-9

        return preg_match($validacion, $name);
    }

    private function corroborarPassword($password) {

        $validacion = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/"; //si la cadena de texto que compara contiene almenos 1 minuscula, 1 mayuscula, 1 numero y un caracter especial 

        return preg_match($validacion, $password);
    }

    public function recuperarContraseña($email) {

        if(self::buscarEmail($email)){

            $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->execute(['email' => $email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            setcookie("opcionSeguridad", $user['opcionSeguridad'], time() +600);
            setcookie("email", $user['email'], time() +600);

            return ['status' => true, 'error' => ''];
            
        }

        else{
            return ['status' => false, 'error' => 'El email no está registrado.'];
        }
    }

    public function cambiarPassword($password, $password2) {

        if($password != $password2) {
            $mensajeError = 'Las contraseñas ingresadas no coinciden entre ellas. Ingréselas nuevamente.';
        }
    
        elseif (strlen($password) > 20 || strlen($password) < 8) {
    
            if (strlen($password) > 20) {
                $mensajeError = 'La contraseña es demasiado larga. Debe tener máximo 20 caracteres.';
            } elseif (strlen($password) < 8) {
                $mensajeError = 'La contraseña es demasiado corta. Debe tener al menos 8 caracteres.';
            }
    
        }
    
        elseif (!self::corroborarPassword($password)) {
            $mensajeError = 'La contraseña debe contener al menos una letra minúscula, una mayúscula, un número y un carácter especial (ej: !, ?, #, @).';
        }
    
        if ($mensajeError !== '') {
            return ['status' => false, 'error' => $mensajeError];
        }
        
        $nuevoPasswordHashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('UPDATE usuarios SET password = :nuevoPassword WHERE email = :email');
        $stmt->execute(['nuevoPassword' => $nuevoPasswordHashed, 'email' => $_COOKIE["email"]]);

        return ['status' => true];

    }

    public function buscarRespuesta ($respuestaSeguridad) {

        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $_COOKIE["email"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($respuestaSeguridad === $user['respuestaSeguridad']) {
            return ['status' => true, 'error' => ''];
        } else {
            return ['status' => false, 'error' => 'La respuesta de seguridad no coincide.'];
        }

    }

    private function buscarEmail($email){

          // Preparar la consulta SQL para seleccionar al usuario por su email     
          $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
          // Ejecutar la consulta SQL, pasando el email del usuario como parámetro        
          $stmt->execute(['email' => $email]);
         // Obtener la fila del usuario de la base de datos
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          
          // Verificar si se encontró un usuario y si la password coincide con el hash almacenado
          
          if ($user) {
              // El email ya existe
              return true;
          }
          // Si no se encontró email se puede registrar      
          return false;

    }

    public function login($email, $password) {
        // Preparar la consulta SQL para seleccionar al usuario por su nombre de usuario       
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        // Ejecutar la consulta SQL, pasando el email como parametro
        $stmt->execute(['email' => $email]);
        // Obtener la fila del usuario de la base de datos
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar si se encontró un email y si la password coincide con el hash almacenado
        if ($user && password_verify($password, $user['password'])) {
            return ['status' => true, 'user' => $user, 'error' => ''];
        }
        // Si no se encontró usuario o la password no coincide, devolver falso       
        return ['status' => false, 'user' => null, 'error' => 'Credenciales de inicio de sesión inválidas.'];
    }

    public function consultarDatos() {

        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['nombre'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        if($_SESSION['nombre'] && $_SESSION['email']){

            return true;

        } else{

            return false;
            echo 'usted no tiene ninguna cuenta creada';

        }
    }

    public function consultarCuentas() {

        $stmt = $this->pdo->prepare('SELECT * FROM cuentas WHERE idUsuario = :idUsuario');
        $stmt->execute(['idUsuario' => $_SESSION['user_id']]);
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC); // aca usamos a fetchAll para obtener todas las cuentas
        
        if (!empty($cuentas)) {

            $_SESSION['cuentas'] = $cuentas;
            return true;
        
        } else {    
            echo 'usted no tiene ninguna cuenta creada';
            return false;
        }

    }

    private function obtenerCuentaDestino($cuentaDestino){
        $stmt = $this->pdo->prepare('SELECT * FROM cuentas WHERE numeroCuenta = :numeroCuenta');
        $stmt->execute(['numeroCuenta' => $cuentaDestino]);
        $cuentaDestino = $stmt->fetch(PDO::FETCH_ASSOC);

        return $cuentaDestino;
    }

    private function obtenerCuentaRemitente($cuentaRemitente) {
        $stmt = $this->pdo->prepare('SELECT * FROM cuentas WHERE numeroCuenta = :numeroCuenta');
        $stmt->execute(['numeroCuenta' => $cuentaRemitente]);
        $cuentaRemitente = $stmt->fetch(PDO::FETCH_ASSOC);

         return $cuentaRemitente;
    }

    public function realizarTransferencia($cuentaRemitente, $saldoTransferido, $cuentaDestino, $conceptoTransferencia) {

        $cuentaDestinoInfo = self::obtenerCuentaDestino($cuentaDestino);
        $cuentaRemitenteInfo = self::obtenerCuentaRemitente($cuentaRemitente);
    
        if (empty($saldoTransferido) || empty($cuentaDestinoInfo) || empty($conceptoTransferencia)) {
            echo 'Ingresó un campo vacío.';
            return false;
        }
    
        if ($cuentaRemitente == $cuentaDestino || $cuentaRemitenteInfo['idUsuario'] == $cuentaDestinoInfo['idUsuario']) {
            echo 'Usted está intentando realizar una transferencia a una de sus cuentas propias.';
            return false;
        }
    
        if ($saldoTransferido > $cuentaRemitenteInfo['saldoCuenta']) {
            echo 'Usted está intentando hacer una transferencia con una cantidad mayor a su saldo disponible.';
            return false;
        }
    
        if (empty($cuentaDestinoInfo['numeroCuenta'])) {
            echo 'El número de cuenta destino no existe.';
            return false;
        }
    
        $saldoActualizadoDestino = $saldoTransferido + $cuentaDestinoInfo['saldoCuenta'];
        $saldoActualizadoRemitente = $cuentaRemitenteInfo['saldoCuenta'] - $saldoTransferido;

        $stmt = $this->pdo->prepare('UPDATE cuentas SET saldoCuenta = :saldoCuenta WHERE numeroCuenta = :numeroCuenta');
        $stmt->execute(['saldoCuenta' => $saldoActualizadoDestino, 'numeroCuenta' => $cuentaDestinoInfo['numeroCuenta']]);
    
        $stmt = $this->pdo->prepare('UPDATE cuentas SET saldoCuenta = :saldoCuenta WHERE numeroCuenta = :numeroCuenta');
        $stmt->execute(['saldoCuenta' => $saldoActualizadoRemitente, 'numeroCuenta' => $cuentaRemitenteInfo['numeroCuenta']]);
    
        $stmt = $this->pdo->prepare('INSERT INTO transferencias (numeroCuentaRemitente, numeroCuentaDestino, saldoEnviado, concepto) VALUES (:numeroCuentaRemitente, :numeroCuentaDestino, :saldoEnviado, :concepto)');
        $stmt->execute(['numeroCuentaRemitente' => $cuentaRemitenteInfo['numeroCuenta'], 'numeroCuentaDestino' => $cuentaDestinoInfo['numeroCuenta'], 'saldoEnviado' => $saldoTransferido, 'concepto' => $conceptoTransferencia]);
        
        return true;
        
    }
    
    
}
?>
