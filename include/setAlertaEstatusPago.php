<?php

date_default_timezone_set('America/Mexico_City');
session_start();

require '../conexion/bd.php';





    $Num_Operacion = $_POST['Num_Operacion'];
    

    // Realizar la conexión a la base de datos aquí, asumiendo que tienes $conn_bd como la conexión.

    try {
        // Inicio de la transacción
        $conn_bd->beginTransaction();

        // Consulta de actualización
        $stmt_update = $conn_bd->prepare("UPDATE [dbo].[FK_Solicitud_Pago] SET Estatus_Alerta_Pago = 0 WHERE Num_Operacion = :Num_Operacion");
 
        $stmt_update->bindParam(':Num_Operacion', $Num_Operacion);
        $stmt_update->execute();


        // Commit de la transacción
        $conn_bd->commit();



        // Respuesta exitosa
        echo json_encode(array('success' => true, 'message' => $Num_Operacion.' CHECK!'));

        

    } catch (PDOException $e) {
        // En caso de error, deshacer la transacción
        $conn_bd->rollback();

        // Respuesta de error con el mensaje de la excepción
        echo json_encode(array('success' => false, 'message' => 'Error en la transacción:  '. $e->getMessage()));
    }


?>