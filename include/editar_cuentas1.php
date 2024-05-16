<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 
        /*
        if ( isset($_POST['idMovimiento']) && !empty($_POST['idMovimiento']) &&
             isset($_POST['Banco']) && !empty($_POST['Banco']) &&
             isset($_POST['Cuenta']) && !empty($_POST['Cuenta']) &&
             isset($_POST['Clabe']) && !empty($_POST['Clabe']) &&
             isset($_POST['Swt']) && !empty($_POST['Swt']) &&
             isset($_POST['Intermediario']) && !empty($_POST['Intermediario'])
            ) {
        */

        if(isset($_POST)){

        $data = file_get_contents("php://input");
        $params=json_decode($data,true);

        $idMovimiento=$params['idMovimiento'];
        $Banco=$params['Banco'];
        $Cuenta=$params['Cuenta'];
        $Clabe=$params['Clabe'];
        $Swt=$params['Swt'];
        $Intermediario=$params['Intermediario'];

            
        $sql_modificar="UPDATE Razon_Bancos
                        SET Id_banco=".$Banco.",
                            Cuenta='".$Cuenta."',
                            Clabe='".$Clabe."',
                            SWT_ABBA='".$Swt."',
                            Banco_Intermediario='".$Intermediario."'
                        WHERE id_Movimiento=".$idMovimiento;     
                              

            try{

                $guardar=$conn_bd->prepare($sql_modificar);
                $guardar->execute();
                echo json_encode(["success" => true, "message" => "Cuenta modificada con exito."]);
                die();

            }catch(PDOException $e){
                echo json_encode(["success" => false, "message" => "No se pudo Modificar la cuenta: ".$e->getMessage()]);
                die();
            }

        }else{
            echo json_encode(["success" => false, "message" => "Parametros POST Incorrectos"]);
            die();
        }
