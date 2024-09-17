<?php
    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    return function() {
        session_start();

        $nombrePlan = $_GET["nombre"];
        $duracionPlan = $_GET["duracion"];
        $duracionPlan = (int)$duracionPlan;
        $precioPlan = $_GET["precio"];
        $precioPlan = (int)$precioPlan;
        $descripcionPlan = $_GET["descripcion"];
    
        $retornos = [
            "Nombre" => true,
            "Duracion" => true,
            "Precio" => true,
            "Descripcion" => true
        ];

        if(!isset($nombrePlan) || gettype($nombrePlan) != "string" || (isset($nombrePlan) && !$nombrePlan)) {
            $retornos["Nombre"] = false;
        }

        if ($duracionPlan == null || !isset($duracionPlan) || !is_numeric($duracionPlan) || (isset($duracionPlan) && $duracionPlan < 0)) {
            $retornos["Duracion"] = false;
        }

        if ($precioPlan == null || !isset($precioPlan) || !is_numeric($precioPlan) || (isset($precioPlan) && $precioPlan < 0)) {
            $retornos["Precio"] = false;
        }

        if(!isset($descripcionPlan) || gettype($descripcionPlan) != "string" || (isset($descripcionPlan) && !$descripcionPlan)) {
            $retornos["Descripcion"] = false;
        }
    
        //ACA VA LOGICA PARA SUBIR A LA BD O AL SERVIDOR
        //
        /////////////////

        return $retornos;
    }
?>