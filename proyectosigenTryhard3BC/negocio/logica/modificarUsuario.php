<?php 

include_once("../../datos/conexion/puenteMySQL.php");
include_once("..\objetos\Persona.php");
include_once("..\objetos\Usuario.php");
include_once("checkRole.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(checkRol() !== "Administrador") { echo json_encode(["error" => "No tienes permisos."]); return; }

        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
        $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : null;
        $tipoDoc = isset($_POST["tipoDocumento"]) ? $_POST["tipoDocumento"] : null;
        $documento = isset($_POST["documento"]) ? (int)$_POST["documento"] : null;
        $fechaNac = isset($_POST["fechaNacimiento"]) ? $_POST["fechaNacimiento"] : null;
        $contrasena = isset($_POST["contrasena"]) ? $_POST["contrasena"] : null;
        $rol = isset($_POST["rol"]) ? $_POST["rol"] : null;
        
        $datosValidados = [
            "Nombre" => true,
            "Apellido" => true,
            "TipoDocumento" => true,
            "NroDocumento" => true,
            "FechaNacimiento" => true,
            "Contrasena" => true,
            "Rol" => true,
        ];

        $rolPosible = ["Administrador", "Entrenador", "Cliente"];
        $documentosPosibles = ["ci", "pasaporte"];

        //COMPROBAMOS QUE TODOS LOS DATOS TENGAN SENTIDO

        if (empty($nombre) || !is_string($nombre)) {
            $datosValidados["Nombre"] = false;
        }

        if (empty($apellido) || !is_string($apellido)) {
            $datosValidados["Apellido"] = false;
        }

        if (empty($tipoDoc) || !is_string($tipoDoc) || !in_array($tipoDoc, $documentosPosibles)) {
            $datosValidados["TipoDocumento"] = false;
        }

        if (!empty($documento) || !is_numeric($documento) || $documento < 0) {
            $datosValidados["NroDocumento"] = false;
        }

        if (empty($fechaNac) || !is_string($fechaNac)) {
            $datosValidados["FechaNacimiento"] = false;
        }

        if (empty($contrasena) || !is_string($contrasena)) {
            $datosValidados["Contrasena"] = false;
        }

        if (empty($rol) || !is_string($rol) || !in_array($rol, $rolPosible)) {
            $datosValidados["Rol"] = false;
        }
        
        /////////////////
        // EL USUARIO EXISTE?
        /////////////////

        $existeQuery = "
            SELECT * FROM usuario
            WHERE tipoDocumento = '$tipoDoc' AND nroDocumento = '$documento';
        ";
            
        $resultado = consultaServidor($existeQuery);
        if ($resultado) {
            if ($dataUsuario = $resultado->fetch_assoc()) {
                /////////////////
                // EXISTE, MODIFICAR
                /////////////////

                $nombreUsuario = $dataUsuario["tipoDocumento"] . "-" . $dataUsuario["nroDocumento"];

                $personaQuery = "
                    SELECT * FROM persona
                    WHERE nombreUsuario = '$nombreUsuario';
                ";

                $resultado = consultaServidor($personaQuery);
                if ($dataPersona = $resultado->fetch_assoc()) {
                    $codigoPersona = $dataPersona["codigoPersona"];
                        
                    //nuevos datos de la persona
                    $nuevoNombre = $datosValidados["Nombre"] === true ? $nombre : $dataPersona["nombre"];
                    $nuevoApellido = $datosValidados["Apellido"] === true ? $apellido : $dataPersona["apellido"];
                    $nuevaFechaNac = $datosValidados["FechaNacimiento"] === true ? $fechaNac : $dataPersona["fechaNacimiento"];

                    //nuevos datos del usuario
                    $nuevaContrasena = $datosValidados["Contrasena"] === true ? $contrasena : $dataUsuario["contrasena"];
                    $nuevoRol = $datosValidados["Rol"] === true ? $rol : $dataUsuario["rol"];

                    //DEBUG: var_dump($nuevoNombre, $nuevoApellido, $nuevaFechaNac, $nuevaContrasena, $nuevoRol);

                    $_SERVER[$codigoPersona]["persona"] = new Persona($nuevoNombre, $nuevoApellido, $nuevaFechaNac, $codigoPersona, $nombreUsuario);
                    $_SERVER[$codigoPersona]["usuario"] = new Usuario($dataUsuario["tipoDocumento"], $dataUsuario["nroDocumento"], $nuevaContrasena, $codigoPersona, $nuevoRol);
    
                    if(!isset($_SESSION)) { session_start(); }

                    if(isset($_SESSION["usuario"])) {
                        if($_SESSION["usuario"]->getCodigoPersona() == $codigoPersona) {
                            echo json_encode(["cerrarSesion" => true, "success" => "Su usuario ha sido modificado, porfavor vuelva a iniciar sesión"]);
                            $_SESSION["usuario"] = null;

                            return;
                        }
                    }

                    echo json_encode(["success" => "Usuario modificado con éxito."]);
                    return;
                } else {
                    echo json_encode(["error" => "No se pudo obtener los datos de la persona."]);
                    return;
                }
            } else {
                echo json_encode(["error" => "No existe o no se pudieron obtener los datos de un usuario con ese documento."]);
                return;
            }
        } else {
            /////////////////
            // NO EXISTE, RETORNO
            /////////////////

            echo json_encode(["error" => "No existe un usuario con ese documento."]);
            return;
        }
} else {
    echo json_encode(["error" => "Faltan parametros."]);
}

?>

