<?php
    /**
     * Función anónima con el objetivo de retornar si el usuario tiene su sesión guardada en el servidor.
     * No recibe ningún parámetro.
     * @return bool Devuelve un bool.
    */

    return function (): bool {
        if(!isset($_SESSION)) { session_start(); }

        if(!isset($_SESSION["usuario"])) {
            return false;
        }
        
        return true;
    }
?>