<?php

    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
     */

    return function () {
        if(!isset($_SESSION)) { session_start(); }

        $logoInstitucion = $_POST["logo"] ?? null;
        $nombreInstitucion = $_POST["nombre"] ?? null;

        $retornos = [
            "Logo" => true,
            "Nombre" => true
        ];

        if(!isset($_FILES["logo"])) {
            $retornos["Logo"] = false;
        } else {
            //comprobamos el archivo

            $datoLogo = $_FILES["logo"];

            $archivo = $datoLogo["tmp_name"];
            $nombre = $datoLogo["name"];
            $tamaño = $datoLogo["size"];
    
            $tamañoMaximo = 10 * 1024 * 1024;
    
            $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
            $extensionesPermitidas = ["jpg", "jpeg", "png", "webp"];
    
            if(in_array($extension, $extensionesPermitidas)) {
    
                if($tamaño <= $tamañoMaximo) {
                    $directorio = "../../datos/logo." . $extension;
        
                    if(!move_uploaded_file($archivo, $directorio)) {
                        $retornos["Logo"] = false;
                    }
                } else {
                    $retornos["Logo"] = false;
                }
            } else {
                $retornos["Logo"] = false;
            }
        }

        if (empty($nombreInstitucion) || !is_string($nombreInstitucion)) {
            $retornos["Nombre"] = false;
        }

        //ruta del config.json
        $rutaArchivo = "../../datos/config.json";

        //si existe,
        if (file_exists($rutaArchivo)) {
            $json = file_get_contents($rutaArchivo);
            $data = json_decode($json, true);

            //reemplazamos si es que hay parametros
            if ($retornos["Nombre"] === true) {
                $data["Nombre"] = $nombreInstitucion;
            }

            //EL CODIGO DE ABAJO ES PARA SALTARSE LOS REQUISITOS QUE YA EXISTAN EN CONFIG.JSON, SINO LOS CAMPOS SON OBLIGATORIOS

            //obtenemos su contenido

            //$json = file_get_contents($rutaArchivo);
            //$data = json_decode($json, true);

            //reemplazamos si es que hay parametros

            //foreach ($retornos as $key => $value) {
            //    if($value == true) {
            //        $data[$key] = $_POST[strtolower($key)];
            //    }
            //}

            //puesto que el archivo originalmente ya existia, los parametros no son obligatorios
            //por lo que podemos saltar los checks de arriba
        } else {
            //como no existe el archivo, comprobamos que cada parametro sea valido
            $canProceed = true;

            foreach ($retornos as $key => $value) {
                if ($value !== true) {
                    $canProceed = false;
                }
            }

            //se puede avanzar?
            if($canProceed) {
                $data = [];

                //metemos los parametros enviados por el POST en el arreglo
                foreach ($retornos as $key => $value) {
                    if(strtolower($key) != "logo") {
                        $data[$key] = $_POST[strtolower($key)];
                    }
                }
            }
        }

        //si el arreglo no esta vacio, metemos los datos en el json y le decimos que quede aesthetic
        if(!empty($data)) {
            file_put_contents($rutaArchivo, json_encode($data, JSON_PRETTY_PRINT));
        }

        return $retornos;
    }
?>