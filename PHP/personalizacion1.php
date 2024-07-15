<?php
    session_start();

    $logoInstitucion = $_GET["logo"];
    $nombreInstitucion = $_GET["nombre"];
    $parametrosInstitucion = $_GET["parametros"];

    $check = false;

    //COMPROBAR SI LOGO PASA LOS SANITY CHECKS

    if (!isset($nombreInstitucion) || gettype($nombreInstitucion) != "string" || (isset($nombreInstitucion) && !$nombreInstitucion)) {
        $check = true;
    }

    if (isset($parametrosInstitucion) && is_array($parametrosInstitucion)) {
        foreach ($parametrosInstitucion as $parametro) {
            if (!isset($parametro) || !is_string($parametro)) {
                $check = true;
                break;
            }
        }
    } else {
        $check = true;
    }

    if ($check) {
        header("Location: ../personalizacion1.html");
        exit;
    }

    //ACA VA LA LOGICA PARA SUBIRLO A LA BD
    //
    //
    //
    //retorno a la siguiente pagina correspondiente:

    header("Location: ../personalizacion2.html");
    exit;
?>