<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 // LIBRERTIAS PARA ENVIAR CORREO
 require '../PHPMailer/PHPMailer.php';
 require '../PHPMailer/Exception.php';
 require '../PHPMailer/SMTP.php';

    // $query_correo ="SELECT Usuario,correo,area,Estatus FROM [dbo].[Usuarios_Login_Web] where area = 'finanzas' OR area = 'Supervisor' OR area = 'Auxiliar'";
    // $query_correo =$conn_bd->prepare($query_correo);
    // $query_correo->execute();
    // $usuarios =$query_correo->fetchAll(PDO::FETCH_ASSOC);

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.titan.email';
    $mail->SMTPAuth = true;
    $mail->Username = 'nexen.admin@nexenelog.com';
    $mail->Password = 'Nexen2022#';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465; //587, 25, 483
    //$mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
    $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');

    $nombreUsuario = 'ADMIN';
    $correoUsuario ='desarrollo.3@nexen-elog.com';

    $correos=[$correoUsuario,"sayulilla1@gmail.com"];

    foreach($correos as $correo){
        $mail->addAddress($correo, '');
    }

    // $mail->addAddress($correoUsuario, '');
    // $mail->addAddress('sayulilla1@gmail.com', '');
    
    // Configuración del cuerpo del correo
    $mail->isHTML(true);
    $mail->Subject = 'Solicitud de Pago: Mensaje de prueba, favor de eliminar';
    $mail->Body = 'Estimado (a):  <b>'.$nombreUsuario.'</b><br><br>
    Tu solicitud de pago se ha registrado correctamente.  <br>
    <br>
    <b>DATOS DE LA SOLICITUD:</b><br>
    ¡Saludos!<br><br>
    <b>ATENTAMENTE </b><br>
    Equipo Nexen-Elog<br>';
    // Envío del correo
    $mail->send();

?>