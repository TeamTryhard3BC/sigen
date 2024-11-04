<?php 

include_once("../../datos/conexion/puenteMySQL.php");
include_once("..\objetos\Persona.php");
include_once("..\objetos\Usuario.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(checkRol() !== "Administrador") { echo json_encode(["error" => "No tienes permisos."]); return; }

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $tipoDoc = $_POST["tipoDocumento"];
        $documento = $_POST["documento"];
        $documento = (int)$documento;
        $fechaNac = $_POST["fechaNacimiento"];
        $contrasena = $_POST["contrasena"];
        $rol = $_POST["rol"];

        $retornos = [
            "Nombre" => true,
            "Apellido" => true,
            "TipoDocumento" => true,
            "NroDocumento" => true,
            "FechaNacimiento" => true,
            "Contrasena" => true,
        ];

        //COMPROBAMOS QUE TODOS LOS DATOS TENGAN SENTIDO

        if (empty($nombre) || !is_string($nombre)) {
            $retornos["Nombre"] = false;
        }

        if (empty($apellido) || !is_string($apellido)) {
            $retornos["Apellido"] = false;
        }

        if (empty($tipoDoc) || !is_string($tipoDoc)) {
            $retornos["TipoDocumento"] = false;

            if($tipoDoc != "ci" && $tipoDoc != "pasaporte") { $retornos["TipoDocumento"] = false; }
        }

        if (empty($documento) || !is_numeric($documento) || $documento < 0) {
            $retornos["NroDocumento"] = false;
        }

        if (empty($fechaNac) || !is_string($fechaNac)) {
            $retornos["FechaNacimiento"] = false;
        }

        if (empty($contrasena) || !is_string($contrasena)) {
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
                WHERE tipoDocumento = '$tipoDoc' AND nroDocumento = '$documento';
            ";
            
            $resultado = consultaServidor($existeQuery);
            if ($resultado) {
                if ($resultado->fetch_assoc()) {
                    /////////////////
                    // EXISTE, RETORNO
                    /////////////////

                    $retornos["TipoDocumento"] = false;
                    $retornos["NroDocumento"] = false;

                    echo json_encode(["error" => "Ya existe un usuario con ese tipo y número de documento."]);
                    return;
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
                            $codigoPersona = $cantidadPersonas + 1;

                            $nombreUsuario = $tipoDoc . "-" . $documento;
            
                            $_SERVER[$codigoPersona]["persona"] = new Persona($nombre, $apellido, $fechaNac, $codigoPersona, $nombreUsuario);
                            $_SERVER[$codigoPersona]["usuario"] = new Usuario($tipoDoc, $documento, $contrasena, $codigoPersona, $rol);
                        
                            //var_dump($_SERVER[$codigoPersona]["persona"]);
                            //var_dump($_SERVER[$codigoPersona]["usuario"]);
                            echo json_encode(["success" => "Usuario creado con éxito."]);
                            return;
                        }
                        //echo $cantidadPersonas;
                    } else {
                        echo json_encode(["error" => "No se pudo obtener la información de personas."]);
                        return;
                    }
                }
            } else {
                echo json_encode(["error" => "No se pudo obtener la información de los usuarios."]);
                return;
            }
        }
} else {
    echo json_encode(["error" => "Faltan parametros."]);
}

?>

