<?php
    include_once("..\..\datos\conexion\puenteMySQL.php");

    $consultaPaciente = "
        SELECT p.codigoPersona AS id, p.nombre, p.apellido 
        FROM Persona p
        INNER JOIN Cliente c ON p.codigoPersona = c.codigoPersona 
        INNER JOIN Paciente pa ON pa.codigoPersona = p.codigoPersona
    ";

    $consultaDeportista = "
        SELECT p.codigoPersona AS id, p.nombre, p.apellido 
        FROM Persona p
        INNER JOIN Cliente c ON p.codigoPersona = c.codigoPersona
        INNER JOIN Deportista d ON d.codigoPersona = p.codigoPersona
    ";

    $consultaCliente = "
    SELECT p.codigoPersona AS id, p.nombre, p.apellido 
    FROM Persona p
    INNER JOIN Cliente c ON p.codigoPersona = c.codigoPersona
    LEFT JOIN Paciente pa ON pa.codigoPersona = p.codigoPersona
    LEFT JOIN Deportista d ON d.codigoPersona = p.codigoPersona
    WHERE pa.codigoPersona IS NULL AND d.codigoPersona IS NULL
    ";

    $consultaEntrenador = "
    SELECT p.codigoPersona AS id, p.nombre, p.apellido 
    FROM Persona p
    INNER JOIN Entrenador c ON p.codigoPersona = c.codigoPersona
";

    //usamos el concat para que concatene los datos de la tabla usuario y los compruebe mismo en la consulta, podriamos hacerlo con codigo pero así queda mas fácil.
    $consultaSoloPersona = "
    SELECT p.codigoPersona AS id, p.nombre, p.apellido 
    FROM Persona p
    LEFT JOIN Cliente c ON p.codigoPersona = c.codigoPersona
    LEFT JOIN Entrenador e ON p.codigoPersona = e.codigoPersona
    WHERE c.codigoPersona IS NULL 
      AND e.codigoPersona IS NULL
      AND (
          SELECT activo FROM Usuario u 
          WHERE CONCAT(u.tipoDocumento, '-', u.nroDocumento) = p.nombreUsuario) = TRUE
";

    $consultaSoloPersonaInactiva = "
    SELECT p.codigoPersona AS id, p.nombre, p.apellido 
    FROM Persona p
    LEFT JOIN Cliente c ON p.codigoPersona = c.codigoPersona
    LEFT JOIN Entrenador e ON p.codigoPersona = e.codigoPersona
    WHERE c.codigoPersona IS NULL 
    AND e.codigoPersona IS NULL
    AND (
        SELECT activo FROM Usuario u 
        WHERE CONCAT(u.tipoDocumento, '-', u.nroDocumento) = p.nombreUsuario) = FALSE
    ";



    $resultadoSoloPersona = consultaServidor($consultaSoloPersona);
    $consultaSoloPersonaInactiva = consultaServidor($consultaSoloPersonaInactiva);
    $resultadoPaciente = consultaServidor($consultaPaciente);
    $resultadoDeportista = consultaServidor($consultaDeportista);
    $resultadoCliente = consultaServidor($consultaCliente);
    $resultadoEntrenador = consultaServidor($consultaEntrenador);

    $soloPersona = [];
    $pacientes = [];
    $deportistas = [];
    $cliente = [];
    $entrenador = [];
    $soloPersonaInactiva = [];

    if ($resultadoSoloPersona) {
        while ($row = $resultadoSoloPersona->fetch_assoc()) {
            $soloPersona[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No se pudieron obtener las personas sin clasificación']);
        return;
    }

    if ($consultaSoloPersonaInactiva) {
        while ($row = $consultaSoloPersonaInactiva->fetch_assoc()) {
            $soloPersonaInactiva[] = $row;
        }
    } else {
        return json_encode(['error' => 'No se pudieron obtener los pacientes']);
    }

    
    if ($resultadoPaciente) {
        while ($row = $resultadoPaciente->fetch_assoc()) {
            $pacientes[] = $row;
        }
    } else {
        return json_encode(['error' => 'No se pudieron obtener los pacientes']);
    }
    
    if ($resultadoDeportista) {
        while ($row = $resultadoDeportista->fetch_assoc()) {
            $deportistas[] = $row;
        }
    } else {
        return json_encode(['error' => 'No se pudieron obtener los deportistas']);
    }

    if ($resultadoCliente) {
        while ($row = $resultadoCliente->fetch_assoc()) {
            $cliente[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No se pudieron obtener los clientes sin clasificar']);
        return;
    }

    if ($resultadoEntrenador) {
        while ($row = $resultadoEntrenador->fetch_assoc()) {
            $entrenador[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No se pudieron obtener los entrenadores']);
        return;
    }

    
    $response = [
        'SoloPersona' => $soloPersona,
        'SoloPersonaInactiva' => $soloPersonaInactiva,
        'Pacientes' => $pacientes,
        'Deportistas' => $deportistas,
        'Cliente' => $cliente,
        'Entrenador' => $entrenador
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);    
?>