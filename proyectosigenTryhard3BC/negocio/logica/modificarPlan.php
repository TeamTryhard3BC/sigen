<?php
    /**
     * Función anónima con el objetivo de modificar un plan.
     * No recibe ningún parámetro, sin embargo la variable global POST debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    include_once("checkRole.php");

    return function() {
        if(checkRol() !== "Administrador") { return ["error" => "No tienes permisos."]; }

        if(!isset($_SESSION)) { session_start(); }

        $nombreOriginal = $_POST["nombreOriginal"];
        $nombrePlan = $_POST["nombre"];
        $duracionPlan = $_POST["duracion"];
        $duracionPlan = (int)$duracionPlan;
        $precioPlan = $_POST["precio"];
        $precioPlan = (int)$precioPlan;
        $descripcionPlan = $_POST["descripcion"];
    
        $retornos = [
            "NombreOriginal" => true,
            "Nombre" => true,
            "Duracion" => true,
            "Precio" => true,
            "Descripcion" => true
        ];

        if (empty($nombreOriginal) || !is_string($nombreOriginal)) {
            $retornos["NombreOriginal"] = false;
        }

        if (empty($nombrePlan) || !is_string($nombrePlan)) {
            $retornos["Nombre"] = false;
        }

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

                if (!isset($data["planes"])) { $data["planes"] = []; }

                // buscamos el plan
                $planEncontrado = false;

                for ($i = 0; $i < count($data["planes"]); $i++) {
                    // buscamos plan por el nombreOriginal
                    if (strtolower($data["planes"][$i]["Nombre"]) == strtolower($nombreOriginal)) {
                        // encontramos un plan, reemplazamos sus datos por los que el usuario quiere
                        $data["planes"][$i]["Nombre"] = $nombrePlan;
                        $data["planes"][$i]["Duracion"] = $duracionPlan;
                        $data["planes"][$i]["Precio"] = $precioPlan;
                        $data["planes"][$i]["Descripcion"] = $descripcionPlan;
                        $planEncontrado = true;
            
                        break; // Stop loop once the plan is found and modified
                    }
                }

                if (!$planEncontrado) { return ["error" => "El plan no existe"]; }
            } else {
                $retornos = [
                    "NombreOriginal" => false,
                    "Nombre" => false,
                    "Duracion" => false,
                    "Precio" => false,
                    "Descripcion" => false
                ];

                return ["error" => "Error al cargar plan, el archivo 'config' no existe."];
            }

            //si el arreglo no esta vacio, metemos los datos en el json y le decimos que quede aesthetic
            if(!empty($data)) {
                file_put_contents($rutaArchivo, json_encode($data, JSON_PRETTY_PRINT));

                return ["success" => "¡Plan modificado con éxito!"];
            }

            return ["error" => "Error al cargar plan."];
        } else {
            return ["error" => "Error, el archivo 'config' no existe."];
        }
    }
?>