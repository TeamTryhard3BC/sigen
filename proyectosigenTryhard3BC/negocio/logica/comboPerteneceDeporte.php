<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoDeporte'], $_POST['codigoCombo'])) {
    if (checkRol() === "Cliente") {
        echo json_encode(["error" => "No tienes permisos."]);
        return;
    }

    $codigoDeporte = $_POST['codigoDeporte'];
    $codigoCombo = $_POST['codigoCombo'];

    try {
        $dataExistencia = consultaServidor("
            SELECT COUNT(*) as count
            FROM compone
            WHERE codigoDeporte = '$codigoDeporte' AND codigoCombo = '$codigoCombo'
        ")->fetch_assoc();

        if ($dataExistencia['count'] > 0) {
            echo json_encode(["error" => "El combo ya está asignado a este deporte."]);
            return;
        }

        $consulta = "
            INSERT INTO compone (codigoDeporte, codigoCombo) 
            VALUES ('$codigoDeporte', '$codigoCombo')
        ";
        $resultado = consultaServidor(consulta: $consulta);

        if ($resultado) {
            echo json_encode(["success" => "Combo asignado al deporte"]);
        } else {
            echo json_encode(["error" => "No se pudo asignar el combo al deporte"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parámetros"]);
}
?>
