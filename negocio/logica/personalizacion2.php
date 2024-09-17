<?php
    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    return function() {
        session_start();

        $descripcionI = $_GET["descripcion"];
        $ubicacionI = $_GET["ubicacion"];
        $contactoI = $_GET["contacto"];
        $contactoI = (int)$contactoI;
        $instagramI = $_GET["instagram"];
        $mailI = $_GET["mail"];
    
        $retornos = [
            "Descripcion" => true,
            "Ubicacion" => true,
            "NumeroContacto" => true,
            "Instagram" => true,
            "Mail" => true
        ];

        if (!isset($descripcionI) || gettype($descripcionI) != "string" || (isset($descripcionI) && !$descripcionI)) {
            $retornos["Descripcion"] = false;
        }

        if (!isset($ubicacionI) || gettype($ubicacionI) != "string" || (isset($ubicacionI) && !$ubicacionI)) {
            $retornos["Ubicacion"] = false;
        }

        if ($contactoI == null || !isset($contactoI) || !is_numeric($contactoI) || (isset($contactoI) && $contactoI < 0)) {
            $retornos["NumeroContacto"] = false;
        }

        if (!isset($instagramI) || gettype($instagramI) != "string" || (isset($instagramI) && !$instagramI)) {
            $retornos["Instagram"] = false;
        }

        if (!isset($mailI) || gettype($mailI) != "string" || (isset($mailI) && (!$mailI || !str_contains($mailI, "@")))) {
            $retornos["Mail"] = false;
        }
    
        //ACA VA LOGICA PARA SUBIR A LA BD O AL SERVIDOR
        //
        /////////////////

        return $retornos;
    }
?>