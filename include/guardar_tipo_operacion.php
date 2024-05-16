<?php
require '../conexion/bd.php';

if(isset($_POST['tipo_operacion']) && !empty($_POST['tipo_operacion'])){

    $new_tipo_operacion = $_POST['tipo_operacion'];
    
    $insert_tipo_operacion =$conn_bd->prepare("INSERT INTO [dbo].[TIPO_OPERACION]
                                                        ([DESCRIPCION])
                                                    VALUES
                                                        ('$new_tipo_operacion')");
    if($insert_tipo_operacion->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE EL NUEVO TIPO OPERACION");
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR EL NUEVO TIPO OPERACION");
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