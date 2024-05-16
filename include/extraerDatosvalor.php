<?php  

require '../conexion/bd.php';

$htmlOptions = "";
    
$query="SELECT * FROM [dbo].[valor] ";
$request = $conn_bd->prepare($query);
$request->execute();
$arrData = $request-> fetchAll(PDO::FETCH_ASSOC);
if(count($arrData) > 0 ){
    $htmlOptions .= '<option value="">Selecciona una opcion</option>';  
    for ($i=0; $i < count($arrData); $i++) { 
        if($arrData[$i]['Estatus'] =='A' ){
            
        $htmlOptions .= '<option value="'.$arrData[$i]['Id_valor'].'">'.$arrData[$i]['Nombre'].'</option>';
        }
    }
}
echo $htmlOptions;
die();

            

?>