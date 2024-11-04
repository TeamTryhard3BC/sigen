<?php
    /**
     * Función anónima con el objetivo de retornar el rol si el usuario tiene su sesión guardada en el servidor.
     * No recibe ningún parámetro.
     * @return string|null Devuelve un string o null.
    */

    include_once("../objetos/Usuario.php");

    function checkRol(): string|null {
        if(!isset($_SESSION)) { session_start(); }

        if(!isset($_SESSION["usuario"])) {
            return null;
        }
        
        return $_SESSION["usuario"]->getRol();
    }
?>