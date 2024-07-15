<?php
    session_start();

    $nombrePlan = $_GET["nombre"];
    $tiempoPlan = $_GET["tiempo"];
    $tiempoPlan = (int)$tiempoPlan;
    $precioPlan = $_GET["precio"];
    $precioPlan = (int)$precioPlan;
    $descripcionPlan = $_GET["descripcion"];

    $check = false;

    //Comprobar bien todo en caso de q no sea una consulta, pasarlo por los objetos de la otra carpeta wip
    //ningun string puede devolver verdadero al comprobar si es un query de SQL o no

    if(!isset($nombrePlan) || gettype($nombrePlan) != "string" || (isset($nombrePlan) && !$nombrePlan)) {
        $check = true;
    }

    if(!isset($tiempoPlan) || gettype($tiempoPlan) != "integer" || (isset($tiempoPlan) && $tiempoPlan < 0)) {
        $check = true;
    }

    if(!isset($precioPlan) || gettype($precioPlan) != "integer" || (isset($precioPlan) && $precioPlan < 0)) {
        $check = true;
    }

    if(!isset($descripcionPlan) || gettype($descripcionPlan) != "string" || (isset($descripcionPlan) && !$descripcionPlan)) {
        $check = true;
    }
    
    if($check) {
        header("Location: ../personalizacion3.html");
        exit;
    }

    //ACA VA LA LOGICA PARA SUBIRLO A LA BD
    //
    //
    //
    //retorno a la siguiente pagina correspondiente:

    header("Location: ../index.html");
    exit;
?>
