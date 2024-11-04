<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoCliente'], $_POST['codigoEntrenador'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $codigoCliente = $_POST['codigoCliente'];
    $codigoEntrenador = $_POST['codigoEntrenador'];

    try {
        $consulta = "INSERT INTO Atiende (codigoCliente, codigoEntrenador) VALUES ('$codigoCliente', '$codigoEntrenador')";

        $resultado = consultaServidor($consulta);
        if ($resultado) {
            echo json_encode(["success" => "Cliente asignado al entrenador"]);
        } else {
            echo json_encode(["error" => "No se pudo asignar el cliente al entrenador"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parámetros."]);
}
?>
