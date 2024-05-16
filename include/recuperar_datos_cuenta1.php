<?php   
    require('../conexion/bd.php');

    $valor = $_POST['valor'];

    // $sql = "SELECT * From [dbo].[Cuenta_Destino] WHERE [Cuenta_Destino] ='{$valor}'" ;
    $sql = "SELECT * From [dbo].[Cuenta_Destino] WHERE [Id_Cuenta] ='{$valor}'" ;
    
    $resultado_datos_cuenta = $conn_bd->prepare($sql);
    $resultado_datos_cuenta->execute();
    $row_cuenta = $resultado_datos_cuenta->fetchAll( PDO::FETCH_ASSOC );
    echo json_encode($row_cuenta);
    
?>