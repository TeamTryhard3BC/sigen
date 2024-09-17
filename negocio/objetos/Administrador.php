<?php
    include_once("Persona.php");
    include_once("Pago.php");

    class Cliente extends Persona {
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
            $_SERVER["administradores"][$codigoPersona] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
 
        //GETTERS
        
        //OTRAS FUNCIONES
        public function registrarUsuario(string $nombre, string $apellido, string $contrasena, string $fechaNacimiento, string $tipoDocumento,
        int $nroDocumento, string $tipoUsuario): Object {
            //usando el constructor de la clase de tipoUsuario, forzar la creacion del usuario
            new $tipoUsuario();
            //devolver el objeto
            return new Pago();
        }

        public function modificarUsuario(int $codigoPersona, array $parametros): bool {
            //modifica el usuario, recorrer $parametros y cambiar ese respectivo dato del usuario

            //retorna si se dio o no se dio
            return true;
        }

        public function eliminarUsuario(int $codigoPersona): bool {
            //elimina el usuario

            //retorna si se dio o no se dio
            return true;
        }

        public function buscarUsuario(int $filtros): array {
            //busca a todos los usuarios que cumplan con los filtros 

            //retorna los usuarios
            return [];
        }

        public function modificarPago(int $id, array $parametros): bool {
            //modifica el pago, recorrer $parametros y cambiar ese respectivo dato del pago

            //retorna si se dio o no se dio
            return true;
        }

        public function getAllPagos(): array {
            //retorna todos los pagos de la variable $_SERVER
            return [];
        }

        public function jsonSerialize() {
            return [];
        }
    }
?>