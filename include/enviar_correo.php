<?php
require '../conexion/bd.php';
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

         // LIBRERTIAS PARA ENVIAR CORREO
         require '../PHPMailer/PHPMailer.php';
         require '../PHPMailer/Exception.php';
         require '../PHPMailer/SMTP.php';
         
if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php';
        $referencia = $_GET['referencia'];

        $user_nexen = $_SESSION['usuario_nexen'];
        $query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$user_nexen'";
        $select_user_name = $conn_bd->prepare($query_user_name);

        if($select_user_name -> execute()){
            $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
            $name_usuario = $result_name['nombre_usuario'];
            //$correo_operador = $result_name['correo'];
        }
        //Validar si nombre viene vacio
        if(!$name_usuario){
            header('Location: login.php');
        }
        $correo_operador = "gerencia.desarrollo@nexen-elog.com";
        //Parametros de envio de correo
        $mail = new PHPMailer(true);
        //$correo_operador = "gerencia.desarrollo@nexen-elog.com";
       
        // //ENVIAR CORREO A USUARIO
        try{
            //Datos del servidor de correo
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
                $mail->isSMTP(); 
                $mail->charSet = "UTF-8";                                               
                $mail->Host       = 'smtp.titan.email';                     
                $mail ->SMTPAuth  = true;                                 
                $mail->Username   = 'nexen.admin@nexenelog.com'; 
                $mail->Password   = 'Nexen2022#';                       
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                $mail->Port       = 465;                                   

                //Direccion de la cuenta de donde se enviará el mensaje 
                $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
                $mail->addAddress($correo_operador,'');     //Email receptor
            
                //Mensaje
                $mail->isHTML(true);  
                $mail->CharSet = 'UTF-8';                               
                $mail->Subject = 'Solicitud de Pago : Mensaje de prueba, favor de eliminar';
                $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                Tu solicitud de pago se ha registrado correctamente.  <br>
                <br>
                Espera la confirmaci&oacute;n del &aacute;rea de Finanzas:
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="http://158.69.113.62/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
            
            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
    }

    echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia.'">'; 

}//Si existe Usuario

?>