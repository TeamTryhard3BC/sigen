<?php
    include("..\Conexion\PuenteMySQL.php");

    class Persona implements JsonSerializable {
        //VARS
        private $nombre;
        private $apellido;
        private $tipoDocumento;
        private $nroDocumento;
        private $fechaNacimiento;
        private $contrasena;
        private $tipoUsuario;
        private $codigoPersona;

        //CONSTRUCTOR
        //(Nombre: str, Apellido: str, TipoDocumento: str, NumeroDocumento: int, FechaNacimiento: date, Contrasena: str, TipoDeUsuario: str)
        public function __construct(
            $nombre = null,
            $apellido = null,
            $tipoDocumento = null,
            $nroDocumento = null,
            $fechaNacimiento = null,
            $contrasena = null,
            $tipoUsuario = "Cliente"
        ) {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->tipoDocumento = $tipoDocumento;
            $this->nroDocumento = $nroDocumento;
            $this->fechaNacimiento = $fechaNacimiento;
            $this->contrasena = $contrasena;
            $this->tipoUsuario = $tipoUsuario;
        }

        //GETTERS
        public function getNombre() {
            return $this->nombre;
        }

        public function getApellido() {
            return $this->apellido;
        }

        public function getTipoDocumento() {
            return $this->tipoDocumento;
        }

        public function getNroDocumento() {
            return $this->nroDocumento;
        }

        public function getFechaNacimiento() {
            return $this->fechaNacimiento;
        }

        public function getContrasena() {
            return $this->contrasena;
        }

        public function getTipoUsuario() {
            return $this->tipoUsuario;
        }

        //SETTERS
        public function setUsuario($usuario) {
            $this->usuario = $usuario;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        public function setApellido($apellido) {
            $this->apellido = $apellido;
        }

        public function setCorreo($correo) {
            $this->correo = $correo;
        }

        public function setTipoDocumento($tipoDocumento) {
            $this->tipoDocumento = $tipoDocumento;
        }

        public function setNroDocumento($nroDocumento) {
            $this->nroDocumento = $nroDocumento;
        }

        public function setFechaNacimiento($fechaNacimiento) {
            $this->fechaNacimiento = $fechaNacimiento;
        }

        public function setContrasena($contrasena) {
            $this->contrasena = $contrasena;
        }

        public function setTipoUsuario($tipoUsuario) {
            $this->tipoUsuario = $tipoUsuario;
        }
        
        //FUNCS
        public function jsonSerialize() {
            return [
                "nombre"=>$this->nombre,
                "apellido"=>$this->apellido,
                "tipoDocumento"=>$this->tipoDocumento,
                "nroDocumento"=>$this->nroDocumento,
                "fechaNacimiento"=>$this->fechaNacimiento,
                "contrasena"=>$this->contrasena,
                "tipoUsuario"=>$this->tipoUsuario
            ];
        }


    }

    $personaNew = new Persona();
?>