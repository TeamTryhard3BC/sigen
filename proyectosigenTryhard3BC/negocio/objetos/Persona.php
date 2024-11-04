<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class Persona implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////

        private string $nombre; 
        private string $apellido;
        private int $codigoPersona;
        private string $fechaNacimiento;
        private string $nombreUsuario;
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombre = null,
            string $apellido = null,
            string $fechaNacimiento = null,
            int $codigoPersona = null,
            string $nombreUsuario = null
        ) {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->fechaNacimiento = $fechaNacimiento;
            $this->codigoPersona = $codigoPersona;
            $this->nombreUsuario = $nombreUsuario;

            $cargarPersona = "
                INSERT INTO Persona (codigoPersona, nombre, apellido, fechaNacimiento, nombreUsuario)
                SELECT $codigoPersona, '$nombre', '$apellido', '$fechaNacimiento', '$nombreUsuario'
                ON DUPLICATE KEY UPDATE 
                    nombre = '$nombre',
                    apellido = '$apellido',
                    fechaNacimiento = '$fechaNacimiento';
            ";

            consultaServidor($cargarPersona);
            $_SERVER["personas"][$codigoPersona] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setNombre(string $nombre): void {
            $this->nombre = $nombre;
        }

        public function setApellido(string $apellido): void {
            $this->apellido = $apellido;
        }

        public function setFechaNacimiento(int $fechaNacimiento): void {
            $this->fechaNacimiento = $fechaNacimiento;
        }
        
        //GETTERS
        public function getNombre(): string {
            return $this->nombre;
        }

        public function getApellido(): string {
            return $this->apellido;
        }

        public function getFechaNacimiento(): string {
            return $this->fechaNacimiento;
        }

        public function getCodigoPersona(): int {
            return $this->codigoPersona;
        }
        
        //OTRAS FUNCIONES
        public function jsonSerialize() {
            return [
                "nombre"=>$this->nombre,
                "apellido"=>$this->apellido,
                "fechaNacimiento"=>$this->fechaNacimiento,
                "codigoPersona"=>$this->codigoPersona
            ];
        }


    }
?>