<?php
require '../conexion/bd.php';

if(isset($_POST['razon_social_cliente']) && !empty($_POST['razon_social_cliente']) &&
    isset($_POST['rfc_cliente']) && !empty($_POST['rfc_cliente']) &&
    isset($_POST['telefono_cliente']) && !empty($_POST['telefono_cliente']) &&
    isset($_POST['nombre_contacto']) && !empty($_POST['nombre_contacto']) &&
    isset($_POST['email_cliente_1']) && !empty($_POST['email_cliente_1']) &&
    isset($_POST['domicilio_cliente']) && !empty($_POST['domicilio_cliente'])){

    $razon_social = $_POST['razon_social_cliente'];
    $rfc_cliente = $_POST['rfc_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];
    $nombre_contacto = $_POST['nombre_contacto'];
    $email_1 = $_POST['email_cliente_1'];
    $domicilio_cliente = $_POST['domicilio_cliente'];

    if($_POST['movil_cliente']){
        $movil_cliente = $_POST['movil_cliente'];
    }else{
        $movil_cliente = "";
    }

    if($_POST['email_cliente_2']){
        $email_2 = $_POST['email_cliente_2'];
    }else{
        $email_2 = "";
    }
    
    $insert_cliente =$conn_bd->prepare("INSERT INTO [dbo].[Clientes]
                                        ([RAZON SOCIAL ]
                                        ,[RFC ]
                                        ,[TELEFONO]
                                        ,[MOVIL ]
                                        ,[CONTACTO]
                                        ,[EMAIL 1]
                                        ,[EMAIL 2]
                                        ,[Domilio_Fisico])
                                    VALUES
                                        ('$razon_social'
                                        ,'$rfc_cliente'
                                        ,'$telefono_cliente'
                                        ,'$movil_cliente'
                                        ,'$nombre_contacto'
                                        ,'$email_1'
                                        ,'$email_2' 
                                        ,'$domicilio_cliente')");

    if($insert_cliente->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE EL CLIENTE");
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR EL CLIENTE");
            window.history.back();
        </script>';
    }
}else{
    echo'<script type="text/javascript">
    alert("POR FAVOR LLENA TODOS LOS CAMPOS SOLICITADOS");
    window.history.back();
</script>';
}




?>