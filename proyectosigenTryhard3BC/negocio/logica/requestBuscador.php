<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    /**
     * Función anónima con el objetivo de retornar los datos necesarios de la base de datos al cliente.
     * No recibe ningún parámetro.
     * @return array Devuelve un arreglo.
    */

    return function (): array|null {
        if(!isset($_SESSION)) { session_start(); }
        $retorno = [
            "ejercicio" => [],
            "combo" => [],
            "grupomuscular" => [],
        ];

        $parametros = [
            "multiple" => true
        ];

        foreach($retorno as $key => $value) {
            $query = "
                SELECT * FROM $key;
            ";

            $resultado = fetch($query, $parametros);

            if ($resultado) {
                /////////////////
                // EXISTE, RETORNO
                /////////////////

                $retorno[$key] = $resultado;
            }
        }
        
        return $retorno;
    }
?>