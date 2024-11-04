<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'], $_POST['codigoCombo'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $codigoCombo = $_POST['codigoCombo'];
    $codigoPersona = $_POST['codigoPersona'];

    try {
        $consulta = "INSERT INTO realizacombo (id, codigoPersona) VALUES ('$codigoCombo', '$codigoPersona')";
        $resultado = consultaServidor($consulta);

        if ($resultado) {
            echo json_encode(["success" => "Combo asignado al Cliente"]);
        } else {
            echo json_encode(["error" => "No se pudo asignar el combo al cliente"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parametros"]);
}
?>