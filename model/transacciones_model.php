<?php

require "config.php";

function obtenerTransaccionesPorUsuario($userId) {
    $db = getConnection();

    // Consultar todas las cuentas del usuario
    $stmt = $db->prepare('SELECT numeroCuenta FROM cuentas WHERE idUsuario = :idUsuario');
    $stmt->execute(['idUsuario' => $userId]);
    $cuentas = $stmt->fetchAll(PDO::FETCH_COLUMN); // Obtener solo los números de cuenta

    if (empty($cuentas)) {
        return []; // Si no tiene cuentas, devolver un array vacío
    }

    // Consultar las transferencias relacionadas con esas cuentas
    $stmt = $db->prepare(' SELECT t.*  FROM transferencias t WHERE t.numeroCuentaRemitente IN (' . implode(',', array_fill(0, count($cuentas), '?')) . ')  OR t.numeroCuentaDestino IN (' . implode(',', array_fill(0, count($cuentas), '?')) . ') ');

    $stmt->execute(array_merge($cuentas, $cuentas)); // Combinar ambas listas de cuentas en un solo array
    $transferencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $transferencias;
}


?>
