<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

header('Content-Type: application/json');

try {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $consulta = "
        SELECT d.codigoDeporte, d.nombreDeporte, c.codigoCombo, c.nombreCombo 
        FROM Deporte d
        JOIN Compone co ON d.codigoDeporte = co.codigoDeporte
        JOIN Combo c ON co.codigoCombo = c.codigoCombo
        GROUP BY d.codigoDeporte, c.codigoCombo
    ";
    $resultado = consultaServidor($consulta);
    
    $sugerencias = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $sugerencias[] = [
            'nombreDeporte' => $fila['nombreDeporte'],
            'nombreCombo' => $fila['nombreCombo']
        ];
    }

    echo json_encode($sugerencias);
} catch (Exception $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>
