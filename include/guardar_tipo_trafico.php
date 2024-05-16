<?php
require '../conexion/bd.php';

if(isset($_POST['tipo_trafico']) && !empty($_POST['tipo_trafico'])){

    $new_tipo_trafico = $_POST['tipo_trafico'];
    
    $insert_tipo_trafico =$conn_bd->prepare("INSERT INTO [dbo].[ESTATUS_OPERACION]
                                                        ([ESTATUS])
                                                    VALUES
                                                        ('$new_tipo_trafico')");
    if($insert_tipo_trafico->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE EL NUEVO TIPO TRÁFICO");
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR EL TIPO TRÁFICO");
            window.history.back();
        </script>';
    }
}else{
    echo'<script type="text/javascript">
    alert("POR FAVOR LLENA TODOS LOS CAMPOS SOLICITADOS");
    window.history.back();
</script>';
}


