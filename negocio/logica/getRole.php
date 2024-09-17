<?php
    /**
     * Función anónima con el objetivo de retornar el rol del usuario.
     * No recibe ningún parámetro.
     * @return string|bool Devuelve un string o un bool.
    */

    return function (): string|bool {
        session_start();

        if(!isset($_SESSION["usuario"])) {
            return false;
        }
        
        return gettype($_SESSION["usuario"]);
    }
?>