<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    //require_once (INCLUDE_PATH.'functions.php');
    //require_once (CONEXION_PATH.'bd.php');

    $sql_guardar="UPDATE FK_Solicitud_Pago SET Estatus='".$_POST['estatus']."' WHERE Num_Operacion=".$_POST['id_pago'];
    // echo json_encode(["success" => true, "message" => "Si entro: ".$sql_guardar]);
    // die();

    try{
        $guardar=$conn_bd->prepare($sql_guardar);
        $guardar->execute();

        echo json_encode(["success" => true, "message" => "Pago aceptado"]);
        die();

    }catch(PDOException $e){
        echo json_encode(["success" => false, "message" => "Error:"]);
    }
