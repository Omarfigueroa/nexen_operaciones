<?php
require '../conexion/bd.php';
$usuario = "JOVIEDOR";

if(isset( $_POST['tax_id']) && !empty($_POST['tax_id'])&&
    isset($_POST['proveedor']) && !empty($_POST['proveedor'])&&
    isset($_POST['domicilio']) && !empty($_POST['domicilio'])&&
    isset($_POST['pass']) && !empty($_POST['pass'])){

        $taxid_proveedor = $_POST['tax_id'];
        $nombre_proveedor = $_POST['proveedor'];
        $domicilio_proveedor = $_POST['domicilio'];
        if($_POST['email']){
            $email_proveedor = $_POST['email'];
        }else{
            $email_proveedor = "";
        }

        if($_POST['whatsapp']){
            $whats_proveedor = $_POST['whatsapp'];
        }else{
            $whats_proveedor = "";
        }
        $pass = $_POST['pass'];
        $estatus = 1; 

        $consulta = $conn_bd->prepare("SELECT * FROM Contraseña_Sup WHERE Contraseña = :password");
        $consulta->bindParam(':password', $pass);
        $consulta->execute();
        $pass_supervisor = $consulta->fetch(PDO::FETCH_ASSOC);

        // $pass_supervisor = "NEXOPE2023";
        // $pass_supervisor2 = "RI#2023GA";

        if($pass_supervisor){
            $insert_proveedor = $conn_bd->prepare("INSERT INTO [dbo].[provedores]
            ([codigo]
            ,[Proveedor]
            ,[domicilio]
            ,[correo]
            ,[whatsapp]
            ,[estatus])
            VALUES
            ('$taxid_proveedor'
            ,'$nombre_proveedor'
            ,'$domicilio_proveedor'
            ,'$email_proveedor'
            ,'$whats_proveedor','$estatus')");

                if($insert_proveedor->execute()){
                    echo'<script type="text/javascript">
                    alert("SE INSERTO CORRECTAMENTE EL PROVEEDOR");
                    window.history.back();
                    </script>';
                }else{
                    echo'<script type="text/javascript">
                    alert("NO SE PUDO INSERTAR EL PROVEEDOR");
                    window.history.back();
                    </script>';
                }

        }else{
            echo'<script type="text/javascript">
            alert("LA CONTRASEÑA ES INCORRECTA, POR FAVOR VUELVE A INGRESAR LA INFORMACION");
            window.history.back();
            </script>';
        }
       
    }