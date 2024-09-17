<?php
    //MySQLi es necesario para que la conexion funcione (https://www.w3schools.com/php/php_mysql_connect.asp)

    //En este archivo se encuentra la configuración principal de la base de datos,
    //el servidor al que se conecta, las llaves del usuario root y el nombre de la base de datos

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Reemplazar "localhost" con la direccion del servidor MySQL, en caso de ser local mantener intacto
    $_SERVER["nombreServidor"] = "localhost";
    $server = $_SERVER["nombreServidor"];

    //Reemplazar "sigen" con el nombre deseado de la base de datos en el servidor dado
    $_SERVER["baseDatos"] = "teamtryhard_sigen";
    $bd = $_SERVER["baseDatos"];

    //Reemplazar "admin" con el nombre del usuario administrador previamente definido en la base de datos
    $_SERVER["rootName"] = "teamtryhard_ti";
    $rootUser = $_SERVER["rootName"];

    //Reemplazar "admin" con la contraseña del usuario administrador previamente definido en la base de datos
    $_SERVER["rootPass"] = "teamtryhard_ti";
    $rootPass = $_SERVER["rootPass"];

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Reemplazar la URL con el directorio absoluto del fichero idioma.html en su servidor
    $URL = "http://localhost/proyectosigen/presentacion/html/idioma.html";
?>
