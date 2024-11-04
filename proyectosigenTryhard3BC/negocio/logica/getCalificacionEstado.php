<?php
    //este script agarra la calificacion del usuario segun el codigoPersona,
    //la consulta tambien busca el mismo codigoPersona en forma de 
    //ID en estadod o estadop dependiendo de q tipo sea la persona logeada, esto desde el SESSION

    include_once("..\..\datos\conexion\puenteMySQL.php");
    include_once("..\objetos\Usuario.php");

    if(!isset($_SESSION)) { session_start(); }

    if(!isset($_SESSION["usuario"])) {
        echo json_encode(["error" => "No se pudo cargar el usuario logueado"]);
        return;
    }

    $usuario = $_SESSION["usuario"];
    $codigoPersona = $usuario->getCodigoPersona();

    //consultas para obtener la ultima calificacion y actualizaciones de estado de un usuario
    $consultaCalificacion = "
        SELECT codigoPersona, idCalificacion, fechaCalificacion
        FROM recibe
        WHERE codigoPersona = '$codigoPersona'
        ORDER BY idCalificacion DESC
        LIMIT 1
    ";
    $consultaPasaD = "
        SELECT codigoPersona, ID, Estado, fechaEstado
        FROM pasaD
        WHERE codigoPersona = '$codigoPersona'
        ORDER BY ID DESC
        LIMIT 1
    ";
    $consultaPasaP = "
        SELECT codigoPersona, ID, Estado, fechaEstado
        FROM pasaP
        WHERE codigoPersona = '$codigoPersona'
        ORDER BY ID DESC
        LIMIT 1
    ";
    $resultadoCalificacion = consultaServidor($consultaCalificacion);
    $resultadoPasaP = consultaServidor($consultaPasaP);
    $resultadoPasaD = consultaServidor($consultaPasaD);

    $ultimaCalificacionUsuario = $resultadoCalificacion->fetch_assoc();
    if ($ultimaCalificacionUsuario) { $ultimaCalificacionUsuario = $ultimaCalificacionUsuario["idCalificacion"]; } else {
        echo json_encode(["error" => "No se pudo cargar la calificacion"]);
        return;
    }

    //consulta para obtener los datos de esa ultima calificacion segun la ID obtenida
    $consulta = "
        SELECT c.idCalificacion AS id, c.puntajeCliente as puntaje, c.fechaCalificacion
        FROM Calificacion c
        WHERE c.idCalificacion = '$ultimaCalificacionUsuario'
    ";
    $resultadoConsulta = consultaServidor($consulta);
    $calificacion = [];
    $estadoP;
    $estadoD;

    if ($resultado = $resultadoConsulta->fetch_assoc()) {
        $calificacion = $resultado;
    }

    //vemos q devolvio en el pasaP y pasaD para decidir el retorno
    if ($resultado = $resultadoPasaP->fetch_assoc()) {
        $estadoP = $resultado;
    } elseif ($resultado = $resultadoPasaD->fetch_assoc()) {
        $estadoD = $resultado;
    }

    //codigo ternario soy re pro (me costo 10 min al pedo)
    $retorno = [
        'DatosEstado' => !empty($estadoP) ? $estadoP : (!empty($estadoD) ? $estadoD : null),
        'Calificacion' => !empty($calificacion) ? $calificacion : null
    ];

    header('Content-Type: application/json');
    echo json_encode($retorno);
?>