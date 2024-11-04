<?php 
include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'])) {
    if (checkRol() !== "Administrador") { 
        echo json_encode(["error" => "No tienes permisos"]); 
        return; 
    }

    $codigoPersona = $_POST['codigoPersona'];

    // Lo borramos de todas las tablas en las que esté asociado
    try {
        $consultaPasap = "
            DELETE FROM pasap
            WHERE codigoPersona = '$codigoPersona';
        ";
        $resultadoPasap = consultaServidor($consultaPasap);

        if (!$resultadoPasap) {
            echo json_encode(["error" => "Error al eliminar de la tabla pasap"]);
            return;
        }

        $consultaPasad = "
            DELETE FROM pasad
            WHERE codigoPersona IN (SELECT codigoPersona FROM deportista WHERE codigoPersona = '$codigoPersona');
        ";
        $resultadoPasad = consultaServidor($consultaPasad);
        
        if (!$resultadoPasad) {
            echo json_encode(["error" => "Error al eliminar de la tabla pasad"]);
            return;
        }

        $consultaDeportista = "
            DELETE FROM deportista
            WHERE codigoPersona = '$codigoPersona';
        ";
        $consultaPaciente = "
            DELETE FROM paciente
            WHERE codigoPersona = '$codigoPersona';
        ";
        $resultadoDeportista = consultaServidor($consultaDeportista);
        $resultadoPaciente = consultaServidor($consultaPaciente);
        
        if (!$resultadoDeportista) {
            echo json_encode(["error" => "Error al eliminar de la tabla deportista"]);
            return;
        }
        if (!$resultadoPaciente) {
            echo json_encode(["error" => "Error al eliminar de la tabla paciente"]);
            return;
        }

        $listaTablas = ["realizaCombo", "recibe", "reserva", "cliente"];

        foreach ($listaTablas as $tablaSQL) {
            $consulta = "
                DELETE FROM $tablaSQL
                WHERE codigoPersona = '$codigoPersona'
            ";
            $resultado = consultaServidor($consulta);
        
            if (!$resultado) {
                echo json_encode(["error" => "Ayuda en $tablaSQL"]);
                return;
            }
        }

        //estas tablas quedan huerfanas por que son entidades y no tienen relación a través de código persona, entonces con el not in busca en recibe y si no está la wipea
        $consultaCalificacion = "
            DELETE FROM calificacion WHERE idCalificacion NOT IN (SELECT idCalificacion FROM recibe);
        ";
        $consultaEstadoD = "
            DELETE FROM estadoD WHERE ID NOT IN (SELECT ID FROM pasaD);
        ";
        $consultaEstadoP = "
            DELETE FROM estadoP WHERE ID NOT IN (SELECT ID FROM pasaP);
        ";
        $resultadoCalificacion = consultaServidor($consultaCalificacion);
        $resultadoEstadoD = consultaServidor($consultaEstadoD);
        $resultadoEstadoP = consultaServidor($consultaEstadoP);

        $countQuery = "
            SELECT nombreUsuario FROM persona
            WHERE codigoPersona = '$codigoPersona'
        ";

        $resultadoNombreUser = consultaServidor($countQuery);

        if ($resultadoNombreUser) {
            // como el nombreUsuario está separado por un guion, usamos explode para obtener un arreglo con el string separado
            $nombreUsuario = $resultadoNombreUser->fetch_assoc()["nombreUsuario"];
            $arregloNombre = explode("-", $nombreUsuario);

            $tipoDocumento = $arregloNombre[0];
            $nroDocumento = $arregloNombre[1];

            // ccambiamos el estado del usuario a activo
            $consulta = "
                UPDATE usuario
                SET activo = FALSE
                WHERE tipoDocumento = '$tipoDocumento' AND nroDocumento = '$nroDocumento'
            ";

            $resultado = consultaServidor($consulta);
        
            if (!$resultado) {
                echo json_encode(["error" => "No se pudo actualizar el estado del usuario"]);
                return;
            }
        } else {
            echo json_encode(["error" => "Ayuda en $tablaSQL"]);
            return;
        }

        echo json_encode(["success" => "Usuario dado de baja logicamente"]);
    } catch (Exception $error) {
        echo json_encode(["error" => "Error al eliminar: " . $error->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parametros"]);
}
?>

