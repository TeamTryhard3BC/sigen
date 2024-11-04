<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class Ejercicio implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////

        private int $codigoEjercicio; 
        private string $nombreEjercicio;
        private string $descripcionEjercicio;
        private GrupoMuscular $musculoTrabajado;
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombreEjercicio = null,
            string $descripcionEjercicio = null,
            GrupoMuscular $musculoTrabajado = null
        ) {
            $this->nombreEjercicio = $nombreEjercicio;
            $this->descripcionEjercicio = $descripcionEjercicio;
            $this->musculoTrabajado = $musculoTrabajado;

            $codigoEjercicio = 1;

            $_SERVER["ejercicios"][$codigoEjercicio] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setNombre(string $nombreEjercicio): void {
            $this->nombreEjercicio = $nombreEjercicio;
        }

        public function setDescripcion(string $descripcionEjercicio): void {
            $this->descripcionEjercicio = $descripcionEjercicio;
        }

        public function setMusculoTrabajado(GrupoMuscular $musculoTrabajado): void {
            $this->musculoTrabajado = $musculoTrabajado;
        }
        
        //GETTERS
        public function getCodigoEjercicio(): int {
            return $this->codigoEjercicio;
        }

        public function getNombre(): string {
            return $this->nombreEjercicio;
        }

        public function getDescripcion(): string {
            return $this->descripcionEjercicio;
        }

        public function getMusculoTrabajado(): GrupoMuscular {
            return $this->musculoTrabajado;
        }
        
        //OTRAS FUNCIONES
        public function eliminarEjercicio(int $codigoEjercicio): bool {
            //eliminar ejercicio
            
            //retorna si se dio o no
            return true;
        }

        public function jsonSerialize() {
            return [
                "codigoEjercicio"=>$this->codigoEjercicio,
                "nombreEjercicio"=>$this->nombreEjercicio,
                "descripcionEjercicio"=>$this->descripcionEjercicio,
                "musculoTrabajado"=>$this->musculoTrabajado->getNombreMusculo(),
            ];
        }


    }
?>