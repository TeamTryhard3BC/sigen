<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreDeporte'], $_POST['descripcionDeporte'], $_POST['reglasDeporte'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $nombreDeporte = $_POST['nombreDeporte'];
    $descripcionDeporte = $_POST['descripcionDeporte'];
    $reglasDeporte = $_POST['reglasDeporte'];

    try {
        $consulta = "INSERT INTO Deporte (nombreDeporte, descripcion, reglas) VALUES ('$nombreDeporte', '$descripcionDeporte', '$reglasDeporte')";
        $resultado = consultaServidor($consulta);

        if($resultado) {
            echo json_encode(["success" => "Deporte creado exitosamente."]);
        } else {
            echo json_encode(["error" => "No se pudo crear el deporte."]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parámetros."]);
}
?>
