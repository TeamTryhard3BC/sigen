<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'], $_POST['codigoDeporte'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $codigoPersona = $_POST['codigoPersona'];
    $codigoDeporte = $_POST['codigoDeporte'];

    try {
        $consulta = "INSERT INTO Hace (codigoPersona, codigoDeporte) VALUES ('$codigoPersona', '$codigoDeporte')";
        $resultado = consultaServidor($consulta);

        if ($resultado) {
            echo json_encode(["success" => "Deporte asignado al deportista"]);
        } else {
            echo json_encode(["error" => "No se pudo asignar el deporte al deportista"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parametros"]);
}
?>

