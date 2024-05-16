<?php

date_default_timezone_set('America/Mexico_City');


session_start();
require '../conexion/bd.php';



    // Ejemplo de cómo podrías obtener los datos de la base de datos
    $stmt = $conn_bd->prepare("SELECT * FROM [dbo].[Log_Solicitud_Pagos] ORDER BY Id_Operacion DESC");


    $stmt->execute();
    $LogdetallePagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
    // Retorna los datos en formato JSON
    echo json_encode(array('success'=> true,'data' => $LogdetallePagos));

?>