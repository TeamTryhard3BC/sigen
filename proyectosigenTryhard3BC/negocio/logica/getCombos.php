<?php
include_once("../../datos/conexion/puenteMySQL.php");

header('Content-Type: application/json'); 

try {
    $consulta = "SELECT codigoCombo, nombreCombo, descripcion FROM combo";
    $resultado = consultaServidor($consulta);

    $combos = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $combos[] = $row;
    }
    echo json_encode($combos);

} catch (Exception $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>
