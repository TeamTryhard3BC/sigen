<?php
    //Archivo general para llamar a las diferentes funciones anónimas o clases de la carpeta negocio
    //Actúa como API, sirviendo de puente entre la logica y las solicitudes del cliente (AJAX)
    //Solo funcionará si existen las variables indicadas en la variable global $_POST

    $retorno = null;

    //Comprobamos que se esté llamando a un método de una clase especifica
    if(isset($_POST["funcion"]) && isset($_POST["clase"])) {  
        $funcion = $_POST["funcion"];
        $clase = $_POST["clase"];

        //Requerimos el archivo una vez para no causar errores la proxima vez que se requiera.
        //Solo le permitimos al cliente hacer llamadas a objetos de la carpeta clases por las dudas.
        include_once "../clases/$clase.php";

        //Creamos el objeto
        $entidad = new $clase();
        //Llamamos a su método con los parámetros de la variable $_POST, pues esta puede incluir N cantidad de parámetros
        $retorno = $entidad->{$funcion}();
    } else {
        //Si no es una clase especifica, comprobar que el archivo esté definido en la variable $_POST
        if(isset($_POST["fileName"])) {
            $fileName = $_POST["fileName"];

            //Requerimos el archivo una vez para no causar errores la proxima vez que se requiera y lo guardamos en una variable
            $module = include_once "../logica/$fileName.php";
            //Puesto que tenemos el archivo en una variable, y dicho archivo retorna una variable anónima, podemos llamarlo como una función
            $retorno = $module();
        } else {
            $retorno = "Error: No se pudo llamar a la función.";
        }
    }

    //Devolvemos la solicitud AJAX en forma de JSON para ser legible por el JavaScript
    echo json_encode($retorno);
    return;
?>