<?php
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'], $_POST['tipoUsuario'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No tienes permisos."]); return; }

    $codigoPersona = $_POST['codigoPersona'];
    $tipoUsuario = $_POST['tipoUsuario'];

    try {
        if ($tipoUsuario === 'deportista') {
            $consulta = "INSERT INTO Deportista (codigoPersona) VALUES ('$codigoPersona')";
        } elseif ($tipoUsuario === 'paciente') {
            $consulta = "INSERT INTO Paciente (codigoPersona) VALUES ('$codigoPersona')";
        } else {
            echo json_encode(["error" => "Tipo de usuario no válido"]);
            return;
        }

        $resultado = consultaServidor($consulta);
        if ($resultado) {
            echo json_encode(["success" => "Cliente añadido como $tipoUsuario"]);
        } else {
            echo json_encode(["error" => "No se pudo añadir el cliente a la tabla $tipoUsuario"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parámetros."]);
}
?>
