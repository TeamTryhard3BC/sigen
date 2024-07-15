<?php
    session_start();

    $nombreReg = $_GET["nombre"];
    $apellidoReg = $_GET["apellido"];
    $tipoDocReg = $_GET["tipoDocumento"];
    $documentoReg = $_GET["documento"];
    $documentoReg = (int)$documentoReg;
    $fechaNacReg = $_GET["fechaNacimiento"];
    $contrasenaReg = $_GET["contrasena"];

    $check = false;

    //Comprobar bien todo en caso de q no sea una consulta, pasarlo por los objetos de la otra carpeta wip
    //ningun string puede devolver verdadero al comprobar si es un query de SQL o no

    if(!isset($nombreReg) || gettype($nombreReg) != "string" || (isset($nombreReg) && !$nombreReg)) {
        $check = true;
    }

    if(!isset($apellidoReg) || gettype($apellidoReg) != "string" || (isset($apellidoReg) && !$apellidoReg)) {
        $check = true;
    }

    if(!isset($tipoDocReg) || gettype($tipoDocReg) != "string" || (isset($tipoDocReg) && !$tipoDocReg)) {
        $check = true;
    }

    if(!isset($documentoReg) || gettype($documentoReg) != "integer" || (isset($documentoReg) && $documentoReg < 0)) {
        $check = true;
    }

    if(!isset($fechaNacReg) || gettype($fechaNacReg) != "string" || (isset($fechaNacReg) && !$fechaNacReg)) {
        $check = true;
    }

    if(!isset($contrasenaReg) || gettype($contrasenaReg) != "string" || (isset($contrasenaReg) && !$contrasenaReg)) {
        $check = true;
    }
    
    if($check) {
        header("Location: ../registro.html");
        exit;
    }

    //ACA VA LA LOGICA PARA SUBIRLO A LA BD
    //
    //
    //
    //retorno a la siguiente pagina correspondiente:

    header("Location: ../index.html");
    exit;
?>
