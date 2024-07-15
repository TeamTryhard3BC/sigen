<?php
    $jsonstr = file_get_contents("brayan.json");
    $tareas = json_decode($jsonstr);

    class Cliente implements JsonSerializable {
        //VARS
        private $persona;
        private $codigoCliente;

        //CONSTRUCTOR
        //(Persona: ObjetoPersona)
        public function __construct(
            $persona = null

           
        ) {
            $this->persona = $persona;

            if(isset($tareas)) {
                $this->id = count($tareas) + 1;
            } else {
                $this->id = 1;
            }
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
?>