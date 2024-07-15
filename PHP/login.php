<?php
    session_start();

    $tipoDocLog = $_GET["tipoDocumento"];
    $documentoLog = $_GET["documento"];
    $documentoLog = (int)$documentoLog;
    $contrasenaLog = $_GET["contrasena"];

    $check = false;

    //Comprobar bien todo en caso de q no sea una consulta, pasarlo por los objetos de la otra carpeta wip
    //ningun string puede devolver verdadero al comprobar si es un query de SQL o no

    if(!isset($documentoLog) || gettype($documentoLog) != "integer" || (isset($documentoLog) && $documentoLog < 0)) {
        $check = true;
    }

    if(!isset($contrasenaLog) || gettype($contrasenaLog) != "string" || (isset($contrasenaLog) && !$contrasenaLog)) {
        $check = true;
    }
    
    if($check) {
        header("Location: ../login.html");
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
