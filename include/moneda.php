<?php  

require '../conexion/bd.php';

$htmlOptions = "";
	
$query = "SELECT * FROM [dbo].[MONEDAS]";
$request = $conn_bd->prepare($query);
$request->execute();
$arrData = $request->fetchAll(PDO::FETCH_ASSOC);

if (count($arrData) > 0) {
    $htmlOptions .= '<option value="">Selecciona una opción</option>';
    for ($i = 0; $i < count($arrData); $i++) {
        $htmlOptions .= '<option value="' . $arrData[$i]['PREFIJO'] . '">' . $arrData[$i]['PREFIJO'] . '</option>';
    }
}
echo $htmlOptions;
die();
?>