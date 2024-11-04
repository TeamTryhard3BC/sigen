<?php 
    include_once("../../datos/conexion/PuenteMySQL.php"); 

    $conn = conectar($rootUser, $rootPass); 

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos de cupos disponibles
    $sql = "SELECT diaSemana, cuposDisponibles FROM agenda"; 
    $result = $conn->query($sql);

    $cupos = array();

    if ($result && $result->num_rows > 0) {
        // Guardar los resultados en un array
        while ($row = $result->fetch_assoc()) {
            $cupos[$row['diaSemana']] = $row['cuposDisponibles'];
        }
    } else {
        $cupos['error'] = 'No hay datos disponibles';
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($cupos);
?>