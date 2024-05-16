<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 

    $query= "SELECT  id_razon, Razon_Social, CURP, RFC, Tipo_Persona, Tipo_Servicio 
             FROM Proveedores_Cuentas";

    //echo $query;

        $consultar=$conn_bd->prepare($query);
        if($consultar){
            $consultar -> execute();
            $row= $consultar -> fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($row); 
            die;
        }else{
            echo "No se pudo realizar la consulta"; 
        }        