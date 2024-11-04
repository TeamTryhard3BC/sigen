<?php
    include_once("..\objetos\Persona.php");
    include_once("..\objetos\Usuario.php");
    include_once("..\..\datos\conexion\PuenteMySQL.php");

    /**
     * Función anónima con el objetivo de comprobar las solicitudes enviadas por el cliente.
     * No recibe ningún parámetro, sin embargo la variable global GET debe ser cargada.
     * @return array Devuelve un arreglo.
    */

    return function() {
        if(!isset($_SESSION)) { session_start(); }

        $tipoDocLog = $_POST["tipoDocumento"];
        $documentoLog = $_POST["documento"];
        $documentoLog = (int)$documentoLog;
        $contrasenaLog = $_POST["contrasena"];
    
        $retornos = [
            "TipoDocumento" => true,
            "NroDocumento" => true,
            "Contrasena" => true,
        ];

        //COMPROBAMOS QUE TODOS LOS DATOS TENGAN SENTIDO

        if (empty($tipoDocLog) || !is_string($tipoDocLog)) {
            $retornos["TipoDocumento"] = false;

            if($tipoDocLog != "ci" && $tipoDocLog != "pasaporte") { $retornos["TipoDocumento"] = false; }
        }

        if (empty($documentoLog) || !is_numeric($documentoLog) || $documentoLog < 0) {
            $retornos["NroDocumento"] = false;
        }

        if (empty($contrasenaLog) || !is_string($contrasenaLog)) {
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
                WHERE tipoDocumento = '$tipoDocLog' AND nroDocumento = '$documentoLog';
            ";
            
            $resultado = consultaServidor($existeQuery);
            if ($resultado) {
                if ($resultado->fetch_assoc()) {
                    /////////////////
                    // EXISTE, COMPROBAR CONTRASEÑA
                    /////////////////

                    $checkPasswdQuery = "
                        SELECT contrasena FROM usuario
                        WHERE tipoDocumento = '$tipoDocLog' AND nroDocumento = '$documentoLog'
                    ";

                    $resultadoPass = fetch($checkPasswdQuery, ["multiple" => false]);

                    //var_dump($contrasenaLog);
                    //var_dump($resultadoPass["contrasena"]);
                    //var_dump(password_verify($contrasenaLog, $resultadoPass["contrasena"]));
                    if($resultadoPass && isset($resultadoPass["contrasena"])) {
                        $contrasenaGuardada = $resultadoPass["contrasena"];

                        // INTENTAMOS COMPARAR LA CONTRASEÑA A LA INGRESADA POR EL USUARIO
                        if (password_verify($contrasenaLog, $contrasenaGuardada)) {
                            /////////////////
                            // CONTRASEÑA CORRECTA, CONTINUAMOS
                            /////////////////

                            $usuarioCompuesto = $tipoDocLog . "-" . $documentoLog;

                            // INTENTAMOS OBTENER LOS DATOS DE LA PERSONA
                            $personaDataQuery = "
                                SELECT * FROM persona
                                WHERE nombreUsuario = '$usuarioCompuesto'
                            ";

                            $usuarioDataQuery = "
                                SELECT * FROM usuario
                                WHERE tipoDocumento = '$tipoDocLog' AND nroDocumento = '$documentoLog' AND activo = TRUE
                            ";

                            $resultadoData = fetch($personaDataQuery, ["multiple" => false]);
                            $resultadoDataUser = fetch($usuarioDataQuery, ["multiple" => false]);

                            if($resultadoData && $resultadoDataUser && isset($resultadoData, $resultadoDataUser)) {
                                $nombre = $resultadoData["nombre"];
                                $apellido = $resultadoData["apellido"];
                                $fechaNac = $resultadoData["fechaNacimiento"];
                                $codigoPersona = $resultadoData["codigoPersona"];
                                $nombreUsuario = $resultadoData["nombreUsuario"]; 
                                $rolUsuario = $resultadoDataUser["rol"]; 
                                    
                                // CREAMOS LOS OBJETOS CORRESPONDIENTES EN EL SERVIDOR
                                $_SERVER[$codigoPersona]["persona"] = new Persona($nombre, $apellido, $fechaNac, $codigoPersona, $nombreUsuario);
                                $_SERVER[$codigoPersona]["usuario"] = new Usuario($tipoDocLog, $documentoLog, $contrasenaLog, $codigoPersona, $rolUsuario);
                                $_SESSION["usuario"] = $_SERVER[$codigoPersona]["usuario"];
                                $_SESSION["persona"] = $_SERVER[$codigoPersona]["persona"];
                            } else {
                                $retornos["TipoDocumento"] = false;
                                $retornos["NroDocumento"] = false;
                                $retornos["Contrasena"] = false;
                                return $retornos;
                            }
                        } else {
                            $retornos["Contrasena"] = false;
                            return $retornos;
                        }
                    } else {
                        /////////////////
                        // CONTRASEÑA NO ENCONTRADA? COMO LLEGAS HASTA ACA?
                        /////////////////

                        $retornos["Contrasena"] = false;
                        return $retornos;
                    }

                    
                } else {
                    /////////////////
                    // NO EXISTE, RETORNAR QUE ESTA MAL EL TIPO DOCUMENTO O NRO DOCUMENTO
                    /////////////////

                    $retornos["TipoDocumento"] = false;
                    $retornos["NroDocumento"] = false;
                    return $retornos;
                }
            }
        }

        return $retornos;
    }
?>