<?php
    include_once("../../datos/conexion/PuenteMySQL.php");
    include_once("checkRole.php");

    if(checkRol() !== "Cliente") { echo json_encode(["error" => "No eres un cliente."]); return; }

    $conn = conectar($rootUser, $rootPass);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $dias = $_POST['dias'];
    $codigoPersona = $_POST['codigoPersona'];

    $response = ['success' => false, 'message' => ''];
    $contadorReservasExitosas = 0;
    $errores = [];
    $diasReservados = [];

    foreach ($dias as $dia) {
        //ver si ya tiene reservado el dia
        $check = "
            SELECT COUNT(*) AS recuentoReserva 
            FROM reserva
            JOIN agenda ON reserva.idAgenda = agenda.idAgenda
            WHERE codigoPersona = '$codigoPersona' AND diaSemana = '$dia'
        ";

        $resultado = $conn->query($check);
        $arrayResultado = $resultado->fetch_assoc();

        //tiene al menos 1 reserva de ese dia, no puede reservar
        if ($arrayResultado['recuentoReserva'] > 0) {
            //para manejar multiples errores, ver afuera del for
            $errores[] = "Ya tienes una reserva para el $dia.";
            continue;
        }

        //le sacamos uno al total de cupos de ese dia
        $consultaCupos = "
            UPDATE agenda
            SET cuposDisponibles = cuposDisponibles - 1
            WHERE diaSemana = '$dia' AND cuposDisponibles > 0
        ";
        $conn->query($consultaCupos);

        if ($conn->affected_rows > 0) {
            //le reservamos el dia a ese codigoPersona
            $sqlReserva = "
                INSERT INTO reserva (idAgenda, codigoPersona)
                SELECT idAgenda, '$codigoPersona' 
                FROM agenda WHERE diaSemana = '$dia'
            ";

            if ($conn->query($sqlReserva)) {
                $contadorReservasExitosas++;
                //es para manejar multiples errores, ver afuera del for
                $diasReservados[] = $dia;
            } else {
                //es para manejar multiples errores, ver afuera del for
                $errores[] = "Error al guardar la reserva para el $dia.";
            }
        } else {
            //es para manejar multiples errores, ver afuera del for
            $errores[] = "No hay cupos disponibles para $dia.";
        }
    }

    //devolvemos que hubieron al menos 1 dia(s) reservados con exito
    if ($contadorReservasExitosas > 0) {
        $response['success'] = true;
        if ($contadorReservasExitosas > 1) {
            $response['message'] = "Reserva realizada con éxito para $contadorReservasExitosas días.";
        } else {
            $response['message'] = "Reserva realizada con éxito para el día $diasReservados[0].";
        }
    }

    //devolvemos que errores hubieron
    if (!empty($errores)) {
        if(!$response['success']) {
            $errorMessage = "Errores: \n";
        } else {
            $errorMessage = "\n\nErrores: \n";
        }

        foreach ($errores as $error) {
            $errorMessage .= $error . "\n";
        }

        $response['message'] .= $errorMessage; 
    }


    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
?>
