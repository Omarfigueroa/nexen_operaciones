<?php
require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) 
{ 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{

        require '../utils/catalogos.php'; 
        require '../utils/utils.php';


$user_nexen = $_SESSION['usuario_nexen'];
$query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$user_nexen'";
$select_user_name = $conn_bd->prepare($query_user_name);

if($select_user_name -> execute()){
    $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
    $name_usuario = $result_name['nombre_usuario'];
}

//Validar si nombre viene vacio
if(!$name_usuario){
    header('Location: login.php');
}


// print_r($_POST);
// die;

        if(isset($_GET['id_referencia']) && !empty($_GET['id_referencia']) && 
            isset($_POST['razon_social_o']) && !empty($_POST['razon_social_o']) && 
            isset($_POST['nombre_operador_o']) && !empty($_POST['nombre_operador_o']) && 
            isset($_POST['tipo_operacion_o']) && !empty($_POST['tipo_operacion_o']) && 
            isset($_POST['tipo_trafico_o']) && !empty($_POST['tipo_trafico_o']) && 
            isset($user_nexen) && !empty($user_nexen)){

            $referencia = $_GET['id_referencia'];
            #verificar si ningun registro viene vacío
            if(isset($_POST['razon_social_o']) && !empty($_POST['razon_social_o'])){ $razon_social = $_POST['razon_social_o']; }else{ $razon_social = NULL;}
            if(isset($_POST['referencia_cliente_o']) && !empty($_POST['referencia_cliente_o'])){ $referencia_cliente = $_POST['referencia_cliente_o']; }else{ $referencia_cliente = NULL; }
            if(isset($_POST['referencia_cliente']) && !empty($_POST['referencia_cliente'])){ $refer_cliente = $_POST['referencia_cliente']; }else{ $refer_cliente = NULL; }
            if(isset($_POST['estatus']) && !empty($_POST['estatus'])){ $estatus = $_POST['estatus']; }else{ $estatus = NULL; }
            if(isset($_POST['saldo_cliente_o']) && !empty($_POST['saldo_cliente_o'])){ $saldo_cliente = $_POST['saldo_cliente_o']; }else{ $saldo_cliente = NULL; }
            if(isset($_POST['nombre_operador_o']) && !empty($_POST['nombre_operador_o'])){ $nombre_operador = $_POST['nombre_operador_o']; }else{ $nombre_operador = NULL; }
            if(isset($_POST['tipo_operacion_o']) && !empty($_POST['tipo_operacion_o'])){ $tipo_operacion = $_POST['tipo_operacion_o']; }else{ $tipo_operacion = NULL; }
            if(isset($_POST['cve_pedimento']) && !empty($_POST['cve_pedimento'])){ $cve_pedimento = $_POST['cve_pedimento']; }else{ $cve_pedimento = NULL; }
            if(isset($_POST['tipo_trafico_o']) && !empty($_POST['tipo_trafico_o'])){ $tipo_trafico = $_POST['tipo_trafico_o']; }else{ $tipo_trafico = NULL; }
    if(isset($_POST['tipo_trafico']) && !empty($_POST['tipo_trafico'])){ $tipo_trafico_s = $_POST['tipo_trafico']; }else{ $tipo_trafico_s = NULL; }
            if(isset($_POST['denominacion_aduana']) && !empty($_POST['denominacion_aduana'])){
                $denominacion_aduana = $_POST['denominacion_aduana']; 
                $obtener_codigo_aduana = "SELECT Codigo FROM  [dbo].[Catalogo_Aduanas] WHERE Denominación = '$denominacion_aduana'";
                
                $cod_aduana = $conn_bd->prepare($obtener_codigo_aduana);
                $cod_aduana -> execute();
                $results_cod_ad = $cod_aduana -> fetchAll(PDO::FETCH_ASSOC); 
                foreach ($results_cod_ad as $value) {
                    $codigo_aduana = $value['Codigo'];
                }
            }else{ 
                $denominacion_aduana = NULL; 
            }

            if(isset($codigo_aduana) && !empty($codigo_aduana)){ $pto_aduana = $codigo_aduana; }else{ $pto_aduana = NULL; }

            if(isset($_POST['master']) && !empty($_POST['master'])){ $master = $_POST['master']; }else{ $master = NULL; }
            if(isset($_POST['house']) && !empty($_POST['house'])){ $house = $_POST['house']; }else{ $house = NULL; }
            if(isset($_POST['contenedor1']) && !empty($_POST['contenedor1'])){ $contenedor1 = $_POST['contenedor1']; }else{ $contenedor1 = NULL; }
            if(isset($_POST['contenedor2']) && !empty($_POST['contenedor2'])){ $contenedor2 = $_POST['contenedor2']; }else{ $contenedor2 = NULL; }
            if(isset($_POST['num_eco']) && !empty($_POST['num_eco'])){ $num_eco = $_POST['num_eco']; }else{ $num_eco = NULL; }
            if(isset($_POST['bultos']) && !empty($_POST['bultos'])){ $bultos = $_POST['bultos']; }else{ $bultos = NULL; }
            if(isset($_POST['proveedor']) && !empty($_POST['proveedor'])){ $proveedor = $_POST['proveedor']; }else{ $proveedor = NULL; }
            if(isset($_POST['valor_factura']) && !empty($_POST['valor_factura'])){ $valor_factura = $_POST['valor_factura']; }else{ $valor_factura = NULL; }
            if(isset($_POST['moneda']) && !empty($_POST['moneda'])){ $moneda = $_POST['moneda']; }else{ $moneda = NULL; }
            if(isset($_POST['tipo_cambio']) && !empty($_POST['tipo_cambio'])){ $tipo_cambio = $_POST['tipo_cambio']; }else{ $tipo_cambio = NULL; }
            if(isset($_POST['peso_bruto']) && !empty($_POST['peso_bruto'])){ $peso_bruto = $_POST['peso_bruto']; }else{ $peso_bruto = NULL; }
            if(isset($_POST['fechNotifi']) && !empty($_POST['fechNotifi'])){ $fecha_notificacion = $_POST['fechNotifi']; }else{ $fecha_notificacion = NULL; }
            if(isset($_POST['fecharribo']) && !empty($_POST['fecharribo'])){ $fecha_arribo = $_POST['fecharribo']; }else{ $fecha_arribo = NULL; }
            if(isset($_POST['fechpedimento']) && !empty($_POST['fechpedimento'])){ $fecha_pago_pedimento = $_POST['fechpedimento']; }else{ $fecha_pago_pedimento = NULL; }
            if(isset($_POST['fechamodulacion']) && !empty($_POST['fechamodulacion'])){ $fecha_modulacion = $_POST['fechamodulacion']; }else{ $fecha_modulacion = NULL; }
            if(isset($_POST['patente']) && !empty($_POST['patente'])){ $patente = $_POST['patente']; }else{ $patente = NULL; }
            if(isset($_POST['num_pedimento']) && !empty($_POST['num_pedimento'])){ $num_pedimento = $_POST['num_pedimento']; }else{ $num_pedimento = NULL; }
            if(isset($_POST['wms']) && !empty($_POST['wms'])){ $wms = $_POST['wms']; }else{ $wms = NULL; }
            if(isset($_POST['anexo_24']) && !empty($_POST['anexo_24'])){ $anexo_24 = $_POST['anexo_24']; }else{ $anexo_24 = NULL; }
            if(isset($_POST['opeNex']) && !empty($_POST['opeNex'])){ $fecha_factura_anexo24 = $_POST['opeNex']; }else{ $fecha_factura_anexo24 = NULL; }
            if(isset($_POST['descripcion_cove']) && !empty($_POST['descripcion_cove'])){ $descripcion_cove = $_POST['descripcion_cove']; }else{ $descripcion_cove = NULL; }
            if(isset($_POST['bl']) && !empty($_POST['bl'])){ $bl = $_POST['bl']; }else{ $bl = NULL; }
    if(isset($_POST['det_mercancia']) && !empty($_POST['det_mercancia'])){ $det_mercancia = $_POST['det_mercancia']; }else{ $det_mercancia = NULL; }

        
        
            date_default_timezone_set('America/Mexico_City');
            $HoraOpe = date('H:i:s');
            $sql_update="UPDATE [dbo].[Operacion_nexen]
                SET [Referencia_Cliente] = '$refer_cliente'
                    ,[Cliente] = '$razon_social'
                    ,[Contenedor_1] = '$contenedor1'
                    ,[Fecha_Arribo] = '$fecha_arribo'
                    ,[Fecha_Notificación] = '$fecha_notificacion'
                    ,[Fecha_Pago_Anticipo] = '$fecha_pago_pedimento'
                    ,[Fecha_Modulación] = '$fecha_modulacion'
                    ,[No_Pedimento] = '$num_pedimento'
                    ,[Importador_Exportador] = '$nombre_operador'
                    ,[Clave_Pedimento] = '$cve_pedimento'
                    ,[Valor_Factura] = '$valor_factura'
                    ,[Descripcion_Cove] = '$descripcion_cove'
                    ,[Fecha_Factura24] = '$fecha_factura_anexo24'
                    ,[tipo_cambio] = '$tipo_cambio'
                    ,[WMS] = '$wms'
                    ,[Estatus] = '$estatus'
                    ,[Patente] = '$patente'
                    ,[Moneda] = '$moneda'
                    ,[DENOMINACION_ADUANA] = '$denominacion_aduana'
                    ,[Tipo_Operacion] = '$tipo_operacion'
                    ,[BULTOS] = '$bultos'
                    ,[peso_bruto] = '$peso_bruto'
            ,[tipo_trafico] = '$tipo_trafico_s'
                    ,[GUIA_HOUSE1] = '$house'
                    ,[FACTURA_SALIDA_ANEXO24] = '$anexo_24'
                    ,[NUMERO_ECONOMICO] = '$num_eco'
                    ,[Contenedor_2] = '$contenedor2'
                    ,[BL] = '$bl'
                    ,[Pto_LLegada] = '$pto_aduana'
            ,[DETALLE_MERCANCIA]='$det_mercancia'
            WHERE [REFERENCIA_NEXEN]= '$referencia'";

            $update_operaciones = $conn_bd->prepare($sql_update);

            if($update_operaciones->execute()){
                $insert_log_update = "INSERT INTO [dbo].[Fk_Log_Detalle_Ope_Nexen]
                                            ([Usuario]
                                            ,[Referencia_Cliente]
                                            ,[Cliente]
                                            ,[BL]
                                            ,[Contenedor_1]
                                            ,[Fecha_Arribo]
                                            ,[Fecha_Notificacion]
                                            ,[Fecha_Pago_Anticipo]
                                            ,[Fecha_Modulacion]
                                            ,[No_Pedimento]
                                            ,[Importador_Exportador]
                                            ,[Clave_Pedimento]
                                            ,[Valor_Factura]
                                            ,[Descripcion_Cove]
                                            ,[tipo_cambio]
                                            ,[Fecha_Factura24]
                                            ,[WMS]
                                            ,[Estatus]
                                            ,[HORA_OPE]
                                            ,[FECHOPE]
                                            ,[Patente]
                                            ,[Moneda]
                                            ,[DENOMINACION_ADUANA]
                                            ,[Tipo_Operacion]
                                            ,[BULTOS]
                                            ,[peso_bruto]
                                            ,[tipo_trafico]
                                            ,[GUIA_HOUSE1]
                                            ,[FACTURA_SALIDA_ANEXO24]
                                            ,[NUMERO_ECONOMICO]
                                            ,[REFERENCIA_NEXEN]
                                            ,[Contenedor_2]
                                    ,[DETALLE_MERCANCIA]
                                            ,[Tipo_OPE])
                                    VALUES
                                    ('$name_usuario'
                                            ,'$refer_cliente'
                                            ,'$razon_social'
                                            ,'$bl'
                                            ,'$contenedor1'
                                            ,'$fecha_arribo'
                                            ,'$fecha_notificacion'
                                            ,'$fecha_pago_pedimento'
                                            ,'$fecha_modulacion'
                                            ,'$num_pedimento'
                                            ,'$nombre_operador'
                                            ,'$cve_pedimento'
                                            ,'$valor_factura'
                                            ,'$descripcion_cove'
                                            ,'$tipo_cambio'
                                            ,'$fecha_factura_anexo24'
                                            ,'$wms'
                                            ,'$estatus'
                                            ,'$HoraOpe'
                                            ,GETDATE()
                                            ,'$patente'
                                            ,'$moneda' 
                                            ,'$denominacion_aduana'
                                            ,'$tipo_operacion'
                                            ,'$bultos'
                                            ,'$peso_bruto'
                                    ,'$tipo_trafico_s'
                                            ,'$house'
                                            ,'$anexo_24'
                                            ,'$num_eco'
                                            ,'$referencia'
                                            ,'$contenedor2'
                                    ,'$det_mercancia'
                                            ,'UPDATE')";

                    
                    $update_operaciones_log= $conn_bd->prepare($insert_log_update);

           if($update_operaciones_log->execute()){
                print_r("SE LOGRO INSERTAR EN BASE DE DATOS");
                // echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nexen.'">';
                $query_exist_tipo_trafico = "SELECT * FROM [dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] WHERE REFERENCIA_NEXEN = '$referencia'";
                $es_igual_tipo_trafico= $conn_bd->prepare($query_exist_tipo_trafico);

                 if($es_igual_tipo_trafico->execute()){
                    $results_trafico = $es_igual_tipo_trafico -> fetchAll(PDO::FETCH_ASSOC); 
                    if(!empty($results_trafico)){
                        foreach ($results_trafico as $trafico){
                            if($trafico['Tipo_Documento']!==$tipo_trafico_s){
                                $id= $trafico['id'];

                                
                                $update_tipo_trafico = "UPDATE [dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION]
                                                        SET TIPO_DOCUMENTO = '$tipo_trafico_s'
                                                        WHERE REFERENCIA_NEXEN = '$referencia'
                                                        AND ID = $id";
                                 
                                 $result_tipo_trafico = $conn_bd->prepare($update_tipo_trafico);
                                 if($result_tipo_trafico->execute()){
                                 }
                            }
                        }
                    }
                }
                echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia.'">'; 
           }
           
    }else {
        print_r("NO SE LOGRO INSERTAR EN BASE DE DATOS CORRECTAMENTE");
        echo'<script type="text/javascript">
        </script>';
                echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia.'">';   

            }
        
        }else{
            echo'<script type="text/javascript">
            alert("NO EXISTE UNA REFERENCIA, FAVOR DE INGRESAR UNA");
            </script>';
            echo '<meta http-equiv="REFRESH" content="0;url=../view/operaciones.php">';   
        }

    }
}//validacion de la sesión

?>