<?php
require '../conexion/bd.php';
$usuario = "JOVIEDOR";

if(isset( $_POST['moneda']) && !empty($_POST['moneda']) &&
    isset($_POST['prefijo']) && !empty($_POST['prefijo'])){


        $moneda = $_POST['moneda'];
        $prefijo = $_POST['prefijo'];

        $insert_moneda = $conn_bd->prepare("INSERT INTO [dbo].[MONEDAS]
                                                                ([DESCRIPCION]
                                                                ,[PREFIJO])
                                                            VALUES
                                                                ('$moneda'
                                                                ,'$prefijo')");

        if($insert_moneda->execute()){
            echo'<script type="text/javascript">
                alert("SE INSERTO CORRECTAMENTE LA MONEDA");
                window.history.back();
                </script>';
        }else{
            echo'<script type="text/javascript">
                alert("NO SE PUDO INSERTAR LA MONEDA");
                window.history.back();
                </script>';
        }
    }