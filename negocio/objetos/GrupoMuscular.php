<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class GrupoMuscular implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////

        private string $nombreMusculo;
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombreMusculo = null,
        ) {
            $this->nombreMusculo = $nombreMusculo;

            $_SERVER["gruposMusculares"][$nombreMusculo] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        
        //GETTERS
        public function getNombreMusculo(): string {
            return $this->nombreMusculo;
        }
        
        //OTRAS FUNCIONES
        public function getEjercicios(): array {
            //recorrer array con todos los ejercicios en $_SERVER con el mismo nombreMusculo
            return [];
        }

        public function getAll(): array {
            //recorrer array con todos los gruposMusculares
            return [];
        }

        public function eliminarGrupoMuscular(): bool {
            //eliminar el grupo muscular revisando y todo
            return true;
        }

        public function jsonSerialize() {
            return [
                "nombreMusculo"=>$this->nombreMusculo
            ];
        }
    }
?>