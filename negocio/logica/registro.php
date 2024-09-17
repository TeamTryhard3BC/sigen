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
        session_start();

        $nombreReg = $_GET["nombre"];
        $apellidoReg = $_GET["apellido"];
        $tipoDocReg = $_GET["tipoDocumento"];
        $documentoReg = $_GET["documento"];
        $documentoReg = (int)$documentoReg;
        $fechaNacReg = $_GET["fechaNacimiento"];
        $contrasenaReg = $_GET["contrasena"];

        $retornos = [
            "Nombre" => true,
            "Apellido" => true,
            "TipoDocumento" => true,
            "NroDocumento" => true,
            "FechaNacimiento" => true,
            "Contrasena" => true,
        ];

        //COMPROBAMOS QUE TODOS LOS DATOS TENGAN SENTIDO

        if(!isset($nombreReg) || gettype($nombreReg) != "string" || (isset($nombreReg) && !$nombreReg)) {
            $retornos["Nombre"] = false;
        }
    
        if(!isset($apellidoReg) || gettype($apellidoReg) != "string" || (isset($apellidoReg) && !$apellidoReg)) {
            $retornos["Apellido"] = false;
        }
    
        if(!isset($tipoDocReg) || gettype($tipoDocReg) != "string" || (isset($tipoDocReg) && !$tipoDocReg)) {
            $retornos["TipoDocumento"] = false;
        }

        if(gettype(value: $tipoDocReg) == "string" && isset($tipoDocReg)) {
            if($tipoDocReg != "ci" && $tipoDocReg != "pasaporte") { $retornos["TipoDocumento"] = false; }
        }
    
        if ($documentoReg == null || !isset($documentoReg) || !is_numeric($documentoReg) || (isset($documentoReg) && $documentoReg < 0)) {
            $retornos["NroDocumento"] = false;
        }
    
        if(!isset($fechaNacReg) || gettype($fechaNacReg) != "string" || (isset($fechaNacReg) && !$fechaNacReg)) {
            $retornos["FechaNacimiento"] = false;
        }
    
        if(!isset($contrasenaReg) || gettype($contrasenaReg) != "string" || (isset($contrasenaReg) && !$contrasenaReg)) {
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
                            $_SERVER[$codigoPersonaReg]["usuario"] = new Usuario($tipoDocReg, $documentoReg, $contrasenaReg, $codigoPersonaReg);
                        }
                        //echo $cantidadPersonas;
                    }
                }
            }
        }

        return $retornos;
    }
?>