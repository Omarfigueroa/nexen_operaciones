<?php
        require_once('../conexion/bd.php');

        // $datos = mysqli_query($conexion, $sql);
        // $arrData = mysqli_fetch_all($datos,MYSQLI_ASSOC);

        $query= "SELECT *
                FROM [dbo].[Fk_Log_Detalle_Ope_Nexen]";
        $consultar=$conn_bd->prepare($query);
        $consultar -> execute();
        $row= $consultar -> fetchAll(PDO::FETCH_ASSOC);

        /*
        $query = "SELECT * FROM [Candados_RFID].[dbo].[Empresas] WHERE Estado != 0";  
                $stmt = $base_de_datos->prepare( $query );  
                $stmt->execute();  
                $row = $stmt->fetchAll( PDO::FETCH_ASSOC );
        */

        echo json_encode($row); 
        die;