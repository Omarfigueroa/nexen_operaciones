<?php

$conexion = mysqli_connect('localhost', 'root', '', 'consulta_candados');

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=reporte_csv.csv");

$sql="select * from reporte";
$result=mysqli_query($conexion,$sql);


echo "TID" . ",";
echo "UNIDAD MODELO" . ",";
echo "PLACA" . ",";
echo "OPERADOR" . ",";
echo "FECHA DE SALIDA" . ",";
echo "HORA DE SALIDA" . ",";
echo "ORIGEN" . ",";
echo "DESTINO" . ",";
echo "EMPRESA ASIGNADA" . ",";
echo "ESTATUS" . "\n";

while($mostrar=mysqli_fetch_array($result)){
        echo $mostrar['tid'] . ",";
        echo $mostrar['unidad_modelo'] . ",";
        echo $mostrar['placa'] . ",";
        echo $mostrar['operador'] . ",";
        echo $mostrar['fecha_salida'] . ",";
        echo $mostrar['hora_salida'] . ",";
        echo $mostrar['origen'] . ",";
        echo $mostrar['destino'] . ",";
        echo $mostrar['empresa_asignada'] . ",";
        echo $mostrar['estatus'] . "\n"; 
}
?>
