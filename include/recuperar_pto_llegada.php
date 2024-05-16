<?php   
    require('../conexion/bd.php');

    $valor = $_POST['valor'];
   
    $sql = "SELECT * From [dbo].[Catalogo_Aduanas] WHERE Denominación ='{$valor}'" ;
    
    $resultado = $conn_bd->prepare($sql);
    $resultado->execute();
    $row = $resultado->fetchAll( PDO::FETCH_ASSOC );
    echo json_encode($row);
?>