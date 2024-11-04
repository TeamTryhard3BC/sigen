<?php 

include_once("../../datos/conexion/puenteMySQL.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigoPersona'], $_POST['puntajeCliente'])) {
    if(checkRol() === "Cliente") { echo json_encode(["error" => "No eres un cliente."]); return; }

    $codigoPersona = $_POST['codigoPersona'];
    $puntajeCliente = $_POST['puntajeCliente'];

    $conexion = conectar($rootUser, $rootPass);

    //aca vemos si es paciente
    $consultaPaciente = "SELECT 1 FROM Paciente WHERE codigoPersona = $codigoPersona";
    $resultadoPaciente = $conexion->query($consultaPaciente);
    //si es paciente suma 1 y tipo persona es paciente, lo mismo abajo para deportista
    if ($resultadoPaciente->num_rows > 0) {
        $tipoPersona = 'paciente';
    } else {
        //aca vemos si es deportista
        $consultaDeportista = "SELECT 1 FROM Deportista WHERE codigoPersona = $codigoPersona";
        $resultadoDeportista = $conexion->query($consultaDeportista);
        
        if ($resultadoDeportista->num_rows > 0) {
            $tipoPersona = 'deportista';
        } else {
            echo json_encode(['error' => 'El codigo de persona no es de un Paciente o un Deportista']);
            $conexion->close();
            exit;
        }
    }

    $consultaCalificacion = "INSERT INTO Calificacion (puntajeCliente) VALUES ($puntajeCliente)";
    if ($conexion->query($consultaCalificacion) === TRUE) {
        $idCalificacion = $conexion->insert_id;

        $consultaRecibe = "INSERT INTO Recibe (codigoPersona, idCalificacion) VALUES ($codigoPersona, $idCalificacion)";
        if ($conexion->query($consultaRecibe) === TRUE) {

            if ($tipoPersona === 'deportista') {
                if ($puntajeCliente == 80) {
                    $estado = 'Principiante';
                } else if ($puntajeCliente >= 81 && $puntajeCliente <= 99) {
                    $estado = 'Bajo';
                } else if ($puntajeCliente >= 100 && $puntajeCliente <= 119) {
                    $estado = 'Medio';
                } else if ($puntajeCliente >= 120 && $puntajeCliente <= 139) {
                    $estado = 'Alto';
                } else {
                    $estado = 'Para Seleccionar';
                }

                $consultaEstadoD = "INSERT INTO EstadoD (ID, Estado) VALUES ($idCalificacion, '$estado')";
                if ($conexion->query($consultaEstadoD) === TRUE) {
                    $consultaPasaD = "INSERT INTO PasaD (codigoPersona, ID, Estado) VALUES ($codigoPersona, $idCalificacion, '$estado')";
                    if ($conexion->query($consultaPasaD) === TRUE) {
                        echo json_encode(['success' => 'calificacion y estado guardados para deportista']);
                    } else {
                        echo json_encode(['error' => 'no se pudo actualizar el estado en PasaD: ']);
                    }
                } else {
                    echo json_encode(['error' => 'no se pudo guardar el estado en EstadoD: ']);
                }
            } else if ($tipoPersona === 'paciente') {
                if ($puntajeCliente == 80) {
                    $estado = 'Inicio';
                } else if ($puntajeCliente >= 81 && $puntajeCliente <= 99) {
                    $estado = 'Sin evolución';
                } else if ($puntajeCliente >= 100 && $puntajeCliente <= 119) {
                    $estado = 'En evolución';
                } else if ($puntajeCliente >= 120) {
                    $estado = 'Satisfactoria';
                } 
                $consultaEstadoP = "INSERT INTO EstadoP (ID, Estado) VALUES ($idCalificacion, '$estado')";
                if ($conexion->query($consultaEstadoP) === TRUE) {
                    $consultaPasaP = "INSERT INTO PasaP (codigoPersona, ID, Estado) VALUES ($codigoPersona, $idCalificacion, '$estado')";
                    if ($conexion->query($consultaPasaP) === TRUE) {
                        echo json_encode(['success' => 'calificacion y estado guardados para paciente']);
                    } else {
                        echo json_encode(['error' => 'no se pudo actualizar el estado en PasaP']);
                    }
                } else {
                    echo json_encode(['error' => 'no se pudo guardar el estado en EstadoP']);
                }
            }
        } else {
            echo json_encode(['error' => 'no se guardo la relacion en Recibe']);
        }
    } else {
        echo json_encode(['error' => 'no se guardo la calificacion']);
    }

    $conexion->close(); 
} else {
    echo json_encode(['error' => 'Datos inválidos.']);
}

?>

