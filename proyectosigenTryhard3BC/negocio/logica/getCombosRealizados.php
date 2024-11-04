<?php
    include_once("..\..\datos\conexion\puenteMySQL.php");
    include_once("..\objetos\Usuario.php");

    if(!isset($_SESSION)) { session_start(); }

    if(!isset($_SESSION["usuario"])) {
        echo json_encode(["error" => "No se pudo cargar el usuario logeado"]);
        return;
    }

    $usuario = $_SESSION["usuario"];
    $codigoPersona = $usuario->getCodigoPersona();

    //consultas para obtener los combos q realiza un usuario cliente, tanto el codigo como el nombre (inner join para obtenerlos de combo)
    $consultaCombos = "
        SELECT rc.ID AS codigoCombo, c.nombreCombo
        FROM realizaCombo rc
        INNER JOIN Combo c ON rc.ID = c.codigoCombo
        WHERE rc.codigoPersona = '$codigoPersona'
    ";

    $resultadoCombos = consultaServidor($consultaCombos);
    $combos = [];
    
    if ($resultadoCombos) {
        while ($combo = $resultadoCombos->fetch_assoc()) {
            $codigoCombo = $combo["codigoCombo"];
            $combo["ejercicios"] = [];

            //consulta para obtener la lista de ejercicios que componen a un combo
            $consultaForma = "
                SELECT codigoEjercicio, codigoCombo
                FROM forma
                WHERE codigoCombo = '$codigoCombo'
            ";
            $resultadoForma = consultaServidor(consulta: $consultaForma);

            if ($resultadoForma) {
                while ($ejercicio = $resultadoForma->fetch_assoc()) {
                    $codigoEjercicio = $ejercicio["codigoEjercicio"];

                    //consulta para obtener los datos de los ejercicios
                    $consultaEjercicios = "
                        SELECT codigoEjercicio, nombreEjercicio, descripcion, musculoTrabajado
                        FROM Ejercicio
                        WHERE codigoEjercicio = '$codigoEjercicio'
                    ";
                    $resultadoEjercicios = consultaServidor(consulta: $consultaEjercicios);

                    if ($resultadoEjercicios) {
                        while ($datosEjercicio = $resultadoEjercicios->fetch_assoc()) {
                            $combo["ejercicios"][] = $datosEjercicio;
                        }
                    }
                }
            }

            $combos[] = $combo;
        }
    } else {
        echo json_encode(["error" => "No se pudo cargar los combos"]);
        return;
    }

    //codigo ternario soy re pro (me costo 10 min al pedo)
    $retorno = [
        'Combos' => !empty($combos) ? $combos : null,
    ];

    header('Content-Type: application/json');
    echo json_encode($retorno);
    return;
?>