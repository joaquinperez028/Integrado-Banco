<?php


require 'model/transacciones_model.php';

function verTransacciones() {
    session_start();

    if (isset($_SESSION['user_id'])) {
        // Obtener las transferencias del usuario
        $userId = $_SESSION['user_id'];
        $transferencias = obtenerTransaccionesPorUsuario($userId);

        include 'view/privado/movimientos_view.php';
    } else {
        header('Location: index.php?controller=user&action=verLogin');
    }
}


?>
