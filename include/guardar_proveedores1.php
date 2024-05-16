<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    require_once (CONEXION_PATH.'bd.php'); 

        if ( isset($_POST['razon_social']) && !empty($_POST['razon_social']) &&
             isset($_POST['alias']) && !empty($_POST['alias']) &&
             isset($_POST['tipo_persona']) && !empty($_POST['tipo_persona']) &&
             isset($_POST['tipo_servicio']) && !empty($_POST['tipo_servicio']) &&
             isset($_POST['curp']) && !empty($_POST['curp']) &&
             isset($_POST['rfc']) && !empty($_POST['rfc']) &&
             isset($_POST['id_razon']) && !empty($_POST['id_razon'])
            ) {
            
            $razon_social=$_POST['razon_social'];
            $alias=$_POST['alias'];
            $tipo_persona=$_POST['tipo_persona'];
            $tipo_servicio=$_POST['tipo_servicio'];
            $curp=$_POST['curp'];
            $rfc=$_POST['rfc'];
            $id_razon=$_POST['id_razon'];

            date_default_timezone_set('America/Mexico_City');
            $fecha_actual = date("Y-m-d");
            $hora_carga = date('His');
            
            $sql_modificar="UPDATE Proveedores_Cuentas
                            SET Razon_Social='".$razon_social."',
                                Alias='".$alias."',
                                Tipo_Persona='".$tipo_persona."',
                                Tipo_Servicio='".$tipo_servicio."',
                                CURP='".$curp."',
                                RFC='".$rfc."'
                            WHERE id_Razon=".$id_razon;       

            try{

                $guardar=$conn_bd->prepare($sql_modificar);
                $guardar->execute();
                echo json_encode(["success" => true, "message" => "Proveedor modificado con exito."]);
                die();

            }catch(PDOException $e){
                echo json_encode(["success" => false, "message" => "No se pudo Modificar el Proveedor: ".$e->getMessage()]);
                die();
            }

        }else{
            echo json_encode(["success" => false, "message" => "Parametros POST Incorrectos"]);
            die();
        }