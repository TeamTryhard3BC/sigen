<?php
    //////////////////////
    //     DEPENDENCIAS
    //////////////////////
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class Usuario implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////
        
        private int $codigoPersona;
        private string $nombreUsuario;
        private string $tipoDocumento; 
        private int $nroDocumento;
        private string $contrasena;
        private int $ultimaSesion;

        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $tipoDocumento = null,
            int $nroDocumento = null,
            string $contrasena = null,
            int $codigoPersona = null,
        ) {
            $this->codigoPersona = $codigoPersona;
            $this->tipoDocumento = $tipoDocumento;
            $this->nroDocumento = $nroDocumento;
            $this->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
            $this->nombreUsuario = $tipoDocumento . "-" . $nroDocumento;

            $cargarUsuario = "
                INSERT INTO Usuario (tipoDocumento, nroDocumento, contrasena) 
                SELECT '$tipoDocumento', $nroDocumento, '$this->contrasena'
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM Usuario
                    WHERE tipoDocumento = '$tipoDocumento' AND nroDocumento = $nroDocumento
                );
            ";
            
            consultaServidor($cargarUsuario);
            $_SERVER["usuarios"][$codigoPersona] = $this;
            $_SESSION["usuario"] = $this;
        }

        function login($username, $password) {
            $users = $this->getUsers();
            if (isset($users[$username]) && password_verify($password, $users[$username])) {
                $_SESSION['username'] = $username;
                return true;
            }
            return false;
        }


        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setTipoDocumento(string $tipoDocumento): void {
            $this->tipoDocumento = $tipoDocumento;
        }

        public function setNroDocumento(int $nroDocumento): void {
            $this->nroDocumento = $nroDocumento;
        }

        public function setContrasena(string $contrasena): void {
            $this->contrasena = $contrasena;
        }
        
        //GETTERS
        public function getTipoDocumento(): string {
            return $this->tipoDocumento;
        }

        public function getNroDocumento(): int {
            return $this->nroDocumento;
        }

        public function getContrasena(): string {
            return $this->contrasena;
        }

        public function getNombreUsuario(): string {
            return $this->nombreUsuario;
        }

        public function getCodigoPersona(): int {
            return $this->codigoPersona;
        }

        public function getUltimaSesion(): int {
            return $this->ultimaSesion;
        }
        
        //OTRAS FUNCIONES
        public function iniciarSesion(): bool {
            $this->ultimaSesion = time();
            return true;
        }

        public function getUsers(): array {
            return [];
        }

        public function eliminarUsuario(int $codigoPersona): bool {
            return true;
        }

        public function jsonSerialize() {
            return [
                "codigoPersona"=>$this->codigoPersona,
                "tipoDocumento"=>$this->tipoDocumento,
                "nroDocumento"=>$this->nroDocumento,
                "contrasena"=>$this->contrasena,
                "ultimaSesion"=>$this->ultimaSesion,
            ];
        }
    }
?>