<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 
    $anio='2023';
    $query= "SELECT  id id_doc, Nombre_Archivo, Mes, Anio, Tipo_Documento 
             FROM    Proveedor_Documentos
             WHERE   Anio='$anio' and Id_Proveedor=".$_GET['idProveedor'];

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