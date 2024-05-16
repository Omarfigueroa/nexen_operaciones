<?php
    require '../conexion/bd.php';
    if($_POST){
        $usuario = $_POST['usuario'];
        $numero_pedimento = $_POST['num_pedimento'];
        $cve_pedimento = $_POST['cve_pedimento'];
        $Rectificacion_Descripcion = $_POST['rectificacion'];
        $referencia_nex = $_POST['referencia_nexen_rectifica'];

        if(empty($usuario) || empty($numero_pedimento) || empty($cve_pedimento) || empty($Rectificacion_Descripcion) || empty($referencia_nex)){
            echo "<script>alert('OPERACION NO CREADA')</script>";
            echo'<script type="text/javascript">
            window..back();
             </script>';
        }else{
            date_default_timezone_set('America/Mexico_City');
            $Fecha = date('Y-m-d');
            $Hora = date('H:i:s');
            $insert = "INSERT INTO [dbo].[FK_RECTIFICADO] (Usuario,Numero_pedimento,Clave_pedimento,Fechope,Horaope,Descripcion,Estatus,Referencia_Nexen) 
            VALUES ('$usuario','$numero_pedimento','$cve_pedimento','$Fecha','$Hora','$Rectificacion_Descripcion','A','$referencia_nex')";
            $insert_new_log=$conn_bd->prepare($insert);
            if($insert_new_log->execute()){
                echo '<script>alert("Se agrego correctamente la rectificacion")</script>';
                echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nex.'">';
            }else{

            }
        }

    }

?>