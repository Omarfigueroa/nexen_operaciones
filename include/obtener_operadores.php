<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    //require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php');

    if(isset($_POST)){

        $query="SELECT Razon_Social,ID_EMPRESA FROM EMPRESAS";
        
        try{
            $request = $conn_bd->prepare($query);
            $request->execute();
            $arrData = $request-> fetchAll(PDO::FETCH_ASSOC);
            $response = array("success" => true, "datos" => $arrData);
            echo json_encode($response);
            die();

        }catch(PDOException $e){
            echo json_encode(["success" => false, "datos" => "Error:"]);
        }
    }
            

?>