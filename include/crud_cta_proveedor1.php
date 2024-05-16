<?php

date_default_timezone_set('America/Mexico_City');


session_start();
require '../conexion/bd.php';

// Obtener el valor de la opción y el nombre de operador enviados por AJAX
$opcion = $_POST['opcion'];
//$nombreOperador = $_POST['nombreOperador'];
$nombreOperador = isset($_POST['nombreOperador']) ? $_POST['nombreOperador'] : '';

$usuario = $_SESSION['usuario_nexen'];

$query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$usuario'";
$select_user_name = $conn_bd->prepare($query_user_name);

if($select_user_name -> execute()){
    $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
    $name_usuario = $result_name['nombre_usuario'];
}

if(!$name_usuario){
    header('Location: login.php');
}

//Verificar la opción
if($opcion === 'mostarCtaProveedor'){
    //Recuperamos el id de la cuenta destino
    $d_id_cuenta = $_POST['d_id_cuenta'];
    $sql_select = "SELECT * FROM [dbo].[Cuenta_Destino]  WHERE [Id_Cuenta] = :id_cuenta";

    $stmt = $conn_bd->prepare($sql_select);
    $stmt->bindParam(':id_cuenta', $d_id_cuenta);
    $stmt->execute();
   
    $cuenta_destino = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
       // $response = [ 'success'=> true,'data' => $cuenta_destino];
        echo json_encode(array('success'=> true,'object' => $cuenta_destino));
    } else {
        $response = [ 'success'=> true,'message' => 'No se pudo obtener los datos del proveedor'];
    }


}else if ($opcion === 'updateCtaProveedor') {
   
   $u_id_cta = $_POST['u_id_cta'];
   $u_operador = $_POST['u_operador'];
   $u_cuenta_destino = $_POST['u_cuenta_destino'];
   $u_razon_social = $_POST['u_razon_social'];
   $u_referencia_proveedor = $_POST['u_referencia_proveedor'];
   $u_curp = $_POST['u_curp'];
   $u_tipo_servicio = $_POST['u_tipo_servicio'];
   $u_tipo_persona = $_POST['u_tipo_persona'];
   $u_rfc = $_POST['u_rfc'];
   $u_tipo_cuenta = $_POST['u_tipo_cuenta'];
   $u_banco = $_POST['u_banco'];
   $u_cuenta = $_POST['u_cuenta'];
   $u_abba = $_POST['u_abba'];
   $u_clabe = $_POST['u_clabe'];
   $u_banco_inter = $_POST['u_banco_inter'];
   $u_domicilio = $_POST['u_domicilio'];

   $sql_update = "UPDATE [dbo].[Cuenta_Destino]
                        SET [Cuenta_Destino] = :cuenta_destino
                            ,[Razon_social] = :razon_social
                            ,[RFC] = :rfc
                            ,[Banco] = :banco
                            ,[Cuenta] = :cuenta
                            ,[Clabe] = :clabe
                            ,[SWT_ABBA] = :abba
                            ,[Banco_Intermediario] = :banco_intermediario
                            ,[Domicilio_Completo] = :domicilio_completo
                            ,[Operador] = :operador
                            ,[tipo_cuenta] = :tipo_cuenta
                            ,[Ref_proveedor] = :referencia_proveedor
                            ,[Tipo_servicio] = :tipo_servicio
                            ,[CURP] = :curp
                            ,[Tipo_Persona] = :tipo_persona
                        WHERE [Id_Cuenta] = :id_cuenta";

    $stmt = $conn_bd->prepare($sql_update);
    $stmt->bindParam(':cuenta_destino', $u_cuenta_destino);
    $stmt->bindParam(':razon_social', $u_razon_social);
    $stmt->bindParam(':rfc', $u_rfc);
    $stmt->bindParam(':banco', $u_banco);
    $stmt->bindParam(':cuenta', $u_cuenta);
    $stmt->bindParam(':clabe', $u_clabe);
    $stmt->bindParam(':abba', $u_abba);
    $stmt->bindParam(':banco_intermediario', $u_banco_inter);
    $stmt->bindParam(':domicilio_completo', $u_domicilio);
    $stmt->bindParam(':operador', $u_operador);
    $stmt->bindParam(':tipo_cuenta', $u_tipo_cuenta);
    $stmt->bindParam(':referencia_proveedor', $u_referencia_proveedor);
    $stmt->bindParam(':tipo_servicio', $u_tipo_servicio);
    $stmt->bindParam(':curp', $u_curp);
    $stmt->bindParam(':tipo_persona', $u_tipo_persona);
    $stmt->bindParam(':id_cuenta', $u_id_cta);

    $stmt->execute();
   
    //$update_cuenta_destino = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
       // $response = [ 'success'=> true,'data' => $cuenta_destino];
       $response = [ 'success'=> true,'message' => 'Se actualizaron correctamente los datos del proveedor'];
    } else {
        $response = [ 'success'=> false,'message' => 'No se pudo obtener los datos del proveedor'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);

   

}else if($opcion === 'deleteCtaProveedor'){
    //recuperamos el id de la cuenta
    $d_id_cuenta = $_POST['d_id_cuenta'];
    
        $sql_update = "UPDATE [dbo].[Cuenta_Destino] SET [Estatus] = 'N' WHERE [Id_Cuenta] = :id_cuenta";

        $stmt = $conn_bd->prepare($sql_update);
        $stmt->bindParam(':id_cuenta', $d_id_cuenta);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response = [ 'success'=> true,'message' => 'Se actualizo correctamente el proveedor'];
        } else {
            $response = [ 'success'=> false,'message' => 'No se pudo actualizar el proveedor'];
        }

    header('Content-Type: application/json');
    echo json_encode($response);

}

?>