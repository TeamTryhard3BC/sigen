<?php
    //MySQLi es necesario para que la conexion funcione (https://www.w3schools.com/php/php_mysql_connect.asp)
    include("configuracion.php");

    /**
     * Función con el objetivo de establecer una conexion con la base de datos,
     * el objetivo principal de esta función es ser utilizada por otras funciones que lo requieran.
     * Recibe dos strings como parametros, representando el usuario y contraseña
     * del usuario que establecerá la conexión.
     * @return bool|mysqli Devuelve un bool o un objeto mysqli.
    */
    function conectar($usuarioBD, $contrasenaBD) {
        //Variables definidas en configuracion.php
        global $bd, $server;
        $conexion = new mysqli($server, $usuarioBD, $contrasenaBD);
    
        //Fallo la conexion
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        
        try {
            //Comprobamos que exista la base de datos
            if (isset($bd)) {
                //Creamos la base de datos si no existe
                $sql = "CREATE DATABASE IF NOT EXISTS " . $bd;
                //Realizamos la consulta
                if($conexion->query($sql)){
                    //Seguimos, pero ahora utilizando la nueva base de datos
                    $conexion->select_db($bd);
                }
            } else {
                echo "consulta vacia";
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }

        return $conexion;
    }

    /**
     * Función con el objetivo de llevar a cabo las distintas consultas a la base de datos.
     * Recibe tres strings como parametros, representando el usuario y contraseña
     * del usuario que establecerá la conexión tanto como la consulta a realizar.
     * @return bool|mysqli Devuelve un bool o un objeto mysqli_result.
    */
    function consulta($usuarioBD, $contrasenaBD, $consulta) {
        $conexion = conectar($usuarioBD, $contrasenaBD);
       
        if(!$conexion) { return false; }
        $resultado = false;

        try {
            if (isset($consulta) && !empty($consulta)) {
                $resultado = $conexion->query($consulta);
            } else {
                echo "consulta vacia";
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }

        $conexion->close();
        return $resultado;
    }
    
    /**
     * Función con el objetivo de llevar a cabo consultas multiples a la base de datos.
     * Recibe tres strings como parametros, representando el usuario y contraseña
     * del usuario que establecerá la conexión tanto como la consulta a realizar.
     * @return bool Devuelve un bool.
    */
    function consulta_multiple($usuarioBD, $contrasenaBD, $consulta): bool {
        $conexion = conectar($usuarioBD, $contrasenaBD);
        $resultado = null;

        if(!$conexion) { return false; }

        try {
            if (isset($consulta) && !empty($consulta)) {
                $resultado = $conexion->multi_query($consulta);
            } else {
                echo "consulta vacia";
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }

        $conexion->close();
        return $resultado;
    }

    /**
     * Función con el objetivo de llevar a cabo las distintas consultas utilizando el usuario root,
     * especificado en el fichero configuracion.php del mismo directorio.
     * Recibe un string que representa la consulta y un bool que representa si la consulta a
     * realizar debe ser múltiple o no.
     * @return bool|mysqli Devuelve un bool o un objeto mysqli_result.
    */
    function consultaServidor($consulta, $multiple=false): bool|mysqli_result {
        //Variables definidas en configuracion.php
        global $rootUser, $rootPass;

        //Intentamos establecer una conexión
        $conexion = conectar($rootUser, $rootPass);
       
        if(!$conexion) { return false; }
        $resultado = false;

        if(!$multiple) {    
            try {
                //Verificamos que las variables sean validas
                if (isset($consulta) && !empty($consulta)) {
                    //Realizamos la consulta
                    $resultado = $conexion->query($consulta);
                } else {
                    echo "consulta vacia";
                    return false;
                }
            } catch (Exception $e) {
                echo $e;
                return false;
            }
    
            $conexion->close();
        } else {
            try {
                //Verificamos que las variables sean validas
                if (isset($consulta) && !empty($consulta)) {
                    //Realizamos la consulta
                    $resultado = $conexion->multi_query($consulta);
                } else {
                    echo "consulta multiple vacia";
                    return false;
                }
            } catch (Exception $e) {
                echo $e;
                return false;
            }
    
            $conexion->close();
        }

        return $resultado;
    }

    /**
     * Función con el objetivo de llevar a cabo las distintas consultas utilizando tanto un posible
     * usuario root, como un usuario cualquiera.
     * Recibe un string que representa la consulta y un array que contendrá el usuarioBD y la contrasenaBD en
     * caso de querer hacer una consulta como un usuario cualquiera y un parametro "multiple" que indicará
     * si la consulta a realizar debe ser múltiple o no.
     * @return array|bool Devuelve un array o un bool.
    */
    function fetch($consulta, $parametros) {
        //Variables definidas en configuracion.php
        global $rootUser, $rootPass;
        $conexion = false;

        if(isset($parametros["usuarioBD"])) {
            //Intentamos establecer una conexión
            $conexion = conectar($parametros["usuarioBD"], $parametros["contrasenaBD"]);
        } else {
            //Intentamos establecer una conexión
            $conexion = conectar($rootUser, $rootPass);
        }
       
        if(!$conexion) { return false; }

        if($parametros["multiple"]) {  
            $resultado = [];

            try {
                //Verificamos que las variables sean validas
                if (isset($consulta) && !empty($consulta)) {
                    //Realizamos la consulta
                    if($conexion->multi_query($consulta)) {
                        do {
                            if ($res = $conexion->use_result()) {
                                while ($row = $res->fetch_assoc()) {
                                    //La guardamos en un arreglo a retornar
                                    $resultado[] = $row;
                                }
                            }
                            //Repetimos hasta que no existan mas resultados de la consulta múltiple
                        } while ($conexion->more_results() && $conexion->next_result());
                    }
                }
            } catch (Exception $e) {
                echo $e;
            }
    
            $conexion->close();
        } else {
            $resultado = null;

            try {
                if (isset($consulta) && !empty($consulta)) {
                    if($res = $conexion->query($consulta)) {
                        $resultado = $res->fetch_assoc();
                    }
                }
            } catch (Exception $e) {
                echo $e;
            }

            $conexion->close();
        }

        return $resultado;
    }
?>