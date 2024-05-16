<?php 
require '../conexion/bd.php';

if($_POST){
    if(!empty($_POST['contenedor'])){
        $contenedor = $_POST['contenedor'];
        $query_contenedor ="SELECT * FROM [dbo].[Operacion_nexen] WHERE replace(Contenedor_1,' ','') = replace('{$contenedor}',' ','')";
        $select_contenedor = $conn_bd->prepare($query_contenedor);    
        $select_contenedor->execute();
        $result_consulta = $select_contenedor -> fetchAll(PDO::FETCH_ASSOC); 
        if(empty($result_consulta)){
            $result = array('msg'=>'false');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }else{
            $result = array($result_consulta,'msg'=>'true');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }
    }else if(!empty($_POST['BL'])){
        $BL = $_POST['BL'];
        $query_BL ="SELECT * FROM [dbo].[Operacion_nexen] WHERE replace(BL,' ','') = replace('{$BL}',' ','')";
        $select_BL = $conn_bd->prepare($query_BL);    
        $select_BL->execute();
        $result_consulta = $select_BL -> fetchAll(PDO::FETCH_ASSOC);
        if(empty($result_consulta)){
            $result = array('msg'=>'false');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }else{
            $result = array($result_consulta,'msg'=>'true');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }
    }else if(!empty($_POST['Pedimento'])){
        $Pedimento = $_POST['Pedimento'];
        $query_Pedimento ="SELECT * FROM [dbo].[Operacion_nexen] WHERE replace(No_Pedimento,' ','') = replace('{$Pedimento}',' ','')";
        $select_Pedimento = $conn_bd->prepare($query_Pedimento);    
        $select_Pedimento->execute();
        $result_consulta = $select_Pedimento -> fetchAll(PDO::FETCH_ASSOC); 
        if(empty($result_consulta)){
            $result = array('msg'=>'false');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }else{
            $result = array($result_consulta,'msg'=>'true');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }
    }else{
        $result = 'false';
        echo json_encode($result,JSON_UNESCAPED_UNICODE);

    }

}
?>