<?php
    //Archivo general para llamar a las diferentes funciones anónimas o clases de la carpeta negocio
    //Actúa como API, sirviendo de puente entre la logica y las solicitudes del cliente (AJAX)
    //Solo funcionará si existen las variables indicadas en la variable global $_GET

    $retorno = null;

    //Comprobamos que se esté llamando a un método de una clase especifica
    if(isset($_GET["funcion"]) && isset($_GET["clase"])) {  
        $funcion = $_GET["funcion"];
        $clase = $_GET["clase"];

        //Requerimos el archivo una vez para no causar errores la proxima vez que se requiera
        include_once "../clases/$clase.php";

        //Creamos el objeto
        $entidad = new $clase($_GET);
        //Llamamos a su método con los parámetros de la variable $_GET, pues esta puede incluir N cantidad de parámetros
        $retorno = $entidad->{$funcion}();
    } else {
        //Si no es una clase especifica, comprobar que el archivo esté definido en la variable $_GET
        if(isset($_GET["fileName"])) {
            $fileName = $_GET["fileName"];

            //Requerimos el archivo una vez para no causar errores la proxima vez que se requiera y lo guardamos en una variable
            $module = include_once "../logica/$fileName.php";
            //Puesto que tenemos el archivo en una variable, y dicho archivo retorna una variable anónima, podemos llamarlo como una función
            $retorno = $module();
        } else {
            $retorno = "Error: Couldn't call function.";
        }
    }

    //Devolvemos la solicitud AJAX en forma de JSON para ser legible por el JavaScript
    echo json_encode($retorno);
    return;
?>