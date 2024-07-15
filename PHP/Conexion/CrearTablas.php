<?php
    include("Conexion\PuenteMySQL.php");

    $res = consulta("servidor", "root",
        "CREATE TABLE `persona` (
            `nombre` varchar(24) NOT NULL,
            `apellido` varchar(24) NOT NULL,
            `tipoDocumento` varchar(16) NOT NULL,
            `nroDocumento` int(16) NOT NULL,
            `fechaNacimiento` date NOT NULL,
            `contrasena` varchar(16) NOT NULL,
            `tipoUsuario` varchar(16) NOT NULL,
            `codigoPersona` int(6) NOT NULL
        )"
    );

    if (isset($res) && $res) {
        echo $res[0];
        //echo $res[1];
        echo "hola";
        
        //aca irian acciones extra que utilicen la conexion
        
        $res[1]->close();
    }
?>