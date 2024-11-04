<?php
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    class Pago implements JsonSerializable {
        //////////////////////
        //     VARIABLES
        //////////////////////

        private int $id;
        private string $nombre; 
        private string $fechaPago;
        private string $metodoPago;
        private int $montoPago;
        private int $cuotas;
        private bool $estado;
        
        //////////////////////
        //     CONSTRUCTOR
        //////////////////////
        public function __construct(
            string $nombre = null,
            string $metodoPago = null,
            int $montoPago = null,
            int $cuotas = null,
        ) {
            $this->nombre = $nombre;
            $this->metodoPago = $metodoPago;
            $this->montoPago = $montoPago;
            $this->cuotas = $cuotas;

            $id = 1;

            $_SERVER["pagos"][$id] = $this;
        }

        //////////////////////
        //     MÉTODOS
        //////////////////////

        //SETTERS
        public function setNombre(string $nombre): void {
            $this->nombre = $nombre;
        }

        public function setFechaPago(string $fechaPago): void {
            $this->fechaPago = $fechaPago;
        }

        public function setMetodoPago(string $metodoPago): void {
            $this->metodoPago = $metodoPago;
        }

        public function setMontoPago(int $montoPago): void {
            $this->montoPago = $montoPago;
        }

        public function setCuotas(int $cuotas): void {
            $this->cuotas = $cuotas;
        }
        public function setEstado(bool $estado): void {
            $this->estado = $estado;
        }
        
        //GETTERS
        public function getID(): int {
            return $this->id;
        }
        public function getNombre(): string {
            return $this->nombre;
        }

        public function getFechaPago(): string {
            return $this->fechaPago;
        }

        public function getMetodoPago(): string {
            return $this->metodoPago;
        }

        public function getMontoPago(): int {
            return $this->montoPago;
        }

        public function getCuotas(): int {
            return $this->cuotas;
        }
        public function getEstado(): bool {
            return $this->estado;
        }
        
        //OTRAS FUNCIONES
        public function jsonSerialize() {
            return [
                "id"=>$this->id,
                "nombre"=>$this->nombre,
                "fechaPago"=>$this->fechaPago,
                "metodoPago"=>$this->metodoPago,
                "montoPago"=>$this->montoPago,
                "cuotas"=>$this->cuotas,
                "estado"=>$this->estado,
            ];
        }
    }
?>