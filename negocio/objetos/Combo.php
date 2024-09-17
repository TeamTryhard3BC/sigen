<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class Ejercicio implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////

        private int $idCombo; 
        private string $nombreCombo;
        private string $descripcionCombo;
        private array $ejerciciosCombo;
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombreCombo = null,
            string $descripcionCombo = null,
            array $ejerciciosCombo = null
        ) {
            $this->nombreCombo = $nombreCombo;
            $this->descripcionCombo = $descripcionCombo;
            $this->ejerciciosCombo = $ejerciciosCombo;

            $idCombo = 1;

            $_SERVER["combos"][$idCombo] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setNombre(string $nombreCombo): void {
            $this->nombreCombo = $nombreCombo;
        }

        public function setDescripcion(string $descripcionCombo): void {
            $this->descripcionCombo = $descripcionCombo;
        }
        
        //GETTERS
        public function getNombre(): string {
            return $this->nombreCombo;
        }

        public function getDescripcion(): string {
            return $this->descripcionCombo;
        }

        public function getEjercicios(): array {
            //devuelve ejercicios de un combo
            
            //retorna los ejercicios de un combo
            return [];
        }
        
        //OTRAS FUNCIONES
        public function addEjercicio(int $codigoEjercicio): void {
            //agregar ejercicio
        }

        public function removeEjercicio(int $codigoEjercicio): void {
            //quitar ejercicio
        }

        public function jsonSerialize() {
            return [
                "idCombo"=>$this->idCombo,
                "nombreCombo"=>$this->nombreCombo,
                "descripcionCombo"=>$this->descripcionCombo,
                "ejerciciosCombo"=>$this->ejerciciosCombo,
            ];
        }


    }
?>