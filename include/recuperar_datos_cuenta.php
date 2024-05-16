<?php   
    require('../conexion/bd.php');

    $valor = $_POST['valor'];


    $sql = "SELECT DISTINCT PC.Alias, Clabe, Cuenta, Tipo_Cuenta,SWT_ABBA, Banco_Intermediario, Domicilio_Completo,id_Movimiento, NOMBRE_BANCO, PC.Razon_Social AS Razon_Social, PC.RFC from Proveedores_Cuentas PC 
                INNER JOIN Razon_Bancos RB
                ON  RB.Id_Razon_Social=PC.id_Razon 
                INNER JOIN Catalogo_Bancos C
                ON C.ID_BANCO=RB.Id_banco where RB.id_Movimiento = '{$valor}'" ;
    
          
    // SELECT * From [dbo].[Cuenta_Destino] WHERE [Cuenta_Destino] ='{$valor}'" ;
    
    $resultado_datos_cuenta = $conn_bd->prepare($sql);
    $resultado_datos_cuenta->execute();
    $row_cuenta = $resultado_datos_cuenta->fetchAll( PDO::FETCH_ASSOC );
    echo json_encode($row_cuenta);
    
?>