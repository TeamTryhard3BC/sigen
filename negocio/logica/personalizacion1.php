<?php

    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    return function() {
        session_start();

        $logoInstitucion = $_GET["logo"];
        $nombreInstitucion = $_GET["nombre"];
        $parametrosInstitucion = isset($_GET['parametros']) ? $_GET['parametros'] : [];
    
        $retornos = [
            "Nombre" => true,
            "Parametros" => true
        ];
    
        //COMPROBAR LOGO
        //
        /////////////////
    
        if (!isset($nombreInstitucion) || gettype($nombreInstitucion) != "string" || (isset($nombreInstitucion) && !$nombreInstitucion)) {
            $retornos["Nombre"] = false;
        }
    
        if (isset($parametrosInstitucion) && is_array($parametrosInstitucion)) {
            if(count($parametrosInstitucion) != 0) {
                foreach ($parametrosInstitucion as $parametro) {
                    if (!isset($parametro) || !is_string($parametro)) {
                        $retornos["Parametros"] = false;
                        break;
                    }
                }
            } else {
                $retornos["Parametros"] = false;
            }
        } else {
            $retornos["Parametros"] = false;
        }

        //ACA VA LOGICA PARA SUBIR A LA BD O AL SERVIDOR
        //
        /////////////////

        return $retornos;
    }
?>