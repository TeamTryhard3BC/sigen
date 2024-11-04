<?php 
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'])) {
    if (checkRol() !== "Administrador") { 
        echo json_encode(["error" => "No tienes permisos."]); 
        return; 
    }

    $codigoPersona = $_POST['codigoPersona'];

    try {
        // Obtenemos el nombreUsuario guardado en la tabla Persona
        $countQuery = "
            SELECT nombreUsuario FROM persona
            WHERE codigoPersona = '$codigoPersona'
        ";

        $resultadoNombreUser = consultaServidor($countQuery);

        if ($resultadoNombreUser) {
            // Como el nombreUsuario está separado por un guion, usamos explode para obtener un arreglo con el string separado
            $nombreUsuario = $resultadoNombreUser->fetch_assoc()["nombreUsuario"];
            $arregloNombre = explode("-", $nombreUsuario);

            $tipoDocumento = $arregloNombre[0];
            $nroDocumento = $arregloNombre[1];

            // Cambiamos el estado del usuario a activo
            $consulta = "
                UPDATE usuario
                SET activo = TRUE
                WHERE tipoDocumento = '$tipoDocumento' AND nroDocumento = '$nroDocumento'
            ";

            $resultado = consultaServidor($consulta);
        
            if (!$resultado) {
                echo json_encode(["error" => "No se pudo activar el usuario"]);
                return;
            }
        } else {
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        echo json_encode(["success" => "Usuario activado exitosamente"]);
    } catch (Exception $error) {
        echo json_encode(["error" => "Error al activar: " . $error->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parametros"]);
}
?>
