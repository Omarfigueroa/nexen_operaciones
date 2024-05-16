<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 

    $query="SELECT ID_BANCO,NOMBRE_BANCO
            FROM Catalogo_Bancos 
            WHERE ESTATUS='A'";

    //echo $query;

    $consultar=$conn_bd->prepare($query);
    if($consultar){
        $consultar -> execute();
        $row= $consultar -> fetchAll(PDO::FETCH_ASSOC);
        $response = array("success" => true, "datos" => $row);
        echo json_encode($response); 
        die;
    }else{
        echo json_encode(["success" => false, "datos" => "No se pudo realizar la consulta"]);
        die();
        //echo "No se pudo realizar la consulta"; 
    }        
    