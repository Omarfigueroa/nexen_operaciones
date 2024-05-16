<?php
require '../conexion/bd.php';

        
if(!isset($_SESSION['usuario_nexen'])) { 
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
            header('Location: ../view/login.php');
        }  
       

        if(!isset($_POST['tipo_solicitud'])){
           $referencia_nexen_sp = $_POST['referencia_nexen_sp'];
           $cliente = $_POST['razon_social_sp'];
           $operador = $_POST['operador_sp'];
          
           $tipo_trafico = $_POST['tipo_trafico_pago'];
            // VALIDAMOS PRIMERO DATOS GENERALES Y USUARIO CON REFERENCIA Y EL TIPO DE TRAFICO
            if($tipo_trafico == 'CARRETERO'){
                if(isset($_POST['num_eco_sol_pago_sp']) && !empty($_POST['num_eco_sol_pago_sp'])){
                    $num_economico = $_POST['num_eco_sol_pago_sp'];
                
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            die;
                        }else{
                            
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = ""; 
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Numero_Economico]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$num_economico'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF" 
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $num_economico);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                        
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = "";
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Numero_Economico]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$num_economico'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $num_economico);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN NUMERO ECONOMICO','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }
            else if($tipo_trafico === 'AEREO'){
                if(isset($_POST['guia_sol_pago_sp']) && !empty($_POST['guia_sol_pago_sp'])){
                
                    $house = $_POST['guia_sol_pago_sp'];
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }else{
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = "";
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Guia_House]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$house'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $house);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST['cuenta_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = "";
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Guia_House]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$house'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $house);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UNA GUIA','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }else if($tipo_trafico === 'VIRTUAL'){
                if(isset($_POST['pedimento_sp']) && !empty($_POST['pedimento_sp'])){
                    
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            die;
                        }else{
                            
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = ""; 
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $numero_pedimento);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = "";
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $numero_pedimento);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO INGRESAR UN PEDIMENTO','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }else{
                if(isset($_POST['contenedor_sol_pago_sp']) && !empty($_POST['contenedor_sol_pago_sp'])){
                    $contenedor = $_POST['contenedor_sol_pago_sp'];
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }else{
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = "";
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Contenedor]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$contenedor'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $contenedor);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }                  
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = "";
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Contenedor]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$contenedor'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $contenedor);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN CONTENEDOR','val'=>'1');
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                } 
            }    
        }else if($_POST['tipo_solicitud'] === "ANTICIPO"){  
           $referencia_nexen_sp = $_POST['referencia_nexen_sp'];
           $cliente = $_POST['razon_social_sp'];
           $operador = $_POST['operador_sp'];
          
           $tipo_trafico = $_POST['tipo_trafico_pago'];
            // VALIDAMOS PRIMERO DATOS GENERALES Y USUARIO CON REFERENCIA Y EL TIPO DE TRAFICO
            if($tipo_trafico == 'CARRETERO'){
                if(isset($_POST['num_eco_sol_pago_sp']) && !empty($_POST['num_eco_sol_pago_sp'])){
                    $num_economico = $_POST['num_eco_sol_pago_sp'];
                
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }else{
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = $_POST['tipo_solicitud'];
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Numero_Economico]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$num_economico'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $num_economico);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                        
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = "";
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Numero_Economico]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$num_economico'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $num_economico);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN NUMERO ECONOMICO','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }else if($tipo_trafico === 'AEREO'){

                if(isset($_POST['guia_sol_pago_sp']) && !empty($_POST['guia_sol_pago_sp'])){
                    $house = $_POST['guia_sol_pago_sp'];
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }else{
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                            $referencia_proveedor= $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = $_POST['tipo_solicitud'];
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Guia_House]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$house'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $house);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = $_POST['tipo_solicitud'];
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Guia_House]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$house'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $house);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UNA GUIA','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }else if($tipo_trafico === 'VIRTUAL'){
                if(isset($_POST['pedimento_sp']) && !empty($_POST['pedimento_sp'])){
                    
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            die;
                        }else{
                            
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = $_POST['tipo_solicitud']; 
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $numero_pedimento);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }else{
                        
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = $_POST['tipo_solicitud'];
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $numero_pedimento);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO INGRESAR UN PEDIMENTO','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }else{
                if(isset($_POST['contenedor_sol_pago_sp']) && !empty($_POST['contenedor_sol_pago_sp'])){
                    $contenedor = $_POST['contenedor_sol_pago_sp'];
                    if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                    if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                    if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                    if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                    if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                    $concepto_sp = $_POST['concepto_sp'];
                    if($concepto_sp != 'OTROS'){
                        $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                        $select_pago= $conn_bd->prepare($query_select_pago);
                        $select_pago->execute();
                        $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                        if($existe_concepto>1){
                            $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }else{
                            $numero_pedimento = $_POST['pedimento_sp'];
                            $razon_social_receptora = $_POST['razon_social_pago'];
                            $rfc = $_POST['rfc_sp_pago'];
                            $ABBA = $_POST['abba_sp_pago'];
                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                            $banco = $_POST['banco_sp_pago'];
                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                            $monto = $_POST['monto_sp'];
                            $tipo_solicitud = $_POST['tipo_solicitud'];
                            $Observaciones = $_POST['observaciones_sp'];
                            date_default_timezone_set('America/Mexico_City');
                            $fechaOpe  = date('Y/m/d');
                            $hora = date('H:i:s');
                            $moneda = $_POST['moneda_sp'];
                            $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Contenedor]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$contenedor'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                
                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                            if($insertar_solicitud_pago->execute()){
                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                $buscar_id= $conn_bd->prepare($query_id);
                                if($buscar_id->execute()){
                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    // Verificar si se envió un archivo
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        //variables del archivo
                                        $file = $_FILES['file']['tmp_name'];
                                        $name = $_FILES['file']['name'];
                                        $tipe_file = $_FILES['file']['type'];
                                        $name_archivo = $id_pago.'.pdf';
                                        $uploadFileDir = 'PDFLog/';
                                        $dest_path = $uploadFileDir . $name_archivo;
                                        move_uploaded_file($file, $dest_path);
                                        //validacion de archivo correcto
                                        $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                        if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                            || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                $data = base64_encode(file_get_contents($dest_path));
                                            
                                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                ([Documento]
                                                ,[Tipo]
                                                ,[Referencia_Nexen]
                                                ,[Contenedor_guia_economico]
                                                ,[fechope]
                                                ,[hora]
                                                ,[estatus]
                                                ,[Usuario]
                                                ,[Id_Pago]
                                                )
                                                VALUES
                                                (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                $stmt->bindParam(2, $concepto_sp);
                                                $stmt->bindParam(3, $referencia_nexen_sp);
                                                $stmt->bindParam(4, $contenedor);
                                                $stmt->bindParam(5, $fechaOpe);
                                                $stmt->bindParam(6, $hora);
                                                $stmt->bindParam(7, $estatus);
                                                $stmt->bindParam(8, $name_usuario);
                                                $stmt->bindParam(9, $id_pago);
                                                $stmt->execute();
                                                $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }                
                                }else{
                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            }else{
                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }else{
                        $numero_pedimento = $_POST['pedimento_sp'];
                        $razon_social_receptora = $_POST['razon_social_pago'];
                        $rfc = $_POST['rfc_sp_pago'];
                        $ABBA = $_POST['abba_sp_pago'];
                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                        $banco = $_POST['banco_sp_pago'];
                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                        $monto = $_POST['monto_sp'];
                        $tipo_solicitud = $_POST['tipo_solicitud'];
                        $Observaciones = $_POST['observaciones_sp'];
                        date_default_timezone_set('America/Mexico_City');
                        $fechaOpe  = date('Y/m/d');
                        $hora = date('H:i:s');
                        $moneda = $_POST['moneda_sp'];
                        $estatus = 1;
                        //Insertar en Solicitus de Pagos                            
                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                    ,[Cliente]
                                                                                    ,[Operador]
                                                                                    ,[Contenedor]
                                                                                    ,[Numero_Pedimento]
                                                                                    ,[Razon_Social_Receptora]
                                                                                    ,[RFC]
                                                                                    ,[SWT_ABBA]
                                                                                    ,[Cuenta_Clabe]
                                                                                    ,[Banco_Destino]
                                                                                    ,[Domicilio_Destino]
                                                                                    ,[Concepto]
                                                                                    ,[Monto]
                                                                                    ,[Tipo_Solicitud]
                                                                                    ,[Observaciones]
                                                                                    ,[Fechope]
                                                                                    ,[Hora]
                                                                                    ,[Usuario]
                                                                                    ,[Estatus]
                                                                                    ,[Moneda]
                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                    ,[Ref_proveedor]
                                                                                    ,[Tipo_Operacion])
                                                                                    VALUES
                                                                                    ('$referencia_nexen_sp'
                                                                                    ,'$cliente'
                                                                                    ,'$operador'
                                                                                    ,'$contenedor'
                                                                                    ,'$numero_pedimento'
                                                                                    ,'$razon_social_receptora'
                                                                                    ,'$rfc'
                                                                                    ,'$ABBA'
                                                                                    ,'$cuenta_clabe'
                                                                                    ,'$banco'
                                                                                    ,'$domicilio_destino'
                                                                                    ,'$concepto_sp'
                                                                                    ,'$monto'
                                                                                    ,'$tipo_solicitud'
                                                                                    ,'$Observaciones'
                                                                                    ,'$fechaOpe'
                                                                                    ,'$hora'
                                                                                    ,'$name_usuario'
                                                                                    ,'PENDIENTE'
                                                                                    ,'$moneda'
                                                                                    ,'$cuenta_interbancaria'
                                                                                    ,'$referencia_proveedor'
                                                                                    ,'$tipo_trafico')";                                                         
                    
                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
        
                        if($insertar_solicitud_pago->execute()){
        
                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                            $buscar_id= $conn_bd->prepare($query_id);
                            if($buscar_id->execute()){
                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                            
                                // Verificar si se envió un archivo
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                
                                    //variables del archivo
                                    $file = $_FILES['file']['tmp_name'];
                                    $name = $_FILES['file']['name'];
                                    $tipe_file = $_FILES['file']['type'];
                                    $name_archivo = $id_pago.'.pdf';
        
                                    $uploadFileDir = 'PDFLog/';
                                    $dest_path = $uploadFileDir . $name_archivo;
                                    move_uploaded_file($file, $dest_path);
                                
                                    //validacion de archivo correcto
                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                    
                                            $data = base64_encode(file_get_contents($dest_path));
                                        
                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                            ([Documento]
                                            ,[Tipo]
                                            ,[Referencia_Nexen]
                                            ,[Contenedor_guia_economico]
                                            ,[fechope]
                                            ,[hora]
                                            ,[estatus]
                                            ,[Usuario]
                                            ,[Id_Pago]
                                            )
                                            VALUES
                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
        
                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $concepto_sp);
                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                            $stmt->bindParam(4, $contenedor);
                                            $stmt->bindParam(5, $fechaOpe);
                                            $stmt->bindParam(6, $hora);
                                            $stmt->bindParam(7, $estatus);
                                            $stmt->bindParam(8, $name_usuario);
                                            $stmt->bindParam(9, $id_pago);
                                            $stmt->execute();
                                        
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }                
                            }else{
                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    } 
                }else{
                    $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN CONTENEDOR','val'=>'1');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }
        }else if($_POST['tipo_solicitud'] === "FINANCIADO"){
           
            $user_sup = $_POST['supervisor_user'];
            $pass_sup = $_POST['pass_supervisor_sol'];

            if($user_sup === "" || $pass_sup === ""){
                $arrData = array('status' => false,'msg' =>'LAS CREDENCIALES DEL SUPERVISOR SON OBLIGATORIAS','val'=>'6');
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }else{
                $query_ver = "SELECT Usuario,Contraseña FROM [dbo].[Contraseña_Sup] WHERE Usuario = '{$user_sup}' AND Contraseña = '{$pass_sup}' ";
                $valida = $conn_bd->prepare($query_ver);
                $valida->execute();
                $result_valid_super = $valida ->fetch(PDO::FETCH_ASSOC);
               
                if(!empty($result_valid_super)){
                    $referencia_nexen_sp = $_POST['referencia_nexen_sp'];
                    $cliente = $_POST['razon_social_sp'];
                    $operador = $_POST['operador_sp'];
                   
                    $tipo_trafico = $_POST['tipo_trafico_pago'];
                     // VALIDAMOS PRIMERO DATOS GENERALES Y USUARIO CON REFERENCIA Y EL TIPO DE TRAFICO
                    if($tipo_trafico == 'CARRETERO'){
                        if(isset($_POST['num_eco_sol_pago_sp']) && !empty($_POST['num_eco_sol_pago_sp'])){
                            $num_economico = $_POST['num_eco_sol_pago_sp'];
                            if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                            if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                            if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                            if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                            if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                            $concepto_sp = $_POST['concepto_sp'];
                            if($concepto_sp != 'OTROS'){
                                $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                                $select_pago= $conn_bd->prepare($query_select_pago);
                                $select_pago->execute();
                                $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                                if($existe_concepto>1){
                                    $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    die;
                                }else{
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                            $numero_pedimento = $_POST['pedimento_sp'];
                                            $razon_social_receptora = $_POST['razon_social_pago'];
                                            $rfc = $_POST['rfc_sp_pago'];
                                            $ABBA = $_POST['abba_sp_pago'];
                                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                            $banco = $_POST['banco_sp_pago'];
                                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                                            $monto = $_POST['monto_sp'];
                                            $tipo_solicitud = $_POST['tipo_solicitud'];
                                            $Observaciones = $_POST['observaciones_sp'];
                                            date_default_timezone_set('America/Mexico_City');
                                            $fechaOpe  = date('Y/m/d');
                                            $hora = date('H:i:s');
                                            $moneda = $_POST['moneda_sp'];
                                            $estatus = 1;
                                                //Insertar en Solicitus de Pagos                            
                                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                            ,[Cliente]
                                                                                                            ,[Operador]
                                                                                                            ,[Numero_Economico]
                                                                                                            ,[Numero_Pedimento]
                                                                                                            ,[Razon_Social_Receptora]
                                                                                                            ,[RFC]
                                                                                                            ,[SWT_ABBA]
                                                                                                            ,[Cuenta_Clabe]
                                                                                                            ,[Banco_Destino]
                                                                                                            ,[Domicilio_Destino]
                                                                                                            ,[Concepto]
                                                                                                            ,[Monto]
                                                                                                            ,[Tipo_Solicitud]
                                                                                                            ,[Observaciones]
                                                                                                            ,[Fechope]
                                                                                                            ,[Hora]
                                                                                                            ,[Usuario]
                                                                                                            ,[Estatus]
                                                                                                            ,[Moneda]
                                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                                            ,[Ref_proveedor]
                                                                                                            ,[Tipo_Operacion])
                                                                                                            VALUES
                                                                                                            ('$referencia_nexen_sp'
                                                                                                            ,'$cliente'
                                                                                                            ,'$operador'
                                                                                                            ,'$num_economico'
                                                                                                            ,'$numero_pedimento'
                                                                                                            ,'$razon_social_receptora'
                                                                                                            ,'$rfc'
                                                                                                            ,'$ABBA'
                                                                                                            ,'$cuenta_clabe'
                                                                                                            ,'$banco'
                                                                                                            ,'$domicilio_destino'
                                                                                                            ,'$concepto_sp'
                                                                                                            ,'$monto'
                                                                                                            ,'$tipo_solicitud'
                                                                                                            ,'$Observaciones'
                                                                                                            ,'$fechaOpe'
                                                                                                            ,'$hora'
                                                                                                            ,'$name_usuario'
                                                                                                            ,'PENDIENTE'
                                                                                                            ,'$moneda'
                                                                                                            ,'$cuenta_interbancaria'
                                                                                                            ,'$referencia_proveedor'
                                                                                                            ,'$tipo_trafico')";                                                         
                                
                                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                            if($insertar_solicitud_pago->execute()){
                                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                                $buscar_id= $conn_bd->prepare($query_id);
                                                if($buscar_id->execute()){
                                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                    // Verificar si se envió un archivo
                                                    $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        
                                    }else{
                                        $numero_pedimento = $_POST['pedimento_sp'];
                                        $razon_social_receptora = $_POST['razon_social_pago'];
                                        $rfc = $_POST['rfc_sp_pago'];
                                        $ABBA = $_POST['abba_sp_pago'];
                                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                        $banco = $_POST['banco_sp_pago'];
                                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                                        $monto = $_POST['monto_sp'];
                                        $tipo_solicitud = $_POST['tipo_solicitud'];
                                        $Observaciones = $_POST['observaciones_sp'];
                                        date_default_timezone_set('America/Mexico_City');
                                        $fechaOpe  = date('Y/m/d');
                                        $hora = date('H:i:s');
                                        $moneda = $_POST['moneda_sp'];
                                        $estatus = 1;
                                            //Insertar en Solicitus de Pagos                            
                                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                        ,[Cliente]
                                                                                                        ,[Operador]
                                                                                                        ,[Numero_Economico]
                                                                                                        ,[Numero_Pedimento]
                                                                                                        ,[Razon_Social_Receptora]
                                                                                                        ,[RFC]
                                                                                                        ,[SWT_ABBA]
                                                                                                        ,[Cuenta_Clabe]
                                                                                                        ,[Banco_Destino]
                                                                                                        ,[Domicilio_Destino]
                                                                                                        ,[Concepto]
                                                                                                        ,[Monto]
                                                                                                        ,[Tipo_Solicitud]
                                                                                                        ,[Observaciones]
                                                                                                        ,[Fechope]
                                                                                                        ,[Hora]
                                                                                                        ,[Usuario]
                                                                                                        ,[Estatus]
                                                                                                        ,[Moneda]
                                                                                                        ,[CLABE_INTERBANCARIA]
                                                                                                        ,[Ref_proveedor]
                                                                                                        ,[Tipo_Operacion])
                                                                                                        VALUES
                                                                                                        ('$referencia_nexen_sp'
                                                                                                        ,'$cliente'
                                                                                                        ,'$operador'
                                                                                                        ,'$num_economico'
                                                                                                        ,'$numero_pedimento'
                                                                                                        ,'$razon_social_receptora'
                                                                                                        ,'$rfc'
                                                                                                        ,'$ABBA'
                                                                                                        ,'$cuenta_clabe'
                                                                                                        ,'$banco'
                                                                                                        ,'$domicilio_destino'
                                                                                                        ,'$concepto_sp'
                                                                                                        ,'$monto'
                                                                                                        ,'$tipo_solicitud'
                                                                                                        ,'$Observaciones'
                                                                                                        ,'$fechaOpe'
                                                                                                        ,'$hora'
                                                                                                        ,'$name_usuario'
                                                                                                        ,'PENDIENTE'
                                                                                                        ,'$moneda'
                                                                                                        ,'$cuenta_interbancaria'
                                                                                                        ,'$referencia_proveedor'
                                                                                                        ,'$tipo_trafico')";                                                         
                            
                                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                        if($insertar_solicitud_pago->execute()){
                                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                            $buscar_id= $conn_bd->prepare($query_id);
                                            if($buscar_id->execute()){
                                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                // Verificar si se envió un archivo
                                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                    //variables del archivo
                                                    $file = $_FILES['file']['tmp_name'];
                                                    $name = $_FILES['file']['name'];
                                                    $tipe_file = $_FILES['file']['type'];
                                                    $name_archivo = $id_pago.'.pdf';
                                                    $uploadFileDir = 'PDFLog/';
                                                    $dest_path = $uploadFileDir . $name_archivo;
                                                    move_uploaded_file($file, $dest_path);
                                                    //validacion de archivo correcto
                                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                            $data = base64_encode(file_get_contents($dest_path));
                                                        
                                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                            ([Documento]
                                                            ,[Tipo]
                                                            ,[Referencia_Nexen]
                                                            ,[Contenedor_guia_economico]
                                                            ,[fechope]
                                                            ,[hora]
                                                            ,[estatus]
                                                            ,[Usuario]
                                                            ,[Id_Pago]
                                                            )
                                                            VALUES
                                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                            $stmt->bindParam(2, $concepto_sp);
                                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                                            $stmt->bindParam(4, $num_economico);
                                                            $stmt->bindParam(5, $fechaOpe);
                                                            $stmt->bindParam(6, $hora);
                                                            $stmt->bindParam(7, $estatus);
                                                            $stmt->bindParam(8, $name_usuario);
                                                            $stmt->bindParam(9, $id_pago);
                                                            $stmt->execute();
                                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                            die;
                                                    }else{
                                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                    }
                                                }                
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }
                                }
                            }else{
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Numero_Economico]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$num_economico'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }else{
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Numero_Economico]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$num_economico'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                //variables del archivo
                                                $file = $_FILES['file']['tmp_name'];
                                                $name = $_FILES['file']['name'];
                                                $tipe_file = $_FILES['file']['type'];
                                                $name_archivo = $id_pago.'.pdf';
                                                $uploadFileDir = 'PDFLog/';
                                                $dest_path = $uploadFileDir . $name_archivo;
                                                move_uploaded_file($file, $dest_path);
                                                //validacion de archivo correcto
                                                $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                    || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                    ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                        $data = base64_encode(file_get_contents($dest_path));
                                                    
                                                        $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                        ([Documento]
                                                        ,[Tipo]
                                                        ,[Referencia_Nexen]
                                                        ,[Contenedor_guia_economico]
                                                        ,[fechope]
                                                        ,[hora]
                                                        ,[estatus]
                                                        ,[Usuario]
                                                        ,[Id_Pago]
                                                        )
                                                        VALUES
                                                        (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                        $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                        $stmt->bindParam(2, $concepto_sp);
                                                        $stmt->bindParam(3, $referencia_nexen_sp);
                                                        $stmt->bindParam(4, $num_economico);
                                                        $stmt->bindParam(5, $fechaOpe);
                                                        $stmt->bindParam(6, $hora);
                                                        $stmt->bindParam(7, $estatus);
                                                        $stmt->bindParam(8, $name_usuario);
                                                        $stmt->bindParam(9, $id_pago);
                                                        $stmt->execute();
                                                        $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }                
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN NUMERO ECONOMICO','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    }else if($tipo_trafico == 'AEREO'){
                        if(isset($_POST['guia_sol_pago_sp']) && !empty($_POST['guia_sol_pago_sp'])){
                            $house = $_POST['guia_sol_pago_sp'];
                            if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                            if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                            if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                            if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                            if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                            $concepto_sp = $_POST['concepto_sp'];
                            if($concepto_sp != 'OTROS'){
                                $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                                $select_pago= $conn_bd->prepare($query_select_pago);
                                $select_pago->execute();
                                $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                                if($existe_concepto>1){
                                    $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }else{
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                            $numero_pedimento = $_POST['pedimento_sp'];
                                            $razon_social_receptora = $_POST['razon_social_pago'];
                                            $rfc = $_POST['rfc_sp_pago'];
                                            $ABBA = $_POST['abba_sp_pago'];
                                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                            $banco = $_POST['banco_sp_pago'];
                                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                                            $monto = $_POST['monto_sp'];
                                            $tipo_solicitud = $_POST['tipo_solicitud'];
                                            $Observaciones = $_POST['observaciones_sp'];
                                            date_default_timezone_set('America/Mexico_City');
                                            $fechaOpe  = date('Y/m/d');
                                            $hora = date('H:i:s');
                                            $moneda = $_POST['moneda_sp'];
                                            $estatus = 1;
                                                //Insertar en Solicitus de Pagos                            
                                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                            ,[Cliente]
                                                                                                            ,[Operador]
                                                                                                            ,[Guia_House]
                                                                                                            ,[Numero_Pedimento]
                                                                                                            ,[Razon_Social_Receptora]
                                                                                                            ,[RFC]
                                                                                                            ,[SWT_ABBA]
                                                                                                            ,[Cuenta_Clabe]
                                                                                                            ,[Banco_Destino]
                                                                                                            ,[Domicilio_Destino]
                                                                                                            ,[Concepto]
                                                                                                            ,[Monto]
                                                                                                            ,[Tipo_Solicitud]
                                                                                                            ,[Observaciones]
                                                                                                            ,[Fechope]
                                                                                                            ,[Hora]
                                                                                                            ,[Usuario]
                                                                                                            ,[Estatus]
                                                                                                            ,[Moneda]
                                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                                            ,[Ref_proveedor]
                                                                                                            ,[Tipo_Operacion])
                                                                                                            VALUES
                                                                                                            ('$referencia_nexen_sp'
                                                                                                            ,'$cliente'
                                                                                                            ,'$operador'
                                                                                                            ,'$house'
                                                                                                            ,'$numero_pedimento'
                                                                                                            ,'$razon_social_receptora'
                                                                                                            ,'$rfc'
                                                                                                            ,'$ABBA'
                                                                                                            ,'$cuenta_clabe'
                                                                                                            ,'$banco'
                                                                                                            ,'$domicilio_destino'
                                                                                                            ,'$concepto_sp'
                                                                                                            ,'$monto'
                                                                                                            ,'$tipo_solicitud'
                                                                                                            ,'$Observaciones'
                                                                                                            ,'$fechaOpe'
                                                                                                            ,'$hora'
                                                                                                            ,'$name_usuario'
                                                                                                            ,'PENDIENTE'
                                                                                                            ,'$moneda'
                                                                                                            ,'$cuenta_interbancaria'
                                                                                                            ,'$referencia_proveedor'
                                                                                                            ,'$tipo_trafico')";                                                         
                                
                                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                            if($insertar_solicitud_pago->execute()){
                                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                                $buscar_id= $conn_bd->prepare($query_id);
                                                if($buscar_id->execute()){
                                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                    // Verificar si se envió un archivo
                                                    $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        
                                    }else{
                                        $numero_pedimento = $_POST['pedimento_sp'];
                                        $razon_social_receptora = $_POST['razon_social_pago'];
                                        $rfc = $_POST['rfc_sp_pago'];
                                        $ABBA = $_POST['abba_sp_pago'];
                                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                        $banco = $_POST['banco_sp_pago'];
                                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                                        $monto = $_POST['monto_sp'];
                                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                        $tipo_solicitud = $_POST['tipo_solicitud'];
                                        $Observaciones = $_POST['observaciones_sp'];
                                        date_default_timezone_set('America/Mexico_City');
                                        $fechaOpe  = date('Y/m/d');
                                        $hora = date('H:i:s');
                                        $moneda = $_POST['moneda_sp'];
                                        $estatus = 1;
                                            //Insertar en Solicitus de Pagos                            
                                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                        ,[Cliente]
                                                                                                        ,[Operador]
                                                                                                        ,[Guia_House]
                                                                                                        ,[Numero_Pedimento]
                                                                                                        ,[Razon_Social_Receptora]
                                                                                                        ,[RFC]
                                                                                                        ,[SWT_ABBA]
                                                                                                        ,[Cuenta_Clabe]
                                                                                                        ,[Banco_Destino]
                                                                                                        ,[Domicilio_Destino]
                                                                                                        ,[Concepto]
                                                                                                        ,[Monto]
                                                                                                        ,[Tipo_Solicitud]
                                                                                                        ,[Observaciones]
                                                                                                        ,[Fechope]
                                                                                                        ,[Hora]
                                                                                                        ,[Usuario]
                                                                                                        ,[Estatus]
                                                                                                        ,[Moneda]
                                                                                                        ,[CLABE_INTERBANCARIA]
                                                                                                        ,[Ref_proveedor]
                                                                                                        ,[Tipo_Operacion])
                                                                                                        VALUES
                                                                                                        ('$referencia_nexen_sp'
                                                                                                        ,'$cliente'
                                                                                                        ,'$operador'
                                                                                                        ,'$house'
                                                                                                        ,'$numero_pedimento'
                                                                                                        ,'$razon_social_receptora'
                                                                                                        ,'$rfc'
                                                                                                        ,'$ABBA'
                                                                                                        ,'$cuenta_clabe'
                                                                                                        ,'$banco'
                                                                                                        ,'$domicilio_destino'
                                                                                                        ,'$concepto_sp'
                                                                                                        ,'$monto'
                                                                                                        ,'$tipo_solicitud'
                                                                                                        ,'$Observaciones'
                                                                                                        ,'$fechaOpe'
                                                                                                        ,'$hora'
                                                                                                        ,'$name_usuario'
                                                                                                        ,'PENDIENTE'
                                                                                                        ,'$moneda'
                                                                                                        ,'$cuenta_interbancaria'
                                                                                                        ,'$referencia_proveedor'
                                                                                                        ,'$tipo_trafico')";                                                         
                            
                                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                        if($insertar_solicitud_pago->execute()){
                                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                            $buscar_id= $conn_bd->prepare($query_id);
                                            if($buscar_id->execute()){
                                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                // Verificar si se envió un archivo
                                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                    //variables del archivo
                                                    $file = $_FILES['file']['tmp_name'];
                                                    $name = $_FILES['file']['name'];
                                                    $tipe_file = $_FILES['file']['type'];
                                                    $name_archivo = $id_pago.'.pdf';
                                                    $uploadFileDir = 'PDFLog/';
                                                    $dest_path = $uploadFileDir . $name_archivo;
                                                    move_uploaded_file($file, $dest_path);
                                                    //validacion de archivo correcto
                                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                            $data = base64_encode(file_get_contents($dest_path));
                                                        
                                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                            ([Documento]
                                                            ,[Tipo]
                                                            ,[Referencia_Nexen]
                                                            ,[Contenedor_guia_economico]
                                                            ,[fechope]
                                                            ,[hora]
                                                            ,[estatus]
                                                            ,[Usuario]
                                                            ,[Id_Pago]
                                                            )
                                                            VALUES
                                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                            $stmt->bindParam(2, $concepto_sp);
                                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                                            $stmt->bindParam(4, $house);
                                                            $stmt->bindParam(5, $fechaOpe);
                                                            $stmt->bindParam(6, $hora);
                                                            $stmt->bindParam(7, $estatus);
                                                            $stmt->bindParam(8, $name_usuario);
                                                            $stmt->bindParam(9, $id_pago);
                                                            $stmt->execute();
                                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                            die;
                                                    }else{
                                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                    }
                                                }                
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }
                                }
                            }else{
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Guia_House]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$house'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }else{
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Guia_House]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$house'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                //variables del archivo
                                                $file = $_FILES['file']['tmp_name'];
                                                $name = $_FILES['file']['name'];
                                                $tipe_file = $_FILES['file']['type'];
                                                $name_archivo = $id_pago.'.pdf';
                                                $uploadFileDir = 'PDFLog/';
                                                $dest_path = $uploadFileDir . $name_archivo;
                                                move_uploaded_file($file, $dest_path);
                                                //validacion de archivo correcto
                                                $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                    || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                    ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                        $data = base64_encode(file_get_contents($dest_path));
                                                    
                                                        $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                        ([Documento]
                                                        ,[Tipo]
                                                        ,[Referencia_Nexen]
                                                        ,[Contenedor_guia_economico]
                                                        ,[fechope]
                                                        ,[hora]
                                                        ,[estatus]
                                                        ,[Usuario]
                                                        ,[Id_Pago]
                                                        )
                                                        VALUES
                                                        (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                        $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                        $stmt->bindParam(2, $concepto_sp);
                                                        $stmt->bindParam(3, $referencia_nexen_sp);
                                                        $stmt->bindParam(4, $house);
                                                        $stmt->bindParam(5, $fechaOpe);
                                                        $stmt->bindParam(6, $hora);
                                                        $stmt->bindParam(7, $estatus);
                                                        $stmt->bindParam(8, $name_usuario);
                                                        $stmt->bindParam(9, $id_pago);
                                                        $stmt->execute();
                                                        $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }                
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UNA GUIA','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    }else if($tipo_trafico == 'VIRTUAL'){
                        if(isset($_POST['pedimento_sp']) && !empty($_POST['pedimento_sp'])){
                    
                            if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                            if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                            if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                            if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                            if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                            $concepto_sp = $_POST['concepto_sp'];
                            if($concepto_sp != 'OTROS'){
                                $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                                $select_pago= $conn_bd->prepare($query_select_pago);
                                $select_pago->execute();
                                $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                                if($existe_concepto>1){
                                    $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    die;
                                }else{
                                    
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud']; 
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                //variables del archivo
                                                $file = $_FILES['file']['tmp_name'];
                                                $name = $_FILES['file']['name'];
                                                $tipe_file = $_FILES['file']['type'];
                                                $name_archivo = $id_pago.'.pdf';
                                                $uploadFileDir = 'PDFLog/';
                                                $dest_path = $uploadFileDir . $name_archivo;
                                                move_uploaded_file($file, $dest_path);
                                                //validacion de archivo correcto
                                                $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                    || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                    ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                        $data = base64_encode(file_get_contents($dest_path));
                                                    
                                                        $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                        ([Documento]
                                                        ,[Tipo]
                                                        ,[Referencia_Nexen]
                                                        ,[Contenedor_guia_economico]
                                                        ,[fechope]
                                                        ,[hora]
                                                        ,[estatus]
                                                        ,[Usuario]
                                                        ,[Id_Pago]
                                                        )
                                                        VALUES
                                                        (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                        $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                        $stmt->bindParam(2, $concepto_sp);
                                                        $stmt->bindParam(3, $referencia_nexen_sp);
                                                        $stmt->bindParam(4, $numero_pedimento);
                                                        $stmt->bindParam(5, $fechaOpe);
                                                        $stmt->bindParam(6, $hora);
                                                        $stmt->bindParam(7, $estatus);
                                                        $stmt->bindParam(8, $name_usuario);
                                                        $stmt->bindParam(9, $id_pago);
                                                        $stmt->execute();
                                                        $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                }
                                            }                
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }
                            }else{
                                
                                $numero_pedimento = $_POST['pedimento_sp'];
                                $razon_social_receptora = $_POST['razon_social_pago'];
                                $rfc = $_POST['rfc_sp_pago'];
                                $ABBA = $_POST['abba_sp_pago'];
                                $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                $cuenta_interbancaria = $_POST ['clabe_sp_pago'];
                                $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                $banco = $_POST['banco_sp_pago'];
                                $domicilio_destino = $_POST['domicilio_sp_pago'];
                                $monto = $_POST['monto_sp'];
                                $tipo_solicitud = $_POST['tipo_solicitud'];
                                $Observaciones = $_POST['observaciones_sp'];
                                date_default_timezone_set('America/Mexico_City');
                                $fechaOpe  = date('Y/m/d');
                                $hora = date('H:i:s');
                                $moneda = $_POST['moneda_sp'];
                                $estatus = 1;
                                //Insertar en Solicitus de Pagos                            
                                $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                            ,[Cliente]
                                                                                            ,[Operador]
                                                                                            ,[Numero_Pedimento]
                                                                                            ,[Razon_Social_Receptora]
                                                                                            ,[RFC]
                                                                                            ,[SWT_ABBA]
                                                                                            ,[Cuenta_Clabe]
                                                                                            ,[Banco_Destino]
                                                                                            ,[Domicilio_Destino]
                                                                                            ,[Concepto]
                                                                                            ,[Monto]
                                                                                            ,[Tipo_Solicitud]
                                                                                            ,[Observaciones]
                                                                                            ,[Fechope]
                                                                                            ,[Hora]
                                                                                            ,[Usuario]
                                                                                            ,[Estatus]
                                                                                            ,[Moneda]
                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                            ,[Ref_proveedor]
                                                                                            ,[Tipo_Operacion])
                                                                                            VALUES
                                                                                            ('$referencia_nexen_sp'
                                                                                            ,'$cliente'
                                                                                            ,'$operador'
                                                                                            ,'$numero_pedimento'
                                                                                            ,'$razon_social_receptora'
                                                                                            ,'$rfc'
                                                                                            ,'$ABBA'
                                                                                            ,'$cuenta_clabe'
                                                                                            ,'$banco'
                                                                                            ,'$domicilio_destino'
                                                                                            ,'$concepto_sp'
                                                                                            ,'$monto'
                                                                                            ,'$tipo_solicitud'
                                                                                            ,'$Observaciones'
                                                                                            ,'$fechaOpe'
                                                                                            ,'$hora'
                                                                                            ,'$name_usuario'
                                                                                            ,'PENDIENTE'
                                                                                            ,'$moneda'
                                                                                            ,'$cuenta_interbancaria'
                                                                                            ,'$referencia_proveedor'
                                                                                            ,'$tipo_trafico')";                                                         
                            
                                $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                
                                if($insertar_solicitud_pago->execute()){
                
                                    $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                    $buscar_id= $conn_bd->prepare($query_id);
                                    if($buscar_id->execute()){
                                        $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                        $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                    
                                        // Verificar si se envió un archivo
                                        if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                        
                                            //variables del archivo
                                            $file = $_FILES['file']['tmp_name'];
                                            $name = $_FILES['file']['name'];
                                            $tipe_file = $_FILES['file']['type'];
                                            $name_archivo = $id_pago.'.pdf';
                
                                            $uploadFileDir = 'PDFLog/';
                                            $dest_path = $uploadFileDir . $name_archivo;
                                            move_uploaded_file($file, $dest_path);
                                        
                                            //validacion de archivo correcto
                                            $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                            if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                            
                                                    $data = base64_encode(file_get_contents($dest_path));
                                                
                                                    $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                    ([Documento]
                                                    ,[Tipo]
                                                    ,[Referencia_Nexen]
                                                    ,[Contenedor_guia_economico]
                                                    ,[fechope]
                                                    ,[hora]
                                                    ,[estatus]
                                                    ,[Usuario]
                                                    ,[Id_Pago]
                                                    )
                                                    VALUES
                                                    (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                
                                                    $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                    $stmt->bindParam(2, $concepto_sp);
                                                    $stmt->bindParam(3, $referencia_nexen_sp);
                                                    $stmt->bindParam(4, $numero_pedimento);
                                                    $stmt->bindParam(5, $fechaOpe);
                                                    $stmt->bindParam(6, $hora);
                                                    $stmt->bindParam(7, $estatus);
                                                    $stmt->bindParam(8, $name_usuario);
                                                    $stmt->bindParam(9, $id_pago);
                                                    $stmt->execute();
                                                
                                                    $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            }
                                        }else{
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        }            
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    }
                                }else{
                                    $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                }
                            } 
                        }else{
                            $arrData = array('status' => false,'msg' =>'ES NECESARIO INGRESAR UN PEDIMENTO','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }
                    }else{ 
                        if(isset($_POST['contenedor_sol_pago_sp']) && !empty($_POST['contenedor_sol_pago_sp'])){ 
                            $contenedor = $_POST['contenedor_sol_pago_sp'];
                            if(!isset($_POST['cuenta_sp_pago'])){$cuenta = "";}else{$cuenta = $_POST['cuenta_sp_pago'];} 
                            if(!isset($_POST['clabe_sp_pago'])){$clabe = "";}else {$clabe = $_POST['clabe_sp_pago'];}
                            if(!isset($_POST['abba_sp_pago'])){$swift ="";}else{$swift =$_POST['abba_sp_pago'];}  
                            if(!isset($_POST['banco_inter_sp_pago'])){$banco ="";}else{$banco =$_POST['banco_inter_sp_pago'];}
                            if(!isset($_POST['domicilio_sp_pago'])){$domicilio_destino ="";}else{$domicilio_destino =$_POST['domicilio_sp_pago'];}
                            $concepto_sp = $_POST['concepto_sp'];
                            if($concepto_sp != 'OTROS'){
                                $query_select_pago = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Concepto ='$concepto_sp' AND Referencia_Nexen='$referencia_nexen_sp'";
                                $select_pago= $conn_bd->prepare($query_select_pago);
                                $select_pago->execute();
                                $existe_concepto = $select_pago -> fetch(PDO::FETCH_ASSOC); 
                                if($existe_concepto>1){
                                    $arrData = array('status' => false,'msg' =>'YA EXISTE UNA SOLICITUD DE PAGO CON ESTE CONCEPTO, EN CASO DE QUE SE REQUIERA UNA SOLICITUD DE PAGO CON EL MISMO CONCEPTO, SELECCIONE LA OPCION DE <strong>OTROS</strong> Y AGREGE LAS OBSERVACIONES PERTINENTES','val'=>'1');
                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                    die;
                                }else{
                                    if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                            $numero_pedimento = $_POST['pedimento_sp'];
                                            $razon_social_receptora = $_POST['razon_social_pago'];
                                            $rfc = $_POST['rfc_sp_pago'];
                                            $ABBA = $_POST['abba_sp_pago'];
                                            $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                            $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                            $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                            $banco = $_POST['banco_sp_pago'];
                                            $domicilio_destino = $_POST['domicilio_sp_pago'];
                                            $monto = $_POST['monto_sp'];
                                            $tipo_solicitud = $_POST['tipo_solicitud'];
                                            $Observaciones = $_POST['observaciones_sp'];
                                            date_default_timezone_set('America/Mexico_City');
                                            $fechaOpe  = date('Y/m/d');
                                            $hora = date('H:i:s');
                                            $moneda = $_POST['moneda_sp'];
                                            $estatus = 1;
                                                //Insertar en Solicitus de Pagos                            
                                            $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                            ,[Cliente]
                                                                                                            ,[Operador]
                                                                                                            ,[Contenedor]
                                                                                                            ,[Numero_Pedimento]
                                                                                                            ,[Razon_Social_Receptora]
                                                                                                            ,[RFC]
                                                                                                            ,[SWT_ABBA]
                                                                                                            ,[Cuenta_Clabe]
                                                                                                            ,[Banco_Destino]
                                                                                                            ,[Domicilio_Destino]
                                                                                                            ,[Concepto]
                                                                                                            ,[Monto]
                                                                                                            ,[Tipo_Solicitud]
                                                                                                            ,[Observaciones]
                                                                                                            ,[Fechope]
                                                                                                            ,[Hora]
                                                                                                            ,[Usuario]
                                                                                                            ,[Estatus]
                                                                                                            ,[Moneda]
                                                                                                            ,[CLABE_INTERBANCARIA]
                                                                                                            ,[Ref_proveedor]
                                                                                                            ,[Tipo_Operacion])
                                                                                                            VALUES
                                                                                                            ('$referencia_nexen_sp'
                                                                                                            ,'$cliente'
                                                                                                            ,'$operador'
                                                                                                            ,'$contenedor'
                                                                                                            ,'$numero_pedimento'
                                                                                                            ,'$razon_social_receptora'
                                                                                                            ,'$rfc'
                                                                                                            ,'$ABBA'
                                                                                                            ,'$cuenta_clabe'
                                                                                                            ,'$banco'
                                                                                                            ,'$domicilio_destino'
                                                                                                            ,'$concepto_sp'
                                                                                                            ,'$monto'
                                                                                                            ,'$tipo_solicitud'
                                                                                                            ,'$Observaciones'
                                                                                                            ,'$fechaOpe'
                                                                                                            ,'$hora'
                                                                                                            ,'$name_usuario'
                                                                                                            ,'PENDIENTE'
                                                                                                            ,'$moneda'
                                                                                                            ,'$cuenta_interbancaria'
                                                                                                            ,'$referencia_proveedor'
                                                                                                            ,'$tipo_trafico')";                                                         
                                
                                            $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                            if($insertar_solicitud_pago->execute()){
                                                $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                                $buscar_id= $conn_bd->prepare($query_id);
                                                if($buscar_id->execute()){
                                                    $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                    $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                    // Verificar si se envió un archivo
                                                    $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        
                                    }else{
                                        $numero_pedimento = $_POST['pedimento_sp'];
                                        $razon_social_receptora = $_POST['razon_social_pago'];
                                        $rfc = $_POST['rfc_sp_pago'];
                                        $ABBA = $_POST['abba_sp_pago'];
                                        $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                        $banco = $_POST['banco_sp_pago'];
                                        $domicilio_destino = $_POST['domicilio_sp_pago'];
                                        $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                        $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                        $monto = $_POST['monto_sp'];
                                        $tipo_solicitud = $_POST['tipo_solicitud'];
                                        $Observaciones = $_POST['observaciones_sp'];
                                        date_default_timezone_set('America/Mexico_City');
                                        $fechaOpe  = date('Y/m/d');
                                        $hora = date('H:i:s');
                                        $moneda = $_POST['moneda_sp'];
                                        $estatus = 1;
                                            //Insertar en Solicitus de Pagos                            
                                        $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                        ,[Cliente]
                                                                                                        ,[Operador]
                                                                                                        ,[Contenedor]
                                                                                                        ,[Numero_Pedimento]
                                                                                                        ,[Razon_Social_Receptora]
                                                                                                        ,[RFC]
                                                                                                        ,[SWT_ABBA]
                                                                                                        ,[Cuenta_Clabe]
                                                                                                        ,[Banco_Destino]
                                                                                                        ,[Domicilio_Destino]
                                                                                                        ,[Concepto]
                                                                                                        ,[Monto]
                                                                                                        ,[Tipo_Solicitud]
                                                                                                        ,[Observaciones]
                                                                                                        ,[Fechope]
                                                                                                        ,[Hora]
                                                                                                        ,[Usuario]
                                                                                                        ,[Estatus]
                                                                                                        ,[Moneda]
                                                                                                        ,[CLABE_INTERBANCARIA]
                                                                                                        ,[Ref_proveedor]
                                                                                                        ,[Tipo_Operacion])
                                                                                                        VALUES
                                                                                                        ('$referencia_nexen_sp'
                                                                                                        ,'$cliente'
                                                                                                        ,'$operador'
                                                                                                        ,'$contenedor'
                                                                                                        ,'$numero_pedimento'
                                                                                                        ,'$razon_social_receptora'
                                                                                                        ,'$rfc'
                                                                                                        ,'$ABBA'
                                                                                                        ,'$cuenta_clabe'
                                                                                                        ,'$banco'
                                                                                                        ,'$domicilio_destino'
                                                                                                        ,'$concepto_sp'
                                                                                                        ,'$monto'
                                                                                                        ,'$tipo_solicitud'
                                                                                                        ,'$Observaciones'
                                                                                                        ,'$fechaOpe'
                                                                                                        ,'$hora'
                                                                                                        ,'$name_usuario'
                                                                                                        ,'PENDIENTE'
                                                                                                        ,'$moneda'
                                                                                                        ,'$cuenta_interbancaria'
                                                                                                        ,'$referencia_proveedor'
                                                                                                        ,'$tipo_trafico')";                                                         
                            
                                        $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                        if($insertar_solicitud_pago->execute()){
                                            $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                            $buscar_id= $conn_bd->prepare($query_id);
                                            if($buscar_id->execute()){
                                                $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                                $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                                // Verificar si se envió un archivo
                                                if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                    //variables del archivo
                                                    $file = $_FILES['file']['tmp_name'];
                                                    $name = $_FILES['file']['name'];
                                                    $tipe_file = $_FILES['file']['type'];
                                                    $name_archivo = $id_pago.'.pdf';
                                                    $uploadFileDir = 'PDFLog/';
                                                    $dest_path = $uploadFileDir . $name_archivo;
                                                    move_uploaded_file($file, $dest_path);
                                                    //validacion de archivo correcto
                                                    $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                    if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                        || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                        ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                            $data = base64_encode(file_get_contents($dest_path));
                                                        
                                                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                            ([Documento]
                                                            ,[Tipo]
                                                            ,[Referencia_Nexen]
                                                            ,[Contenedor_guia_economico]
                                                            ,[fechope]
                                                            ,[hora]
                                                            ,[estatus]
                                                            ,[Usuario]
                                                            ,[Id_Pago]
                                                            )
                                                            VALUES
                                                            (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                            $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                            $stmt->bindParam(2, $concepto_sp);
                                                            $stmt->bindParam(3, $referencia_nexen_sp);
                                                            $stmt->bindParam(4, $contenedor);
                                                            $stmt->bindParam(5, $fechaOpe);
                                                            $stmt->bindParam(6, $hora);
                                                            $stmt->bindParam(7, $estatus);
                                                            $stmt->bindParam(8, $name_usuario);
                                                            $stmt->bindParam(9, $id_pago);
                                                            $stmt->execute();
                                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                            die;
                                                    }else{
                                                        $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                    }
                                                }                
                                            }else{
                                                $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                die;
                                            }
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }
                                }
                            }else{
                                if(isset($_FILES['file']) && $_FILES['file']['error'] === 4){
                                    
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Contenedor]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$contenedor'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }else{
                                    $numero_pedimento = $_POST['pedimento_sp'];
                                    $razon_social_receptora = $_POST['razon_social_pago'];
                                    $rfc = $_POST['rfc_sp_pago'];
                                    $ABBA = $_POST['abba_sp_pago'];
                                    $cuenta_clabe = $_POST['cuenta_sp_pago'];
                                    $cuenta_interbancaria = $_POST['clabe_sp_pago'];
                                    $referencia_proveedor = $_POST['Referencia_Proveedor_pago'];
                                    $banco = $_POST['banco_sp_pago'];
                                    $domicilio_destino = $_POST['domicilio_sp_pago'];
                                    $monto = $_POST['monto_sp'];
                                    $tipo_solicitud = $_POST['tipo_solicitud'];
                                    $Observaciones = $_POST['observaciones_sp'];
                                    date_default_timezone_set('America/Mexico_City');
                                    $fechaOpe  = date('Y/m/d');
                                    $hora = date('H:i:s');
                                    $moneda = $_POST['moneda_sp'];
                                    $estatus = 1;
                                        //Insertar en Solicitus de Pagos                            
                                    $query_solicitud_pago = "INSERT INTO [dbo].[FK_Solicitud_Pago] ([Referencia_Nexen]
                                                                                                    ,[Cliente]
                                                                                                    ,[Operador]
                                                                                                    ,[Contenedor]
                                                                                                    ,[Numero_Pedimento]
                                                                                                    ,[Razon_Social_Receptora]
                                                                                                    ,[RFC]
                                                                                                    ,[SWT_ABBA]
                                                                                                    ,[Cuenta_Clabe]
                                                                                                    ,[Banco_Destino]
                                                                                                    ,[Domicilio_Destino]
                                                                                                    ,[Concepto]
                                                                                                    ,[Monto]
                                                                                                    ,[Tipo_Solicitud]
                                                                                                    ,[Observaciones]
                                                                                                    ,[Fechope]
                                                                                                    ,[Hora]
                                                                                                    ,[Usuario]
                                                                                                    ,[Estatus]
                                                                                                    ,[Moneda]
                                                                                                    ,[CLABE_INTERBANCARIA]
                                                                                                    ,[Ref_proveedor]
                                                                                                    ,[Tipo_Operacion])
                                                                                                    VALUES
                                                                                                    ('$referencia_nexen_sp'
                                                                                                    ,'$cliente'
                                                                                                    ,'$operador'
                                                                                                    ,'$contenedor'
                                                                                                    ,'$numero_pedimento'
                                                                                                    ,'$razon_social_receptora'
                                                                                                    ,'$rfc'
                                                                                                    ,'$ABBA'
                                                                                                    ,'$cuenta_clabe'
                                                                                                    ,'$banco'
                                                                                                    ,'$domicilio_destino'
                                                                                                    ,'$concepto_sp'
                                                                                                    ,'$monto'
                                                                                                    ,'$tipo_solicitud'
                                                                                                    ,'$Observaciones'
                                                                                                    ,'$fechaOpe'
                                                                                                    ,'$hora'
                                                                                                    ,'$name_usuario'
                                                                                                    ,'PENDIENTE'
                                                                                                    ,'$moneda'
                                                                                                    ,'$cuenta_interbancaria'
                                                                                                    ,'$referencia_proveedor'
                                                                                                    ,'$tipo_trafico')";                                                         
                        
                                    $insertar_solicitud_pago= $conn_bd->prepare($query_solicitud_pago);
                                    if($insertar_solicitud_pago->execute()){
                                        $query_id = "SELECT @@IDENTITY AS ID_PAGO_SOLICITUD";
                                        $buscar_id= $conn_bd->prepare($query_id);
                                        if($buscar_id->execute()){
                                            $result_buscar_id = $buscar_id -> fetch(PDO::FETCH_ASSOC); 
                                            $id_pago = $result_buscar_id['ID_PAGO_SOLICITUD'];
                                            // Verificar si se envió un archivo
                                            if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                                //variables del archivo
                                                $file = $_FILES['file']['tmp_name'];
                                                $name = $_FILES['file']['name'];
                                                $tipe_file = $_FILES['file']['type'];
                                                $name_archivo = $id_pago.'.pdf';
                                                $uploadFileDir = 'PDFLog/';
                                                $dest_path = $uploadFileDir . $name_archivo;
                                                move_uploaded_file($file, $dest_path);
                                                //validacion de archivo correcto
                                                $tipoArchivo = '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                                                if($tipoArchivo === ".pdf" || $tipoArchivo === ".PDF"
                                                    || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
                                                    ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG"){
                                                        $data = base64_encode(file_get_contents($dest_path));
                                                    
                                                        $stmt = $conn_bd->prepare("INSERT INTO [dbo].[Documentos_Solicitud_Pagos]
                                                        ([Documento]
                                                        ,[Tipo]
                                                        ,[Referencia_Nexen]
                                                        ,[Contenedor_guia_economico]
                                                        ,[fechope]
                                                        ,[hora]
                                                        ,[estatus]
                                                        ,[Usuario]
                                                        ,[Id_Pago]
                                                        )
                                                        VALUES
                                                        (CONVERT(VARBINARY(MAX),?),?,?,?,?,?,?,?,?)");
                                                        $stmt->bindParam(1, $data, PDO::PARAM_STR);
                                                        $stmt->bindParam(2, $concepto_sp);
                                                        $stmt->bindParam(3, $referencia_nexen_sp);
                                                        $stmt->bindParam(4, $contenedor);
                                                        $stmt->bindParam(5, $fechaOpe);
                                                        $stmt->bindParam(6, $hora);
                                                        $stmt->bindParam(7, $estatus);
                                                        $stmt->bindParam(8, $name_usuario);
                                                        $stmt->bindParam(9, $id_pago);
                                                        $stmt->execute();
                                                        $arrData = array('status' => true,'msg' =>'SE GUARDO CORRECTAMENTE LA SOLICITUD','id'=>$id_pago);
                                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                        die;
                                                }else{
                                                    $arrData = array('status' => false,'msg' =>'EL ARCHIVO NO ES EL FORMATO CORRECTO','val'=>'2');
                                                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                                    die;
                                                }
                                            }                
                                        }else{
                                            $arrData = array('status' => false,'msg' =>'OCURRIO UN ERORR AL OBTENER EL ID DE PAGO','val'=>'3');
                                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                            die;
                                        }
                                    }else{
                                        $arrData = array('status' => false,'msg' =>'NO SE PUDO GUARDAR LA SOLICITUD','val'=>'4');
                                        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                                        die;
                                    }
                                }
                            }
                        }else{
                            $arrData = array('status' => false,'msg' =>'ES NECESARIO TENER UN CONTENEDOR','val'=>'1');
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        }     
                    }
                }else{
                    $arrData = array('status' => false,'msg' =>'LAS CREDENCIALES NO SON CORRECTAS','val'=>'5');
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                    die;
                }
            }
        }
    }
}
?>
