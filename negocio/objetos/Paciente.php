<?php
    include_once("Cliente.php");

    class Paciente extends Cliente {
        //////////////////////
        //     VARIABLES
        //////////////////////
        private string $estado;
        
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
            $_SERVER["pacientes"][$codigoPersona] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setEstado(string $estado): void {
            $this->estado = $estado;
        }
        
        //GETTERS
        public function getEstado(): string {
            return $this->estado;
        }
        
        //OTRAS FUNCIONES
        public function jsonSerialize() {
            return [
                "estado"=>$this->estado,
            ];
        }


    }
?>