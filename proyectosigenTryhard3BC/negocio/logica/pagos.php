<?php 
include_once("../../datos/conexion/PuenteMySQL.php"); 
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodoPago'], $_POST['cuotas'], $_POST['codigoPersona'])) {
    if(checkRol() !== "Cliente") { echo "No eres un cliente."; return; }

    $metodoPago = $_POST['metodoPago'];
    $cuotas = $_POST['cuotas'];
    $codigoPersona = $_POST['codigoPersona'];

    $consulta = "INSERT INTO Pago (metodoPago, cuotas) VALUES ('$metodoPago', $cuotas)";
    $resultado = consultaServidor($consulta);

    if ($resultado) {
        $codigoPago = obtenerUltimoCodigoPago(); 

        $consultaRealiza = "INSERT INTO Realiza (codigoPersona, codigoPago) VALUES ($codigoPersona, $codigoPago)";
        $resultadoRealiza = consultaServidor($consultaRealiza);

        if ($resultadoRealiza) {
            echo "Pago registrado correctamente.";
        } else {
            echo "Se hizo el pago, pero no la relacion realiza";
        }
    } else {
        echo "Error al registrar el pago";
    }
} else {
    echo "Datos no validos";
}

function obtenerUltimoCodigoPago() {
    $consulta = "SELECT MAX(codigoPago) AS codigoPago FROM Pago";
    $resultado = consultaServidor($consulta);

    if ($resultado) {
        $row = $resultado->fetch_assoc();
        return $row['codigoPago'] ?? null; 
    }

    return null; 
}
?>

