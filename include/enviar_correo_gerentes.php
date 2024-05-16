<?php
require '../conexion/bd.php';
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php';
       
        // LIBRERTIAS PARA ENVIAR CORREO
        require '../PHPMailer/PHPMailer.php';
        require '../PHPMailer/Exception.php';
        require '../PHPMailer/SMTP.php';

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
        $mailFinanzas = new PHPMailer(true);

        // //ENVIAR CORREO A USUARIO
        try{
            //Datos del servidor de correo
                $mailFinanzas->SMTPDebug = SMTP::DEBUG_SERVER;   
                $mailFinanzas->isSMTP(); 
                $mailFinanzas->charSet = "UTF-8";                                               
                $mailFinanzas->Host       = 'smtp.titan.email';                     
                $mailFinanzas ->SMTPAuth  = true;                                 
                $mailFinanzas->Username   = 'nexen.admin@nexenelog.com'; 
                $mailFinanzas->Password   = 'Nexen2022#';                       
                $mailFinanzas->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                $mailFinanzas->Port       = 465;                                   

                //Direccion de la cuenta de donde se enviará el mensaje 
                $mailFinanzas->setFrom('nexen.admin@nexenelog.com', 'Nexen');
                $mailFinanzas->addAddress('liliana.rodriguez@xtrategas.com','');     //Email receptor
            
                //Mensaje
                $mailFinanzas->isHTML(true);  
                $mailFinanzas->CharSet = 'UTF-8';                               
                $mailFinanzas->Subject = 'Solicitud de Pago : Mensaje de prueba, favor de eliminar';
                $mailFinanzas->Body    = 'Estimado (a):  <b>Usuario de Fianzas </b><br><br>

                El Operador:  <b>'.$name_usuario.'</b> realiz&oacute; una solicitud de pago, favor de dar seguimiento:
                <br>
                
                <br>
                As&iacute; mismo te recordamos que podr&aacute;s revisar tus solicitudes en el portal de <b> Operaciones Nexen, </b> Solo ingresa al siguiente link: <A HREF="https://nexenelog.mx/nexen_operaciones/view/login.php"><b> Operaciones Nexen </b></A>  <br>
                
                <br><br>
                &iexcl;Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';
            
            $mailFinanzas->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mailFinanzas->ErrorInfo}";
        }

    }

}//Si existe Usuario

?>