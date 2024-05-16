<?php
require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];

        date_default_timezone_set('America/Mexico_City');
        $fechaOpe  = date('Y/m/d');
        $hora = date('H:i:s');

        $c_operador = $_POST['c_operador'];
        $c_cuenta_destino = $_POST['c_cuenta_destino'];
        $c_razon_social = $_POST['c_razon_social']; 
        $c_rfc = $_POST['c_rfc']; 
        $c_cuenta = $_POST['c_cuenta']; 
        $c_abba = $_POST['c_abba'];
        $c_banco = $_POST['c_banco']; 
        $c_clabe = $_POST['c_clabe']; 
        $c_banco_inter = $_POST['c_banco_inter']; 
        $c_domicilio = $_POST['c_domicilio'];
        $c_tipo_cuenta = $_POST['c_tipo_cuenta'];
        $c_referencia_proveedor = $_POST['c_referencia_proveedor'];
        $c_curp = $_POST['c_curp'];
        $c_tipo_servicio = $_POST['c_tipo_servicio'];
        $c_tipo_persona = $_POST['c_tipo_persona'];

        $query_select_cuenta= "SELECT * FROM [dbo].[Cuenta_Destino] WHERE [Operador]='$c_operador' AND [Cuenta]='$c_cuenta' AND ([Clabe]='$c_clabe' OR [SWT_ABBA]='$c_abba')";
        $existe_cuenta= $conn_bd->prepare($query_select_cuenta);

        if($existe_cuenta->execute()){
            $si_existe = $existe_cuenta -> fetch(PDO::FETCH_ASSOC); 
                if($si_existe>0){
                    echo'<script type="text/javascript">
                    alert("YA EXISTE LA CUENTA, POR FAVOR ASEGURATE QUE SEAN LOS DATOS CORRECTOS");
                    </script>';

                }else{
                    $query_insert_provee_finanzas ="INSERT INTO [dbo].[Cuenta_Destino]
                    ([Cuenta_Destino]
                    ,[Razon_social]
                    ,[RFC]
                    ,[Banco]
                    ,[Cuenta]
                    ,[Clabe]
                    ,[SWT_ABBA]
                    ,[Banco_Intermediario]
                    ,[Domicilio_Completo]
                    ,[fechope]
                    ,[horaope]
                    ,[Usuario]
                    ,[Estatus]
                    ,[Operador]
                    ,[tipo_cuenta]
                    ,[Ref_proveedor]
                    ,[Tipo_servicio]
                    ,[CURP]
                    ,[Tipo_Persona])
                VALUES
                    ('$c_cuenta_destino'
                    ,'$c_razon_social'
                    ,'$c_rfc'
                    ,'$c_banco'
                    ,'$c_cuenta'
                    ,'$c_clabe'
                    ,'$c_abba'
                    ,'$c_banco_inter'
                    ,'$c_domicilio'
                    ,'$fechaOpe'
                    ,'$hora'
                    ,'$user_nexen'
                    ,'A'
                    ,'$c_operador'
                    ,'$c_tipo_cuenta'
                    ,'$c_referencia_proveedor'
                    ,'$c_tipo_servicio'
                    ,'$c_curp'
                    ,'$c_tipo_persona')";

                    $insertar_provee_finanzas= $conn_bd->prepare($query_insert_provee_finanzas);

                    if($insertar_provee_finanzas->execute()){
                        echo'<script type="text/javascript">
                        alert("SE INSERTO CORRECTAMENTE EL PROVEEDOR");
                        </script>';
                        echo '<meta http-equiv="REFRESH" content="0;url=../view/cargar_proveedor_finanzas.php">'; 
                    }else{
                        echo'<script type="text/javascript">
                        alert("NO SE PUDO INSERTAR EL PROVEEDOR, POR FAVOR INTENTATO NUEVAMENTE");
                        </script>';

                        echo'<script type="text/javascript">
                        window..back();
                        </script>';
                    }
                }
        
            echo '<meta http-equiv="REFRESH" content="0;url=../view/cargar_proveedor_finanzas.php">'; 
        }else{
            echo'<script type="text/javascript">
            alert("OCURRIO UN ERROR AL REVISAR SI EXISTE LA CUENTA, POR FAVOR INTENTATO NUEVAMENTE");
            </script>';
        }
    }

}//Si existe Usuario

?>




