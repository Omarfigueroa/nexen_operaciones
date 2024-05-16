<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 

    $query="SELECT DISTINCT R.id_Movimiento,C.NOMBRE_BANCO,R.Cuenta,R.Clabe,R.SWT_ABBA,R.Banco_Intermediario
            FROM Razon_Bancos R 
                INNER JOIN Proveedores_Cuentas P on P.id_Razon=R.id_razon_social 
                INNER JOIN Catalogo_Bancos AS C ON C.ID_BANCO=R.Id_banco
            WHERE R.id_razon_social=".$_GET['idRazon'];

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