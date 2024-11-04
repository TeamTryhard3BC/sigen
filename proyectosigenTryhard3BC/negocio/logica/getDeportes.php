<?php
include_once("../../datos/conexion/puenteMySQL.php");

header('Content-Type: application/json'); 

try {
    $consulta = "SELECT codigoDeporte, nombreDeporte FROM Deporte";
    $resultado = consultaServidor($consulta);

    $deportes = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $deportes[] = $row;
    }
    echo json_encode($deportes);

} catch (Exception $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>
