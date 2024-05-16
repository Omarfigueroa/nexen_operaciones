<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    //require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php');

    if(isset($_POST)){

        //$query="SELECT Empresa,Id_Empresa FROM Empresas";
        $query="SELECT  SP.Num_Operacion NO_SOLICITUD, 
                        CC.Concepto CONCEPTO,
                        SP.Cliente CLIENTE, 
                        SP.Operador OPERADOR, 
                        EB.Nombre_Cuenta+' - ('+EB.Banco+')' CUENTA_OPERADOR,
                        SP.Razon_Social_Receptora PROVEEDOR,
                        RB.Tipo_Cuenta+' - '+SP.Banco_Destino+' - ' +
                        (
                        CASE 
                            WHEN RB.Tipo_Cuenta='NACIONAL' AND (RB.Cuenta='' OR RB.Cuenta IS NULL) THEN RB.Clabe
                            WHEN RB.Tipo_Cuenta='NACIONAL' AND (RB.Cuenta!='' OR RB.Cuenta IS NOT NULL) THEN RB.Cuenta
                            WHEN RB.Tipo_Cuenta='INTERNACIONAL' THEN RB.SWT_ABBA
                        END
                        ) CUENTA_PROVEEDOR,
                        CC.Clave_Rastreo CLAVE_RASTREO,
                        CC.Monto_Cargo MONTO_CARGO
                FROM Fk_Contabilidad_Cargos CC
                    INNER JOIN FK_Solicitud_Pago SP ON CC.Id_Solicitud_Pago=SP.Num_Operacion
                    INNER JOIN FK_Empresas_Bancos EB ON CC.Id_cuenta_operador=EB.Id_Mov
                    INNER JOIN Razon_Bancos RB ON CC.id_cuenta_proveedor=RB.id_Movimiento";
        
        try{
            $request = $conn_bd->prepare($query);
            $request->execute();
            $arrData = $request-> fetchAll(PDO::FETCH_ASSOC);
            //$response = array("success" => true, "datos" => $arrData);
            echo json_encode($arrData);
            die();

        }catch(PDOException $e){
            echo json_encode(["success" => false, "datos" => "Error:"]);
        }
    }
            

?>