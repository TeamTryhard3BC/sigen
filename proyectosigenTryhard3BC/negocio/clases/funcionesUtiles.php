<?php
include_once("../objetos/Usuario.php");
include_once("../objetos/Persona.php");
include_once("../../datos/conexion/PuenteMySQL.php");

class funcionesUtiles
{
    public function __construct()
    {
    }

    /**
     * Función anónima con el objetivo de retornar los datos del usuario.
     * No recibe ningún parámetro.
     * @return array | bool Devuelve un array o booleano.
     */

    public function getDatosUsuario(): array|bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION["usuario"])) {
            return false;
        }

        return $_SESSION["usuario"]->jsonSerialize();
    }

    public function getConfiguracionGimnasio(): array|bool
    {
        $rutaArchivo = "../../datos/config.json";

        if (!file_exists($rutaArchivo)) {
            return false;
        }

        $json = file_get_contents($rutaArchivo);
        $data = json_decode($json, true);

        return $data;
    }

    public function getDatosPersona(): array|bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION["persona"])) {
            return false;
        }

        return $_SESSION["persona"]->jsonSerialize();
    }

    public function getDatosTabla(): callable|string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $tablaSolicitada = $_POST["tabla"];

        $solicitudesAceptadas = [
            "ejercicio",
            "combo",
            "grupomuscular",
            "forma"
        ];

        if (isset($_POST["tabla"])) {
            if (in_array($tablaSolicitada, $solicitudesAceptadas)) {
                //lo puse aca pq no agarraba el rootUser ni el rootPass si lo incluia al principio del archivo
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM $tablaSolicitada";
                $result = $conn->query($sql);

                $datos = array();

                if ($result && $result->num_rows > 0) {
                    // Guardar los resultados en un array
                    while ($row = $result->fetch_assoc()) {
                        $datos[] = $row;
                    }
                }

                $conn->close();

                header('Content-Type: application/json');
                return json_encode($datos);
            } else {
                return "No puedes acceder a esta seccion";
            }
        } else {
            return "Faltan parametros.";
        }
    }

    public function crearEnTabla(): array|bool|string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $tablaSolicitada = $_POST["tabla"];

        $solicitudesAceptadas = [
            "ejercicio" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste los datos."];
                }
                if (
                    !isset($datos["nombreEjercicio"])
                    || !isset($datos["descripcion"])
                    || !isset($datos["musculoTrabajado"])
                ) {
                    return [false, "Faltaron algunos campos."];
                }

                $nombreEjercicio = $datos["nombreEjercicio"];
                $descripcion = $datos["descripcion"];
                $musculoTrabajado = $datos["musculoTrabajado"];

                if (
                    gettype($nombreEjercicio) != "string"
                    || gettype($descripcion) != "string"
                    || gettype($musculoTrabajado) != "string"
                ) {
                    return [false, "Datos invalidos"];
                }

                $countQuery = "
                        SELECT COUNT(*) AS total FROM ejercicio
                    ";
                $resultado = consultaServidor($countQuery);

                if ($resultado) {
                    $cantidadEjercicios = $resultado->fetch_assoc()['total'];
                    if (is_numeric($cantidadEjercicios)) {
                        $codigoEjercicio = $cantidadEjercicios + 1;

                        $checkMusculo = "
                                SELECT COUNT(*) AS musculosCoincidentes FROM grupomuscular
                                WHERE grupomuscular.nombreMusculo = '$musculoTrabajado'
                            ";
                        $comparacion = consultaServidor($checkMusculo);

                        if ($comparacion && $row = $comparacion->fetch_assoc()) {
                            $musculosCoincidentes = $row["musculosCoincidentes"];

                            if ($musculosCoincidentes > 0) {
                                $countQuery = "
                                        SELECT COUNT(*) AS total FROM ejercicio
                                        WHERE ejercicio.nombreEjercicio = '$nombreEjercicio' AND ejercicio.musculoTrabajado = '$musculoTrabajado'
                                    ";
                                $resultado = consultaServidor($countQuery);

                                if ($resultado && $row = $resultado->fetch_assoc()) {
                                    $total = $row["total"];

                                    if ($total > 0) {
                                        return [false, "ya taba creado papi"];
                                    } else {
                                        $cargarEjercicio = "
                                                INSERT INTO ejercicio (codigoEjercicio, nombreEjercicio, descripcion, musculoTrabajado) 
                                                SELECT '$codigoEjercicio', '$nombreEjercicio', '$descripcion', '$musculoTrabajado'
                                                WHERE NOT EXISTS (
                                                    SELECT 1
                                                    FROM ejercicio
                                                    WHERE nombreEjercicio = '$nombreEjercicio' AND musculoTrabajado = '$musculoTrabajado'
                                                );
                                            ";

                                        $resultadoCargar = consultaServidor($cargarEjercicio);
                                        if ($resultadoCargar) {
                                            $cargarTrabaja = "
                                            INSERT INTO trabaja (codigoEjercicio, nombreMusculo) VALUES ('$codigoEjercicio', '$musculoTrabajado');
                                    ";   
                                        $resultadoCargarTrabaja = consultaServidor($cargarTrabaja);
                                        if($resultadoCargarTrabaja){
                                            return [true, "creado con exito"];
                                        }else{
                                            return [false, "No se pudo crear en la relacion trabaja"];
                                        }
                                        } else {
                                            return [false, "no se pudo crear el ejercicio"];
                                        }
                                    }
                                } else {
                                    return [false, "fallo en la consulta"];
                                }
                            } else {
                                return [false, "Ese musculo no existe."];
                            }
                        } else {
                            return [false, "El musculo no existe."];
                        }
                    }
                }
            },
            "combo" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste los datos"];
                }
                if (
                    !isset($datos["nombreCombo"])
                    || !isset($datos["descripcion"])
                    || !isset($datos["ejercicios"])
                ) {
                    return [false, "Faltaron algunos datos"];
                }

                $nombreCombo = $datos["nombreCombo"];
                $descripcion = $datos["descripcion"];
                $ejercicios = $datos["ejercicios"];
                if (gettype($nombreCombo) != "string" || gettype($descripcion) != "string") {
                    return [false, "Fallaste introduciendo los datos"];
                }
                if (count($ejercicios) <= 0) {
                    return [false, "Elige almenos 1 ejercicio"];
                }

                $codigoCombo = null;

                $countQuery = "
                        SELECT COUNT(*) AS total FROM combo
                    ";
                $resultado = consultaServidor($countQuery);

                if ($resultado) {
                    $cantidadCombos = $resultado->fetch_assoc()['total'];
                    if (is_numeric($cantidadCombos)) {
                        $codigoCombo = $cantidadCombos + 1;

                        $countQuery = "
                                SELECT COUNT(*) AS total FROM combo
                                WHERE combo.nombreCombo = '$nombreCombo'
                            ";
                        $resultado = consultaServidor($countQuery);

                        if ($resultado && $row = $resultado->fetch_assoc()) {
                            $total = $row["total"];

                            if ($total > 0) {
                                return [false, "El combo ya existe"];
                            } else {
                                $cargarCombo = "
                                        INSERT INTO combo (codigoCombo, nombreCombo, descripcion) 
                                        SELECT '$codigoCombo', '$nombreCombo', '$descripcion'
                                        WHERE NOT EXISTS (
                                            SELECT 1
                                            FROM ejercicio
                                            WHERE nombreEjercicio = '$nombreCombo'
                                        );
                                    ";
                                $resultadoCargar = consultaServidor($cargarCombo);
                                if (!$resultadoCargar) {
                                    
                                    return [false, "No se pudo crear el combo"];
                                }
                            }
                        } else {
                            return [false, "Fallo en la consulta"];
                        }
                    }
                }

                $result = [true, "Cargado con exito"];

                foreach ($ejercicios as $codigoEjercicio) {
                    $checkEjercicio = "
                            SELECT COUNT(*) AS ejerciciosCoincidentes FROM ejercicio
                            WHERE ejercicio.codigoEjercicio = '$codigoEjercicio'
                        ";
                    $comparacion = consultaServidor($checkEjercicio);

                    if ($comparacion && $row = $comparacion->fetch_assoc()) {
                        $ejerciciosCoincidentes = $row["ejerciciosCoincidentes"];

                        if ($ejerciciosCoincidentes > 0) {
                            $cargarForma = "
                                    INSERT INTO forma (codigoEjercicio, codigoCombo) 
                                    SELECT '$codigoEjercicio', '$codigoCombo'
                                    WHERE NOT EXISTS (
                                        SELECT 1
                                        FROM forma
                                        WHERE codigoEjercicio = '$codigoEjercicio' AND codigoCombo = '$codigoCombo'
                                    );
                                ";
                            $resultadoCargar = consultaServidor($cargarForma);
                            if (!$resultadoCargar) {
                                $result = [false, "El ejercicio ($codigoEjercicio) no se pudo cargar al combo"];
                            }
                        } else {
                            $result = [false, "como osas enviar un ejercicio fake? PUM BANEADO ($codigoEjercicio)"];
                        }
                    } else {
                        $result = [false, "Fallo en la consulta."];
                    }
                }

                return $result;
            },
            "grupomuscular" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste algunos datos"];
                }
                if (!isset($datos["nombreMusculo"])) {
                    return [false, "Faltaron datos"];
                }

                $nombreMusculo = $datos["nombreMusculo"];
                if (gettype($nombreMusculo) != "string") {
                    return [false, "Datos invalidos"];
                }

                $checkMusculo = "
                        SELECT COUNT(*) AS musculosCoincidentes FROM grupomuscular
                        WHERE grupomuscular.nombreMusculo = '$nombreMusculo'
                    ";
                $comparacion = consultaServidor($checkMusculo);

                if ($comparacion && $row = $comparacion->fetch_assoc()) {
                    $musculosCoincidentes = $row["musculosCoincidentes"];

                    if ($musculosCoincidentes > 0) {
                        return [false, "El musculo ya existe en la base de datos"];
                    } else {
                        $cargarGrupo = "
                                INSERT INTO grupomuscular (nombreMusculo) 
                                SELECT '$nombreMusculo'
                                WHERE NOT EXISTS (
                                    SELECT 1
                                    FROM grupomuscular
                                    WHERE nombreMusculo = '$nombreMusculo'
                                );
                            ";
                        $resultadoCargar = consultaServidor($cargarGrupo);
                        if ($resultadoCargar) {
                            return [true, "creado con exito"];
                        } else {
                            return [false, "no se pudo crear el grupo muscular"];
                        }
                    }
                } else {
                    return [false, "fallo en la consulta"];
                }
            },
        ];

        if (isset($_POST["tabla"])) {
            $tablaSolicitada = $_POST["tabla"];

            if (isset($solicitudesAceptadas[$tablaSolicitada])) {
                return $solicitudesAceptadas[$tablaSolicitada]();
            } else {
                return "no tienes acceso a esto";
            }
        } else {
            return "no se recibio parametros";
        }
    }

    public function modificarEnTabla(): array|bool|string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $tablaSolicitada = $_POST["tabla"];

        $solicitudesAceptadas = [
            "ejercicio" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste los datos"];
                }
                if (
                    !isset($datos["codigoEjercicio"])
                    || !isset($datos["nombreEjercicio"])
                    || !isset($datos["descripcion"])
                    || !isset($datos["musculoTrabajado"])
                ) {
                    return [false, "Faltan datos"];
                }

                $codigoEjercicio = $datos["codigoEjercicio"];
                $nombreEjercicio = $datos["nombreEjercicio"];
                $descripcion = $datos["descripcion"];
                $musculoTrabajado = $datos["musculoTrabajado"];

                if (
                    !is_numeric($codigoEjercicio)
                    || gettype($nombreEjercicio) != "string"
                    || gettype($descripcion) != "string"
                    || gettype($musculoTrabajado) != "string"
                ) {
                    return [false, "Datos invalidos"];
                }

                $countQuery = "
                        SELECT COUNT(*) AS total FROM ejercicio
                        WHERE ejercicio.codigoEjercicio = '$codigoEjercicio'
                    ";

                $resultado = consultaServidor($countQuery);

                if ($resultado && $row = $resultado->fetch_assoc()) {
                    $total = $row["total"];

                    if ($total > 0) {
                        $updatearEjercicio = "
                                UPDATE ejercicio 
                                SET nombreEjercicio = '$nombreEjercicio', descripcion = '$descripcion', musculoTrabajado = '$musculoTrabajado'
                                WHERE codigoEjercicio = '$codigoEjercicio';
                            ";

                        $resultadoCargar = consultaServidor($updatearEjercicio);
                        if ($resultadoCargar) {
                            return [true, "Actualizado con exito"];
                        } else {
                            return [false, "No se actualizo el ejercicio"];
                        }
                    } else {
                        return [false, "No existe el ejercicio"];
                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }
            },
            "combo" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste los datos"];
                }
                if (
                    !isset($datos["codigoCombo"])
                    || !isset($datos["nombreCombo"])
                    || !isset($datos["descripcion"])
                    || !isset($datos["ejercicios"])
                ) {
                    return [false, "Faltaron datos"];
                }

                $codigoCombo = $datos["codigoCombo"];
                $nombreCombo = $datos["nombreCombo"];
                $descripcion = $datos["descripcion"];
                $ejercicios = $datos["ejercicios"];
                if (
                    !is_numeric($codigoCombo)
                    || gettype($nombreCombo) != "string"
                    || gettype($descripcion) != "string"
                ) {
                    return [false, "Datos invalidos"];
                }
                if (count($ejercicios) <= 0) {
                    return [false, "Debes seleccionar almenos 1 ejercicio"];
                }

                $countQuery = "
                        SELECT COUNT(*) AS total FROM combo
                        WHERE combo.codigoCombo = '$codigoCombo'
                    ";
                $resultado = consultaServidor($countQuery);

                if ($resultado && $row = $resultado->fetch_assoc()) {
                    $total = $row["total"];

                    if ($total > 0) {
                        $cargarCombo = "
                                UPDATE combo
                                SET nombreCombo = '$nombreCombo', descripcion = '$descripcion'
                                WHERE codigoCombo = '$codigoCombo';
                            ";

                        $resultadoCargar = consultaServidor($cargarCombo);
                        if (!$resultadoCargar) {
                            return [false, "No se pudo actualizar el combo"];
                        }
                    } else {
                        return [false, "No existe el combo"];

                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }

                $wipearForma = "
                        DELETE FROM forma
                        WHERE codigoCombo = '$codigoCombo';
                    ";

                $result = [true, "cargado con exito!!"];
                $resultadoWipearForma = consultaServidor($wipearForma);

                if (!$resultadoWipearForma) {
                    $result = [false, "No se pudo eliminar la relacion Forma"];
                } else {
                    foreach ($ejercicios as $codigoEjercicio) {
                        $checkEjercicio = "
                                SELECT COUNT(*) AS ejerciciosCoincidentes FROM ejercicio
                                WHERE ejercicio.codigoEjercicio = '$codigoEjercicio'
                            ";
                        $comparacion = consultaServidor($checkEjercicio);

                        if ($comparacion && $row = $comparacion->fetch_assoc()) {
                            $ejerciciosCoincidentes = $row["ejerciciosCoincidentes"];

                            if ($ejerciciosCoincidentes > 0) {
                                $cargarForma = "
                                        INSERT INTO forma (codigoEjercicio, codigoCombo) 
                                        SELECT '$codigoEjercicio', '$codigoCombo'
                                        WHERE NOT EXISTS (
                                            SELECT 1
                                            FROM forma
                                            WHERE codigoEjercicio = '$codigoEjercicio' AND codigoCombo = '$codigoCombo'
                                        );
                                    ";

                                $resultadoCargar = consultaServidor($cargarForma);
                                if (!$resultadoCargar) {
                                    $result = [false, "No se pudo cargar ($codigoEjercicio) al combo"];
                                }
                            } else {
                                $result = [false, "No existe el ($codigoEjercicio)"];
                            }
                        } else {
                            $result = [false, "Fallo en la consulta"];
                        }
                    }
                }

                return $result;
            },
            "grupomuscular" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Datos incompletos"];
                }
                if (
                    !isset($datos["nombreMusculo"])
                    || !isset($datos["nombreMusculoOG"])
                ) {
                    return [false, "Faltan datos"];
                }

                $nombreMusculoOG = $datos["nombreMusculoOG"];
                $nombreMusculo = $datos["nombreMusculo"];
                if (gettype($nombreMusculo) != "string") {
                    return [false, "Datos invalidos"];
                }

                $checkMusculo = "
                        SELECT COUNT(*) AS musculosCoincidentes FROM grupomuscular
                        WHERE grupomuscular.nombreMusculo = '$nombreMusculoOG'
                    ";
                $comparacion = consultaServidor($checkMusculo);

                if ($comparacion && $row = $comparacion->fetch_assoc()) {
                    $musculosCoincidentes = $row["musculosCoincidentes"];

                    if ($musculosCoincidentes > 0) {
                        $editarMusculo = "
                                UPDATE grupomuscular
                                SET nombreMusculo = '$nombreMusculo'
                                WHERE nombreMusculo = '$nombreMusculoOG';
                            ";
                        $editarMusculoEjercicio = "
                                UPDATE Ejercicio
                                SET musculoTrabajado = '$nombreMusculo'
                                WHERE musculoTrabajado = '$nombreMusculoOG';
                            ";

                        $resultadoEdit = consultaServidor($editarMusculo);
                        if ($resultadoEdit) {
                            consultaServidor($editarMusculoEjercicio);
                            return [true, "editado con exito"];
                        } else {
                            return [false, "no se pudo actualizar el grupo muscular"];
                        }
                    } else {
                        return [false, "El musculo no existe"];
                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }
            },
        ];

        if (isset($_POST["tabla"])) {
            $tablaSolicitada = $_POST["tabla"];

            if (isset($solicitudesAceptadas[$tablaSolicitada])) {
                return $solicitudesAceptadas[$tablaSolicitada]();
            } else {
                return "No tienes acceso a la seccion";
            }
        } else {
            return "no se recibieron parametros";
        }
    }

    public function eliminarEnTabla(): array|bool|string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $tablaSolicitada = $_POST["tabla"];

        $solicitudesAceptadas = [
            "ejercicio" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste introducir datos"];
                }
                if (!isset($datos["codigoEjercicio"])) {
                    return [false, "Faltan datos"];
                }

                $codigoEjercicio = $datos["codigoEjercicio"];
                if (!is_numeric($codigoEjercicio)) {
                    return [false, "Datos invalidos"];
                }

                $checkEjercicio = "
                        SELECT COUNT(*) AS ejerciciosCoincidentes FROM ejercicio
                        WHERE ejercicio.codigoEjercicio = '$codigoEjercicio'
                    ";
                $comparacion = consultaServidor($checkEjercicio);

                if ($comparacion && $row = $comparacion->fetch_assoc()) {
                    $ejerciciosCoincidentes = $row["ejerciciosCoincidentes"];

                    if ($ejerciciosCoincidentes > 0) {
                        $wipearForma = "
                                DELETE FROM forma
                                WHERE codigoEjercicio = '$codigoEjercicio';
                            ";
                        $resultadoForma = consultaServidor($wipearForma);

                        if ($resultadoForma) {
                            $wipearEjercicio = "
                                    DELETE FROM ejercicio
                                    WHERE codigoEjercicio = '$codigoEjercicio';
                                ";

                            $resultadoWipear = consultaServidor($wipearEjercicio);
                            if ($resultadoWipear) {
                                return [true, "Ejercicio eliminado con exito"];
                            } else {
                                return [false, "No se pudo eliminar el ejercicio"];
                            }
                        }
                    } else {
                        return [false, "El ejercicio no existe, no se puede eliminar"];
                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }
            },
            "combo" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Olvidaste los datos"];
                }
                if (!isset($datos["codigoCombo"])) {
                    return [false, "Faltaron algunos datos"];
                }

                $codigoCombo = $datos["codigoCombo"];
                if (!is_numeric($codigoCombo)) {
                    return [false, "Fallaste al introducir los datos"];
                }

                $checkCombo = "
                    SELECT COUNT(*) AS combosCoincidentes FROM combo
                    WHERE combo.codigoCombo = '$codigoCombo'
                ";
                $comparacion = consultaServidor($checkCombo);

                if ($comparacion && $row = $comparacion->fetch_assoc()) {
                    $combosCoincidentes = $row["combosCoincidentes"];

                    if ($combosCoincidentes > 0) {
                        $wipearForma = "
                            DELETE FROM forma
                            WHERE codigoCombo = '$codigoCombo';
                        ";

                        $resultadoWipearForma = consultaServidor($wipearForma);
                        if ($resultadoWipearForma) {
                            $wipearRealizaCombo = "
                                DELETE FROM realizacombo
                                WHERE id = '$codigoCombo';
                            ";

                            $resultadoWipearRealizaCombo = consultaServidor($wipearRealizaCombo);
                            if ($resultadoWipearRealizaCombo) {
                                $wipearCompone = "
                                    DELETE FROM compone
                                    WHERE codigoCombo = '$codigoCombo';
                                ";

                                $resultadoWipearCompone = consultaServidor($wipearCompone);
                                if ($resultadoWipearCompone) {
                                    $wipearCombo = "
                                        DELETE FROM combo
                                        WHERE codigoCombo = '$codigoCombo';
                                    ";

                                    $resultadoWipear = consultaServidor($wipearCombo);
                                    if ($resultadoWipear) {
                                        return [true, "Eliminado con éxito"];
                                    } else {
                                        return [false, "no se pudo Eliminar el combo"];
                                    }
                                } else {
                                    return [false, "no se pudo eliminar de compone"];
                                }
                            } else {
                                return [false, "no se pudo eliminar de realizacombo"];
                            }
                        } else {
                            return [false, "no se pudo eliminar a los ejercicios de forma"];
                        }
                    } else {
                        return [false, "El combo no existe"];
                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }
            },
            "grupomuscular" => function () {
                $conn = conectar($_SERVER["rootName"], $_SERVER["rootPass"]);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $datos = $_POST["datos"];
                if (!isset($datos)) {
                    return [false, "Faltan datos"];
                }
                if (!isset($datos["nombreMusculo"])) {
                    return [false, "Faltaron algunos datos"];
                }

                $nombreMusculo = $datos["nombreMusculo"];
                if (gettype($nombreMusculo) != "string") {
                    return [false, "Erraste en algunos datos"];
                }

                $checkMusculo = "
                        SELECT COUNT(*) AS musculosCoincidentes FROM grupomuscular
                        WHERE grupomuscular.nombreMusculo = '$nombreMusculo'
                    ";
                $comparacion = consultaServidor($checkMusculo);

                if ($comparacion && $row = $comparacion->fetch_assoc()) {
                    $musculosCoincidentes = $row["musculosCoincidentes"];

                    if ($musculosCoincidentes > 0) {
                        $wipearMusculo = "
                                DELETE FROM grupomuscular
                                WHERE nombreMusculo = '$nombreMusculo';
                            ";

                        $resultadoWipear = consultaServidor($wipearMusculo);
                        if ($resultadoWipear) {
                            return [true, "wipeado con exito"];
                        } else {
                            return [false, "no se pudo eliminar el grupo muscular"];
                        }
                    } else {
                        return [false, "El musculo no existe"];
                    }
                } else {
                    return [false, "Fallo en la consulta"];
                }
            },
        ];

        if (isset($_POST["tabla"])) {
            $tablaSolicitada = $_POST["tabla"];

            if (isset($solicitudesAceptadas[$tablaSolicitada])) {
                return $solicitudesAceptadas[$tablaSolicitada]();
            } else {
                return "no puede acceder a la seccion";
            }
        } else {
            return "no se recibieron parametros";
        }
    }

}
?>