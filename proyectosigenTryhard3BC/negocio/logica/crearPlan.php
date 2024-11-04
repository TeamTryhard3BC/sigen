<?php
    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global POST debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    include_once("checkRole.php");

    return function() {
        if(checkRol() !== "Administrador") { return ["error" => "No tienes permisos."]; }

        if(!isset($_SESSION)) { session_start(); }

        $nombrePlan = $_POST["nombre"];
        $duracionPlan = $_POST["duracion"];
        $duracionPlan = (int)$duracionPlan;
        $precioPlan = $_POST["precio"];
        $precioPlan = (int)$precioPlan;
        $descripcionPlan = $_POST["descripcion"];
    
        $retornos = [
            "Nombre" => true,
            "Duracion" => true,
            "Precio" => true,
            "Descripcion" => true
        ];

        if (empty($nombrePlan) || !is_string($nombrePlan)) {
            $retornos["Nombre"] = false;
        }

        if (empty($duracionPlan) || !is_numeric($duracionPlan) || $duracionPlan < 0) {
            $retornos["Duracion"] = false;
        }

        if (empty($precioPlan) || !is_numeric($precioPlan) || $precioPlan < 0) {
            $retornos["Precio"] = false;
        }

        if (empty($descripcionPlan) || !is_string($descripcionPlan)) {
            $retornos["Descripcion"] = false;
        }
    
        //ACA VA LOGICA PARA SUBIR AL SERVIDOR

        //ruta del config.json
        $rutaArchivo = "../../datos/config.json";

        //si existe,
        if (file_exists($rutaArchivo)) {
            $canProceed = true;

            foreach ($retornos as $key => $value) {
                if ($value !== true) {
                    $canProceed = false;
                }
            }

            //se puede avanzar?
            if($canProceed) {
                //obtenemos su contenido
                $json = file_get_contents($rutaArchivo);
                $data = json_decode($json, true);

                if(!isset($data["planes"])) { $data["planes"] = []; }

                $planNuevo = [];

                //reemplazamos si es que hay parametros
                foreach ($retornos as $key => $value) {
                    if($value == true) {
                        $planNuevo[$key] = $_POST[strtolower($key)];
                    }

                    $datoYaExistia = in_array(strtolower($key), $data);                    
                    $value = $datoYaExistia;
                }
                //var_dump($retornos);
                //var_dump($data);

                if(count($data["planes"]) > 2) {
                    $retornos = [
                        "Nombre" => false,
                        "Duracion" => false,
                        "Precio" => false,
                        "Descripcion" => false
                    ];
                } else {
                    $data["planes"][] = $planNuevo;
                }
            }
        } else {
            $retornos = [
                "Nombre" => false,
                "Duracion" => false,
                "Precio" => false,
                "Descripcion" => false
            ];

            return ["success" => "Error al cargar plan, el archivo 'config' no existe."];
        }

        //si el arreglo no esta vacio, metemos los datos en el json y le decimos que quede aesthetic
        if(!empty($data)) {
            file_put_contents($rutaArchivo, json_encode($data, JSON_PRETTY_PRINT));

            return ["success" => "¡Plan creado con éxito!"];
        }

        return ["success" => "Error al cargar plan."];
    }
?>