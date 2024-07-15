<?php
    //MySQLi es necesario para que la conexion funcione (https://www.w3schools.com/php/php_mysql_connect.asp)

    //reemplazar "localhost" con la direccion del servidor MySQL, en caso de ser local mantenerlo intacto
    $_SERVER["nombreServidor"] = "localhost";
    //reemplazar "sigen" con el nombre de la base de datos en el servidor dado
    $_SERVER["baseDatos"] = "sigen";

    function conectar($usuarioBD, $contrasenaBD) {
        //establecer conexion
        $conexion = new mysqli($_SERVER["nombreServidor"], $usuarioBD, $contrasenaBD, $_SERVER["baseDatos"]);

        //validar conexion
        if (!$conexion || $conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        return $conexion;
    }

    function consulta($usuarioBD, $contrasenaBD, $consulta) {
        //comprobar conexion
        $conexion = conectar($usuarioBD, $contrasenaBD);
       
        if(!$conexion) {
            return false;
        }

        //retorno del resultado
        $resultado = false;

        try {
            if (isset($consulta) && !empty($consulta)) {
                $resultado = $conexion->query($consulta);
            } else {
                echo "consulta vacia";
                return false;
            }
        } catch (Exception $e) {
            echo "error en la consulta";
            return false;
        }

        $conexion->close();


        return $resultado;
    }

    /*
    DEBUG

    $res = consulta("clienteTest", "123", "SELECT * FROM `persona`");

    if ($res) {
        while($row = $res->fetch_assoc()) {
            var_dump($row);
        }
    }
    */
?>