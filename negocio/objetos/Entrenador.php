<?php
    include_once("Persona.php");

    class Entrenador extends Persona {
        //////////////////////
        //     VARIABLES
        //////////////////////
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombre = null,
            string $apellido = null,
            string $fechaNacimiento = null,
            int $codigoPersona = null
        ) {
            parent::__construct($nombre, $apellido, $fechaNacimiento, $codigoPersona);
            $_SERVER["entrenadores"][$codigoPersona] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        
        //GETTERS
        
        //OTRAS FUNCIONES
        public function modificarEjercicio(int $codigoEjercicio, array $parametros): bool {
            //modifica el ejercicio, recorrer $parametros y cambiar ese respectivo dato del ejercicio

            //retorna si se dio o no se dio
            return true;
        }

        public function getEjercicio(int $codigoEjercicio): Object {
            //retorna el objeto del ejercicio con la id dada
            return new Pago();
        }

        public function getAllEjercicios(): array {
            //retorna todos los ejercicios de la variable $_SERVER
            return [];
        }

        public function modificarCombo(int $idCombo, array $parametros): bool {
            //modifica el combo, recorrer $parametros y cambiar ese respectivo dato del combo

            //retorna si se dio o no se dio
            return true;
        }

        public function evaluarDeportista(int $codigoPersona, int $nivelCalificacion): bool {
            //evaluar a deportista

            //retorna si se dio o no se dio
            return true;
        }

        public function jsonSerialize() {
            return [
                
            ];
        }


    }
?>