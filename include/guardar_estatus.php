<?php
require '../conexion/bd.php';

if(isset($_POST['estatus']) && !empty($_POST['estatus'])){

    $new_estatus = $_POST['estatus'];
    
    $insert_estatus =$conn_bd->prepare("INSERT INTO [dbo].[ESTATUS_OPERACION]
                                                        ([Descripcion])
                                                    VALUES
                                                        ('$new_estatus')");
    if($insert_estatus->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE EL NUEVO ESTATUS");
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR EL ESTATUS NUEVO");
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