<?php
    session_start();

    $descripcionI = $_GET["descripcion"];
    $ubicacionI = $_GET["ubicacion"];
    $contactoI = $_GET["contacto"];
    $contactoI = (int)$contactoI;
    $instagramI = $_GET["instagram"];
    $mailI = $_GET["mail"];

    $check = false;

    //Comprobar bien todo en caso de q no sea una consulta
    //ningun string puede devolver verdadero al comprobar si es un query de SQL o no

    if (!isset($descripcionI) || gettype($descripcionI) != "string" || (isset($descripcionI) && !$descripcionI)) {
        $check = true;
        echo "a";
    }

    if (!isset($ubicacionI) || gettype($ubicacionI) != "string" || (isset($ubicacionI) && !$ubicacionI)) {
        $check = true;
        echo "b";
    }

    if(!isset($contactoI) || gettype($contactoI) != "integer" || (isset($contactoI) && $contactoI < 0)) {
        $check = true;
        echo gettype($contactoI);
        echo "c";
    }

    if (!isset($instagramI) || gettype($instagramI) != "string" || (isset($instagramI) && !$instagramI)) {
        $check = true;
        echo "d";
    }

    if (!isset($mailI) || gettype($mailI) != "string" || (isset($mailI) && (!$mailI || !str_contains($mailI, "@")))) {
        $check = true;
        echo "e";
    }
    
    if($check) {
        header("Location: ../personalizacion2.html");
        exit;
    }

    //ACA VA LA LOGICA PARA SUBIRLO A LA BD
    //
    //
    //
    //retorno a la siguiente pagina correspondiente:

    header("Location: ../personalizacion3.html");
    exit;
?>
