<?php
require '../conexion/bd.php';
include '../plantilla/alertas.php';
require '../include/validacion_usuarios.php';

    if($_SESSION['usuario_nexen']){
    $usuario_nexen = $_SESSION['usuario_nexen']; 

    $query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$usuario_nexen'";
    $select_user_name = $conn_bd->prepare($query_user_name);
    
    if($select_user_name -> execute()){
        $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
        $name_usuario = $result_name['nombre_usuario'];
    }
    
    //Validar si nombre viene vacio
    if(!$name_usuario){
        header('Location: login.php');
    }

        if(!empty($_POST['razon_social']) && isset($_POST['razon_social']) && 
            isset($_POST['tipo_operacion']) && !empty($_POST['tipo_operacion']) &&
            isset($_POST['tipo_trafico']) && !empty($_POST['tipo_trafico']) &&
            isset($_POST['nombre_operador']) && !empty($_POST['nombre_operador']))
        {
            $razon_social = $_POST['razon_social']; 
            $tipo_trafic = $_POST['tipo_trafico'];
            $import_export = $_POST['nombre_operador'];
            $tipo_ope = $_POST['tipo_operacion'];
            $año_actual = date("Y");
            $select_cliente = "SELECT * FROM [dbo].[Clientes] WHERE [RAZON SOCIAL ] = '$razon_social'";
            $cliente=$conn_bd->prepare($select_cliente);
            $cliente -> execute();
            $result_cliente= $cliente -> fetchAll(PDO::FETCH_ASSOC);
            foreach ($result_cliente as $cliente) {
                $consecutivo=$cliente['CONSECUTIVO'];
            }
            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo = "1";
            }
            $cadena_razon_social = substr($razon_social, 0,2);
            $cadena_tipo_trafico = substr($tipo_trafic, 0,1);
            $cadena_import_export = substr($import_export, 0,1);
            $prefijo = $cadena_razon_social."-".$cadena_tipo_trafico.$cadena_import_export;
           
            
            $sql_next_value = "SELECT NEXT VALUE FOR NUM_OPERACION_SEQ AS ID";
            $next_value=$conn_bd->prepare($sql_next_value);
            $next_value->execute();
            $next_id= $next_value -> fetch(PDO::FETCH_ASSOC);

            if(!empty($next_id)){
                $referencia_nex = $prefijo."-".$año_actual."-".$consecutivo."-".$next_id['ID'];
            }else{
                echo 'NO SE PUDO CREAR LA REFERENCIA ';
                exit;
            }

            date_default_timezone_set('America/Mexico_City');
            $HoraOpe = date('H:i:s');
            $fecha = date('Y-m-d');
           
            $query_insert_operación = " INSERT INTO [dbo].[Operacion_nexen] 
                    ([Usuario],[Cliente],[tipo_trafico],[Estatus],[HORA_OPE],[FECHOPE],[REFERENCIA_NEXEN],Importador_Exportador,Tipo_Operacion) 
            VALUES ('$name_usuario',' $razon_social','$tipo_trafic','EN PROCESO','$HoraOpe','$fecha' ,'$referencia_nex','$import_export','$tipo_ope')";
            $insert_new_op=$conn_bd->prepare($query_insert_operación);
            
            if($insert_new_op -> execute()){

                $query_insert_operación_log = "INSERT INTO [dbo].[Fk_Log_Detalle_Ope_Nexen] 
                        ([Usuario],[Cliente],[tipo_trafico],[Estatus],[HORA_OPE],[FECHOPE],[REFERENCIA_NEXEN],Importador_Exportador,[Tipo_OPE]) 
                VALUES ('$name_usuario',' $razon_social','$tipo_trafic','EN PROCESO', '$HoraOpe', GETDATE(),'$referencia_nex','$import_export','INSERTA')";

                $insert_new_op_log=$conn_bd->prepare($query_insert_operación_log);
                if($insert_new_op_log -> execute()){
                    $update_consecutivo_cliente = "UPDATE [dbo].[Clientes] SET [CONSECUTIVO] = $consecutivo WHERE [RAZON SOCIAL ]= '$razon_social'";
                    $update_consecutivo=$conn_bd->prepare($update_consecutivo_cliente);    
                    if($update_consecutivo -> execute()){
                        echo 'OPERACION CREADA CORRECTAMENTE';
                        echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nex.'">';
                    }else{
                        echo 'ERROR';
                        echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nex.'">';
                    }
                }else{
                    echo 'NO SE PUDO CREAR LA OPERACIÓN';
                    echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php">';
                } 
            }else{
                echo 'NO SE PUDO CREAR LA OPERACIÓN, FAVOR DE VOLVER A INTENTAR';
                echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php">';
            }
        }
    }else{
        echo 'INICIA SESIÓN';                     
    }
?>