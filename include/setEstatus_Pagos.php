<?php

date_default_timezone_set('America/Mexico_City');
session_start();

require '../conexion/bd.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// LIBRERTIAS PARA ENVIAR CORREO
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/SMTP.php';

$usuario = $_SESSION['usuario_nexen'];

$fechope = date('Y-m-d');
$horaope = date("H:i:s");

if (
    isset($_POST['id']) && isset($_POST['estatus']) && isset($_POST['referencia_nexen'])
    && isset($_POST['concepto']) && isset($_POST['tipo_solicitud']) && isset($_POST['monto'])
    && isset($_POST['moneda']) && isset($_POST['usuario'])
) {
    $id = $_POST['id'];
    $estatus = $_POST['estatus'];
    $referencia_nexen = $_POST['referencia_nexen'];
    $concepto = $_POST['concepto'];
    $contenedor = $_POST['contenedor'];
    $tipo_solicitud = $_POST['tipo_solicitud'];
    $monto = $_POST['monto'];
    $moneda = $_POST['moneda'];
    $usuario = $_POST['usuario'];

    //Si el estatus es aceptado
    if($estatus == 'ACEPTADO'){

        $file = $_FILES['file_pago_aceptado'];

        // Ruta de la carpeta donde se guardarán los archivos
        $ruta_carpeta = '../archivos_comprobantes_pagos/';
        // Nombre del archivo
        $nombre_archivo = 'comprobantePago_' . $id . '_' . uniqid() . '.pdf';
        // Ruta completa del archivo en el servidor
        $ruta_archivo = $ruta_carpeta . $nombre_archivo;
    
        // Subir el archivo
        if (move_uploaded_file($file['tmp_name'], $ruta_archivo)) {
            // Realizar la conexión a la base de datos aquí, asumiendo que tienes $conn_bd como la conexión.
    
            try {
                // Inicio de la transacción
                $conn_bd->beginTransaction();
    
                // Consulta de actualización
                $stmt_update = $conn_bd->prepare("UPDATE [dbo].[FK_Solicitud_Pago] SET Estatus = :estatus, Estatus_Alerta_Pago = 1, Ruta_Archivo = :ruta_archivo WHERE Num_Operacion = :id");
                $stmt_update->bindParam(':estatus', $estatus);
                $stmt_update->bindParam(':ruta_archivo', $ruta_archivo);
                $stmt_update->bindParam(':id', $id);
                $stmt_update->execute();
    
                // Consulta de inserción
                $stmt_insert = $conn_bd->prepare("INSERT INTO [dbo].[Log_Solicitud_Pagos] (Referencia_Nexen, Num_Operacion, Concepto, Tipo_Solicitud, Monto, Moneda, Estatus, Usuario_Administracion, Fechope, Horaope)
                                                 VALUES (:referencia_nexen, :id, :concepto, :tipo_solicitud, :monto, :moneda, :estatus, :usuario, :fechope, :horaope)");
                $stmt_insert->bindParam(':referencia_nexen', $referencia_nexen);
                $stmt_insert->bindParam(':id', $id);
                $stmt_insert->bindParam(':concepto', $concepto);
                $stmt_insert->bindParam(':tipo_solicitud', $tipo_solicitud);
                $stmt_insert->bindParam(':monto', $monto);
                $stmt_insert->bindParam(':moneda', $moneda);
                $stmt_insert->bindParam(':estatus', $estatus);
                $stmt_insert->bindParam(':usuario', $usuario);
                $stmt_insert->bindParam(':fechope', $fechope);
                $stmt_insert->bindParam(':horaope', $horaope);
                $stmt_insert->execute();
    
                // Commit de la transacción
                $conn_bd->commit();
    
                // Envío de correo (separado de la función principal)
                enviarCorreoAceptado($referencia_nexen, $contenedor,  $estatus, $id, $nombre_archivo, $ruta_archivo);
    
                // Respuesta exitosa
                echo json_encode(array('success' => true, 'message' => 'Se logró el update, insert y envio de correo'));
    
            } catch (PDOException $e) {
                // En caso de error, deshacer la transacción
                $conn_bd->rollback();
    
                // Respuesta de error con el mensaje de la excepción
                echo json_encode(array('success' => false, 'message' => 'Error en la transacción: ' . $e->getMessage()));
            }
        } else {
            // Respuesta de error en caso de fallar al subir el archivo
            echo json_encode(array('success' => false, 'message' => 'Error al subir el archivo'));
        }
    }else if($estatus == 'RECHAZADO'){
       
        $motivoRechazo = $_POST['motivoRechazo'];
    

        try {
            // Inicio de la transacción
            $conn_bd->beginTransaction();

            // Consulta de actualización
            $stmt_update = $conn_bd->prepare("UPDATE [dbo].[FK_Solicitud_Pago] SET Estatus = :estatus, Estatus_Alerta_Pago = 1, motivo_rechazo = :motivoRechazo WHERE Num_Operacion = :id");
            $stmt_update->bindParam(':estatus', $estatus);
            $stmt_update->bindParam(':motivoRechazo', $motivoRechazo);
            $stmt_update->bindParam(':id', $id);
            $stmt_update->execute();

            // Consulta de inserción
            $stmt_insert = $conn_bd->prepare("INSERT INTO [dbo].[Log_Solicitud_Pagos] (Referencia_Nexen, Num_Operacion, Concepto, Tipo_Solicitud, Monto, Moneda, Estatus, Usuario_Administracion, Fechope, Horaope)
                                                VALUES (:referencia_nexen, :id, :concepto, :tipo_solicitud, :monto, :moneda, :estatus, :usuario, :fechope, :horaope)");
            $stmt_insert->bindParam(':referencia_nexen', $referencia_nexen);
            $stmt_insert->bindParam(':id', $id);
            $stmt_insert->bindParam(':concepto', $concepto);
            $stmt_insert->bindParam(':tipo_solicitud', $tipo_solicitud);
            $stmt_insert->bindParam(':monto', $monto);
            $stmt_insert->bindParam(':moneda', $moneda);
            $stmt_insert->bindParam(':estatus', $estatus);
            $stmt_insert->bindParam(':usuario', $usuario);
            $stmt_insert->bindParam(':fechope', $fechope);
            $stmt_insert->bindParam(':horaope', $horaope);
            $stmt_insert->execute();

            // Commit de la transacción
            $conn_bd->commit();

            // Envío de correo (separado de la función principal)
            enviarCorreoRechazo($referencia_nexen, $contenedor, $estatus, $id, $motivoRechazo);

            // Respuesta exitosa
            echo json_encode(array('success' => true, 'message' => 'Se logró el update, insert y envio de correo'));

        } catch (PDOException $e) {
            // En caso de error, deshacer la transacción
            $conn_bd->rollback();

            // Respuesta de error con el mensaje de la excepción
            echo json_encode(array('success' => false, 'message' => 'Error en la transacción: ' . $e->getMessage()));
        }
        
    }else if($estatus == 'SIN FONDOS'){
    

        try {
            // Inicio de la transacción
            $conn_bd->beginTransaction();

            // Consulta de actualización
            $stmt_update = $conn_bd->prepare("UPDATE [dbo].[FK_Solicitud_Pago] SET Estatus = :estatus, Estatus_Alerta_Pago = 1 WHERE Num_Operacion = :id");
            $stmt_update->bindParam(':estatus', $estatus);
            $stmt_update->bindParam(':id', $id);
            $stmt_update->execute();

            // Consulta de inserción
            $stmt_insert = $conn_bd->prepare("INSERT INTO [dbo].[Log_Solicitud_Pagos] (Referencia_Nexen, Num_Operacion, Concepto, Tipo_Solicitud, Monto, Moneda, Estatus, Usuario_Administracion, Fechope, Horaope)
                                                VALUES (:referencia_nexen, :id, :concepto, :tipo_solicitud, :monto, :moneda, :estatus, :usuario, :fechope, :horaope)");
            $stmt_insert->bindParam(':referencia_nexen', $referencia_nexen);
            $stmt_insert->bindParam(':id', $id);
            $stmt_insert->bindParam(':concepto', $concepto);
            $stmt_insert->bindParam(':tipo_solicitud', $tipo_solicitud);
            $stmt_insert->bindParam(':monto', $monto);
            $stmt_insert->bindParam(':moneda', $moneda);
            $stmt_insert->bindParam(':estatus', $estatus);
            $stmt_insert->bindParam(':usuario', $usuario);
            $stmt_insert->bindParam(':fechope', $fechope);
            $stmt_insert->bindParam(':horaope', $horaope);
            $stmt_insert->execute();

            // Commit de la transacción
            $conn_bd->commit();

            // Envío de correo (separado de la función principal)
            enviarCorreoSinFondos($referencia_nexen, $contenedor, $estatus, $id);

            // Respuesta exitosa
            echo json_encode(array('success' => true, 'message' => 'Se logró el update, insert y envio de correo'));

        } catch (PDOException $e) {
            // En caso de error, deshacer la transacción
            $conn_bd->rollback();

            // Respuesta de error con el mensaje de la excepción
            echo json_encode(array('success' => false, 'message' => 'Error en la transacción: ' . $e->getMessage()));
        }
    }

   
} else {
    // Si no se reciben los parámetros esperados, enviamos un mensaje de error
    echo json_encode(array('success' => false, 'message' => 'Faltan parámetros requeridos'));
}

function enviarCorreoAceptado($referencia_nexen, $contenedor,  $estatus, $id, $nombre_archivo, $ruta_archivo)
{
    try {
        require '../utils/catalogos.php';
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];
        $query_user_name = "SELECT * FROM [dbo].[Usuarios_Login_Web] WHERE Usuario = '$user_nexen'";
        $select_user_name = $conn_bd->prepare($query_user_name);

        if ($select_user_name->execute()) {
            $result_name = $select_user_name->fetch(PDO::FETCH_ASSOC);
            $name_usuario = $result_name['nombre_usuario'];
            $correo_operador = $result_name['correo'];
        }

        //PARA CONSEGUIR EL CORREO DEL USUARIO ASOCIADO AL PAGO
        $query_user_pago = "SELECT usuarios_login_web.correo 
        FROM usuarios_login_web
        INNER JOIN fk_solicitud_pago ON usuarios_login_web.nombre_usuario  = fk_solicitud_pago.Usuario 
        WHERE fk_solicitud_pago.Num_Operacion = '$id'";

        $select_user_pago = $conn_bd->prepare($query_user_pago);

        if($select_user_pago->execute()){
            $result_user_pago = $select_user_pago->fetch(PDO::FETCH_ASSOC);
            $correo_usuario_pago = $result_user_pago['correo'];
        }


        // Parámetros de envío de correo
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0; // Cambia el valor de SMTPDebug a 0

        // Datos del servidor de correo
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'nexen.admin@nexenelog.com';
        $mail->Password = 'Nexen2022#';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Direccion de la cuenta de donde se enviará el mensaje
        $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
        $mail->addAddress($correo_operador, ''); // Email receptor
        // Agregar una copia (CC)
        $mail->addCC($correo_usuario_pago, '');

        // Mensaje
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Solicitud de Pago. Contenedor: '.$contenedor.'. Referencia_Nexen: '.$referencia_nexen.'';
        $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                Tu solicitud de pago con referencia: '. $referencia_nexen .' ha cambiado de estatus a: '. $estatus .'.  <br>
                <br>
                Para más información, el número de operación es: '. $id .'
                <br>
                Así mismo te recordamos que podrás revisar tus solicitudes en el portal de <b>Operaciones Nexen</b>. Solo ingresa al siguiente link: <a href="https://nexenelog.mx/nexen_operaciones/view/login.php"><b>Operaciones Nexen</b></a>  <br>
                
                <br><br>
                ¡Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';

        // Adjuntar el archivo
        $mail->addAttachment($ruta_archivo, $nombre_archivo);


        $mail->send();

    } catch (Exception $ex) {
        // En caso de error al enviar el correo, se captura la excepción y se muestra un mensaje de error
        // echo json_encode(array('success' => false, 'message' => $name_usuario. ' ' . $correo_usuario_pago . ' Hubo un error al enviar el correo: ' . $ex->getMessage()));
        return false;
    }
}


function enviarCorreoRechazo($referencia_nexen, $contenedor, $estatus, $id, $motivoRechazo)
{
    try {
        require '../utils/catalogos.php';
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];
        $query_user_name = "SELECT * FROM [dbo].[Usuarios_Login_Web] WHERE Usuario = '$user_nexen'";
        $select_user_name = $conn_bd->prepare($query_user_name);

        if ($select_user_name->execute()) {
            $result_name = $select_user_name->fetch(PDO::FETCH_ASSOC);
            $name_usuario = $result_name['nombre_usuario'];
            $correo_operador = $result_name['correo'];
        }

        //PARA CONSEGUIR EL CORREO DEL USUARIO ASOCIADO AL PAGO
        $query_user_pago = "SELECT usuarios_login_web.correo 
        FROM usuarios_login_web
        INNER JOIN fk_solicitud_pago ON usuarios_login_web.nombre_usuario  = fk_solicitud_pago.Usuario 
        WHERE fk_solicitud_pago.Num_Operacion = '$id'";

        $select_user_pago = $conn_bd->prepare($query_user_pago);


        if($select_user_pago->execute()){
            $result_user_pago = $select_user_pago->fetch(PDO::FETCH_ASSOC);
            $correo_usuario_pago = $result_user_pago['correo'];
        }


        // Parámetros de envío de correo
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0; // Cambia el valor de SMTPDebug a 0

        // Datos del servidor de correo
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'nexen.admin@nexenelog.com';
        $mail->Password = 'Nexen2022#';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Direccion de la cuenta de donde se enviará el mensaje
        $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
        $mail->addAddress($correo_operador, ''); // Email receptor
        // Agregar una copia (CC)
        $mail->addCC($correo_usuario_pago, '');

        // Mensaje
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Solicitud de Pago. Contenedor: '.$contenedor.'. Referencia_Nexen: '.$referencia_nexen.'';
        $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                Tu solicitud de pago con referencia: '. $referencia_nexen .' ha cambiado de estatus a: '. $estatus .'.  <br>
                <br>
                Motivo: '. $motivoRechazo .'
                <br>
                Para más información, el número de operación es: '. $id .'
                <br>
                Así mismo te recordamos que podrás revisar tus solicitudes en el portal de <b>Operaciones Nexen</b>. Solo ingresa al siguiente link: <a href="https://nexenelog.mx/nexen_operaciones/view/login.php"><b>Operaciones Nexen</b></a>  <br>
                
                <br><br>
                ¡Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';

        


        $mail->send();

    } catch (Exception $ex) {
        // En caso de error al enviar el correo, se captura la excepción y se muestra un mensaje de error
        // echo json_encode(array('success' => false, 'message' => $name_usuario. ' ' . $correo_usuario_pago . ' Hubo un error al enviar el correo: ' . $ex->getMessage()));
        return false;
    }
}



function enviarCorreoSinFondos($referencia_nexen, $contenedor, $estatus, $id)
{
    try {
        require '../utils/catalogos.php';
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];
        $query_user_name = "SELECT * FROM [dbo].[Usuarios_Login_Web] WHERE Usuario = '$user_nexen'";
        $select_user_name = $conn_bd->prepare($query_user_name);

        if ($select_user_name->execute()) {
            $result_name = $select_user_name->fetch(PDO::FETCH_ASSOC);
            $name_usuario = $result_name['nombre_usuario'];
            $correo_operador = $result_name['correo'];
        }

        //PARA CONSEGUIR EL CORREO DEL USUARIO ASOCIADO AL PAGO
        $query_user_pago = "SELECT usuarios_login_web.correo 
        FROM usuarios_login_web
        INNER JOIN fk_solicitud_pago ON usuarios_login_web.nombre_usuario  = fk_solicitud_pago.Usuario 
        WHERE fk_solicitud_pago.Num_Operacion = '$id'";

        $select_user_pago = $conn_bd->prepare($query_user_pago);


        if($select_user_pago->execute()){
            $result_user_pago = $select_user_pago->fetch(PDO::FETCH_ASSOC);
            $correo_usuario_pago = $result_user_pago['correo'];
        }


        // Parámetros de envío de correo
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0; // Cambia el valor de SMTPDebug a 0

        // Datos del servidor de correo
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'nexen.admin@nexenelog.com';
        $mail->Password = 'Nexen2022#';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Direccion de la cuenta de donde se enviará el mensaje
        $mail->setFrom('nexen.admin@nexenelog.com', 'Nexen');
        $mail->addAddress($correo_operador, ''); // Email receptor
        // Agregar una copia (CC)
        $mail->addCC($correo_usuario_pago, '');

        // Mensaje
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Solicitud de Pago. Contenedor: '.$contenedor.'. Referencia_Nexen: '.$referencia_nexen.'';
        $mail->Body    = 'Estimado (a):  <b>'.$name_usuario.'</b><br><br>
                Tu solicitud de pago con referencia: '. $referencia_nexen .' ha cambiado de estatus a: '. $estatus .'.  <br>
                <br>
                Para más información, el número de operación es: '. $id .'
                <br>
                Así mismo te recordamos que podrás revisar tus solicitudes en el portal de <b>Operaciones Nexen</b>. Solo ingresa al siguiente link: <a href="https://nexenelog.mx/nexen_operaciones/view/login.php"><b>Operaciones Nexen</b></a>  <br>
                
                <br><br>
                ¡Saludos!
                <br><br>
                <b>ATENTAMENTE </b> <br>
                Equipo Nexen-Elog <br>';

        


        $mail->send();

    } catch (Exception $ex) {
        // En caso de error al enviar el correo, se captura la excepción y se muestra un mensaje de error
        // echo json_encode(array('success' => false, 'message' => $name_usuario. ' ' . $correo_usuario_pago . ' Hubo un error al enviar el correo: ' . $ex->getMessage()));
        return false;
    }
}
?>
