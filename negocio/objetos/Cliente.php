<?php
    include_once("Persona.php");
    include_once("Pago.php");

    class Cliente extends Persona {
        //////////////////////
        //     VARIABLES
        //////////////////////
        private int $nivelCalificacion;
        
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
            $_SERVER["clientes"][$codigoPersona] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setNivelCalificacion(int $nivelCalificacion): void {
            $this->nivelCalificacion = $nivelCalificacion;
        }
        
        //GETTERS
        public function getNivelCalificacion(): int {
            return $this->nivelCalificacion;
        }
        
        //OTRAS FUNCIONES
        public function realizarPago(string $nombrePago, string $metodoPago, int $montoPago, int $cuotas): bool {
            //if pago exitoso
            new Pago();
            //return true o false
            return true;
        }

        public function estadoDeportista(): string {
            return "";
        }

        public function verPagos(): array {
            //for y recorrer todos los pagos en la variable $_SERVER
            return [];
        }

        public function jsonSerialize() {
            return [
                "nivelCalificacion"=>$this->nivelCalificacion
            ];
        }


    }
?>