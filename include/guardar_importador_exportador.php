<?php
require '../conexion/bd.php';

if(isset($_POST['razon_social_impo_expo']) && !empty($_POST['razon_social_impo_expo']) &&
    isset($_POST['rfc_impo_expo']) && !empty($_POST['rfc_impo_expo']) &&
    isset($_POST['domicilio_fiscal']) && !empty($_POST['domicilio_fiscal'])){

    $razon_social_impo_expo = $_POST['razon_social_impo_expo'];
    $rfc_impo_expo = $_POST['rfc_impo_expo'];
    $domicilio_fiscal = $_POST['domicilio_fiscal'];
    
    
    $insert_cliente =$conn_bd->prepare("INSERT INTO [dbo].[exportador_importador]
                                                        ([RAZON_SOCIAL ]
                                                        ,[DOMICILIO_FISCAL ]
                                                        ,[RFC])
                                                    VALUES
                                                        ('$razon_social_impo_expo'
                                                        ,'$rfc_impo_expo'
                                                        ,'$domicilio_fiscal')");

    if($insert_cliente->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE EL IMPORTADOR/EXPORTADOR);
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR EL IMPORTADOR/EXPORTADOR");
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