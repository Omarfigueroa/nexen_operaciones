<?php

ob_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Pagos</title>
    <style>
    .contenedor img,
    h1 {
        display: inline-block;
        vertical-align: bottom;
    }

    .contenedor div,
    input,
    label {
        display: inline-block;
        padding-top: 15px;
        vertical-align: middle;
    }

    .contenedor div {
        margin-left: 400px;
    }

    .contenedor input {
        margin-top: 3px;
    }

    .pago h3,
    input {
        display: inline-block;
        vertical-align: middle;

    }

    .pago input {
        margin-top: -8px;
        vertical-align: middle;
        border-style: none;
        width: 500px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .justificacion textarea {
        text-align: center;
        border-style: none;
    }

    .autorizado label {
        margin-left: 35px;
    }

    .autorizado input {
        align-items: center;
        border-style: none;
        margin-top: 5px;
        vertical-align: middle;
    }

    .centrar {
        text-align: center;
    }

    .paddin {
        padding-right: 200px;
    }

    tr.espacio-tr {
        padding-bottom: 15px;
        margin-bottom: 200px;
    }
    </style>


</head>

<body>
    <?php
        require '../conexion/bd.php';
        $id_pago_solicitud = $_GET['id'];

        $query_valida = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Num_Operacion = :id_pago";
        $valida_pdf = $conn_bd->prepare($query_valida);
        $valida_pdf->bindParam(':id_pago',$id_pago_solicitud);
        $valida_pdf->execute();
        $result_valida = $valida_pdf->fetch(PDO::FETCH_ASSOC);

        $referencia_nexen_contenedor = $result_valida['Referencia_Nexen'];
        $valida_tipo = $result_valida['Tipo_Solicitud'];


        if($valida_tipo != 'FINANCIADO'){
            $query ="SELECT F.*,D.Contenedor_guia_economico,O.Contenedor_2 FROM [dbo].[FK_Solicitud_Pago] F  INNER JOIN [dbo].[Documentos_Solicitud_Pagos] D ON F.Num_Operacion = D.Id_Pago INNER JOIN [dbo].[Operacion_nexen] O ON O.REFERENCIA_NEXEN = F.Referencia_Nexen WHERE  F.Num_Operacion = '{$id_pago_solicitud}'";
            $resul = $conn_bd->prepare($query);
            $resul->execute();
            $datos = $resul->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datos as $dato) {
                $referencia_nexen =  $dato['Referencia_Nexen'];
                $nombre_cliente = $dato['Cliente'];
                $operador = $dato['Operador'];
                $fecha = $dato['Fechope'];
                $Contenedor = $dato['Contenedor'];
                $contenedor_2 = $dato['Contenedor_2'];
                $Numero_Economico = $dato['Numero_Economico'];
                $Gia_House = $dato['Guia_House'];
                $Contenedor_guia_economico = $dato['Contenedor_guia_economico'];
                $Razon_social_Receptora = $dato['Razon_Social_Receptora'];
                $RFC = $dato['RFC'];
                $SWT_ABBA = $dato['SWT_ABBA'];
                $Cuenta_Clabe = $dato['Cuenta_Clabe'];
                $cuenta_interbancaria = $dato['CLABE_INTERBANCARIA'];
                $Banco_Destino = $dato['Banco_Destino'];
                $Domicilio_Destino = $dato['Domicilio_Destino'];
                $Domicilio_Razon_Receptora = $dato['Domicilio_Razon_Receptora'];
                $Banco_Receptor = $dato['Banco_Receptor'];
                $Concepto = $dato['Concepto'];
                $Monto = $dato['Monto'];
                $Tipo_Solicitud = $dato['Tipo_Solicitud'];
                $observaciones = $dato['Observaciones'];
                $tipo_pago = $dato['Tipo_Operacion'];
                $Hora = $dato['Hora'];
                $moneda = $dato['Moneda'];
                $referencia_proveedor = $dato['Ref_proveedor'];
                $Numero_pedimento = $dato['Numero_Pedimento'];
                $valor_formateado = number_format($Monto, 2, '.', ',');
            }
        }else{
            $query ="SELECT S.*,O.Contenedor_2 FROM [dbo].[FK_Solicitud_Pago] S INNER JOIN [dbo].[Operacion_nexen] O ON O.REFERENCIA_NEXEN = s.Referencia_Nexen  WHERE  S.Num_Operacion = '{$id_pago_solicitud}'";
            $resul = $conn_bd->prepare($query);
            $resul->execute();
            $datos = $resul->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datos as $dato) {
                $referencia_nexen =  $dato['Referencia_Nexen'];
                $nombre_cliente = $dato['Cliente'];
                $operador = $dato['Operador'];
                $fecha = $dato['Fechope'];
                $contenedor_2 = $dato['Contenedor_2'];
                $Contenedor = $dato['Contenedor'];
                $Numero_Economico = $dato['Numero_Economico'];
                $Gia_House = $dato['Guia_House'];
                $Razon_social_Receptora = $dato['Razon_Social_Receptora'];
                $RFC = $dato['RFC'];
                $SWT_ABBA = $dato['SWT_ABBA'];
                $Cuenta_Clabe = $dato['Cuenta_Clabe'];
                $cuenta_interbancaria = $dato['CLABE_INTERBANCARIA'];
                $Banco_Destino = $dato['Banco_Destino'];
                $Domicilio_Destino = $dato['Domicilio_Destino'];
                $Domicilio_Razon_Receptora = $dato['Domicilio_Razon_Receptora'];
                $Banco_Receptor = $dato['Banco_Receptor'];
                $Concepto = $dato['Concepto'];
                $Monto = $dato['Monto'];
                $Tipo_Solicitud = $dato['Tipo_Solicitud'];
                $observaciones = $dato['Observaciones'];
                $tipo_pago = $dato['Tipo_Operacion'];
                $Hora = $dato['Hora'];
                $moneda = $dato['Moneda'];
                $Numero_pedimento = $dato['Numero_Pedimento'];
                $referencia_proveedor = $dato['Ref_proveedor'];
                $valor_formateado = number_format($Monto, 2, '.', ',');
            }
        }
     

    ?>

    <div class="contenedor">
        <img src="http://localhost/nexen_operaciones/img/logoNexen.png" class="img-fluid my-3" alt="profile">
        <h1>Solicitud de Pagos Proveedor</h1>
        <div>
            <label for="Referencia">REFERENCIA: </label>
            <input type="text" style=" height: 20px;  border-style: none; " value="<?php echo $referencia_nexen?>">
        </div>
    </div>
    <div class="pago">
        <div>
            <h3>Pago por:</h3>
            <input id="enaltam" type="text" value="<?php echo $operador?>">
        </div>
        <div>
            <h3>Pagar a: </h3>
            <input id="enaltam" type="text" value="<?php echo $Razon_social_Receptora?>">
        </div>
    </div>
    <div>
        <table>
            <tr class="espacio-tr">
                <td class="paddin">FECHA: </td>
                <td class="centrar"><?php echo $fecha?></td>
            </tr>
            <tr class="espacio-tr">
                <td>CLIENTE: </td>
                <td class="centrar"><?php echo $nombre_cliente?></td>
            </tr>
            <?php if($tipo_pago != 'VIRTUAL'){
                    if($Contenedor != NULL){?>
                <tr class="espacio-tr">
                    <td>CONTENEDOR/GUIA: </td>
                    <td class="centrar"><?php echo $Contenedor?></td>
                </tr>
                <?php }else if($Numero_Economico != NULL){?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Numero_Economico?></td>
                    </tr>
                <?php } else if($Gia_House != NULL){ ?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Gia_House?></td>
                    </tr>
                <?php } else if($Contenedor_guia_economico != NULL ){ ?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Contenedor_guia_economico?></td>
                    </tr>
                <?php } 
                }else {
                    if($Numero_pedimento != NULL){ ?>
                    <tr class="espacio-tr">
                        <td>PEDIMENTO: </td>
                        <td class="centrar"><?php echo $Numero_pedimento?></td>
                    </tr>
            <?php   }
                } 
            ?> 
             <tr class="espacio-tr">
                <td>CONTENEDOR 2: </td>
                <td class="centrar"><?php echo $contenedor_2?></td>
            </tr>
            <tr>
                <td>RFC: </td>
                <td class="centrar"><?php echo $RFC?></td>
            </tr>
            <tr>
                <td>RAZON SOCIAL <br>RECEPTORA: </td>
                <td class="centrar"><?php echo $Razon_social_Receptora?></td>
            </tr>

            <tr>
                <td>SWIFT / ABA: </td>
                <td class="centrar"><?php echo $SWT_ABBA?></td>
            </tr>
            <tr>
                <td>CUENTA: </td>
                <td class="centrar"><?php echo $Cuenta_Clabe?></td>
            </tr>
            <tr>
                <td>CUENTA INTERBANCARIA : </td>
                <td class="centrar"><?php echo $cuenta_interbancaria?></td>
            </tr>
            <tr>
                <td>BANCO DESTINO: </td>
                <td class="centrar"><?php echo $Banco_Destino?></td>
            </tr>
            <tr>
                <td>DOMICILIO <br>COMPLETO: </td>
                <td class="centrar"><?php echo $Domicilio_Destino?></td>
            </tr>
            <tr>
                <td>BANCO<br>INTERMEDIARIO: </td>
                <td class="centrar"><?php echo $Banco_Receptor?></td>
            </tr>
            <tr>
                <td>DOMICILIO<br>COMPLETO: </td>
                <td class="centrar"><?php echo $Domicilio_Razon_Receptora?></td>
            </tr>
            <tr>
                <td>CONCEPTO: </td>
                <td class="centrar"><?php echo $Concepto?></td>
            </tr>
            <tr>
                <td>MONEDA: </td>
                <td class="centrar"><?php echo $moneda?></td>
            </tr>
            <tr>
                <td>MONTO: </td>
                <td class="centrar"><?php echo '$ '.$valor_formateado?></td>
            </tr>
            <tr>
                <td>ANTICIPO/FINANCIADO: </td>
                <td class="centrar"><?php echo $Tipo_Solicitud?></td>
            </tr>
            <tr>
                <td>REFERENCIA PROVEEDOR: </td>
                <td class="centrar"><?php echo $referencia_proveedor?></td>
            </tr>
        </table>
    </div>
    <?php if($Tipo_Solicitud === 'ANTICIPO' || $Tipo_Solicitud === ''){?>
    <div class="justificacion">
        <label for="justificacion">JUSTIFICACION: </label>
        <textarea cols="50" rows="50"><?php echo $observaciones ?></textarea>
    </div>
    <div class="autorizado">
        <label for="autorizado">Autorizado por: <strong>Rodolfo SanJuan SanJuan</strong></label>
        <label for="Fecha">Fecha: </label>
        <input type="text" value="<?php echo $fecha?>">
    </div>
    <?php }else if($Tipo_Solicitud === 'FINANCIADO'){?>
    <div class="justificacion">
        <label for="justificacion">JUSTIFICACION: </label>
        <textarea cols="50" rows="50"><?php echo $observaciones ?></textarea>
    </div>
    <div class="autorizado">
        <label for="autorizado">Autorizado por: <strong>Liliana Rodriguez Barros</strong></label>
        <label for="Fecha">Fecha: </label>
        <input type="text" value="<?php echo $fecha?>">
    </div>
    <?php }?>

</body>

</html>

<?php
$html = ob_get_clean();

require_once '../dompdf/autoload.inc.php';


use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper("letter");

$dompdf->render();

$pdfContent = $dompdf->output();


$nombreArchivo = $referencia_nexen.$id_pago_solicitud.'_pago.pdf';
$nombre_archivo_solicitud =$id_pago_solicitud.'.pdf';


$rutaArchivo ='../reportes/solicitudes_pagos/' . $nombreArchivo;
$rutaArchivo_solicitud ='PDFLog/' . $nombre_archivo_solicitud;

file_put_contents($rutaArchivo, $pdfContent);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 // LIBRERTIAS PARA ENVIAR CORREO
 require '../PHPMailer/PHPMailer.php';
 require '../PHPMailer/Exception.php';
 require '../PHPMailer/SMTP.php';


 session_start();
 
 $user_nexen = $_SESSION['usuario_nexen'];
//  $user_nexen = 'LISAN';
 $query_user_name ="SELECT * FROM [dbo].[Usuarios_Login_web] WHERE Usuario = '$user_nexen'";
 $select_user_name = $conn_bd->prepare($query_user_name);

 if($select_user_name -> execute()){
     $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
     $name_usuario = $result_name['nombre_usuario'];
     $sucursal = $result_name['sucursal'];
     $correo_operador = $result_name['correo'];
 }
 //Validar si nombre viene vacio
 if(!$name_usuario){
     header('Location: login.php');
 }

 if($Tipo_Solicitud === 'ANTICIPO' || $Tipo_Solicitud === ''){
        $query_correo ="SELECT Usuario,correo,area,Estatus FROM [dbo].[Usuarios_Login_Web] where (area = 'finanzas' OR area = 'Supervisor' OR area = 'Auxiliar') and estatus = 'A'";
        $query_correo =$conn_bd->prepare($query_correo);
        $query_correo->execute();
        $usuarios =$query_correo->fetchAll(PDO::FETCH_ASSOC);
        // Crear un arreglo para almacenar los correos
        $correos = array();

        // Iterar sobre los resultados y guardar los correos en el arreglo
        foreach ($usuarios as $usuario) {
            $correos[] = $usuario['correo'];
           
        }
        $correos[] = $correo_operador;
        $correos[] = 'gerencia.desarrollo@nexen-elog.com';
        $correos[] = 'desarrollo.5@nexen-elog.com';
        $correos[] = 'contacto2@nexen-elog.com';

 }else{
        $query_correo ="SELECT Usuario,correo,area,Estatus FROM [dbo].[Usuarios_Login_Web] where (area = 'finanzas' OR area = 'Supervisor' OR area = 'Direccion' OR area = 'Auxiliar') and estatus = 'A'";
        $query_correo =$conn_bd->prepare($query_correo);
        $query_correo->execute();
        $usuarios =$query_correo->fetchAll(PDO::FETCH_ASSOC);
        // Crear un arreglo para almacenar los correos
        $correos = array();
        // Iterar sobre los resultados y guardar los correos en el arreglo
        foreach ($usuarios as $usuario) {
            $correos[] = $usuario['correo'];
           
        }
        $correos[] = $correo_operador;
        $correos[] = 'gerencia.desarrollo@nexen-elog.com';
        $correos[] = 'desarrollo.5@nexen-elog.com';
        $correos[] = 'contacto2@nexen-elog.com';
 }
   


 $mail = new PHPMailer(true);
 // //ENVIAR CORREO A USUARIO
 
        $mail->isSMTP(); 
        $mail->CharSet = "UTF-8";                                               
        $mail->Host       = 'smtp.titan.email';                     
        $mail ->SMTPAuth  = true;                                 
        $mail->Username   = 'nexen.admin@nexenelog.com'; 
        $mail->Password   = 'Nexen2022#';                       
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 465; 
            
            // $mail->Host       = 'nexen-elog.com';                     
            // $mail ->SMTPAuth  = true;                                 
            // $mail->Username   = 'notificaciones.pago@nexen-elog.com'; 
            // $mail->Password   = 'CALAVERIN**/8546';                       
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
            // $mail->Port       = 465;
 
         //Direccion de la cuenta de donde se enviará el mensaje 
         $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
         foreach($correos as $correo){
            $mail->addAddress($correo,''); 
         }
         
         //Copia oculta a nuestro correo
         $mail->addBCC('nexen.admin@nexenelog.com');    

             //Email receptor
        if (file_exists($rutaArchivo_solicitud)) {
            $mail->addAttachment($rutaArchivo, $nombreArchivo);
            $mail->addAttachment($rutaArchivo_solicitud, $nombre_archivo_solicitud);

        } else {
            $mail->addAttachment($rutaArchivo, $nombreArchivo);
        }
        
 
         //Mensaje
         $mail->isHTML(true);  
         $mail->CharSet = 'UTF-8';    

        if($tipo_pago != 'VIRTUAL'){

        
            if($Contenedor != NULL){
                $mail->Subject = 'Solicitud de Pago. Contenedor: '.$Contenedor.'. Referencia_Nexen: '.$referencia_nexen.'. Numero Pedimento: '.$Numero_pedimento.'.';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                La solicitud de pago se registro correctamente por el Usuario: '.$name_usuario.' y Sucursal: '.$sucursal.'. <br>
                <br>
                Pago por: <b>'.$operador.'</b>.<br>
                Pago a: <b>'.$Razon_social_Receptora.'</b>.<br>
                <br>
                <b> DATOS DE LA SOLICITUD: </b><br>
                Pagado por: '.$operador.'
                <br>    
                <table border="1">
                    <thead>
                        <tr>
                            <th scope="row"> REFERENCIA </th>
                            <th scope="row"> CONTENEDOR / GUIA / ECO / PEDIMENTO </th>
                            <th scope="row"> NOMBRE CLIENTE </th>
                            <th scope="row"> TIPO PAGO </th>
                            <th scope="row"> MONTO </th>
                            <th scope="row"> CONCEPTO </th>
                            <th scope="row"> OBSERVACIONES </th>
                            <th scope="row"> MONEDA </th>
                            <th scope="row"> REFERENCIA PROVEEDOR </th>
                            <th scope="row"> BANCO </th>
                            <th scope="row"> CONTENEDOR 2 </th>
                    
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td > '.$referencia_nexen.'</td>
                            <td> '.$Contenedor.'</td>
                            <td> '.$nombre_cliente.'</td>
                            <td> '.$Tipo_Solicitud.'</td>
                            <td> $'.$valor_formateado.'</td>
                            <td> '.$Concepto.'</td>
                            <td> '.$observaciones.'</td>
                            <td> '.$moneda.'</td>
                            <td> '.$referencia_proveedor.'</td>
                            <td> '.$Banco_Destino.'</td>
                            <td> '.$contenedor_2.'</td>

                            

        
        
                        </tr>
                    </tbody>
                </table>
            
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
                $javascriptCode = "<script>
                    var link = document.createElement('a');
                    link.href = '$rutaArchivo';
                    link.target = '_blank';
                    link.download = '$nombreArchivo';
                    link.click();
                </script>";
        
                // Imprimir el código JavaScript
                echo $javascriptCode;
            }else if($Numero_Economico != NULL){
                $mail->Subject = 'Solicitud de Pago. Numero Economico: '.$Numero_Economico.'. Referencia_Nexen: '.$referencia_nexen.'. Numero Pedimento: '.$Numero_pedimento.'.';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                La solicitud de pago se registro correctamente por el Usuario: '.$name_usuario.' y Sucursal: '.$sucursal.'. <br>
                <br>
                Pago por: <b>'.$operador.'</b>.<br>
                Pago a: <b>'.$Razon_social_Receptora.'</b>.<br>
                <br>

                <b> DATOS DE LA SOLICITUD: </b><br>
        
        
                <table border="1">
                    <thead>
                        <tr>
                            <th scope="row"> REFERENCIA </th>
                            <th scope="row"> CONTENEDOR / GUIA / ECO / PEDIMENTO </th>
                            <th scope="row"> NOMBRE CLIENTE </th>
                            <th scope="row"> TIPO PAGO </th>
                            <th scope="row"> MONTO </th>
                            <th scope="row"> CONCEPTO </th>
                            <th scope="row"> OBSERVACIONES </th>
                            <th scope="row"> MONEDA </th>
                            <th scope="row"> REFERENCIA PROVEEDOR </th>
                            <th scope="row"> BANCO </th>
                            <th scope="row"> CONTENEDOR 2 </th>

                        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td > '.$referencia_nexen.'</td>
                            <td> '.$Numero_Economico.'</td>
                            <td> '.$nombre_cliente.'</td>
                            <td> '.$Tipo_Solicitud.'</td>
                            <td> $'.$valor_formateado.'</td>
                            <td> '.$Concepto.'</td>
                            <td> '.$observaciones.'</td>
                            <td> '.$moneda.'</td>
                            <td> '.$referencia_proveedor.'</td>
                            <td> '.$Banco_Destino.'</td>
                            <td> '.$contenedor_2.'</td>
                        
        
        
                        </tr>
                    </tbody>
                </table>
            
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
                $javascriptCode = "<script>
                    var link = document.createElement('a');
                    link.href = '$rutaArchivo';
                    link.target = '_blank';
                    link.download = '$nombreArchivo';
                    link.click();
                </script>";
        
                // Imprimir el código JavaScript
                echo $javascriptCode;
            }else if($Gia_House != NULL){
                $mail->Subject = 'Solicitud de Pago. Guia House: '.$Gia_House.'. Referencia_Nexen: '.$referencia_nexen.'. Numero Pedimento: '.$Numero_pedimento.'.';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                La solicitud de pago se registro correctamente por el Usuario: '.$name_usuario.' y Sucursal: '.$sucursal.'. <br>
                <br>
                Pago por: <b>'.$operador.'</b>.<br>
                Pago a: <b>'.$Razon_social_Receptora.'</b>.<br>
                <br>

                <b> DATOS DE LA SOLICITUD: </b><br>
        
        
                <table border="1">
                    <thead>
                        <tr>
                            <th scope="row"> REFERENCIA </th>
                            <th scope="row"> CONTENEDOR / GUIA / ECO / PEDIMENTO</th>
                            <th scope="row"> NOMBRE CLIENTE </th>
                            <th scope="row"> TIPO PAGO </th>
                            <th scope="row"> MONTO </th>
                            <th scope="row"> CONCEPTO </th>
                            <th scope="row"> OBSERVACIONES </th>
                            <th scope="row"> MONEDA </th>
                            <th scope="row"> REFERENCIA PROVEEDOR </th>
                            <th scope="row"> BANCO </th>
                            <th scope="row"> CONTENEDOR 2 </th>

                        
        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td > '.$referencia_nexen.'</td>
                            <td> '.$Gia_House.'</td>
                            <td> '.$nombre_cliente.'</td>
                            <td> '.$Tipo_Solicitud.'</td>
                            <td> $'.$valor_formateado.'</td>
                            <td> '.$Concepto.'</td>
                            <td> '.$observaciones.'</td>
                            <td> '.$moneda.'</td>
                            <td> '.$referencia_proveedor.'</td>
                            <td> '.$Banco_Destino.'</td>
                            <td> '.$contenedor_2.'</td>
                    
        
        
                        </tr>
                    </tbody>
                </table>
            
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
                $javascriptCode = "<script>
                    var link = document.createElement('a');
                    link.href = '$rutaArchivo';
                    link.target = '_blank';
                    link.download = '$nombreArchivo';
                    link.click();
                </script>";
        
                // Imprimir el código JavaScript
                echo $javascriptCode;
            }else if($Contenedor_guia_economico != NULL){
                $mail->Subject = 'Solicitud de Pago. Contenedor_gia_economico: '.$Contenedor_guia_economico.'. Referencia_Nexen: '.$referencia_nexen.'. Numero Pedimento: '.$Numero_pedimento.'.';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                La solicitud de pago se registro correctamente por el Usuario: '.$name_usuario.' y Sucursal: '.$sucursal.'. <br>
                <br>
                Pago por: <b>'.$operador.'</b>.<br>
                Pago a: <b>'.$Razon_social_Receptora.'</b>.<br>
                <br>

                <b> DATOS DE LA SOLICITUD: </b><br>
        
        
                <table border="1">
                    <thead>
                        <tr>
                            <th scope="row"> REFERENCIA </th>
                            <th scope="row"> CONTENEDOR / GUIA / ECO / PEDIMENTO</th>
                            <th scope="row"> NOMBRE CLIENTE </th>
                            <th scope="row"> TIPO PAGO </th>
                            <th scope="row"> MONTO </th>
                            <th scope="row"> CONCEPTO </th>
                            <th scope="row"> OBSERVACIONES </th>
                            <th scope="row"> MONEDA </th>
                            <th scope="row"> REFERENCIA PROVEEDOR </th>
                            <th scope="row"> BANCO </th>
                            <th scope="row"> CONTENEDOR 2 </th>

                        
        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td > '.$referencia_nexen.'</td>
                            <td> '.$Contenedor_guia_economico.'</td>
                            <td> '.$nombre_cliente.'</td>
                            <td> '.$Tipo_Solicitud.'</td>
                            <td> $'.$valor_formateado.'</td>
                            <td> '.$Concepto.'</td>
                            <td> '.$observaciones.'</td>
                            <td> '.$moneda.'</td>
                            <td> '.$referencia_proveedor.'</td>
                            <td> '.$Banco_Destino.'</td>
                            <td> '.$contenedor_2.'</td>
                        
        
        
                        </tr>
                    </tbody>
                </table>
            
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
                $javascriptCode = "<script>
                    var link = document.createElement('a');
                    link.href = '$rutaArchivo';
                    link.target = '_blank';
                    link.download = '$nombreArchivo';
                    link.click();
                </script>";
        
                // Imprimir el código JavaScript
                echo $javascriptCode;
            }
        }else{
            if($Numero_pedimento!= NULL){
                $mail->Subject = 'Solicitud de Pago. Referencia_Nexen: '.$referencia_nexen.'. Numero Pedimento: '.$Numero_pedimento.'.';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                La solicitud de pago se registro correctamente por el Usuario: '.$name_usuario.' y Sucursal: '.$sucursal.'. <br>
                <br>
                Pago por: <b>'.$operador.'</b>.<br>
                Pago a: <b>'.$Razon_social_Receptora.'</b>.<br>
                <br>

                <b> DATOS DE LA SOLICITUD: </b><br>
        
        
                <table border="1">
                    <thead>
                        <tr>
                            <th scope="row"> REFERENCIA </th>
                            <th scope="row"> CONTENEDOR / GUIA / ECO / PEDIMENTO</th>
                            <th scope="row"> NOMBRE CLIENTE </th>
                            <th scope="row"> TIPO PAGO </th>
                            <th scope="row"> MONTO </th>
                            <th scope="row"> CONCEPTO </th>
                            <th scope="row"> OBSERVACIONES </th>
                            <th scope="row"> MONEDA </th>
                            <th scope="row"> REFERENCIA PROVEEDOR </th>
                            <th scope="row"> BANCO </th>
                            <th scope="row"> CONTENEDOR 2 </th>

                        
        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td > '.$referencia_nexen.'</td>
                            <td> '.$Numero_pedimento.'</td>
                            <td> '.$nombre_cliente.'</td>
                            <td> '.$Tipo_Solicitud.'</td>
                            <td> $'.$valor_formateado.'</td>
                            <td> '.$Concepto.'</td>
                            <td> '.$observaciones.'</td>
                            <td> '.$moneda.'</td>
                            <td> '.$referencia_proveedor.'</td>
                            <td> '.$Banco_Destino.'</td>
                            <td> '.$contenedor_2.'</td>
                        
        
        
                        </tr>
                    </tbody>
                </table>
            
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
                $javascriptCode = "<script>
                    var link = document.createElement('a');
                    link.href = '$rutaArchivo';
                    link.target = '_blank';
                    link.download = '$nombreArchivo';
                    link.click();
                </script>";
        
                // Imprimir el código JavaScript
                echo $javascriptCode;
            }

        }


         
         $mail->send();
         echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nexen.'">'; 
?>