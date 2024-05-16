<?php
require '../conexion/bd.php';
$usuario = "JOVIEDOR";

if(isset( $_POST['proveedor_fact']) && !empty($_POST['proveedor_fact'])&&
    isset($_POST['num_factura']) && !empty($_POST['num_factura'])&&
    isset($_POST['fecha_factura']) && !empty($_POST['fecha_factura'])&&
    isset($_POST['desc_factura']) && !empty($_POST['desc_factura'])&&
    isset($_POST['valor_factura']) && !empty($_POST['valor_factura']) &&
    isset($_POST['precio_unitario']) && !empty($_POST['precio_unitario']) &&
    isset($_POST['precio_total']) && !empty($_POST['precio_total']) ){

    $proveedor_fact = $_POST['proveedor_fact'];
    $num_factura = $_POST['num_factura'];
    $fecha_factura = $_POST['fecha_factura'];
    if($_POST['tax_id']){ 
        $tax_id = $_POST['tax_id'];
    }else{
        $tax_id =""; 
    }
    $desc_factura = $_POST['desc_factura'];
    if($_POST['nombre_operador']){
         $nombre_operador = $_POST['nombre_operador']; 
    }else{ 
            $nombre_operador ="";
    }
    if($_POST['rfc_operador']){
         $rfc_operador = $_POST['rfc_operador'];
    }else{
             $rfc_operador ="";
    }
    if( $_POST['domicilio_operador']){ 
        $domicilio_operador = $_POST['domicilio_operador']; 
    }else{
         $domicilio_operador =""; 
    }
    $valor_factura = $_POST['valor_factura'];
    $precio_unitario = $_POST['precio_unitario'];
    $precio_total = $_POST['precio_total'];

    $insert_factura =$conn_bd->prepare("INSERT INTO [dbo].[FACTURAS_PEDIMENTO]
                                                        ([NUM_FACT]
                                                        ,[PROVEEDOR]
                                                        ,[FECHAFACT]
                                                        ,[VALOR_FACTURA]
                                                        ,[DESCRIPCION_COVE]
                                                        ,[FECHA]
                                                        ,[HORA]
                                                        ,[USUARIO])
                                                        VALUES
                                                        ('$num_factura'
                                                        ,'$proveedor_fact'
                                                        ,'$fecha_factura'
                                                        ,'$valor_factura'
                                                        ,'$desc_factura'
                                                        ,GETDATE()
                                                        ,GETDATE()
                                                        ,'$usuario')");

    if($insert_factura->execute()){
        echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE LA FACTURA");
            window.history.back();
        </script>';
    }else{
        echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR LA FACTURA");
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