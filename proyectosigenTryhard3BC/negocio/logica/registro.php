<?php
    include_once("..\objetos\Persona.php");
    include_once("..\objetos\Usuario.php");

    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * En caso de las comprobaciones ser exitosas, se creará una clase usuario y persona que se verán reflejadas en el servidor.
     * @return array Devuelve un arreglo.
    */

    return function() {
        if(!isset($_SESSION)) { session_start(); }

        $nombreReg = $_POST["nombre"];
        $apellidoReg = $_POST["apellido"];
        $tipoDocReg = $_POST["tipoDocumento"];
        $documentoReg = $_POST["documento"];
        $documentoReg = (int)$documentoReg;
        $fechaNacReg = $_POST["fechaNacimiento"];
        $contrasenaReg = $_POST["contrasena"];

        $retornos = [
            "Nombre" => true,
            "Apellido" => true,
            "TipoDocumento" => true,
            "NroDocumento" => true,
            "FechaNacimiento" => true,
            "Contrasena" => true,
        ];

        //COMPROBAMOS QUE TODOS LOS DATOS TENGAN SENTIDO

        if (empty($nombreReg) || !is_string($nombreReg)) {
            $retornos["Nombre"] = false;
        }

        if (empty($apellidoReg) || !is_string($apellidoReg)) {
            $retornos["Apellido"] = false;
        }

        if (empty($tipoDocReg) || !is_string($tipoDocReg)) {
            $retornos["TipoDocumento"] = false;

            if($tipoDocReg != "ci" && $tipoDocReg != "pasaporte") { $retornos["TipoDocumento"] = false; }
        }

        if (empty($documentoReg) || !is_numeric($documentoReg) || $documentoReg < 0) {
            $retornos["NroDocumento"] = false;
        }

        if (empty($fechaNacReg) || !is_string($fechaNacReg)) {
            $retornos["FechaNacimiento"] = false;
        }

        if (empty($contrasenaReg) || !is_string($contrasenaReg)) {
            $retornos["Contrasena"] = false;
        }
        
        $canProceed = true;

        foreach ($retornos as $key => $value) {
            if ($value !== true) {
                $canProceed = false;
            }
        }

        if($canProceed) {
            /////////////////
            // EL USUARIO EXISTE?
            /////////////////

            $existeQuery = "
                SELECT * FROM usuario
                WHERE tipoDocumento = '$tipoDocReg' AND nroDocumento = '$documentoReg';
            ";
            
            $resultado = consultaServidor($existeQuery);
            if ($resultado) {
                if ($resultado->fetch_assoc()) {
                    /////////////////
                    // EXISTE, RETORNO
                    /////////////////

                    $retornos["TipoDocumento"] = false;
                    $retornos["NroDocumento"] = false;
                    return $retornos;
                } else {
                    /////////////////
                    // NO EXISTE, CREAR
                    /////////////////

                    $countQuery = "
                        SELECT COUNT(*) AS total FROM persona
                    ";
                    $resultado = consultaServidor($countQuery);
                        
                    if ($resultado) {
                        $cantidadPersonas = $resultado->fetch_assoc()['total'];
                        if(is_numeric($cantidadPersonas)) {
                            $codigoPersonaReg = $cantidadPersonas + 1;

                            $nombreUsuario = $tipoDocReg . "-" . $documentoReg;
            
                            $_SERVER[$codigoPersonaReg]["persona"] = new Persona($nombreReg, $apellidoReg, $fechaNacReg, $codigoPersonaReg, $nombreUsuario);
                            $_SERVER[$codigoPersonaReg]["usuario"] = new Usuario($tipoDocReg, $documentoReg, $contrasenaReg, $codigoPersonaReg, "Cliente");
                            $_SESSION["usuario"] = $_SERVER[$codigoPersonaReg]["usuario"];
                            $_SESSION["persona"] = $_SERVER[$codigoPersonaReg]["persona"];
                        }
                        //echo $cantidadPersonas;
                    }
                }
            }
        }

        return $retornos;
    }
?>