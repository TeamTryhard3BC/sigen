<?php
    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    return function() {
        if(!isset($_SESSION)) { session_start(); }

        $descripcionI = $_POST["descripcion"];
        $ubicacionI = $_POST["ubicacion"];
        $contactoI = $_POST["numerocontacto"];
        $contactoI = (int)$contactoI;
        $instagramI = $_POST["instagram"];
        $mailI = $_POST["mail"];
    
        $retornos = [
            "Descripcion" => true,
            "Ubicacion" => true,
            "NumeroContacto" => true,
            "Instagram" => true,
            "Mail" => true
        ];

        if (empty($descripcionI) || !is_string($descripcionI)) {
            $retornos["Descripcion"] = false;
        }

        if (empty($ubicacionI) || !is_string($ubicacionI)) {
            $retornos["Ubicacion"] = false;
        }

        if (empty($contactoI) || !is_numeric($contactoI)) {
            $retornos["NumeroContacto"] = false;
        }

        if (empty($instagramI) || !is_string($instagramI)) {
            $retornos["Instagram"] = false;
        }

        if (empty($mailI) || !is_string($mailI) || !str_contains($mailI, "@")) {
            $retornos["Mail"] = false;
        }
    
        //ACA VA LOGICA PARA SUBIR A LA BD O AL SERVIDOR
        //
        /////////////////

        //ruta del config.json
        $rutaArchivo = "../../datos/config.json";

        //si existe,
        if (file_exists($rutaArchivo)) {
            //obtenemos su contenido
            $json = file_get_contents($rutaArchivo);
            $data = json_decode($json, true);

            //reemplazamos si es que hay parametros
            foreach ($retornos as $key => $value) {
                if($value == true) {
                    $data[$key] = $_POST[strtolower($key)];
                }

                $datoYaExistia = in_array(strtolower($key), $data);
                $value = $datoYaExistia;
            }
        } else {
            $retornos = [
                "Descripcion" => false,
                "Ubicacion" => false,
                "NumeroContacto" => false,
                "Instagram" => false,
                "Mail" => false
            ];
        }

        //si el arreglo no esta vacio, metemos los datos en el json y le decimos que quede aesthetic
        if(!empty($data)) {
            file_put_contents($rutaArchivo, json_encode($data, JSON_PRETTY_PRINT));
        }

        return $retornos;
    }
?>